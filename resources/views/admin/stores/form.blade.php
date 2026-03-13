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


    <div class="mb-3 row align-items-end">
      <div class="col-md-7">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $store->name) }}" required>
      </div>
      <div class="col-md-5">
        <label class="form-label">Location Name</label>
        <input type="text" id="location-name" name="location_name" class="form-control" value="{{ old('location_name', $store->location_name ?? '') }}" readonly>
      </div>
    </div>


    <div class="mb-3">
      <label class="form-label">Location</label>
      <div id="map" style="height: 320px; border-radius: 8px; margin-bottom: 10px;"></div>
      <div class="d-flex gap-2 mb-2">
        <input type="text" id="address-search" class="form-control" placeholder="Search address..." style="max-width: 350px;">
        <button type="button" id="detect-location" class="btn btn-outline-secondary">Detect My Location</button>
      </div>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Latitude</label>
          <input type="number" step="0.0000001" name="lat" id="lat-input" class="form-control" value="{{ old('lat', $store->lat) }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Longitude</label>
          <input type="number" step="0.0000001" name="lng" id="lng-input" class="form-control" value="{{ old('lng', $store->lng) }}" required>
        </div>
      </div>
    </div>
</style>

<!-- Leaflet & Geocoder Scripts -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var defaultLat = parseFloat(document.getElementById('lat-input').value) || 14.6532;
  var defaultLng = parseFloat(document.getElementById('lng-input').value) || 121.0458;
  var map = L.map('map').setView([defaultLat, defaultLng], 13);
  var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
  var geocoder = L.Control.geocoder({ defaultMarkGeocode: false }).addTo(map);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
  }).addTo(map);


  // Helper: reverse geocode and fill location name
  function updateLocationName(lat, lng) {
    if (window.L && window.L.Control && window.L.Control.Geocoder) {
      L.Control.Geocoder.nominatim().reverse({lat: lat, lng: lng}, map.options.crs.scale(map.getZoom()), function(results) {
        if (results && results.length > 0) {
          document.getElementById('location-name').value = results[0].name;
        } else {
          document.getElementById('location-name').value = '';
        }
      });
    }
  }

  // Update lat/lng inputs and location name when marker moves
  marker.on('move', function(e) {
    document.getElementById('lat-input').value = e.latlng.lat.toFixed(7);
    document.getElementById('lng-input').value = e.latlng.lng.toFixed(7);
    updateLocationName(e.latlng.lat, e.latlng.lng);
  });

  // Move marker when lat/lng inputs change
  document.getElementById('lat-input').addEventListener('change', function() {
    var lat = parseFloat(this.value);
    var lng = parseFloat(document.getElementById('lng-input').value);
    if (!isNaN(lat) && !isNaN(lng)) {
      marker.setLatLng([lat, lng]);
      updateLocationName(lat, lng);
    }
    map.panTo([lat, lng]);
  });
  document.getElementById('lng-input').addEventListener('change', function() {
    var lat = parseFloat(document.getElementById('lat-input').value);
    var lng = parseFloat(this.value);
    if (!isNaN(lat) && !isNaN(lng)) {
      marker.setLatLng([lat, lng]);
      updateLocationName(lat, lng);
    }
    map.panTo([lat, lng]);
  });

  // Geocoder search
  geocoder.on('markgeocode', function(e) {
    var latlng = e.geocode.center;
    marker.setLatLng(latlng);
    map.setView(latlng, 16);
    document.getElementById('lat-input').value = latlng.lat.toFixed(7);
    document.getElementById('lng-input').value = latlng.lng.toFixed(7);
    document.getElementById('address-search').value = e.geocode.name;
    updateLocationName(latlng.lat, latlng.lng);
  });

  // Address search box
  document.getElementById('address-search').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      geocoder.options.geocoder.geocode(this.value, function(results) {
        if (results.length > 0) {
          geocoder.fire('markgeocode', { geocode: results[0] });
        } else {
          alert('Address not found.');
        }
      });
    }
  });

  // Detect location button
  document.getElementById('detect-location').addEventListener('click', function() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;
        marker.setLatLng([lat, lng]);
        map.setView([lat, lng], 16);
        document.getElementById('lat-input').value = lat.toFixed(7);
        document.getElementById('lng-input').value = lng.toFixed(7);
        updateLocationName(lat, lng);
        // On page load, fill location name if lat/lng present
        updateLocationName(defaultLat, defaultLng);
      }, function(error) {
        alert('Could not get your location: ' + error.message);
      });
    } else {
      alert('Geolocation is not supported by your browser.');
    }
  });
});
</script>

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
