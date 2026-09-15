<section class="delete-account-section">
    <div class="delete-account-icon"><i class="bi bi-shield-exclamation"></i></div>
    <span class="profile-kicker delete-kicker">Danger zone</span>
    <h4 class="profile-panel-title fs-5 text-danger">Delete account</h4>
    <p class="delete-account-copy">
        Permanently remove your ESS account and associated account data. This action cannot be undone.
    </p>

    <button type="button" class="btn delete-account-button w-100" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
        <i class="bi bi-trash3 me-2"></i>Delete account
    </button>

    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content delete-modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header border-0 pb-0">
                        <div>
                            <span class="profile-kicker delete-kicker">Permanent action</span>
                            <h5 class="modal-title fw-bold text-danger" id="confirmUserDeletionLabel">Delete your account?</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body pt-3">
                        <p class="text-secondary mb-4">
                            Your account and associated data will be permanently deleted. Enter your password to confirm.
                        </p>

                        <label for="delete_account_password" class="form-label fw-bold">Password</label>
                        <input
                            id="delete_account_password"
                            name="password"
                            type="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4"><i class="bi bi-trash3 me-2"></i>Delete permanently</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->userDeletion->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalElement = document.getElementById('confirmUserDeletion');
                if (modalElement && window.bootstrap) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif
</section>

<style>
.delete-account-section{margin:0}.delete-account-icon{width:46px;height:46px;display:grid;place-items:center;margin-bottom:1rem;border-radius:15px;background:rgba(181,72,85,.10);color:var(--pob-danger);font-size:1.15rem}.delete-kicker{color:var(--pob-danger)!important}.delete-account-copy{margin:.55rem 0 1rem;color:var(--pob-muted);font-size:.9rem;line-height:1.6}.delete-account-button{min-height:46px;border:1px solid rgba(181,72,85,.22);border-radius:999px;background:rgba(181,72,85,.07);color:var(--pob-danger);font-weight:800;transition:transform .2s ease,background .2s ease,box-shadow .2s ease}.delete-account-button:hover{transform:translateY(-1px);background:var(--pob-danger);color:#fff;box-shadow:0 10px 24px rgba(181,72,85,.18)}.delete-modal-content{border:1px solid var(--pob-line);border-radius:26px;background:var(--pob-surface-solid);box-shadow:0 28px 80px rgba(39,25,34,.22);overflow:hidden}body.dark .delete-modal-content{background:#211c21;color:var(--pob-text)}body.dark .delete-modal-content .btn-close{filter:invert(1)}
</style>
