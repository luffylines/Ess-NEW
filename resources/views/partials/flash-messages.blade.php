<!-- Enhanced Flash Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show auto-hide-alert" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show auto-hide-alert" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif



@if(session('status'))
    <div class="alert alert-info alert-dismissible fade show auto-hide-alert" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Info!</strong> {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-warning alert-dismissible fade show auto-hide-alert" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<style>
.auto-hide-alert {
    position: relative;
    border-radius: 10px;
    border-left: 4px solid;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.auto-hide-alert.alert-success {
    border-left-color: #28a745;
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
}

.auto-hide-alert.alert-danger {
    border-left-color: #dc3545;
    background: linear-gradient(135deg, #f8d7da 0%, #f1aeb5 100%);
}

.auto-hide-alert.alert-warning {
    border-left-color: #ffc107;
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
}

.auto-hide-alert.alert-info {
    border-left-color: #17a2b8;
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
}

@keyframes progressBar {
    from { width: 100%; }
    to { width: 0%; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.auto-hide-alert');
    
    alerts.forEach(function(alert) {
        // Add a progress bar for visual feedback
        const progressBar = document.createElement('div');
        progressBar.style.cssText = `
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(0, 0, 0, 0.2);
            width: 100%;
            animation: progressBar 5s linear forwards;
        `;
        alert.style.position = 'relative';
        alert.appendChild(progressBar);

        // Auto-hide after 5 seconds
        setTimeout(function() {
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });
});


    // CSS for progress bar animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes progressBar {
            from { width: 100%; }
            to { width: 0%; }
        }
    `;
    document.head.appendChild(style);

</script>