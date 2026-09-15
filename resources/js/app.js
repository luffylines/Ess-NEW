import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

const ready = (fn) => {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
};

ready(() => {
    document.body.classList.add('page-enter');

    // Sticky public navigation treatment.
    const publicNav = document.querySelector('.public-nav');
    if (publicNav) {
        const syncNav = () => publicNav.classList.toggle('is-scrolled', window.scrollY > 18);
        syncNav();
        window.addEventListener('scroll', syncNav, { passive: true });
    }

    // Scroll-reveal animation with accessibility-friendly observer fallback.
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

    // Theme toggle for public pages/auth. Existing in-app theme remains compatible.
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

    // Live clocks used by the dashboard/public preview.
    const clocks = document.querySelectorAll('[data-live-clock]');
    const updateClocks = () => {
        const now = new Date();
        const value = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        clocks.forEach((clock) => { clock.textContent = value; });
    };
    if (clocks.length) {
        updateClocks();
        setInterval(updateClocks, 30000);
    }

    // Premium number count-up. Add data-count to numeric elements when desired.
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

    // Button ripple microinteraction.
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

    // Subtle pointer parallax for the landing dashboard mockup only on capable devices.
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
});
