@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Allowed Networks</h3>
    <a href="{{ route('admin.networks.create') }}" class="btn btn-primary">Add Network</a>
  </div>

@include('partials.flash-messages')

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>IP Ranges</th>
          <th>Active</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($networks as $network)
        <tr>
          <td>{{ $network->id }}</td>
          <td>{{ $network->name }}</td>
          <td>
            @if(is_array($network->ip_ranges))
              <code>{{ implode(', ', $network->ip_ranges) }}</code>
            @endif
          </td>
          <td>
            <div class="form-check form-switch">
              <input class="form-check-input status-toggle" 
                     type="checkbox" 
                     id="network-{{ $network->id }}"
                     data-id="{{ $network->id }}"
                     data-type="network"
                     {{ $network->active ? 'checked' : '' }}>
              <label class="form-check-label" for="network-{{ $network->id }}">
                <span class="status-text">{{ $network->active ? 'Active' : 'Inactive' }}</span>
              </label>
            </div>
          </td>
          <td class="text-end">
            <a href="{{ route('admin.networks.edit', $network) }}" class="btn btn-sm btn-outline-primary">Edit</a>
            <form action="{{ route('admin.networks.destroy', $network) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this network?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </td>
        </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted">No networks yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $networks->links() }}
</div>

<style>
.form-switch .form-check-input {
  width: 3em;
  height: 1.5em;
  border-radius: 3em;
  background-color: #e9ecef;
  border: 1px solid #ced4da;
  transition: all 0.3s ease;
}

.form-switch .form-check-input:checked {
  background-color: #198754;
  border-color: #198754;
}

.form-switch .form-check-input:focus {
  border-color: #86b7fe;
  outline: 0;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-switch .form-check-label {
  margin-left: 0.5rem;
  font-weight: 500;
}

.status-text {
  color: #6c757d;
  font-size: 0.875rem;
}

.form-switch .form-check-input:checked + .form-check-label .status-text {
  color: #198754;
}

.status-toggle:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.status-toggle');
    
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const type = this.dataset.type;
            const isActive = this.checked;
            const statusText = this.parentElement.querySelector('.status-text');
            
            // Disable toggle while processing
            this.disabled = true;
            
            // Update status text immediately
            statusText.textContent = isActive ? 'Active' : 'Inactive';
            
            // Send AJAX request
            fetch(`/admin/${type}s/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    active: isActive
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showToast(`${type.charAt(0).toUpperCase() + type.slice(1)} ${data.status} successfully`, 'success');
                } else {
                    // Revert toggle on error
                    this.checked = !isActive;
                    statusText.textContent = !isActive ? 'Active' : 'Inactive';
                    showToast('Failed to update status', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert toggle on error
                this.checked = !isActive;
                statusText.textContent = !isActive ? 'Active' : 'Inactive';
                showToast('Network error occurred', 'error');
            })
            .finally(() => {
                // Re-enable toggle
                this.disabled = false;
            });
        });
    });
    
    function showToast(message, type) {
        // Create a simple toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 350px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 3000);
    }
});
</script>
@endsection