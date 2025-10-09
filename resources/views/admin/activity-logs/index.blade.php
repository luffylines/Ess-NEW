@extends('layouts.app')

@section('content')
<div class="mx-auto p-3">
    <div class="bg-white rounded shadow-sm p-4 card-shadow">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h2 fw-bold text-dark">Activity Logs</h2>
            <div>
                <button class="btn btn-outline-primary btn-sm" id="toggleFiltersBtn">
                    <i class="fas fa-filter me-1"></i>Enable Filtering
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="mb-4">
            <div class="row g-3 align-items-end">

                {{-- Search Bar (always visible) --}}
                <div class="col-md-6">
                    <label for="search" class="form-label fw-medium text-secondary">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Type to filter..." 
                           class="form-control form-control-sm rounded-pill">
                </div>

                {{-- Hidden Filters --}}
                <div id="advancedFilters" class="row g-3 d-none filter-transition">

                    <div class="col-md-3">
                        <label for="action_type" class="form-label fw-medium text-secondary">Action Type</label>
                        <select name="action_type" id="action_type" class="form-select form-select-sm rounded-pill">
                            <option value="">All Types</option>
                            @foreach($actionTypes as $type)
                                <option value="{{ $type }}" {{ request('action_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="user_id" class="form-label fw-medium text-secondary">User</label>
                        <select name="user_id" id="user_id" class="form-select form-select-sm rounded-pill">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="date_from" class="form-label fw-medium text-secondary">Date From</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                               class="form-control form-control-sm rounded-pill">
                    </div>

                    <div class="col-md-3">
                        <label for="date_to" class="form-label fw-medium text-secondary">Date To</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                               class="form-control form-control-sm rounded-pill">
                    </div>

                    <div class="col-12 d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>

                </div>
            </div>
        </form>

        {{-- Activity Logs Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-light text-secondary small">
                    <tr>
                        <th>Action Type</th>
                        <th>Description</th>
                        <th>Action Date</th>
                        <th>Time Elapsed</th>
                        <th>IP Address</th>
                        <th>User</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="table-row-hover">
                            <td>{!! $log->getActionTypeBadge() !!}</td>
                            <td class="small">{{ $log->description }}</td>
                            <td class="small">{{ $log->formatted_date }}</td>
                            <td class="small text-muted">{{ $log->time_elapsed }}</td>
                            <td class="small">{{ $log->ip_address }}</td>
                            <td class="small">
                                @if($log->user)
                                    {{ $log->user->name }} <br>
                                    <span class="text-muted small">{{ $log->user->email }}</span>
                                @else
                                    <span class="text-muted">Unknown User</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.activity-logs.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('admin.activity-logs.export.pdf', $log->id) }}" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-file-pdf me-1"></i>PDF
                                    </a>
                                    <a href="{{ route('admin.activity-logs.export.csv', $log->id) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-file-csv me-1"></i>CSV
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <div>No activity logs found</div>
                                <div class="small">Try adjusting your search criteria</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- Toggle Filters Script --}}
<script>
document.getElementById('toggleFiltersBtn').addEventListener('click', function() {
    const filters = document.getElementById('advancedFilters');
    filters.classList.toggle('d-none');

    if(filters.classList.contains('d-none')) {
        this.innerHTML = '<i class="fas fa-filter me-1"></i>Enable Filtering';
    } else {
        this.innerHTML = '<i class="fas fa-filter me-1"></i>Disable Filtering';
    }
});
</script>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
.card-shadow {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.filter-transition {
    transition: all 0.4s ease;
}

.table-row-hover:hover {
    background-color: #f1f5f9;
    transition: background-color 0.2s ease;
}
</style>
@endpush
@endsection
