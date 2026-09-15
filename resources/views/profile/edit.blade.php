@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 py-lg-5">
    <div class="mx-auto" style="max-width: 1180px;">
        <div class="profile-page-hero mb-4">
            <div>
                <span class="profile-page-kicker">Account center</span>
                <h1 class="profile-page-title">Profile & security</h1>
                <p class="profile-page-copy">Manage how your identity appears across Place Of Beauty ESS and keep your account information up to date.</p>
            </div>
            <div class="profile-page-avatar-wrap">
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="profile-page-avatar" onerror="this.onerror=null;this.src='{{ asset('img/default-avatar.png') }}';">
                <div>
                    <strong class="d-block">{{ $user->name }}</strong>
                    <span class="small text-secondary">{{ ucfirst($user->role) }}{{ $user->employee_id ? ' · '.$user->employee_id : '' }}</span>
                </div>
            </div>
        </div>

        @include('profile.partials.update-profile-information-form')

        <div class="mt-4 danger-zone-card">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>

<style>
.profile-page-hero {
    display: flex;
    align-items: end;
    justify-content: space-between;
    gap: 1.5rem;
    padding: clamp(1.3rem, 3vw, 2rem);
    border-radius: 30px;
    border: 1px solid var(--pob-line);
    background:
        radial-gradient(circle at 85% 20%, rgba(127,103,179,.12), transparent 28%),
        radial-gradient(circle at 8% 90%, rgba(198,79,122,.13), transparent 30%),
        var(--pob-surface);
    box-shadow: var(--pob-shadow-soft);
    overflow: hidden;
}
.profile-page-kicker { display: block; margin-bottom: .45rem; color: var(--pob-rose-deep); font-size: .74rem; font-weight: 800; letter-spacing: .13em; text-transform: uppercase; }
.profile-page-title { margin: 0; font-size: clamp(2rem, 4vw, 3.25rem); line-height: 1; letter-spacing: -.045em; font-weight: 800; }
.profile-page-copy { max-width: 650px; margin: .8rem 0 0; color: var(--pob-muted); line-height: 1.7; }
.profile-page-avatar-wrap { display: flex; align-items: center; gap: .8rem; min-width: max-content; padding: .75rem .95rem; border-radius: 20px; border: 1px solid var(--pob-line); background: rgba(255,255,255,.48); }
body.dark .profile-page-avatar-wrap { background: rgba(255,255,255,.035); }
.profile-page-avatar { width: 54px; height: 54px; border-radius: 17px; object-fit: cover; border: 2px solid rgba(255,255,255,.72); box-shadow: 0 8px 20px rgba(45,31,40,.12); }
.danger-zone-card { border-radius: 26px; border: 1px solid rgba(181,72,85,.20); background: rgba(181,72,85,.05); overflow: hidden; }
.danger-zone-card .card { margin: 0; box-shadow: none !important; border: 0 !important; background: transparent !important; }
@media (max-width: 768px) {
    .profile-page-hero { align-items: flex-start; flex-direction: column; }
    .profile-page-avatar-wrap { width: 100%; min-width: 0; }
}
</style>
@endsection
