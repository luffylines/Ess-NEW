@php
    $user = Auth::user();
@endphp

@if(session('profile_message'))
    <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4" role="alert">
        <i class="fas fa-circle-check me-2"></i>{{ session('profile_message') }}
    </div>
@endif

<div class="profile-settings-grid">
    <section class="profile-panel profile-panel-main">
        <div class="profile-panel-heading">
            <div>
                <span class="profile-kicker">Personal profile</span>
                <h3 class="profile-panel-title">Your employee identity</h3>
                <p class="profile-panel-copy">Keep your photo and contact details current so your profile looks consistent across the ESS workspace.</p>
            </div>
            <span class="profile-status-pill"><i class="fas fa-shield-heart"></i> Private</span>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileInformationForm">
            @csrf
            @method('PATCH')

            <div class="photo-editor">
                <div class="photo-ring-wrap">
                    <img
                        src="{{ $user->profile_photo_url }}"
                        alt="{{ $user->name }} profile photo"
                        id="profile-photo-preview"
                        class="photo-preview"
                        onerror="this.onerror=null;this.src='{{ asset('img/default-avatar.png') }}';"
                    >
                    <span class="photo-online-dot" aria-hidden="true"></span>
                </div>

                <div class="photo-editor-copy">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <h4 class="mb-0 fw-bold">Profile photo</h4>
                        @if($user->profile_photo)
                            <span class="badge rounded-pill text-bg-success-subtle text-success-emphasis">Photo saved</span>
                        @endif
                    </div>
                    <p class="text-secondary mb-3">Choose a clear portrait. JPG, PNG, GIF and WebP are accepted up to 5 MB.</p>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <label for="profile_photo" class="photo-upload-button">
                            <i class="fas fa-camera"></i>
                            <span>Choose new photo</span>
                        </label>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/gif,image/webp" class="visually-hidden">
                        <span id="profile-photo-name" class="small text-secondary">No new file selected</span>
                    </div>

                    @error('profile_photo')
                        <div class="text-danger small mt-2"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="profile-form-divider"></div>

            <div class="row g-3">
                <div class="col-12">
                    <label for="name" class="form-label fw-bold">Full name</label>
                    <input id="name" name="name" type="text" class="form-control form-control-lg" value="{{ old('name', $user->name) }}" required autocomplete="name">
                    @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="gender" class="form-label fw-bold">Gender <span class="text-secondary fw-normal">(optional)</span></label>
                    <select id="gender" name="gender" class="form-select form-select-lg">
                        <option value="">Prefer not to specify</option>
                        <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
                        <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                        <option value="other" @selected(old('gender', $user->gender) === 'other')>Other</option>
                    </select>
                    @error('gender')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label fw-bold">Phone number <span class="text-secondary fw-normal">(optional)</span></label>
                    <input id="phone" name="phone" type="tel" class="form-control form-control-lg" value="{{ old('phone', $user->phone) }}" placeholder="+639171234567" autocomplete="tel">
                    @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label for="address" class="form-label fw-bold">Address <span class="text-secondary fw-normal">(optional)</span></label>
                    <textarea id="address" name="address" rows="3" class="form-control" placeholder="Your current address">{{ old('address', $user->address) }}</textarea>
                    @error('address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
                <div class="small text-secondary"><i class="fas fa-lock me-1"></i>Your profile details are visible only where the ESS workflow needs them.</div>
                <button type="submit" class="btn profile-save-button px-4">
                    <i class="fas fa-sparkles me-2"></i>Save profile
                </button>
            </div>
        </form>
    </section>

    <aside class="profile-side-stack">
        <section class="profile-panel">
            <span class="profile-kicker">Account email</span>
            <h4 class="profile-panel-title fs-5">Sign-in address</h4>
            <p class="profile-panel-copy">Change the email used for account notices and verification.</p>

            <form method="POST" action="{{ route('profile.updateEmail') }}">
                @csrf
                @method('PATCH')

                <label for="email" class="form-label fw-bold">Email</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="email">
                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="profile-inline-note mt-3">
                        <i class="fas fa-envelope-circle-check"></i>
                        <div>
                            <strong>Email verification needed</strong>
                            <div class="small text-secondary">Resend the verification message if you did not receive it.</div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-link px-0 small" onclick="document.getElementById('send-verification').submit();">Resend verification email</button>
                @endif

                <button type="submit" class="btn btn-outline-dark rounded-pill w-100 mt-3">Update email</button>
            </form>
        </section>

        <section class="profile-panel">
            <span class="profile-kicker">Security</span>
            <h4 class="profile-panel-title fs-5">Update password</h4>
            <p class="profile-panel-copy">Use a strong password you do not reuse on other services.</p>

            <form method="POST" action="{{ route('profile.updatePassword') }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="update_password_current_password" class="form-label fw-bold">Current password</label>
                    <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
                    @error('current_password', 'updatePassword')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="update_password_password" class="form-label fw-bold">New password</label>
                    <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password">
                    @error('password', 'updatePassword')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="update_password_password_confirmation" class="form-label fw-bold">Confirm new password</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                </div>

                <button type="submit" class="btn btn-outline-dark rounded-pill w-100">Save password</button>
            </form>
        </section>

        <section class="profile-panel profile-danger-panel">
            @include('profile.partials.delete-user-form')
        </section>
    </aside>
</div>

<form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="d-none">
    @csrf
</form>

<style>
.profile-settings-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.65fr) minmax(300px, .85fr);
    gap: 1.25rem;
    align-items:start;
}
.profile-side-stack { display: grid; gap: 1.25rem; align-content: start; }
.profile-panel {
    background: color-mix(in srgb, var(--pob-surface-solid) 88%, transparent);
    border: 1px solid var(--pob-line);
    border-radius: 28px;
    padding: clamp(1.25rem, 3vw, 2rem);
    box-shadow: var(--pob-shadow-soft);
    backdrop-filter: blur(18px);
}
.profile-panel-main { overflow: hidden; position: relative; }
.profile-panel-main::before {
    content: '';
    position: absolute;
    width: 260px;
    height: 260px;
    border-radius: 50%;
    right: -140px;
    top: -140px;
    background: radial-gradient(circle, rgba(198,79,122,.18), transparent 70%);
    pointer-events: none;
}
.profile-panel-heading { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
.profile-kicker { display: block; color: var(--pob-rose-deep); font-size: .72rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; margin-bottom: .45rem; }
.profile-panel-title { margin: 0; font-weight: 800; letter-spacing: -.025em; }
.profile-panel-copy { color: var(--pob-muted); margin: .55rem 0 0; line-height: 1.65; }
.profile-status-pill { display: inline-flex; align-items: center; gap: .4rem; border: 1px solid var(--pob-line); border-radius: 999px; padding: .45rem .7rem; font-size: .75rem; font-weight: 700; color: var(--pob-muted); background: rgba(255,255,255,.45); white-space: nowrap; }
.photo-editor { display: grid; grid-template-columns: 112px minmax(0,1fr); gap: 1.35rem; align-items:center; padding: 1.15rem; border-radius: 24px; background: linear-gradient(135deg, rgba(198,79,122,.08), rgba(127,103,179,.07)); border: 1px solid rgba(198,79,122,.12); }
.photo-ring-wrap { position: relative; width:112px; height:112px; padding:4px; border-radius:32px; background:linear-gradient(135deg,var(--pob-rose),var(--pob-violet),var(--pob-champagne)); box-shadow:0 16px 36px rgba(127,67,102,.18); justify-self:center; }
.photo-preview { width:100%; height:100%; border-radius:28px; object-fit:cover; object-position:center 18%; display:block; background:#fff; border:4px solid var(--pob-surface-solid); }
.photo-online-dot { position:absolute; right:-2px; bottom:8px; width:22px; height:22px; border-radius:50%; background:#2fa56f; border:4px solid var(--pob-surface-solid); }
.photo-editor-copy { min-width:0; align-self:center; }
.photo-upload-button { display:inline-flex; align-items:center; gap:.55rem; border-radius:999px; padding:.7rem 1rem; background:var(--pob-text); color:var(--pob-surface-solid); font-weight:800; cursor:pointer; transition:transform .2s ease,box-shadow .2s ease; }
.photo-upload-button:hover { transform:translateY(-2px); box-shadow:0 10px 24px rgba(35,25,31,.16); }
.profile-form-divider { height:1px; background:var(--pob-line); margin:1.5rem 0; }
.profile-save-button { border:0; border-radius:999px; min-height:46px; color:#fff; font-weight:800; background:linear-gradient(135deg,var(--pob-rose-deep),var(--pob-rose),var(--pob-violet)); box-shadow:0 12px 28px rgba(159,49,91,.22); }
.profile-save-button:hover { color:#fff; transform:translateY(-1px); }
.profile-inline-note { display:flex; gap:.75rem; align-items:flex-start; padding:.85rem; border-radius:16px; background:rgba(216,183,122,.13); border:1px solid rgba(216,183,122,.22); }
.profile-inline-note i { color:var(--pob-warning); margin-top:.2rem; }
.profile-danger-panel { border-color:rgba(181,72,85,.16); background:linear-gradient(135deg,rgba(181,72,85,.055),rgba(198,79,122,.025)); }
.profile-danger-panel section { margin:0!important; }
body.dark .profile-panel { background:rgba(34,29,34,.84); }
body.dark .profile-status-pill { background:rgba(255,255,255,.04); }
body.dark .btn-outline-dark { color:var(--pob-text); border-color:var(--pob-line); }
body.dark .btn-outline-dark:hover { background:rgba(255,255,255,.08); color:var(--pob-text); }
@media (max-width: 992px) {
    .profile-settings-grid { grid-template-columns:1fr; }
    .profile-side-stack { grid-template-columns:repeat(2,minmax(0,1fr)); }
    .profile-danger-panel { grid-column:1 / -1; }
}
@media (max-width: 700px) {
    .profile-side-stack { grid-template-columns:1fr; }
    .profile-danger-panel { grid-column:auto; }
}
@media (max-width: 600px) {
    .photo-editor { grid-template-columns:1fr; text-align:center; }
    .photo-ring-wrap { width:96px; height:96px; }
    .photo-editor-copy .d-flex { justify-content:center; }
    .profile-panel-heading { flex-direction:column; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('profile_photo');
    const preview = document.getElementById('profile-photo-preview');
    const filename = document.getElementById('profile-photo-name');
    const phone = document.getElementById('phone');

    input?.addEventListener('change', (event) => {
        const file = event.target.files?.[0];
        if (!file) return;

        if (filename) filename.textContent = file.name;
        const objectUrl = URL.createObjectURL(file);
        if (preview) {
            preview.src = objectUrl;
            preview.onload = () => URL.revokeObjectURL(objectUrl);
        }
    });

    phone?.addEventListener('input', (event) => {
        let value = event.target.value.replace(/[^\d+]/g, '');
        if (value.startsWith('09')) value = '+63' + value.slice(1);
        else if (value.startsWith('63')) value = '+' + value;
        else if (value.startsWith('9') && !value.startsWith('+')) value = '+63' + value;
        if (value.startsWith('+63')) value = value.slice(0, 13);
        event.target.value = value;
    });
});
</script>