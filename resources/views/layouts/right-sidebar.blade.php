<!-- THEME config -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="demo_config">
  <div class="">
    <button type="button" class="" data-bs-toggle="offcanvas" data-bs-target="#demo_config">
      <img src="/img/settings.gif" alt="Settings" style="width:24px; height:24px;">
    </button>
  </div>

  <div class="">
    <h5 class="offcanvas-title py-3">Theme Configuration</h5>
    <button type="button" class="" data-bs-dismiss="offcanvas">
      <i class="bi bi-x"></i>
    </button>
  </div>

  <div class="offcanvas-body">
    <div class="mb-2">Color mode</div>
    <div class="mb-3">

      <label class="rounded mb-2">
        <div class="">
          <div class="">
            <img src="/img/light-on.png" alt="Light Theme" style="width:24px; height:24px; margin-right:12px;">
            <div>
              <span class="fw-bold">Light theme</span>
              <div class="fs-sm text-muted">Set light theme or reset to default</div>
            </div>
          </div>
          <input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="light" checked>
        </div>
      </label>

      <label class="rounded mb-2">
        <div class="">
          <div class="">
            <img src="/img/light-off.png" alt="Dark Theme" style="width:24px; height:24px; margin-right:12px;">
            <div>
              <span class="fw-bold">Dark theme</span>
              <div class="fs-sm text-muted">Switch to dark theme</div>
            </div>
          </div>
          <input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="dark">
        </div>
      </label>

      <label class="rounded">
        <div class="">
          <div class="">
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

    <div class="mb-2">Direction</div>
    <div class="mb-3">
      <label class="rounded">
        <div class="">
          <div class="">
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
      // Remove existing theme classes
      body.classList.remove('light', 'dark');

      if (theme === 'auto') {
        const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        body.classList.add(isDark ? 'dark' : 'light');

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
          body.classList.toggle('dark', e.matches);
          body.classList.toggle('light', !e.matches);
          
          if (navbar) {
            navbar.classList.toggle('navbar-dark', e.matches);
            navbar.classList.toggle('bg-dark', e.matches);
            navbar.classList.toggle('navbar-light', !e.matches);
            navbar.classList.toggle('bg-light', !e.matches);
          }
        });
      } else {
        body.classList.add(theme);
        
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
        
        // Update the navbar toggle button icon
        const themeToggleIcon = document.getElementById('themeIcon');
        if (themeToggleIcon) {
          themeToggleIcon.classList.remove('ph-moon', 'ph-sun');
          if (radio.value === 'dark') {
            themeToggleIcon.classList.add('ph-sun');
          } else {
            themeToggleIcon.classList.add('ph-moon');
          }
        }
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