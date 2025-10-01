
<?php

use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;  

namespace App\Http\Controllers;


class OtpController extends Controller
{
    public function verify(Request $request)
{
    $request->validate([
        'otp_code' => 'required|numeric',
    ]);

    $userId = session('temp_user_id');
    $password = session('temp_password');

    $otp = Otp::where('user_id', $userId)
              ->where('otp_code', $request->otp_code)
              ->where('expires_at', '>=', now())
              ->first();

    if ($otp) {
        // Delete OTP after success
        $otp->delete();

        // Clear session temp data
        session()->forget(['temp_user_id', 'temp_password']);

        // Finally login user
        $user = User::find($userId);
        if (Auth::attempt(['email' => $user->email, 'password' => $password])) {
            return redirect()->intended('dashboard');
        }

        return redirect()->route('login')->withErrors(['email' => 'Auth failed, please try again.']);
    }

    return back()->withErrors(['otp_code' => 'Invalid or expired OTP.']);
}

}