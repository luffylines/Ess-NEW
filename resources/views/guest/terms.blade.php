@extends('layouts.welcome')

@section('title', 'Terms & Conditions | Place Of Beauty ESS')

@section('content')
<section class="public-page-hero">
    <div class="container">
        <div class="eyebrow" data-reveal><i class="bi bi-shield-check"></i> Responsible system use</div>
        <h1 class="hero-title mx-auto" style="max-width:900px;" data-reveal data-reveal-delay="1">Clear rules for a <span class="accent">secure workplace system.</span></h1>
        <p class="hero-copy" data-reveal data-reveal-delay="2">These terms explain the expected use of the Place Of Beauty Employee Self-Service system and the responsibilities that come with accessing employee and company information.</p>
    </div>
</section>

<section class="public-section pt-3">
    <div class="container">
        <div class="content-panel" data-reveal>
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div><div class="section-kicker">Terms & Conditions</div><h2 class="h3 fw-bold mb-1">Employee Self-Service System</h2></div>
                <span class="badge rounded-pill px-3 py-2" style="background:rgba(198,79,122,.10);color:var(--pob-rose-deep);">Last reviewed {{ date('F Y') }}</span>
            </div>
            <p class="section-copy">By accessing or using the ESS system, you agree to use it responsibly and in accordance with company policies. The following conditions apply to all authorized users.</p>

            @php
                $terms = [
                    ['Authorized use','ESS is intended for authorized employees and approved company users only. Unauthorized access, account sharing, or attempts to bypass access controls are prohibited.'],
                    ['Data privacy','Personal and work-related information in the system must be treated as confidential and handled in accordance with applicable company privacy and data-handling policies.'],
                    ['Account responsibility','You are responsible for protecting your login credentials, signing out from shared devices, and reporting suspected account misuse as soon as possible.'],
                    ['Accurate information','Employees should provide accurate information when submitting attendance, leave, overtime, profile updates, or other work-related records.'],
                    ['Acceptable behavior','The system must not be used for harassment, illegal activity, malicious interference, or conduct that violates company rules or applicable law.'],
                    ['System availability','The company aims to keep ESS available and reliable, but access may occasionally be interrupted for maintenance, updates, security work, or circumstances outside normal control.'],
                    ['Monitoring and records','System activity may be logged for security, troubleshooting, auditing, and legitimate administrative purposes consistent with company policy.'],
                    ['Feedback and reporting','Users are encouraged to report bugs, suspicious activity, or usability concerns through the designated contact and feedback channels.'],
                    ['Policy compliance','Failure to follow these terms or related company policies may result in restricted system access and other action consistent with company procedures.'],
                ];
            @endphp

            <ol class="terms-list mt-4">
                @foreach($terms as $index => $term)
                    <li data-reveal data-reveal-delay="{{ ($index % 3) + 1 }}">
                        <div class="terms-number">{{ $index + 1 }}</div>
                        <div><strong>{{ $term[0] }}</strong><p class="section-copy mb-0 mt-1">{{ $term[1] }}</p></div>
                    </li>
                @endforeach
            </ol>

            <div class="mt-4 p-4 rounded-4" style="background:rgba(127,103,179,.07);border:1px solid var(--pob-line);">
                <div class="d-flex gap-3 align-items-start">
                    <div class="feature-icon flex-shrink-0"><i class="bi bi-question-circle"></i></div>
                    <div><strong>Questions about these terms?</strong><p class="section-copy mb-2 mt-1">Contact the system administrator or your HR department if you need clarification about system use, access, or data handling.</p><a href="{{ route('contact') }}" class="text-decoration-none fw-bold">Contact support <i class="bi bi-arrow-right"></i></a></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
