<x-app-layout>
    <div class="px-4">
        <h1 class="mb-3">My Attendance</h1>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-3">{{ session('error') }}</div>
        @endif

        {{-- === MARK ATTENDANCE === --}}
        <div class="p-3 rounded mx-auto mb-4" style="max-width: 400px;">
            <h2 class="mb-3">Mark Attendance</h2>
            @if($todayAttendance)
                @if(!$todayAttendance->time_in)
                    <form method="POST" action="{{ route('attendance.submit') }}">
                        @csrf
                        <button type="submit" name="action" value="time_in" class="btn btn-success w-100">
                            Mark Time In
                        </button>
                    </form>
                @elseif(!$todayAttendance->time_out)
                    <form method="POST" action="{{ route('attendance.submit') }}">
                        @csrf
                        <button type="submit" name="action" value="time_out" class="btn btn-danger w-100">
                            Mark Time Out
                        </button>
                    </form>
                @else
                    <p class="text-success fw-semibold">You have completed attendance for today.</p>
                @endif
            @else
                <form method="POST" action="{{ route('attendance.submit') }}">
                    @csrf
                    <button type="submit" name="action" value="time_in" class="btn btn-success w-100">
                        Mark Time In
                    </button>
                </form>
            @endif
        </div>

        {{-- === TOOLBAR ABOVE TABLE === --}}
        <div class="d-flex flex-wrap mb-3 gap-2">
            <div class="d-flex flex-wrap gap-2">
                <button id="enableFilteringBtn" class="btn btn-dark btn-sm">Enable Filtering</button>
            </div>
        </div>

        {{-- === SEARCH & FILTER FORM === --}}
        <form method="GET" action="{{ route('attendance.my') }}" class="d-flex flex-wrap gap-3 mb-3">
            {{-- Search by Created By --}}
            <div>
                <label for="search_created_by" class="form-label small fw-medium">Search by Created:</label>
                <input type="text" name="search_created_by" id="search_created_by" value="{{ request('search_created_by') }}"
                       class="form-control form-control-sm" placeholder="Enter Created By..." />
            </div>

            {{-- Date From --}}
            <div>
                <label for="date_from" class="form-label small fw-medium">From:</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                       class="form-control form-control-sm" />
            </div>

            {{-- Date To --}}
            <div>
                <label for="date_to" class="form-label fw-medium">To:</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                       class="form-control form-control-sm" />
            </div>

                        {{-- Submit and Clear buttons --}}
            <div class="gap-2">
                <!-- Search Button -->
                <button type="submit" class="btn btn-primary btn-sm">
                    Search
                </button>

                <!-- Clear Button -->
                <a href="{{ route('attendance.my') }}" class="btn btn-secondary btn-sm">
                    Clear
                </a>
            </div>
        </form>

        {{-- === ADVANCED FILTERING SECTION === --}}
        <div id="advancedFiltering" class="border rounded p-3 mb-3 d-none">
            <h3 class="mb-3">Advanced Filtering</h3>
            <div class="row">
                <!-- Date Filter -->
                <div class="col-md-3">
                    <label class="mb-1">Date</label>
                    <input type="date" id="filterDate" class="form-control form-control-sm">
                </div>
                
                <!-- Day Type Filter -->
                <div class="col-md-3">
                    <label class="mb-1">Day Type</label>
                    <select id="filterDayType" class="form-select">
                        <option value="">All Day Types</option>
                        <option value="Regular">Regular</option>
                        <option value="Holiday">Holiday</option>
                        <option value="Rest Day">Rest Day</option>
                        <option value="Overtime">Overtime</option>
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div class="col-md-3">
                    <label class="mb-1">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Present">Present</option>
                        <option value="Late">Late</option>
                        <option value="Absent">Absent</option>
                        <option value="Time In Only">Time In Only</option>
                        <option value="Incomplete">Incomplete</option>
                    </select>
                </div>
                
                <!-- Created By Filter -->
                <div class="col-md-3">
                    <label class="mb-1">Created By</label>
                    <input type="text" id="filterCreatedBy" placeholder="Enter name..." class="form-control form-control-sm">
                </div>
                
                <!-- Time In Range -->
                <div class="col-md-3">
                    <label class="mb-1">Time In From</label>
                    <input type="time" id="filterTimeInFrom" class="form-control form-control-sm">
                </div>
                
                <div class="col-md-3">
                    <label class="mb-1">Time In To</label>
                    <input type="time" id="filterTimeInTo" class="form-control form-control-sm">
                </div>
                
                <!-- Remarks Filter -->
                <div class="col-md-3">
                    <label class="mb-1">Remarks</label>
                    <input type="text" id="filterRemarks" placeholder="Search remarks..." class="form-control form-control-sm">
                </div>
                
                <!-- Filter Actions -->
                <div class="gap-2">
                    <button id="applyFiltersBtn" class="btn btn-primary btn-sm">
                        Apply Filters
                    </button>
                    <button id="clearFiltersBtn" class="btn btn-secondary btn-sm">
                        Clear All
                    </button>
                </div>
            </div>
        </div>

        {{-- === ATTENDANCE TABLE === --}}
