@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Activity Logs</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.activity-logs.export.pdf') }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>Export All PDF
                </a>
                <a href="{{ route('admin.activity-logs.export.csv') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-file-csv mr-2"></i>Export All CSV
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Type to filter..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="action_type" class="block text-sm font-medium text-gray-700 mb-1">Action Type</label>
                    <select name="action_type" id="action_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        @foreach($actionTypes as $type)
                            <option value="{{ $type }}" {{ request('action_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <select name="user_id" id="user_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex items-end space-x-2 lg:col-span-5">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.activity-logs.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>

        {{-- Results Info --}}
        <div class="flex justify-between items-center mb-4">
            <p class="text-sm text-gray-600">
                Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} results
            </p>
            <div class="flex items-center space-x-2">
                <label for="per_page" class="text-sm text-gray-600">Show:</label>
                <select id="per_page" onchange="changePerPage(this.value)" 
                        class="px-2 py-1 border border-gray-300 rounded text-sm">
                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </div>

        {{-- Activity Logs Table --}}
        <div class="overflow-x-auto">
            <table id="activityLogsTable" class="min-w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Action Type
                        </th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Action Date
                        </th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Time Elapsed
                        </th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IP Address
                        </th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-4 whitespace-nowrap">
                                {!! $log->getActionTypeBadge() !!}
                            </td>
                            <td class="border border-gray-300 px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $log->description }}</div>
                            </td>
                            <td class="border border-gray-300 px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->formatted_date }}
                            </td>
                            <td class="border border-gray-300 px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->time_elapsed }}
                            </td>
                            <td class="border border-gray-300 px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                {{ $log->ip_address }}
                            </td>
                            <td class="border border-gray-300 px-4 py-4 whitespace-nowrap">
                                @if($log->user)
                                    <div class="text-sm text-gray-900">{{ $log->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $log->user->email }}</div>
                                @else
                                    <span class="text-sm text-gray-400">Unknown User</span>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-4 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-1">
                                    <a href="{{ route('admin.activity-logs.show', $log) }}" 
                                       class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200 transition-colors" 
                                       title="View full details of this activity log">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                    <a href="{{ route('admin.activity-logs.export.pdf', $log->id) }}" 
                                       class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200 transition-colors" 
                                       title="Download this activity log as PDF file"
                                       onclick="showDownloadMessage('PDF')">
                                        <i class="fas fa-file-pdf mr-1"></i>PDF
                                    </a>
                                    <a href="{{ route('admin.activity-logs.export.csv', $log->id) }}" 
                                       class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200 transition-colors" 
                                       title="Download this activity log as CSV file"
                                       onclick="showDownloadMessage('CSV')">
                                        <i class="fas fa-file-csv mr-1"></i>CSV
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium">No activity logs found</p>
                                    <p class="text-sm">Try adjusting your search criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div class="mt-6">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Download Notification --}}
<div id="downloadNotification" class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg hidden z-50">
    <div class="flex items-center">
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