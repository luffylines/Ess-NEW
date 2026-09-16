const prefetchedUrls = new Set();
const hoverTimers = new WeakMap();

function isEligibleNavigation(anchor) {
    if (!anchor || !anchor.href) return false;
    if (anchor.target && anchor.target !== '_self') return false;
    if (anchor.hasAttribute('download')) return false;

    let url;
    try {
        url = new URL(anchor.href, window.location.href);
    } catch {
        return false;
    }

    if (url.origin !== window.location.origin) return false;
    if (!['http:', 'https:'].includes(url.protocol)) return false;
    if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return false;

    return true;
}

function prefetch(anchor) {
    if (!isEligibleNavigation(anchor)) return;
    if (navigator.connection?.saveData) return;

    const url = new URL(anchor.href, window.location.href);
    const key = `${url.pathname}${url.search}`;
    if (prefetchedUrls.has(key)) return;
    prefetchedUrls.add(key);

    const link = document.createElement('link');
    link.rel = 'prefetch';
    link.href = url.href;
    link.setAttribute('fetchpriority', 'low');
    document.head.appendChild(link);
}

function ensureProgressBar() {
    let bar = document.getElementById('pobNavigationProgress');
    if (bar) return bar;

    const style = document.createElement('style');
    style.textContent = `
        #pobNavigationProgress {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            z-index: 2147483647;
            pointer-events: none;
            opacity: 0;
            transform-origin: left center;
            transform: scaleX(.08);
            background: linear-gradient(90deg, #c94f80, #7d68b8, #d6b06f);
            box-shadow: 0 0 16px rgba(201,79,128,.35);
            transition: transform .55s cubic-bezier(.2,.7,.2,1), opacity .16s ease;
        }
        body.pob-navigating #pobNavigationProgress {
            opacity: 1;
            transform: scaleX(.82);
        }
        .pob-sidebar a.nav-link.is-navigating {
            background: linear-gradient(135deg, rgba(198,79,122,.18), rgba(127,103,179,.14)) !important;
            color: var(--pob-rose-deep) !important;
        }
        .pob-sidebar a.nav-link.is-navigating .nav-icon {
            animation: pobNavIconPulse .65s ease-in-out infinite alternate;
        }
        @keyframes pobNavIconPulse {
            from { transform: scale(.92); opacity: .7; }
            to { transform: scale(1.08); opacity: 1; }
        }
        @media (prefers-reduced-motion: reduce) {
            #pobNavigationProgress,
            .pob-sidebar a.nav-link.is-navigating .nav-icon { transition: none; animation: none; }
        }
    `;
    document.head.appendChild(style);

    bar = document.createElement('div');
    bar.id = 'pobNavigationProgress';
    bar.setAttribute('aria-hidden', 'true');
    document.body.appendChild(bar);
    return bar;
}

function startNavigation(anchor) {
    if (!isEligibleNavigation(anchor)) return;

    const destination = new URL(anchor.href, window.location.href);
    if (destination.pathname === window.location.pathname && destination.search === window.location.search) return;

    ensureProgressBar();
    document.body.classList.add('pob-navigating');
    document.querySelectorAll('.pob-sidebar a.nav-link.is-navigating').forEach((item) => item.classList.remove('is-navigating'));
    anchor.classList.add('is-navigating');

    if (window.innerWidth <= 992) {
        document.getElementById('sidebar')?.classList.remove('mobile-active');
        document.getElementById('sidebarOverlay')?.classList.remove('show');
        document.body.style.overflow = '';
    }
}

function sidebarAnchorFromEvent(event) {
    return event.target.closest?.('.pob-sidebar a.nav-link[href], .pob-quick-action[href]') || null;
}

document.addEventListener('DOMContentLoaded', () => {
    ensureProgressBar();

    document.addEventListener('pointerover', (event) => {
        const anchor = sidebarAnchorFromEvent(event);
        if (!anchor || hoverTimers.has(anchor)) return;

        const timer = window.setTimeout(() => {
            hoverTimers.delete(anchor);
            prefetch(anchor);
        }, 70);
        hoverTimers.set(anchor, timer);
    }, { passive: true });

    document.addEventListener('pointerout', (event) => {
        const anchor = sidebarAnchorFromEvent(event);
        if (!anchor) return;
        const timer = hoverTimers.get(anchor);
        if (timer) {
            clearTimeout(timer);
            hoverTimers.delete(anchor);
        }
    }, { passive: true });

    document.addEventListener('focusin', (event) => prefetch(sidebarAnchorFromEvent(event)), { passive: true });
    document.addEventListener('touchstart', (event) => prefetch(sidebarAnchorFromEvent(event)), { passive: true });
    document.addEventListener('mousedown', (event) => prefetch(sidebarAnchorFromEvent(event)), { passive: true });

    document.addEventListener('click', (event) => {
        if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
        const anchor = sidebarAnchorFromEvent(event);
        if (anchor) startNavigation(anchor);
    });
});

window.addEventListener('pageshow', () => {
    document.body.classList.remove('pob-navigating');
    document.querySelectorAll('.pob-sidebar a.nav-link.is-navigating').forEach((item) => item.classList.remove('is-navigating'));
});
