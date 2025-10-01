<?php
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::validate($credentials)) {
            $user = User::where('email', $request->email)->first();

            // Generate OTP
            $otp = rand(100000, 999999);

            // Save OTP valid for 60 seconds
            Otp::create([
                'user_id' => $user->id,
                'otp_code' => $otp,
                'expires_at' => Carbon::now()->addSeconds(60),
            ]);

            // Send OTP email
            Mail::to($user->email)->send(new SendOtpMail($otp));

            // Save user_id temporarily in session
            session([
                'temp_user_id' => $user->id,
                'temp_password' => $request->password,
            ]);

            return redirect()->route('otp.verify')->with('email', $user->email);
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }
}
