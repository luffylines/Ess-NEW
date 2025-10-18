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
                {{-- Show download buttons only after search --}}
                @if (request()->has('search') || request()->has('action_type') || request()->has('user_id') || request()->has('date_from') || request()->has('date_to'))
                    <a href="{{ route('admin.activity-logs.export.csv', request()->query()) }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-file-csv me-1"></i>Download CSV
                    </a>
                    <a href="{{ route('admin.activity-logs.export.pdf', request()->query()) }}" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-file-pdf me-1"></i>Download PDF
                    </a>
                @endif
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
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm align-middle">
                    <thead class="table-light text-secondary small">
                        <tr>
                            <th class="col-action-type">Action Type</th>
                            <th class="col-description">Description</th>
                            <th class="col-action-date">Action Date</th>
                            <th class="col-time-elapsed">Time Elapsed</th>
                            <th class="col-ip-address">IP Address</th>
                            <th class="col-user">User</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="table-row-hover">
                            <td>{!! $log->getActionTypeBadge() !!}</td>
                            <td class="small">{{ $log->description }}</td>
                            <td class="small">{{ $log->formatted_date }}</td>
                            <td class="small text-muted">{{ $log->time_elapsed }}</td>
                            <td class="small ip-address-cell" title="{{ $log->ip_address }}">
                                <span class="ip-address-display" data-original-display="{{ \App\Services\IpAddressService::formatIpForDisplay($log->ip_address) }}">
                                    {{ \App\Services\IpAddressService::formatIpForDisplay($log->ip_address) }}
                                    @if(strlen($log->ip_address) > 25)
                                        <i class="fas fa-info-circle text-muted ms-1" style="font-size: 0.7rem;" title="Click to copy full IP"></i>
                                    @endif
                                </span>
                            </td>
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
                                <div class="empty-state">
                                    <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                                    <div class="h5">No activity logs found</div>
                                    <div class="small">Try adjusting your search criteria or check back later</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
           {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Toggle Filters Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Filters
    document.getElementById('toggleFiltersBtn').addEventListener('click', function() {
        const filters = document.getElementById('advancedFilters');
        filters.classList.toggle('d-none');

        if(filters.classList.contains('d-none')) {
            this.innerHTML = '<i class="fas fa-filter me-1"></i>Enable Filtering';
        } else {
            this.innerHTML = '<i class="fas fa-filter me-1"></i>Disable Filtering';
        }
    });

    // IP Address hover tooltip
    const ipCells = document.querySelectorAll('.ip-address-cell');
    ipCells.forEach(cell => {
        const display = cell.querySelector('.ip-address-display');
        const fullIp = cell.getAttribute('title');
        
        if (fullIp && fullIp.length > 25) {
            cell.addEventListener('mouseenter', function() {
                display.textContent = fullIp;
                display.classList.add('ip-expanded');
            });
            
            cell.addEventListener('mouseleave', function() {
                const originalDisplay = display.getAttribute('data-original-display');
                display.textContent = originalDisplay;
                display.classList.remove('ip-expanded');
            });
        }
    });

    // Copy IP to clipboard on click
    ipCells.forEach(cell => {
        cell.addEventListener('click', function() {
            const fullIp = cell.getAttribute('title');
            navigator.clipboard.writeText(fullIp).then(() => {
                // Show brief success message
                const originalTitle = cell.getAttribute('title');
                cell.setAttribute('title', 'Copied to clipboard!');
                setTimeout(() => {
                    cell.setAttribute('title', originalTitle);
                }, 1000);
            });
        });
    });
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
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
}

/* ✅ Responsive table container */
.table-responsive {
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* ✅ Column width optimization */
.col-action-type {
    width: 120px;
    min-width: 120px;
}

.col-description {
    width: auto;
    min-width: 200px;
}

.col-action-date {
    width: 140px;
    min-width: 140px;
}

.col-time-elapsed {
    width: 100px;
    min-width: 100px;
}

.col-ip-address {
    width: 150px;
    min-width: 150px;
    max-width: 150px;
}

.col-user {
    width: 180px;
    min-width: 180px;
}

.col-actions {
    width: 200px;
    min-width: 200px;
}

/* ✅ IP Address display handling */
.ip-address-cell {
    position: relative;
    overflow: hidden;
}

.ip-address-display {
    display: block;
    max-width: 135px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: help;
}

/* ✅ Show full IP on hover */
.ip-address-cell:hover .ip-address-display {
    white-space: normal;
    word-break: break-all;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 4px 6px;
    position: absolute;
    z-index: 1000;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    min-width: 200px;
    left: 0;
    top: 0;
}

/* ✅ IP address interaction */
.ip-address-cell {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.ip-address-cell:hover {
    background-color: #f0f8ff;
}

.ip-expanded {
    font-weight: 500;
    color: #0066cc;
}

/* ✅ Table styling improvements */
.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
    border-color: #e9ecef;
}

/* ✅ Action buttons styling */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

/* ✅ Badge styling for action types */
.badge {
    font-size: 0.7rem;
    font-weight: 500;
}

/* ✅ Empty state styling */
.empty-state {
    padding: 2rem;
    color: #6c757d;
}

.empty-state i {
    opacity: 0.5;
}

/* ✅ Loading and transition effects */
.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* ✅ Mobile responsiveness */
@media (max-width: 768px) {
    .col-ip-address {
        width: 120px;
        min-width: 120px;
        max-width: 120px;
    }
    
    .ip-address-display {
        max-width: 100px;
    }
    
    .col-actions {
        width: 150px;
        min-width: 150px;
    }
    
    .d-flex.gap-1 {
        flex-direction: column;
        gap: 0.25rem !important;
    }
    
    /* ✅ Smaller text on mobile */
    .table-sm td, .table-sm th {
        font-size: 0.75rem;
    }
}


</style>
@endpush
@endsection
