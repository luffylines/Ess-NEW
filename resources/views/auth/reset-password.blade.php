@extends('layouts.guest')

@section('content')
<style>
:root{
  --left-width: 36%;
  --card-max: 520px;
  --card-radius: 12px;
  --card-shadow: 0 10px 30px rgba(18,38,63,0.12), 0 2px 6px rgba(18,38,63,0.06);
}

/* Full-bleed wrapper to remove page side gaps */
.container-full {
  width: 100%;
  margin: 0;
  padding: 0;
}

/* Two-column auth layout */
.auth-wrapper {
  min-height: 100vh;
  display: flex;
  flex-wrap: nowrap;
  width: 100%;
}

/* Left welcome panel */
.auth-left {
  flex: 0 0 var(--left-width);
  min-width: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2.5rem;
  background: linear-gradient(135deg, #e91e63 0%, #ff6f91 100%);
  color: #fff;
}

/* Right panel: card sits nearer the top on desktop */
.auth-right {
  flex: 1 1 calc(64%);
  min-width: 320px;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding-top: 2.25rem; /* desktop top spacing */
  padding-bottom: 2rem;
  box-sizing: border-box;
}

/* Use your existing card but enforce sizing and shadow */
.auth-card {
  width: 100%;
  max-width: var(--card-max);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  border: 1px solid rgba(18,38,63,0.04);
  background: #fff;
  box-sizing: border-box;
  margin: 0 1rem;
}

/* Mobile-specific adjustments: center card and remove extra top/side gaps */
@media (max-width: 991.98px) {
  .auth-wrapper { flex-direction: column; align-items: stretch; }
  .auth-left { display: none !important; }
  .auth-right {
    align-items: center !important;
    justify-content: center !important;
    padding-top: 1rem !important;    /* reduce top gap on mobile */
    padding-left: 1rem !important;
    padding-right: 1rem !important;
  }
  .auth-card {
    max-width: 100% !important;
    margin: 0 !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 20px rgba(18,38,63,0.10) !important;
    padding: 1rem !important;
  }
  .container-full, .container-fluid { padding-left: 0 !important; padding-right: 0 !important; }
}

/* Tweak for very wide screens */
@media (min-width: 1400px) {
  :root { --left-width: 34%; }
  .auth-card { max-width: 560px; }
}

/* Keep your button and mobile rules intact */
.reset-btn{
  background-color:#0d6efd !important;
  border:none !important;
  color:#fff !important;
  padding:12px 16px !important;
  font-size:16px !important;
  border-radius:8px;
  display:block;
}

@media (max-width:576px){
  .card-body{ padding:1.25rem !important; }
  .form-control-lg{ font-size:1rem; padding:0.75rem 1rem; }
  .btn-lg{ font-size:1rem; padding:0.75rem 1rem; }
}

/* Small utility */
.input-group .form-control { border-radius: 0.5rem; }
.input-group-text { border-radius: 0.5rem; cursor: pointer; }
</style>

<div class="container-fluid container-full">
  <div class="auth-wrapper">
    <!-- Left welcome panel -->
    <div class="auth-left d-none d-md-flex">
      <div class="text-center w-100">
        <h1 class="display-4 fw-bold mb-2">Welcome</h1>
        <p class="fs-5 mb-0">Place Of Beauty - Employee Self-Service System</p>
      </div>
    </div>

    <!-- Right form panel (your original card markup preserved) -->
    <div class="auth-right">
      <div class="card auth-card shadow-lg rounded-4 bg-white border-0">
        <div class="card-body p-3 p-sm-4 p-md-5">

          <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <input type="hidden" name="email" value="{{ $request->email }}">

            <div class="text-center mb-4">
              <i class="bi bi-person-circle display-5 text-primary mb-2"></i>

              @php
                $user = \App\Models\User::where('email', $request->email)->first();
              @endphp

              <div class="fw-semibold">{{ $user ? $user->name : 'User' }}</div>
              <div class="text-muted small">{{ $request->email }}</div>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">New Password</label>

              <div class="input-group">
                <input
                  id="password"
                  name="password"
                  type="password"
                  class="form-control @error('password') is-invalid @enderror"
                  required
                  autocomplete="new-password"
                  aria-describedby="passwordHelp"
                >

                <span class="input-group-text toggle-password" data-target="password">
                  <i class="bi bi-eye-slash"></i>
                </span>
              </div>

              <div id="passwordHelp" class="form-text small">
                At least 6 characters with one uppercase letter.
              </div>

              @error('password')
              <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="password_confirmation" class="form-label fw-semibold">
                Confirm Password
              </label>

              <div class="input-group">
                <input
                  id="password_confirmation"
                  name="password_confirmation"
                  type="password"
                  class="form-control"
                  required
                >

                <span class="input-group-text toggle-password" data-target="password_confirmation">
                  <i class="bi bi-eye-slash"></i>
                </span>
              </div>

              <div id="confirmPasswordFeedback" class="form-text mt-1"></div>
            </div>

            <div class="d-grid gap-2 mt-3">
              <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                Reset Password
              </button>
            </div>

            <div class="text-center mt-3">
              <a href="{{ route('login') }}" class="text-muted small">
                ← Back to Login
              </a>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

  const password = document.getElementById('password');
  const confirm = document.getElementById('password_confirmation');
  const feedback = document.getElementById('confirmPasswordFeedback');
  const submitBtn = document.getElementById('submitBtn');

  function checkPasswordMatch() {

    const val = password.value || '';
    const cVal = confirm.value || '';
    const hasUpper = /[A-Z]/.test(val);

    if (!val && !cVal) {
      feedback.textContent = '';
      submitBtn.disabled = true;
      return;
    }

    if (val !== cVal) {
      feedback.textContent = 'Passwords do not match.';
      feedback.className = 'form-text text-danger';
      submitBtn.disabled = true;
    }
    else if (val.length < 6 || !hasUpper) {
      feedback.textContent = 'Requirements not met.';
      feedback.className = 'form-text text-danger';
      submitBtn.disabled = true;
    }
    else {
      feedback.textContent = 'Passwords match!';
      feedback.className = 'form-text text-success';
      submitBtn.disabled = false;
    }

  }

  password.addEventListener('input', checkPasswordMatch);
  confirm.addEventListener('input', checkPasswordMatch);

  document.querySelectorAll('.toggle-password').forEach(btn => {

    btn.addEventListener('click', function() {

      const targetId = this.getAttribute('data-target');
      const input = document.getElementById(targetId);
      const icon = this.querySelector('i');

      if (!input) return;

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
      } 
      else {
        input.type = 'password';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
      }

    });

  });

});
</script>
@endsection
