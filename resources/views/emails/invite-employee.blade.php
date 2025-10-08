@component('mail::message')
# Welcome to Employee Self Service

Hi **{{ $user->name }}**,

Congratulations! You've been added to our Employee Self Service system.

## Your Details:
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Employee ID:** {{ $user->employee_id }}
- **Role:** {{ ucfirst($user->role) }}

## Next Steps:
Please click the button below to complete your profile setup and create your password. This link will expire in 7 days.

@component('mail::button', ['url' => route('employees.complete', ['token' => $user->remember_token])])
Complete Your Profile
@endcomponent

After completing your profile, you'll be able to log in using:
- **Email:** {{ $user->email }}
- **Employee ID:** {{ $user->employee_id }}
- **Password:** (the one you set during profile completion)

If you have any questions, please contact the Admin.

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent
