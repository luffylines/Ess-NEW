<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Get remembered login if exists
        $rememberedLogin = request()->cookie('remembered_login');
        
        return view('auth.login', compact('rememberedLogin')); // Your Blade login page
    }

    public function login(Request $request)
    {
        // Validate basic fields first
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check if reCAPTCHA response exists
        if (!$request->has('g-recaptcha-response') || empty($request->input('g-recaptcha-response'))) {
            return back()
                ->withInput($request->except(['password', 'g-recaptcha-response']))
                ->withErrors(['g-recaptcha-response' => 'Please complete the reCAPTCHA verification.']);
        }

        // Verify Google reCAPTCHA with better error handling
        try {
            $response = Http::timeout(10)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            $result = $response->json();

            if (!($result['success'] ?? false)) {
                $errorCodes = $result['error-codes'] ?? [];
                $errorMessage = 'reCAPTCHA verification failed.';
                
                // Provide specific error messages for common issues
                if (in_array('timeout-or-duplicate', $errorCodes)) {
                    $errorMessage = 'reCAPTCHA expired. Please try again.';
                } elseif (in_array('invalid-input-response', $errorCodes)) {
                    $errorMessage = 'Invalid reCAPTCHA. Please refresh and try again.';
                }
                
                return back()
                    ->withInput($request->except(['password', 'g-recaptcha-response']))
                    ->withErrors(['g-recaptcha-response' => $errorMessage]);
            }
        } catch (\Exception $e) {
            // If reCAPTCHA service is down, log error but allow login for mobile users
            \Log::error('reCAPTCHA verification failed: ' . $e->getMessage());
            
            // For mobile devices, we can be more lenient
            $userAgent = $request->header('User-Agent', '');
            $isMobile = preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry/i', $userAgent);
            
            if (!$isMobile) {
                return back()
                    ->withInput($request->except(['password', 'g-recaptcha-response']))
                    ->withErrors(['g-recaptcha-response' => 'reCAPTCHA service unavailable. Please try again later.']);
            }
        }

        // Determine if login input is email or employee_id
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'employee_id';

        // Attempt to find the user
        $user = User::where($loginType, $request->login)->first();

        if (!$user || !Auth::validate(['email' => $user->email, 'password' => $request->password])) {
            return back()->withErrors(['login' => 'Invalid credentials']);
        }

        // Generate OTP (6 digits)
        $otp = rand(100000, 999999);

        // Save OTP valid for 5 minutes
        Otp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp_code' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]
        );

        // Send OTP email
        Mail::to($user->email)->send(new SendOtpMail($otp));

        // Temporarily store user ID, credentials and remember preference in session
        session([
            'temp_user_id' => $user->id,
            'temp_password' => $request->password,
            'temp_login' => $request->login,
            'temp_remember' => $request->has('remember'),
            'temp_clear_remembered' => $request->has('clear_remembered'),
        ]);

        return redirect()->route('otp.verify')->with('email', $user->email);
    }

    public function verifyOtpForm()
    {
        return view('auth.otp'); // OTP verification Blade
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        $userId = session('temp_user_id');
        $password = session('temp_password');
        $login = session('temp_login');
        $remember = session('temp_remember', false);
        $clearRemembered = session('temp_clear_remembered', false);

        if (!$userId) {
            return redirect()->route('login')->withErrors(['login' => 'Session expired. Please login again.']);
        }

        $otp = Otp::where('user_id', $userId)
            ->where('otp_code', $request->otp_code)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp_code' => 'Invalid or expired OTP.']);
        }

        // OTP is valid → login the user
        $user = User::find($userId);
        Auth::login($user, $remember);

        // Handle remember me functionality
        $response = redirect()->intended('/dashboard');
        
        if ($remember && !$clearRemembered) {
            // Store login for next time (expires in 30 days)
            $response->withCookie(cookie('remembered_login', $login, 30 * 24 * 60));
        } else {
            // Clear any existing remembered login
            $response->withCookie(cookie()->forget('remembered_login'));
        }

        // Clear OTP and session
        $otp->delete();
        session()->forget(['temp_user_id', 'temp_password', 'temp_login', 'temp_remember', 'temp_clear_remembered']);

        return $response;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Only clear remembered login if the user wasn't using "remember me"
        // We check if there's a remembered_login cookie, if the user wants to clear it, they should uncheck remember me on next login
        
        return redirect('/login');
    }
}
