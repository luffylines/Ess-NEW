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
        return view('auth.login'); // Your Blade login page
    }

    public function login(Request $request)
    {
        // Validate request including reCAPTCHA
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required|string',
        ]);

        // Verify Google reCAPTCHA
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        $result = $response->json();

        if (!($result['success'] ?? false)) {
            return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed.']);
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

        // Temporarily store user ID and credentials in session
        session([
            'temp_user_id' => $user->id,
            'temp_password' => $request->password,
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
        Auth::login($user);

        // Clear OTP and session
        $otp->delete();
        session()->forget(['temp_user_id', 'temp_password']);

        return redirect()->intended('/dashboard');
    }
}
