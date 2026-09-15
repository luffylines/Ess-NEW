@extends('layouts.welcome')

@section('title', 'Place Of Beauty ESS | Modern Employee Self-Service')

@section('content')
<section class="premium-hero">
    <div class="premium-grid-overlay" aria-hidden="true"></div>
    <div class="premium-glow premium-glow-one" aria-hidden="true"></div>
    <div class="premium-glow premium-glow-two" aria-hidden="true"></div>

    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-xl-7 col-lg-6">
                <div class="premium-eyebrow" data-reveal>
                    <span class="eyebrow-dot"></span>
                    Place Of Beauty · Employee Workspace
                </div>

                <h1 class="premium-title" data-reveal data-reveal-delay="1">
                    Work feels better when <span>everything is in one place.</span>
                </h1>

                <p class="premium-lead" data-reveal data-reveal-delay="2">
                    Attendance, schedules, leave, overtime and payslips—organized in one elegant self-service experience built for employees, HR and managers.
                </p>

                <div class="premium-actions" data-reveal data-reveal-delay="3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="premium-primary-btn">
                            <span>Open dashboard</span><i class="bi bi-arrow-up-right"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="premium-primary-btn">
                            <span>Enter employee portal</span><i class="bi bi-arrow-right"></i>
                        </a>
                    @endauth
                    <a href="#experience" class="premium-ghost-btn"><i class="bi bi-play-circle"></i> Explore the experience</a>
                </div>

                <div class="premium-trust-row" data-reveal data-reveal-delay="3">
                    <div><i class="bi bi-shield-check"></i><span><strong>Secure</strong><small>Role-based access</small></span></div>
                    <div><i class="bi bi-phone"></i><span><strong>Responsive</strong><small>Desktop to mobile</small></span></div>
                    <div><i class="bi bi-lightning-charge"></i><span><strong>Fast</strong><small>Daily actions first</small></span></div>
                </div>
            </div>

            <div class="col-xl-5 col-lg-6" data-reveal data-reveal-delay="2">
                <div class="workspace-stage">
                    <div class="workspace-card" data-parallax-card>
                        <div class="workspace-card-top">
                            <div class="workspace-identity">
                                <div class="workspace-avatar"><img src="{{ asset('img/logo.png') }}" alt=""></div>
                                <div>
                                    <span class="workspace-label">MY WORKDAY</span>
                                    <strong>Good day 👋</strong>
                                </div>
                            </div>
                            <span class="live-pill"><span></span> Live</span>
                        </div>

                        <div class="workspace-time">
                            <span>Current time</span>
                            <strong data-live-clock>--:--</strong>
                        </div>

                        <div class="workspace-metrics">
                            <div class="workspace-progress-card">
                                <div class="workspace-ring"><span>92%</span></div>
                                <div>
                                    <small>Attendance</small>
                                    <strong>22 of 24 days</strong>
                                    <span>On track this month</span>
                                </div>
                            </div>

                            <div class="workspace-mini-card">
                                <div class="mini-icon"><i class="bi bi-calendar2-week"></i></div>
                                <small>Next shift</small>
                                <strong>9:00 AM</strong>
                                <span>Today</span>
                            </div>

                            <div class="workspace-mini-card">
                                <div class="mini-icon"><i class="bi bi-receipt"></i></div>
                                <small>Payslip</small>
                                <strong>Ready</strong>
                                <span>Latest cycle</span>
                            </div>
                        </div>

                        <div class="workday-flow">
                            <div class="flow-head"><span>Today's progress</span><strong>Working</strong></div>
                            <div class="flow-track"><span style="width:58%"></span></div>
                            <div class="flow-steps">
                                <span class="done">9:02<br><small>Time in</small></span>
                                <span class="active">Now<br><small>Working</small></span>
                                <span>--:--<br><small>Time out</small></span>
                            </div>
                        </div>
                    </div>

                    <div class="floating-chip chip-one"><i class="bi bi-check2-circle"></i> Attendance synced</div>
                    <div class="floating-chip chip-two"><i class="bi bi-stars"></i> Premium workspace</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="premium-section" id="experience">
    <div class="container">
        <div class="premium-section-head" data-reveal>
            <div>
                <span class="premium-section-kicker">Designed around the workday</span>
                <h2>Useful tools, without the admin-system clutter.</h2>
            </div>
            <p>Everything is grouped around what employees actually need to do—check in, see schedules, request time off, review pay and stay informed.</p>
        </div>

        <div class="premium-bento-grid">
            <a href="{{ route('guest.attendance') }}" class="premium-bento bento-large" data-reveal>
                <div class="bento-icon"><i class="bi bi-fingerprint"></i></div>
                <div>
                    <span class="bento-label">Attendance</span>
                    <h3>A smarter start and finish to every workday.</h3>
                    <p>Time in/out, attendance history and daily status presented in a clear employee-first flow.</p>
                </div>
                <div class="bento-arrow"><i class="bi bi-arrow-up-right"></i></div>
            </a>

            <a href="{{ route('guest.reports') }}" class="premium-bento" data-reveal data-reveal-delay="1">
                <div class="bento-icon violet"><i class="bi bi-graph-up-arrow"></i></div>
                <span class="bento-label">Insights</span>
                <h4>See work patterns at a glance.</h4>
                <p>Readable attendance and work summaries with less visual noise.</p>
                <div class="sparkline" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span></div>
            </a>

            <a href="{{ route('guest.tasks') }}" class="premium-bento" data-reveal data-reveal-delay="2">
                <div class="bento-icon gold"><i class="bi bi-list-check"></i></div>
                <span class="bento-label">Daily workflow</span>
                <h4>Keep important actions visible.</h4>
                <p>Schedules, approvals and employee tasks stay easy to find.</p>
                <div class="task-pills"><span>Schedule</span><span>Leave</span><span>Overtime</span></div>
            </a>

            <div class="premium-bento bento-wide" data-reveal data-reveal-delay="1">
                <div class="bento-wide-copy">
                    <span class="bento-label">Role-aware workspace</span>
                    <h3>One system. Different experiences for every role.</h3>
                    <p>Employees see personal tools. HR and managers see operational workflows. Admins keep system controls separated and organized.</p>
                </div>
                <div class="role-stack">
                    <div><i class="bi bi-person"></i><span><strong>Employee</strong><small>My workday</small></span></div>
                    <div><i class="bi bi-people"></i><span><strong>HR / Manager</strong><small>People operations</small></span></div>
                    <div><i class="bi bi-shield-lock"></i><span><strong>Admin</strong><small>System control</small></span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="premium-section premium-section-soft">
    <div class="container">
        <div class="premium-cta" data-reveal>
            <div>
                <span class="premium-section-kicker">Simple outside. Powerful inside.</span>
                <h2>Ready when your workday starts.</h2>
                <p>Use the employee portal to access your dashboard, attendance, schedules, leave requests, overtime and payslips.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('about') }}" class="premium-ghost-btn">About ESS</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="premium-primary-btn">Open dashboard <i class="bi bi-arrow-up-right"></i></a>
                @else
                    <a href="{{ route('login') }}" class="premium-primary-btn">Employee login <i class="bi bi-arrow-right"></i></a>
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.premium-hero { position: relative; min-height: calc(100vh - 94px); display: flex; align-items: center; padding: clamp(4rem, 8vw, 7rem) 0 5rem; overflow: hidden; isolation: isolate; }
.premium-grid-overlay { position: absolute; inset: 0; z-index: -3; opacity: .38; background-image: linear-gradient(rgba(72,50,64,.055) 1px, transparent 1px), linear-gradient(90deg, rgba(72,50,64,.055) 1px, transparent 1px); background-size: 44px 44px; mask-image: linear-gradient(to bottom, #000 0%, transparent 88%); }
.premium-glow { position: absolute; z-index: -2; border-radius: 50%; filter: blur(24px); opacity: .75; animation: premiumFloat 11s ease-in-out infinite; }
.premium-glow-one { width: 420px; height: 420px; left: -170px; top: 12%; background: radial-gradient(circle, rgba(201,79,128,.22), transparent 68%); }
.premium-glow-two { width: 520px; height: 520px; right: -190px; bottom: -120px; background: radial-gradient(circle, rgba(125,104,184,.18), transparent 68%); animation-delay: -5s; }
.premium-eyebrow { display: inline-flex; align-items: center; gap: .55rem; padding: .48rem .78rem; border: 1px solid rgba(201,79,128,.16); border-radius: 999px; background: rgba(255,255,255,.55); box-shadow: inset 0 1px rgba(255,255,255,.75); backdrop-filter: blur(12px); color: #9f315f; font-size: .74rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
.eyebrow-dot { width: 8px; height: 8px; border-radius: 50%; background: linear-gradient(135deg, #c94f80, #7d68b8); box-shadow: 0 0 0 5px rgba(201,79,128,.10); }
.premium-title { max-width: 820px; margin: 1.4rem 0 1rem; font-size: clamp(3rem, 7.4vw, 6.3rem); line-height: .95; letter-spacing: -.065em; font-weight: 800; }
.premium-title span { background: linear-gradient(110deg, #9f315f 0%, #c94f80 44%, #8069bb 100%); -webkit-background-clip: text; background-clip: text; color: transparent; }
.premium-lead { max-width: 720px; color: var(--public-muted); font-size: clamp(1.05rem, 1.7vw, 1.22rem); line-height: 1.8; }
.premium-actions { display: flex; flex-wrap: wrap; gap: .8rem; margin-top: 1.8rem; }
.premium-primary-btn, .premium-ghost-btn { display: inline-flex; align-items: center; justify-content: center; gap: .55rem; border-radius: 999px; min-height: 50px; padding: .82rem 1.15rem; font-weight: 800; text-decoration: none; transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
.premium-primary-btn { color: #fff !important; border: 0; background: linear-gradient(135deg, #9f315f, #c94f80 55%, #7d68b8); box-shadow: 0 14px 34px rgba(159,49,95,.24); }
.premium-ghost-btn { color: var(--public-ink) !important; border: 1px solid var(--public-line); background: rgba(255,255,255,.55); backdrop-filter: blur(14px); }
.public-shell.dark .premium-ghost-btn { background: rgba(255,255,255,.04); }
.premium-primary-btn:hover, .premium-ghost-btn:hover { transform: translateY(-2px); }
.premium-trust-row { display: flex; flex-wrap: wrap; gap: 1.4rem; margin-top: 2rem; }
.premium-trust-row > div { display: flex; align-items: center; gap: .65rem; }
.premium-trust-row i { width: 34px; height: 34px; display: grid; place-items: center; border: 1px solid var(--public-line); border-radius: 12px; color: #a33c68; background: rgba(255,255,255,.45); }
.premium-trust-row span { display: grid; }
.premium-trust-row strong { font-size: .83rem; }
.premium-trust-row small { color: var(--public-muted); font-size: .7rem; }
.workspace-stage { position: relative; min-height: 580px; display: grid; place-items: center; }
.workspace-card { width: min(100%, 470px); padding: 1.1rem; border: 1px solid rgba(255,255,255,.75); border-radius: 32px; background: rgba(255,255,255,.72); box-shadow: 0 34px 90px rgba(61,37,53,.16); backdrop-filter: blur(26px) saturate(150%); transform: perspective(1300px) rotateY(-5deg) rotateX(2deg); transition: transform .35s ease; }
.public-shell.dark .workspace-card { background: rgba(34,27,34,.78); border-color: var(--public-line); }
.workspace-card:hover { transform: perspective(1300px) rotateY(0) rotateX(0) translateY(-5px); }
.workspace-card-top { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
.workspace-identity { display: flex; align-items: center; gap: .7rem; }
.workspace-avatar { width: 48px; height: 48px; display: grid; place-items: center; border-radius: 16px; background: linear-gradient(135deg, rgba(201,79,128,.12), rgba(125,104,184,.14)); }
.workspace-avatar img { width: 34px; height: 34px; object-fit: contain; }
.workspace-label { display: block; color: var(--public-muted); font-size: .61rem; letter-spacing: .13em; font-weight: 800; }
.workspace-identity strong { display: block; margin-top: .1rem; font-size: .94rem; }
.live-pill { display: inline-flex; align-items: center; gap: .45rem; padding: .42rem .62rem; border-radius: 999px; color: #287a5d; background: rgba(40,122,93,.09); font-size: .72rem; font-weight: 800; }
.live-pill span { width: 7px; height: 7px; border-radius: 50%; background: #2d9a71; box-shadow: 0 0 0 5px rgba(45,154,113,.10); animation: livePulse 2s infinite; }
.workspace-time { display: flex; align-items: end; justify-content: space-between; margin: 1.25rem 0; padding: 1rem 1.05rem; border-radius: 20px; background: linear-gradient(135deg, rgba(201,79,128,.08), rgba(125,104,184,.07)); border: 1px solid rgba(201,79,128,.10); }
.workspace-time span { color: var(--public-muted); font-size: .72rem; font-weight: 700; }
.workspace-time strong { font-size: 2rem; line-height: 1; letter-spacing: -.05em; }
.workspace-metrics { display: grid; grid-template-columns: 1.2fr .8fr; gap: .8rem; }
.workspace-progress-card, .workspace-mini-card { border: 1px solid var(--public-line); border-radius: 22px; background: rgba(255,255,255,.58); }
.public-shell.dark .workspace-progress-card, .public-shell.dark .workspace-mini-card { background: rgba(255,255,255,.035); }
.workspace-progress-card { grid-row: span 2; min-height: 238px; display: grid; align-content: center; justify-items: center; text-align: center; padding: 1rem; }
.workspace-ring { width: 110px; height: 110px; display: grid; place-items: center; border-radius: 50%; margin-bottom: .85rem; position: relative; background: conic-gradient(#c94f80 0 92%, rgba(201,79,128,.10) 92% 100%); }
.workspace-ring::after { content: ''; position: absolute; width: 82px; height: 82px; border-radius: 50%; background: var(--public-panel); }
.workspace-ring span { position: relative; z-index: 1; font-weight: 800; font-size: 1.35rem; }
.workspace-progress-card small, .workspace-mini-card small { color: var(--public-muted); }
.workspace-progress-card strong, .workspace-progress-card span { display: block; }
.workspace-progress-card > div:last-child > span { color: var(--public-muted); font-size: .72rem; margin-top: .2rem; }
.workspace-mini-card { min-height: 113px; padding: .9rem; display: grid; align-content: center; }
.workspace-mini-card strong { font-size: 1.05rem; }
.workspace-mini-card > span { color: var(--public-muted); font-size: .68rem; }
.mini-icon { width: 35px; height: 35px; display: grid; place-items: center; margin-bottom: .4rem; border-radius: 12px; color: #a33c68; background: rgba(201,79,128,.09); }
.workday-flow { margin-top: .85rem; padding: .95rem 1rem; border: 1px solid var(--public-line); border-radius: 20px; background: rgba(255,255,255,.46); }
.public-shell.dark .workday-flow { background: rgba(255,255,255,.025); }
.flow-head { display: flex; justify-content: space-between; font-size: .72rem; color: var(--public-muted); }
.flow-head strong { color: #287a5d; }
.flow-track { height: 6px; margin: .65rem 0 .7rem; border-radius: 999px; overflow: hidden; background: rgba(201,79,128,.09); }
.flow-track span { display: block; height: 100%; border-radius: inherit; background: linear-gradient(90deg, #c94f80, #7d68b8); }
.flow-steps { display: flex; justify-content: space-between; font-size: .68rem; color: var(--public-muted); }
.flow-steps span { text-align: center; }
.flow-steps .done, .flow-steps .active { color: var(--public-ink); font-weight: 800; }
.flow-steps small { font-weight: 500; color: var(--public-muted); }
.floating-chip { position: absolute; display: inline-flex; align-items: center; gap: .45rem; padding: .65rem .82rem; border: 1px solid rgba(255,255,255,.74); border-radius: 999px; background: rgba(255,255,255,.76); box-shadow: 0 16px 34px rgba(60,37,51,.10); backdrop-filter: blur(18px); font-size: .72rem; font-weight: 800; animation: chipFloat 5s ease-in-out infinite; }
.public-shell.dark .floating-chip { background: rgba(31,25,31,.86); border-color: var(--public-line); }
.chip-one { left: -12px; top: 19%; }
.chip-two { right: -15px; bottom: 18%; animation-delay: -2.5s; }
.premium-section { padding: 6.5rem 0; }
.premium-section-soft { padding-top: 2rem; }
.premium-section-head { display: grid; grid-template-columns: 1.15fr .85fr; gap: 2rem; align-items: end; margin-bottom: 2rem; }
.premium-section-kicker { display: block; margin-bottom: .55rem; color: #9f315f; font-size: .75rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
.premium-section-head h2, .premium-cta h2 { margin: 0; max-width: 760px; font-size: clamp(2.2rem, 4vw, 3.8rem); line-height: 1; letter-spacing: -.045em; font-weight: 800; }
.premium-section-head p, .premium-cta p { margin: 0; color: var(--public-muted); line-height: 1.8; }
.premium-bento-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 1rem; }
.premium-bento { position: relative; grid-column: span 4; min-height: 285px; display: flex; flex-direction: column; padding: 1.45rem; border: 1px solid var(--public-line); border-radius: 28px; background: rgba(255,255,255,.58); box-shadow: 0 18px 48px rgba(63,39,53,.07); color: var(--public-ink) !important; text-decoration: none; overflow: hidden; transition: transform .25s ease, box-shadow .25s ease; }
.public-shell.dark .premium-bento { background: rgba(255,255,255,.025); }
.premium-bento:hover { transform: translateY(-5px); box-shadow: 0 26px 58px rgba(63,39,53,.11); }
.bento-large { grid-column: span 6; min-height: 360px; justify-content: space-between; background: linear-gradient(145deg, rgba(255,255,255,.74), rgba(248,236,242,.74)); }
.public-shell.dark .bento-large { background: linear-gradient(145deg, rgba(255,255,255,.04), rgba(201,79,128,.045)); }
.bento-wide { grid-column: span 6; min-height: 300px; flex-direction: row; align-items: center; gap: 1.4rem; }
.bento-wide-copy { flex: 1; }
.bento-icon { width: 52px; height: 52px; display: grid; place-items: center; border-radius: 17px; color: #9f315f; background: rgba(201,79,128,.10); font-size: 1.2rem; }
.bento-icon.violet { color: #65509d; background: rgba(125,104,184,.11); }
.bento-icon.gold { color: #946c25; background: rgba(216,184,120,.13); }
.bento-label { display: block; margin-top: 1.1rem; color: var(--public-muted); font-size: .7rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
.premium-bento h3 { max-width: 520px; margin: .55rem 0 .7rem; font-size: clamp(1.8rem, 3vw, 2.7rem); line-height: 1; letter-spacing: -.04em; font-weight: 800; }
.premium-bento h4 { margin: .55rem 0 .65rem; font-size: 1.35rem; font-weight: 800; letter-spacing: -.025em; }
.premium-bento p { margin: 0; color: var(--public-muted); line-height: 1.7; }
.bento-arrow { position: absolute; right: 1.2rem; top: 1.2rem; width: 42px; height: 42px; display: grid; place-items: center; border: 1px solid var(--public-line); border-radius: 14px; background: rgba(255,255,255,.52); }
.public-shell.dark .bento-arrow { background: rgba(255,255,255,.04); }
.sparkline { display: flex; align-items: end; gap: .35rem; height: 70px; margin-top: auto; }
.sparkline span { flex: 1; border-radius: 8px 8px 3px 3px; background: linear-gradient(180deg, rgba(125,104,184,.85), rgba(201,79,128,.55)); }
.sparkline span:nth-child(1){height:28%}.sparkline span:nth-child(2){height:48%}.sparkline span:nth-child(3){height:38%}.sparkline span:nth-child(4){height:68%}.sparkline span:nth-child(5){height:61%}.sparkline span:nth-child(6){height:88%}
.task-pills { display: flex; flex-wrap: wrap; gap: .45rem; margin-top: auto; }
.task-pills span { padding: .45rem .65rem; border: 1px solid var(--public-line); border-radius: 999px; color: var(--public-muted); font-size: .68rem; font-weight: 700; background: rgba(255,255,255,.45); }
.public-shell.dark .task-pills span { background: rgba(255,255,255,.035); }
.role-stack { width: min(100%, 260px); display: grid; gap: .55rem; }
.role-stack > div { display: flex; align-items: center; gap: .7rem; padding: .75rem; border: 1px solid var(--public-line); border-radius: 18px; background: rgba(255,255,255,.5); }
.public-shell.dark .role-stack > div { background: rgba(255,255,255,.03); }
.role-stack i { width: 36px; height: 36px; display: grid; place-items: center; border-radius: 12px; color: #a33c68; background: rgba(201,79,128,.09); }
.role-stack span { display: grid; }
.role-stack strong { font-size: .78rem; }
.role-stack small { color: var(--public-muted); font-size: .67rem; }
.premium-cta { display: flex; align-items: center; justify-content: space-between; gap: 2rem; padding: clamp(1.6rem, 4vw, 2.8rem); border: 1px solid var(--public-line); border-radius: 32px; background: linear-gradient(135deg, rgba(201,79,128,.08), rgba(125,104,184,.08), rgba(216,184,120,.06)); box-shadow: 0 20px 60px rgba(63,39,53,.07); }
.premium-cta p { max-width: 700px; margin-top: .75rem; }
@keyframes premiumFloat { 0%,100%{transform:translate3d(0,0,0)} 50%{transform:translate3d(12px,-18px,0)} }
@keyframes chipFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
@keyframes livePulse { 0%,100%{box-shadow:0 0 0 4px rgba(45,154,113,.08)} 50%{box-shadow:0 0 0 7px rgba(45,154,113,.14)} }
@media (max-width: 1199px) { .floating-chip { display:none; } }
@media (max-width: 991px) {
    .premium-hero { min-height: auto; padding-top: 4rem; }
    .workspace-stage { min-height: auto; padding-top: 1rem; }
    .workspace-card { transform: none; }
    .premium-section-head { grid-template-columns: 1fr; gap: .8rem; }
    .premium-bento { grid-column: span 6; }
    .bento-large, .bento-wide { grid-column: span 12; }
}
@media (max-width: 640px) {
    .premium-title { font-size: clamp(2.7rem, 15vw, 4.5rem); }
    .premium-trust-row { gap: .8rem 1.1rem; }
    .workspace-metrics { grid-template-columns: 1fr 1fr; }
    .workspace-progress-card { grid-column: span 2; grid-row: auto; min-height: 210px; }
    .premium-bento { grid-column: span 12; min-height: 250px; }
    .bento-wide { flex-direction: column; align-items: stretch; }
    .role-stack { width: 100%; }
    .premium-cta { align-items: flex-start; flex-direction: column; }
}
</style>
@endsection
