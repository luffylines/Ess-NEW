@extends('layouts.welcome')

@section('title', 'About ESS | Place Of Beauty')

@section('content')
<section class="public-page-hero">
    <div class="container">
        <div class="eyebrow" data-reveal><i class="bi bi-info-circle"></i> About the platform</div>
        <h1 class="hero-title mx-auto" style="max-width:900px;" data-reveal data-reveal-delay="1">A calmer way to manage the <span class="accent">employee workday.</span></h1>
        <p class="hero-copy" data-reveal data-reveal-delay="2">Place Of Beauty Employee Self-Service is designed to bring routine HR tasks into one clear, secure and accessible workspace for employees, HR teams and managers.</p>
    </div>
</section>

<section class="public-section pt-3">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5" data-reveal>
                <div class="content-panel h-100">
                    <div class="section-kicker">Why ESS exists</div>
                    <h2 class="section-title fs-1">Less friction. More visibility.</h2>
                    <p class="section-copy">Instead of switching between paper forms, messages and disconnected records, employees can access important work information and submit requests in one place.</p>
                    <div class="d-grid gap-2 mt-4">
                        <div class="d-flex gap-3 align-items-start"><div class="feature-icon flex-shrink-0"><i class="bi bi-person-check"></i></div><div><strong>Employee-first</strong><div class="small text-secondary mt-1">Daily actions are easy to find and understand.</div></div></div>
                        <div class="d-flex gap-3 align-items-start"><div class="feature-icon flex-shrink-0"><i class="bi bi-shield-check"></i></div><div><strong>Role-aware</strong><div class="small text-secondary mt-1">Employees, HR, managers and admins see the tools relevant to them.</div></div></div>
                        <div class="d-flex gap-3 align-items-start"><div class="feature-icon flex-shrink-0"><i class="bi bi-phone"></i></div><div><strong>Responsive</strong><div class="small text-secondary mt-1">The interface is designed to work cleanly on phones and desktops.</div></div></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="row g-3 h-100">
                    <div class="col-md-6" data-reveal data-reveal-delay="1"><div class="feature-card"><div class="feature-icon"><i class="bi bi-fingerprint"></i></div><h5 class="fw-bold mt-3">Attendance</h5><p class="section-copy mb-3">Track workday attendance, time records and attendance history.</p><a href="{{ route('guest.attendance') }}" class="text-decoration-none fw-bold">Explore attendance <i class="bi bi-arrow-right"></i></a></div></div>
                    <div class="col-md-6" data-reveal data-reveal-delay="2"><div class="feature-card"><div class="feature-icon"><i class="bi bi-file-earmark-bar-graph"></i></div><h5 class="fw-bold mt-3">Reports & insights</h5><p class="section-copy mb-3">Turn employee records into more useful summaries and visibility.</p><a href="{{ route('guest.reports') }}" class="text-decoration-none fw-bold">Explore reports <i class="bi bi-arrow-right"></i></a></div></div>
                    <div class="col-md-6" data-reveal data-reveal-delay="2"><div class="feature-card"><div class="feature-icon"><i class="bi bi-calendar2-heart"></i></div><h5 class="fw-bold mt-3">Requests</h5><p class="section-copy mb-0">Submit leave and overtime requests and follow their status through review.</p></div></div>
                    <div class="col-md-6" data-reveal data-reveal-delay="3"><div class="feature-card"><div class="feature-icon"><i class="bi bi-receipt"></i></div><h5 class="fw-bold mt-3">Payroll access</h5><p class="section-copy mb-0">Keep payslips and employee payroll information easy to reach when needed.</p></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="public-section pt-2">
    <div class="container">
        <div class="content-panel" data-reveal>
            <div class="row align-items-center g-4">
                <div class="col-lg-8"><div class="section-kicker">Built for daily use</div><h2 class="section-title mb-2">A system should feel helpful—not like another task.</h2><p class="section-copy mb-0">That is why the redesigned ESS emphasizes clear hierarchy, short paths to common actions, meaningful status feedback and subtle motion instead of distracting effects.</p></div>
                <div class="col-lg-4 text-lg-end"><a href="{{ route('login') }}" class="btn-pob-primary"><i class="bi bi-arrow-right-circle"></i> Go to employee login</a></div>
            </div>
        </div>
    </div>
</section>
@endsection