<table class="" id="attendanceTable">
    <thead class="table-light">
        <tr>
            <th>Date</th>
            <th>Day Type</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Created At</th>
            <th>Created By</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attendances as $attendance)
            <tr class="attendance-row" 
                data-date="{{ $attendance['date'] }}"
                data-day-type="{{ $attendance['day_type'] }}"
                data-time-in="{{ $attendance['time_in'] }}"
                data-time-out="{{ $attendance['time_out'] }}"
                data-status="{{ $attendance['status'] }}"
                data-remarks="{{ $attendance['remarks'] }}"
                data-created-at="{{ $attendance['created_at'] }}"
                data-created-by="{{ $attendance['created_by'] }}">
                <td>{{ $attendance['date'] }}</td>
                <td>{{ $attendance['day_type'] }}</td>
                <td>{{ $attendance['time_in'] }}</td>
                <td>{{ $attendance['time_out'] }}</td>
                <td>
                    @php
                        $status = $attendance['status'];
                        $attendanceStatus = $attendance['attendance_status'] ?? 'pending';
                    @endphp
                    
                    {{-- Display attendance status with approval status --}}
                    <div class="">
                        @if($status === 'Present')
                            <span class="text-success fw-medium">{{ $status }}</span>
                        @elseif($status === 'Late')
                            <span class="text-warning fw-medium">{{ $status }}</span>
                        @elseif($status === 'Absent')
                            <span class="text-danger fw-medium">{{ $status }}</span>
                        @else
                            <span class="text-muted fw-medium">{{ $status }}</span>
                        @endif
                        
                        {{-- Show approval status --}}
                        @if($attendanceStatus === 'approved')
                            <span class="small text-success">✓ Approved</span>
                        @elseif($attendanceStatus === 'rejected')
                            <span class="small text-danger">✗ Rejected</span>
                        @else
                            <span class="small text-warning">⏳ Pending</span>
                        @endif
                    </div>
                </td>
                <td>{{ $attendance['remarks'] }}</td>
                <td>{{ $attendance['created_at'] }}</td>
                <td>{{ $attendance['created_by'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-3">
                    @if(request()->hasAny(['search_created_by', 'date_from', 'date_to']))
                        <div class="">
                            <img src="{{ asset('img/no-results.png') }}" alt="No Results" style="width: 64px; height: 64px;" class="mb-3">
                            <p class="h5 fw-medium">No search results found</p>
                            <p class="small">Try adjusting your search criteria</p>
                            <a href="{{ route('attendance.my') }}" class="mt-3">
                                Clear Search
                            </a>
                        </div>
                    @else
                        <div class="">
                            <img src="{{ asset('img/no-data.png') }}" alt="No Data" style="width: 64px; height: 64px;" class="mb-3">
                            <p class="h5 fw-medium">No attendance records found</p>
                            <p class="small">Start by marking your attendance below</p>
                        </div>
                    @endif
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- === PAGINATION === --}}
@if($attendances->hasPages())
    <div class="mt-3">
        {{ $attendances->appends(request()->query())->links() }}
    </div>
@endif
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const enableFilteringBtn = document.getElementById('enableFilteringBtn');
    const advancedFiltering = document.getElementById('advancedFiltering');
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    
    // Toggle Advanced Filtering
    enableFilteringBtn.addEventListener('click', function() {
        const isHidden = advancedFiltering.classList.contains('d-none');
        if (isHidden) {
            advancedFiltering.classList.remove('d-none');
            enableFilteringBtn.textContent = 'Hide Filtering';
            enableFilteringBtn.className = 'btn btn-danger btn-sm';
        } else {
            advancedFiltering.classList.add('d-none');
            enableFilteringBtn.textContent = 'Enable Filtering';
            enableFilteringBtn.className = 'btn btn-dark btn-sm';
        }
    });
    
    // Filtering functionality
    function applyFilters() {
        const filterDate = document.getElementById('filterDate').value;
        const filterDayType = document.getElementById('filterDayType').value;
        const filterStatus = document.getElementById('filterStatus').value;
        const filterCreatedBy = document.getElementById('filterCreatedBy').value.toLowerCase();
        const filterTimeInFrom = document.getElementById('filterTimeInFrom').value;
        const filterTimeInTo = document.getElementById('filterTimeInTo').value;
        const filterRemarks = document.getElementById('filterRemarks').value.toLowerCase();
        
        const rows = document.querySelectorAll('.attendance-row');
        
        rows.forEach(row => {
            let show = true;
            
            // Date filter
            if (filterDate && row.dataset.date !== filterDate) {
                show = false;
            }
            
            // Day Type filter
            if (filterDayType && row.dataset.dayType !== filterDayType) {
                show = false;
            }
            
            // Status filter
            if (filterStatus && !row.dataset.status.includes(filterStatus)) {
                show = false;
            }
            
            // Created By filter
            if (filterCreatedBy && !row.dataset.createdBy.toLowerCase().includes(filterCreatedBy)) {
                show = false;
            }
            
            // Remarks filter
            if (filterRemarks && !row.dataset.remarks.toLowerCase().includes(filterRemarks)) {
                show = false;
            }
            
            // Time In range filter
            if (filterTimeInFrom || filterTimeInTo) {
                const timeIn = row.dataset.timeIn;
                if (timeIn && timeIn !== '-') {
                    const timeIn24 = convertTo24Hour(timeIn);
                    if (filterTimeInFrom && timeIn24 < filterTimeInFrom) {
                        show = false;
                    }
                    if (filterTimeInTo && timeIn24 > filterTimeInTo) {
                        show = false;
                    }
                }
            }
            
            // Show/hide row
            if (show) {
                row.classList.remove('d-none');
            } else {
                row.classList.add('d-none');
            }
        });
    }
    
    // Convert 12-hour time to 24-hour format
    function convertTo24Hour(time12h) {
        if (time12h === '-') return '';
        const [time, modifier] = time12h.split(' ');
        let [hours, minutes] = time.split(':');
        if (hours === '12') {
            hours = '00';
        }
        if (modifier === 'PM') {
            hours = parseInt(hours, 10) + 12;
        }
        return `${hours.padStart(2, '0')}:${minutes}`;
    }
    
    // Clear all filters
    function clearFilters() {
        document.getElementById('filterDate').value = '';
        document.getElementById('filterDayType').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterCreatedBy').value = '';
        document.getElementById('filterTimeInFrom').value = '';
        document.getElementById('filterTimeInTo').value = '';
        document.getElementById('filterRemarks').value = '';
        
        document.querySelectorAll('.attendance-row').forEach(row => {
            row.classList.remove('d-none');
        });
    }
    
    // Event listeners for filters
    applyFiltersBtn.addEventListener('click', applyFilters);
    clearFiltersBtn.addEventListener('click', clearFilters);
});
</script>
</x-app-layout>
