@extends('layouts.app')

@section('content')
<div class="mx-auto p-3">
    <div class="bg-white rounded p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h2 fw-bold text-dark">Activity Logs</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.activity-logs.export.pdf') }}" 
                   class="bg-danger text-white px-4 py-2 rounded d-flex align-items-center">
                    <i class="fas fa-file-pdf mr-2"></i>Export All PDF
                </a>
                <a href="{{ route('admin.activity-logs.export.csv') }}" 
                   class="bg-success text-white px-4 py-2 rounded d-flex align-items-center">
                    <i class="fas fa-file-csv mr-2"></i>Export All CSV
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-light rounded p-3 mb-4">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row gap-3">
                <div>
                    <label for="search" class="d-block small fw-medium text-secondary mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Type to filter..." 
                           class="w-100 px-3 py-2 border">
                </div>
                
                <div>
                    <label for="action_type" class="d-block small fw-medium text-secondary mb-1">Action Type</label>
                    <select name="action_type" id="action_type" 
                            class="w-100 px-3 py-2 border">
                        <option value="">All Types</option>
                        @foreach($actionTypes as $type)
                            <option value="{{ $type }}" {{ request('action_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="user_id" class="d-block small fw-medium text-secondary mb-1">User</label>
                    <select name="user_id" id="user_id" 
                            class="w-100 px-3 py-2 border">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="date_from" class="d-block small fw-medium text-secondary mb-1">Date From</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                           class="w-100 px-3 py-2 border">
                </div>
                
                <div>
                    <label for="date_to" class="d-block small fw-medium text-secondary mb-1">Date To</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                           class="w-100 px-3 py-2 border">
                </div>
                
                <div class="d-flex align-items-end gap-2">
                    <button type="submit" 
                            class="bg-primary text-white py-2 rounded">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.activity-logs.index') }}" 
                       class="text-white py-2 rounded">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>

        {{-- Results Info --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="small text-muted">
                Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} results
            </p>
            <div class="d-flex align-items-center gap-2">
                <label for="per_page" class="small text-muted">Show:</label>
                <select id="per_page" onchange="changePerPage(this.value)" 
                        class="px-2 py-1 border rounded small">
                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </div>

        {{-- Activity Logs Table --}}
        <div class="overflow-x-auto">
            <table id="activityLogsTable" class="border">
                <thead>
                    <tr class="bg-light">
                        <th class="border px-4 text-start small fw-medium text-muted">
                            Action Type
                        </th>
                        <th class="border px-4 text-start small fw-medium text-muted">
                            Description
                        </th>
                        <th class="border px-4 text-start small fw-medium text-muted">
                            Action Date
                        </th>
                        <th class="border px-4 text-start small fw-medium text-muted">
                            Time Elapsed
                        </th>
                        <th class="border px-4 text-start small fw-medium text-muted">
                            IP Address
                        </th>
                        <th class="border px-4 text-start small fw-medium text-muted">
                            User
                        </th>
                        <th class="border px-4 text-start small fw-medium text-muted">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($logs as $log)
                        <tr class="">
                            <td class="border px-4 py-3">
                                {!! $log->getActionTypeBadge() !!}
                            </td>
                            <td class="border px-4 py-3">
                                <div class="small">{{ $log->description }}</div>
                            </td>
                            <td class="border px-4 py-3 small">
                                {{ $log->formatted_date }}
                            </td>
                            <td class="border px-4 py-3 small text-muted">
                                {{ $log->time_elapsed }}
                            </td>
                            <td class="border px-4 py-3 small">
                                {{ $log->ip_address }}
                            </td>
                            <td class="border px-4 py-3">
                                @if($log->user)
                                    <div class="small">{{ $log->user->name }}</div>
                                    <div class="small text-muted">{{ $log->user->email }}</div>
                                @else
                                    <span class="small text-muted">Unknown User</span>
                                @endif
                            </td>
                            <td class="border px-4 py-3 small fw-medium">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.activity-logs.show', $log) }}" 
                                       class="align-items-center px-2 py-1 rounded small" 
                                       title="View full details of this activity log">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                    <a href="{{ route('admin.activity-logs.export.pdf', $log->id) }}" 
                                       class="align-items-center px-2 py-1 rounded small" 
                                       title="Download this activity log as PDF file"
                                       onclick="showDownloadMessage('PDF')">
                                        <i class="fas fa-file-pdf mr-1"></i>PDF
                                    </a>
                                    <a href="{{ route('admin.activity-logs.export.csv', $log->id) }}" 
                                       class="align-items-center px-2 py-1 rounded small" 
                                       title="Download this activity log as CSV file"
                                       onclick="showDownloadMessage('CSV')">
                                        <i class="fas fa-file-csv mr-1"></i>CSV
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border px-4 text-center text-muted">
                                <div class="d-flex d-flex flex-column align-items-center">
                                    <i class="mb-3"></i>
                                    <p class="h4 fw-medium">No activity logs found</p>
                                    <p class="small">Try adjusting your search criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div class="mt-4">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Download Notification --}}
<div id="downloadNotification" class="position-fixed text-white px-4 py-2 rounded shadow-lg d-none">
    <div class="d-flex align-items-center">
        <i class="fas fa-download mr-2"></i>
        <span id="downloadMessage">Download started...</span>
    </div>
</div>

<script>
function changePerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    url.searchParams.delete('page'); // Reset to first page
    window.location.href = url.toString();
}

function showDownloadMessage(format) {
    const notification = document.getElementById('downloadNotification');
    const message = document.getElementById('downloadMessage');
    
    message.textContent = `Downloading ${format} file...`;
    notification.classList.remove('hidden');
    
    // Hide notification after 3 seconds
    setTimeout(() => {
        notification.classList.add('hidden');
    }, 3000);
}

// Auto-submit form on input change for real-time filtering
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const actionTypeSelect = document.getElementById('action_type');
    const userSelect = document.getElementById('user_id');
    const dateFromInput = document.getElementById('date_from');
    const dateToInput = document.getElementById('date_to');
    
    let searchTimeout;
    
    // Debounced search
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
    
    // Immediate submit on select changes
    [actionTypeSelect, userSelect, dateFromInput, dateToInput].forEach(element => {
        element.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection