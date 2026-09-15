@extends('layouts.welcome')

@section('title', 'Place Of Beauty ESS | Employee Self-Service')

@section('content')
<section class="public-hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="eyebrow" data-reveal><i class="bi bi-stars"></i> Employee experience, simplified</div>
                <h1 class="hero-title" data-reveal data-reveal-delay="1">Your workday, <span class="accent">beautifully organized.</span></h1>
                <p class="hero-copy" data-reveal data-reveal-delay="2">Place Of Beauty ESS brings attendance, schedules, leave, overtime, payroll and employee tools into one secure workspace—designed to feel clear, fast and effortless on desktop or mobile.</p>
                <div class="hero-actions" data-reveal data-reveal-delay="3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-pob-primary"><i class="bi bi-grid-1x2"></i> Open Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-pob-primary"><i class="bi bi-arrow-right-circle"></i> Enter Employee Portal</a>
                    @endauth
                    <a href="#discover" class="btn-pob-secondary"><i class="bi bi-play-circle"></i> Explore ESS</a>
                </div>
                <div class="d-flex flex-wrap gap-4 mt-4 text-secondary small" data-reveal data-reveal-delay="3">
                    <span><i class="bi bi-shield-check me-1"></i> Secure access</span>
                    <span><i class="bi bi-phone me-1"></i> Mobile friendly</span>
                    <span><i class="bi bi-lightning-charge me-1"></i> Real-time workflow</span>
                </div>
            </div>

            <div class="col-lg-5" data-reveal data-reveal-delay="2">
                <div class="hero-orbit">
                    <div class="hero-dashboard" data-parallax-card>
                        <div class="mini-top">
                            <div class="mini-user">
                                <div class="mini-avatar"><i class="bi bi-person-heart"></i></div>
                                <div><strong>My Workday</strong><div class="small text-secondary">Today · <span data-live-clock>--:--</span></div></div>
                            </div>
                            <span class="badge rounded-pill" style="background:rgba(40,122,93,.12);color:var(--pob-success);">Live</span>
                        </div>
                        <div class="mini-grid">
                            <div class="mini-card large">
                                <div class="small text-secondary">Attendance this month</div>
                                <div class="pulse-ring"><strong>92%</strong></div>
                                <div class="text-center"><strong>22 / 24 days</strong><div class="small text-secondary mt-1">Consistent attendance</div></div>
                            </div>
                            <div class="mini-card">
                                <div class="feature-icon mb-2"><i class="bi bi-calendar2-week"></i></div>
                                <div class="small text-secondary">Next shift</div><strong>9:00 AM</strong>
                            </div>
                            <div class="mini-card">
                                <div class="feature-icon mb-2"><i class="bi bi-wallet2"></i></div>
                                <div class="small text-secondary">Payslip</div><strong>Ready</strong>
                            </div>
                        </div>
                        <div class="mt-3 p-3 rounded-4" style="background:rgba(198,79,122,.07);border:1px solid var(--pob-line);">
                            <div class="d-flex justify-content-between small"><span><i class="bi bi-check-circle-fill me-1" style="color:var(--pob-success);"></i> Time in · 9:02 AM</span><span class="text-secondary">Working</span></div>
                            <div class="progress mt-2" style="height:5px;background:rgba(198,79,122,.10);"><div class="progress-bar" style="width:58%;background:linear-gradient(90deg,var(--pob-rose),var(--pob-violet));"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="public-section" id="discover">
    <div class="container">
        <div class="row align-items-end mb-4 g-3">
            <div class="col-lg-7" data-reveal>
                <div class="section-kicker">One connected workspace</div>
                <h2 class="section-title">Everything employees need, without the clutter.</h2>
            </div>
            <div class="col-lg-5" data-reveal data-reveal-delay="1"><p class="section-copy mb-0">The interface is built around daily actions—not complicated menus—so employees and HR teams can get things done faster.</p></div>
        </div>
        <div class="row g-3">
            @php
                $features = [
                    ['bi-fingerprint','Attendance','Time in/out, attendance history and workday visibility in one flow.'],
                    ['bi-calendar2-heart','Leave & Overtime','Submit requests and track every step from review to approval.'],
                    ['bi-calendar-week','Schedules','See assigned shifts, upcoming workdays and schedule updates quickly.'],
                    ['bi-receipt','Payslips','Access payroll information and downloadable payslips securely.'],
                    ['bi-graph-up-arrow','Insights','Useful attendance and work trends presented in easy-to-read visuals.'],
                    ['bi-shield-lock','Secure Access','Role-aware access keeps employee, HR, manager and admin tools separated.'],
                ];
            @endphp
            @foreach($features as $index => $feature)
                <div class="col-md-6 col-xl-4" data-reveal data-reveal-delay="{{ ($index % 3) + 1 }}">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi {{ $feature[0] }}"></i></div>
                        <h5 class="fw-bold mt-3 mb-2">{{ $feature[1] }}</h5>
                        <p class="section-copy mb-0">{{ $feature[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="public-section pt-2">
    <div class="container">
        <div class="content-panel" data-reveal>
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="section-kicker">Know the system</div>
                    <h2 class="section-title mb-3">Transparent, approachable and easy to understand.</h2>
                    <p class="section-copy mb-0">Learn what ESS does, how to reach the team, and the conditions that keep company and employee data used responsibly.</p>
                </div>
                <div class="col-lg-5">
                    <div class="row g-2">
                        <div class="col-12"><a href="{{ route('about') }}" class="btn-pob-secondary w-100 justify-content-between">About ESS <i class="bi bi-arrow-up-right"></i></a></div>
                        <div class="col-6"><a href="{{ route('contact') }}" class="btn-pob-secondary w-100">Contact</a></div>
                        <div class="col-6"><a href="{{ route('terms') }}" class="btn-pob-secondary w-100">Terms</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
