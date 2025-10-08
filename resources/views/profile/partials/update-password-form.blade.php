<section>
    <header class="mb-3">
        <h2 class="h4 fw-medium">Update Password</h2>
        <p class="text-muted small">Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="post" action="{{ route('profile.updatePassword') }}" class="mt-4 mb-3" novalidate>
        @csrf
        @method('patch')

        {{-- Current Password --}}
        <div class="mb-3">
            <label for="update_password_current_password" class="form-label fw-semibold">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
            @error('current_password', 'updatePassword')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- New Password --}}
        <div class="mb-3">
            <label for="update_password_password" class="form-label fw-semibold">New Password</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
            @error('password', 'updatePassword')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label fw-semibold">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Submit Button + Success Message --}}
        <div class="d-flex flex-column align-items-start">
            <button type="submit" class="btn btn-primary mb-1">Save</button>
            @if (session('status') === 'password-updated')
                <span class="text-success small">Password updated successfully.</span>
            @endif
        </div>
    </form>
</section>
