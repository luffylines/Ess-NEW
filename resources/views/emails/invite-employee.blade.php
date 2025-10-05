{{--<x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
--}}

@component('mail::message')
# Welcome to Employee Self Service

Hi {{ $user->name }},

You've been added as an employee to the system. Please click the button below to complete your profile and set your password.

@component('mail::button', ['url' => route('employees.complete', ['token' => $user->remember_token])])
Complete Your Profile
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
