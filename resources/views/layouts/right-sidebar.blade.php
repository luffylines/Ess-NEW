<!-- THEME config -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="demo_config">
  <div class="position-absolute top-50 end-100 visible">
    <button type="button" class="btn btn-primary btn-icon translate-middle-y rounded-end-0" data-bs-toggle="offcanvas" data-bs-target="#demo_config">
      <img src="/img/settings.gif" alt="Settings" style="width:24px; height:24px;">
    </button>
  </div>

  <div class="offcanvas-header border-bottom py-0">
    <h5 class="offcanvas-title py-3">Theme Configuration</h5>
    <button type="button" class="btn btn-light btn-sm btn-icon border-transparent rounded-pill" data-bs-dismiss="offcanvas">
      <i class="ph-x"></i>
    </button>
  </div>

  <div class="offcanvas-body">
    <div class="fw-semibold mb-2">Color mode</div>
    <div class="list-group mb-3">

      <label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-2">
        <div class="d-flex flex-fill my-1">
          <div class="form-check-label d-flex me-2 align-items-center">
            <img src="/img/light-on.png" alt="Light Theme" style="width:24px; height:24px; margin-right:12px;">
            <div>
              <span class="fw-bold">Light theme</span>
              <div class="fs-sm text-muted">Set light theme or reset to default</div>
            </div>
          </div>
          <input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="light" checked>
        </div>
      </label>

      <label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-2">
        <div class="d-flex flex-fill my-1">
          <div class="form-check-label d-flex me-2 align-items-center">
            <img src="/img/light-off.png" alt="Dark Theme" style="width:24px; height:24px; margin-right:12px;">
            <div>
              <span class="fw-bold">Dark theme</span>
              <div class="fs-sm text-muted">Switch to dark theme</div>
            </div>
          </div>
          <input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="dark">
        </div>
      </label>

      <label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-0">
        <div class="d-flex flex-fill my-1">
          <div class="form-check-label d-flex me-2 align-items-center">
            <img src="/img/settings.gif" alt="Auto Theme" style="width:24px; height:24px; margin-right:12px;">
            <div>
              <span class="fw-bold">Auto theme</span>
              <div class="fs-sm text-muted">Set theme based on system mode</div>
            </div>
          </div>
          <input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="auto">
        </div>
      </label>

    </div>

    <div class="fw-semibold mb-2">Direction</div>
    <div class="list-group mb-3">
      <label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-0">
        <div class="d-flex flex-fill my-1">
          <div class="form-check-label d-flex me-2 align-items-center">
            <img src="/img/direction.png" alt="RTL Direction" style="width:24px; height:24px; margin-right:12px;">
            <div>
              <span class="fw-bold">RTL direction</span>
              <div class="text-muted">Toggle between LTR and RTL</div>
            </div>
          </div>
        <input type="checkbox" name="layout-direction" value="rtl" class="form-check-input cursor-pointer m-0 ms-auto">
        </div>
      </label>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const themeRadios = document.querySelectorAll('input[name="main-theme"]');
    const rtlToggle = document.querySelector('input[name="layout-direction"]');
    const body = document.body;
    const html = document.documentElement;
    const offcanvas = document.getElementById('demo_config');
    const navbar = document.querySelector('.navbar');

    function applyTheme(theme) {
      // Remove existing theme classes from both body and html
      body.classList.remove('light', 'dark');
      html.classList.remove('dark');

      if (theme === 'auto') {
        const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Apply to both body (Bootstrap) and html (Tailwind)
        if (isDark) {
          body.classList.add('dark');
          html.classList.add('dark');
        } else {
          body.classList.add('light');
        }

        // Update navbar classes for dark/light
        if (navbar) {
          navbar.classList.toggle('navbar-dark', isDark);
          navbar.classList.toggle('bg-dark', isDark);
          navbar.classList.toggle('navbar-light', !isDark);
          navbar.classList.toggle('bg-light', !isDark);
        }

        // Listen for system theme changes
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', (e) => {
          // Update both body and html
          body.classList.toggle('dark', e.matches);
          body.classList.toggle('light', !e.matches);
          html.classList.toggle('dark', e.matches);
          
          if (navbar) {
            navbar.classList.toggle('navbar-dark', e.matches);
            navbar.classList.toggle('bg-dark', e.matches);
            navbar.classList.toggle('navbar-light', !e.matches);
            navbar.classList.toggle('bg-light', !e.matches);
          }
        });
      } else {
        // Apply theme to both body (Bootstrap) and html (Tailwind)
        body.classList.add(theme);
        if (theme === 'dark') {
          html.classList.add('dark');
        }
        
        // Update navbar classes
        if (navbar) {
          navbar.classList.toggle('navbar-dark', theme === 'dark');
          navbar.classList.toggle('bg-dark', theme === 'dark');
          navbar.classList.toggle('navbar-light', theme === 'light');
          navbar.classList.toggle('bg-light', theme === 'light');
        }
      }
    }

    // Load saved theme or default
    const savedTheme = localStorage.getItem('theme') || 'light';
    const savedRadio = document.querySelector(`input[name="main-theme"][value="${savedTheme}"]`);
    if (savedRadio) {
      savedRadio.checked = true;
    }
    applyTheme(savedTheme);

    // Theme change listeners
    themeRadios.forEach(radio => {
      radio.addEventListener('change', () => {
        applyTheme(radio.value);
        localStorage.setItem('theme', radio.value);
      });
    });

    // RTL toggle handling
    if (rtlToggle) {
      const savedRTL = localStorage.getItem('rtl') === 'true';
      rtlToggle.checked = savedRTL;
      
      if (savedRTL) {
        html.setAttribute('dir', 'rtl');
        offcanvas.classList.remove('offcanvas-end');
        offcanvas.classList.add('offcanvas-start');
        // Move button to start position
        const button = offcanvas.querySelector('.position-absolute');
        button.classList.remove('end-100');
        button.classList.add('start-100');
      }

      rtlToggle.addEventListener('change', () => {
        const isRTL = rtlToggle.checked;
        
        if (isRTL) {
          html.setAttribute('dir', 'rtl');
          offcanvas.classList.remove('offcanvas-end');
          offcanvas.classList.add('offcanvas-start');
          const button = offcanvas.querySelector('.position-absolute');
          button.classList.remove('end-100');
          button.classList.add('start-100');
        } else {
          html.removeAttribute('dir');
          offcanvas.classList.remove('offcanvas-start');
          offcanvas.classList.add('offcanvas-end');
          const button = offcanvas.querySelector('.position-absolute');
          button.classList.remove('start-100');
          button.classList.add('end-100');
        }
        
        localStorage.setItem('rtl', isRTL);
      });
    }
  });
</script>