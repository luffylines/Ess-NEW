@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 720px;">
  <h3 class="mb-3">{{ $network->exists ? 'Edit Network' : 'Add Network' }}</h3>

@include('partials.flash-messages')

  <form method="POST" action="{{ $network->exists ? route('admin.networks.update', $network) : route('admin.networks.store') }}">
    @csrf
    @if($network->exists)
      @method('PUT')
    @endif

    <div class="mb-3">
      <label class="form-label">Network Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $network->name) }}" placeholder="e.g., Office Wi-Fi, VPN Network" required>
    </div>

    <div class="mb-3">
      <label class="form-label">IP Ranges (one per line)</label>
      <textarea name="ip_ranges_text" rows="8" class="form-control" placeholder="203.0.113.5&#10;198.51.100.0/24&#10;192.168.1.0/24" required>{{ old('ip_ranges_text', is_array($network->ip_ranges) ? implode("\n", $network->ip_ranges) : '') }}</textarea>
      <div class="form-text">
        Enter public IPs or CIDR ranges that should allow attendance without GPS:<br>
        • <code>203.0.113.5</code> - single IP address<br>
        • <code>192.168.1.0/24</code> - CIDR range (192.168.1.1 to 192.168.1.254)<br>
        • <code>10.0.0.0/8</code> - large private network range
      </div>
    </div>

    <div class="mb-3">
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" name="active" id="active" value="1" {{ old('active', $network->active) ? 'checked' : '' }}>
        <label class="form-check-label" for="active">
          Status 
          <span class="status-indicator">{{ old('active', $network->active) ? 'Active' : 'Inactive' }}</span>
        </label>
      </div>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('admin.networks.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>
</div>

<style>
.form-switch .form-check-input {
  width: 3em;
  height: 1.5em;
  border-radius: 3em;
}

.status-indicator {
  margin-left: 0.5rem;
  font-size: 0.9rem;
  color: #6c757d;
}

.form-switch .form-check-input:checked + .form-check-label .status-indicator {
  color: #198754;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('active');
    const indicator = document.querySelector('.status-indicator');
    
    if (toggle && indicator) {
        toggle.addEventListener('change', function() {
            indicator.textContent = this.checked ? 'Active' : 'Inactive';
        });
    }
});
</script>
@endsection