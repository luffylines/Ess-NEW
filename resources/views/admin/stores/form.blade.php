@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 720px;">
  <h3 class="mb-3">{{ $store->exists ? 'Edit Store' : 'Add Store' }}</h3>

@include('partials.flash-messages')

  <form method="POST" action="{{ $store->exists ? route('admin.stores.update', $store) : route('admin.stores.store') }}">
    @csrf
    @if($store->exists)
      @method('PUT')
    @endif

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $store->name) }}" required>
    </div>

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Latitude</label>
        <input type="number" step="0.0000001" name="lat" class="form-control" value="{{ old('lat', $store->lat) }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Longitude</label>
        <input type="number" step="0.0000001" name="lng" class="form-control" value="{{ old('lng', $store->lng) }}" required>
      </div>
    </div>

    <div class="row g-3 mt-1">
      <div class="col-md-6">
        <label class="form-label">Radius (meters)</label>
        <input type="number" name="radius_meters" class="form-control" value="{{ old('radius_meters', $store->radius_meters ?? 50) }}" min="1" max="5000" required>
      </div>
      <div class="col-md-6 d-flex align-items-end">
        <div class="form-check form-switch mt-3">
          <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', $store->active) ? 'checked' : '' }}>
          <label class="form-check-label fw-semibold" for="active">
            <span class="status-indicator">{{ old('active', $store->active) ? 'Active' : 'Inactive' }}</span>
          </label>
        </div>
      </div>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('admin.stores.index') }}" class="btn btn-outline-secondary">Cancel</a>
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
