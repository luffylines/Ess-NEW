@component('mail::message')
# Your Login OTP Code

Your one-time password (OTP) for logging in is:

@component('mail::panel')
<div style="text-align: center; font-size: 32px; font-weight: bold; letter-spacing: 8px;">
{{ $otp }}
</div>
@endcomponent

This code will expire in **5 minutes**. Do not share this code with anyone.

If you did not request this, please ignore this email.

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent
