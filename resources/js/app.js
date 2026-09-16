import './bootstrap';
import './navigation-speed';
import '../css/premium-dashboard.css';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

let serverClockOffsetMs = 0;

const ready = (fn) => {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
};

const manilaClockValue = () => new Intl.DateTimeFormat('en-US', {
    timeZone: 'Asia/Manila',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: true,
}).format(new Date(Date.now() + serverClockOffsetMs));

ready(() => {
    document.body.classList.add('page-enter');

    const publicNav = document.querySelector('.public-nav');
    if (publicNav) {
        const syncNav = () => publicNav.classList.toggle('is-scrolled', window.scrollY > 18);
        syncNav();
        window.addEventListener('scroll', syncNav, { passive: true });
    }

    const revealItems = [...document.querySelectorAll('[data-reveal]')];
    if ('IntersectionObserver' in window && revealItems.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealItems.forEach((item) => observer.observe(item));
    } else {
        revealItems.forEach((item) => item.classList.add('is-visible'));
    }

    const themeButtons = document.querySelectorAll('[data-theme-toggle]');
    const storedTheme = localStorage.getItem('pob-theme');
    if (storedTheme === 'dark') document.body.classList.add('dark');
    if (storedTheme === 'light') document.body.classList.remove('dark');

    const syncThemeIcons = () => {
        themeButtons.forEach((button) => {
            const icon = button.querySelector('i');
            if (!icon) return;
            icon.className = document.body.classList.contains('dark') ? 'bi bi-sun' : 'bi bi-moon-stars';
        });
    };
    syncThemeIcons();
    themeButtons.forEach((button) => button.addEventListener('click', () => {
        document.body.classList.toggle('dark');
        localStorage.setItem('pob-theme', document.body.classList.contains('dark') ? 'dark' : 'light');
        syncThemeIcons();
    }));

    const clocks = document.querySelectorAll('[data-live-clock]');
    const updateClocks = () => {
        const value = manilaClockValue();
        clocks.forEach((clock) => { clock.textContent = value; });
    };
    if (clocks.length) {
        updateClocks();
        setInterval(updateClocks, 1000);
    }

    document.querySelectorAll('[data-count]').forEach((element) => {
        const target = Number(element.dataset.count);
        if (!Number.isFinite(target)) return;
        const duration = 750;
        const start = performance.now();
        const render = (time) => {
            const progress = Math.min((time - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            element.textContent = Math.round(target * eased).toLocaleString();
            if (progress < 1) requestAnimationFrame(render);
        };
        requestAnimationFrame(render);
    });

    document.querySelectorAll('.btn-pob-primary, .btn-primary, .btn-gradient-primary').forEach((button) => {
        button.addEventListener('click', (event) => {
            const rect = button.getBoundingClientRect();
            const dot = document.createElement('span');
            const size = Math.max(rect.width, rect.height);
            dot.className = 'ripple-dot';
            dot.style.width = dot.style.height = `${size}px`;
            dot.style.left = `${event.clientX - rect.left - size / 2}px`;
            dot.style.top = `${event.clientY - rect.top - size / 2}px`;
            button.appendChild(dot);
            setTimeout(() => dot.remove(), 650);
        });
    });

    const mockup = document.querySelector('[data-parallax-card]');
    if (mockup && window.matchMedia('(pointer:fine)').matches && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        mockup.addEventListener('mousemove', (event) => {
            const rect = mockup.getBoundingClientRect();
            const x = (event.clientX - rect.left) / rect.width - 0.5;
            const y = (event.clientY - rect.top) / rect.height - 0.5;
            mockup.style.transform = `perspective(1200px) rotateY(${x * 6}deg) rotateX(${y * -5}deg) translateY(-3px)`;
        });
        mockup.addEventListener('mouseleave', () => { mockup.style.transform = ''; });
    }

    // Give existing in-app cards a soft stagger without changing Blade business logic.
    const appCards = [...document.querySelectorAll('main .card, main .stats-card')].filter((card) => !card.closest('.pob-dashboard-intro'));
    appCards.slice(0, 30).forEach((card, index) => {
        card.classList.add('pob-auto-reveal');
        setTimeout(() => card.classList.add('pob-in'), 45 + Math.min(index, 10) * 38);
    });

    // Employee dashboard enhancement. Workday state is synchronized from a
    // lightweight one-row endpoint so it does not block navigation with a full
    // My Attendance page request on Render's single worker.
    if (window.location.pathname === '/dashboard') {
        const welcomeHeading = [...document.querySelectorAll('main h1')].find((el) => /welcome/i.test(el.textContent || ''));
        const attendanceCard = document.querySelector('main .stats-card.bg-primary');
        if (welcomeHeading && attendanceCard && !document.querySelector('.pob-dashboard-intro')) {
            const headingRow = welcomeHeading.closest('.row');
            const nameMatch = (welcomeHeading.textContent || '').match(/Welcome,\s*(.*?)!/i);
            const name = nameMatch ? nameMatch[1].trim() : 'Employee';
            const metaText = welcomeHeading.parentElement?.querySelector('p')?.textContent?.trim() || 'Employee workspace';
            const today = new Intl.DateTimeFormat('en-US', {
                timeZone: 'Asia/Manila',
                weekday: 'long',
                month: 'long',
                day: 'numeric',
            }).format(new Date());

            const intro = document.createElement('section');
            intro.className = 'pob-dashboard-intro';
            intro.innerHTML = `
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3 position-relative" style="z-index:1;">
                    <div>
                        <div class="section-kicker">My workspace</div>
                        <h1 class="h2 fw-bold mb-1">Good day, ${escapeHtml(name)}</h1>
                        <div class="pob-meta small">${escapeHtml(metaText)} · ${escapeHtml(today)}</div>
                    </div>
                    <span class="badge rounded-pill align-self-start" style="background:rgba(40,122,93,.11);color:var(--pob-success);"><i class="bi bi-shield-check me-1"></i> ESS secure session</span>
                </div>
                <div class="pob-workday-grid position-relative" style="z-index:1;">
                    <div class="pob-workday-panel">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="small text-secondary mb-1">Current time · Manila</div>
                                <div class="pob-clock" data-live-clock>--:--:--</div>
                                <div class="small text-secondary mt-2">Use My Attendance to record or review today's workday.</div>
                            </div>
                            <div class="text-center">
                                <div class="pob-progress-orb" style="--progress:0"><span data-workday-percent>0%</span></div>
                                <div class="pob-workday-status mt-2" data-workday-status>Syncing…</div>
                            </div>
                        </div>
                        <div class="pob-timeline" aria-label="Workday journey">
                            <span class="pob-timeline-dot" data-stage="time-in"><i class="bi bi-clock"></i></span>
                            <span class="pob-timeline-line" data-line="after-time-in"></span>
                            <span class="pob-timeline-dot" data-stage="working"><i class="bi bi-briefcase"></i></span>
                            <span class="pob-timeline-line" data-line="after-working"></span>
                            <span class="pob-timeline-dot" data-stage="time-out"><i class="bi bi-box-arrow-right"></i></span>
                        </div>
                        <div class="d-flex justify-content-between small text-secondary mt-2"><span>Time In</span><span>Working</span><span>Time Out</span></div>
                    </div>
                    <div class="pob-workday-actions">
                        <div class="small fw-bold text-secondary text-uppercase mb-2" style="letter-spacing:.08em;">Quick actions</div>
                        <div class="d-grid gap-2">
                            <a href="/attendance/my" class="pob-quick-action"><i class="bi bi-fingerprint"></i><span class="flex-grow-1"><strong class="d-block">My Attendance</strong><small class="text-secondary">Check today's record</small></span><i class="bi bi-chevron-right"></i></a>
                            <a href="/my-schedules" class="pob-quick-action"><i class="bi bi-calendar-event"></i><span class="flex-grow-1"><strong class="d-block">My Schedule</strong><small class="text-secondary">View upcoming shifts</small></span><i class="bi bi-chevron-right"></i></a>
                            <a href="/payslips" class="pob-quick-action"><i class="bi bi-receipt"></i><span class="flex-grow-1"><strong class="d-block">Payslips</strong><small class="text-secondary">Open payroll records</small></span><i class="bi bi-chevron-right"></i></a>
                        </div>
                    </div>
                </div>`;

            if (headingRow) {
                headingRow.replaceWith(intro);
                updateDynamicClock(intro);
                syncWorkdayState(intro);
            }
        }
    }
});

async function syncWorkdayState(root) {
    try {
        const response = await fetch('/api/attendance/today-state', {
            method: 'GET',
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' },
            cache: 'no-store',
        });

        if (!response.ok) throw new Error(`Attendance sync failed: ${response.status}`);

        const payload = await response.json();
        const serverMs = Date.parse(payload.server_time || '');
        if (Number.isFinite(serverMs)) serverClockOffsetMs = serverMs - Date.now();

        applyWorkdayState(root, payload.state || 'unknown');
    } catch (error) {
        console.warn('Unable to synchronize dashboard workday state.', error);
        applyWorkdayState(root, 'unknown');
    }
}

function applyWorkdayState(root, state) {
    const states = {
        not_started: { percent: 0, status: 'Not started', timeIn: 'pending', working: 'pending', timeOut: 'pending', firstLine: 'pending', secondLine: 'pending' },
        working: { percent: 50, status: 'Working', timeIn: 'done', working: 'live', timeOut: 'pending', firstLine: 'done', secondLine: 'active' },
        on_break: { percent: 50, status: 'On break', timeIn: 'done', working: 'live', timeOut: 'pending', firstLine: 'done', secondLine: 'active' },
        working_resumed: { percent: 75, status: 'Working', timeIn: 'done', working: 'live', timeOut: 'pending', firstLine: 'done', secondLine: 'active' },
        complete: { percent: 100, status: 'Complete', timeIn: 'done', working: 'done', timeOut: 'done', firstLine: 'done', secondLine: 'done' },
        unknown: { percent: 0, status: 'Open Attendance', timeIn: 'pending', working: 'pending', timeOut: 'pending', firstLine: 'pending', secondLine: 'pending' },
    };

    const config = states[state] || states.unknown;
    const orb = root.querySelector('.pob-progress-orb');
    const percent = root.querySelector('[data-workday-percent]');
    const status = root.querySelector('[data-workday-status]');

    if (orb) orb.style.setProperty('--progress', String(config.percent));
    if (percent) percent.textContent = `${config.percent}%`;
    if (status) status.textContent = config.status;

    setStage(root.querySelector('[data-stage="time-in"]'), config.timeIn, 'clock');
    setStage(root.querySelector('[data-stage="working"]'), config.working, state === 'on_break' ? 'cup-hot' : 'briefcase');
    setStage(root.querySelector('[data-stage="time-out"]'), config.timeOut, 'box-arrow-right');
    setLine(root.querySelector('[data-line="after-time-in"]'), config.firstLine);
    setLine(root.querySelector('[data-line="after-working"]'), config.secondLine);
}

function setStage(element, state, pendingIcon) {
    if (!element) return;
    element.classList.remove('done', 'live');
    const icon = element.querySelector('i');

    if (state === 'done') {
        element.classList.add('done');
        if (icon) icon.className = 'bi bi-check-lg';
    } else if (state === 'live') {
        element.classList.add('live');
        if (icon) icon.className = `bi bi-${pendingIcon}`;
    } else if (icon) {
        icon.className = `bi bi-${pendingIcon}`;
    }
}

function setLine(element, state) {
    if (!element) return;
    element.classList.remove('active', 'done');
    if (state === 'active') element.classList.add('active');
    if (state === 'done') element.classList.add('done');
}

function updateDynamicClock(root) {
    const clock = root.querySelector('[data-live-clock]');
    if (!clock) return;
    const render = () => { clock.textContent = manilaClockValue(); };
    render();
    setInterval(render, 1000);
}

function escapeHtml(value) {
    return String(value).replace(/[&<>'"]/g, (char) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[char]));
}
