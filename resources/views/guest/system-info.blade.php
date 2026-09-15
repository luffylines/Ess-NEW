@extends('layouts.welcome')

@section('title', 'System Information | Place Of Beauty ESS')

@section('content')
<section class="public-page-hero"><div class="container"><div class="eyebrow" data-reveal><i class="bi bi-cpu"></i> System information</div><h1 class="hero-title mx-auto" style="max-width:900px;" data-reveal data-reveal-delay="1">A connected platform for <span class="accent">employee self-service.</span></h1><p class="hero-copy" data-reveal data-reveal-delay="2">Place Of Beauty ESS centralizes employee access, attendance, schedules, requests, payroll tools and administrative workflows in a responsive web application.</p></div></section>
<section class="public-section pt-3"><div class="container"><div class="row g-3">
<div class="col-lg-4" data-reveal><div class="feature-card"><div class="feature-icon"><i class="bi bi-layers"></i></div><h3 class="h5 fw-bold mt-3">Unified workspace</h3><p class="section-copy mb-0">Common employee and HR functions share one consistent interface and navigation system.</p></div></div>
<div class="col-lg-4" data-reveal data-reveal-delay="1"><div class="feature-card"><div class="feature-icon"><i class="bi bi-person-gear"></i></div><h3 class="h5 fw-bold mt-3">Role-based experience</h3><p class="section-copy mb-0">Employee, HR, manager and administrator accounts receive tools appropriate to their responsibilities.</p></div></div>
<div class="col-lg-4" data-reveal data-reveal-delay="2"><div class="feature-card"><div class="feature-icon"><i class="bi bi-shield-lock"></i></div><h3 class="h5 fw-bold mt-3">Secure workflows</h3><p class="section-copy mb-0">Authentication, permission boundaries and system activity help protect workplace information and actions.</p></div></div>
</div><div class="content-panel mt-4" data-reveal><div class="row g-4"><div class="col-md-6"><div class="section-kicker">Core modules</div><h2 class="h4 fw-bold mt-2">Employee tools</h2><p class="section-copy mb-0">Attendance · schedules · leave · overtime · payslips · profile access</p></div><div class="col-md-6"><div class="section-kicker">Operations</div><h2 class="h4 fw-bold mt-2">HR & administration</h2><p class="section-copy mb-0">Approvals · payroll · reporting · employee management · store/network configuration · activity logs</p></div></div></div></div></section>
@endsection
