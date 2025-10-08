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

        {{-- === TOOLBAR ABOVE TABLE === --}}
        <div class="d-flex flex-wrap mb-3 gap-2">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('attendance.generateShiftSchedule') }}" class="btn btn-primary btn-sm">
                    Generate
                </a>
                <button class="btn btn-emerald btn-sm">New</button>
                <button class="btn btn-primary btn-sm">Advance Sched</button>
                <button id="selectAllBtn" class="btn btn-secondary btn-sm">Select All</button>
                <button class="btn btn-info btn-sm">Refresh</button>
                <button id="enableFilteringBtn" class="btn btn-dark btn-sm">Enable Filtering</button>
                <a href="{{ route('attendance.pdf') }}" target="_blank" class="gap-1">
                    <img src="{{ asset('img/pdf.png') }}" alt="PDF" style="width: 16px; height: 16px;"> PDF Print All
                </a>
                <button id="printSelectedBtn" class="btn btn-purple btn-sm" disabled>
                    <i class="fas fa-print"></i> Print Selected
                </button>
                <button id="downloadSelectedBtn" class="btn btn-success btn-sm" disabled>
                    <i class="fas fa-download"></i> Download Selected
                </button>
            </div>
            <div id="selectedCount" class="small text-muted d-none">
                Selected: <span id="selectedCountNumber">0</span> records
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
        <div id="advancedFiltering" class="border rounded p-3 mb-3">
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
            <th class="border px-4 py-2">
                <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
            </th>
            <th>Date</th>
            <th>Day Type</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Created At</th>
            <th>Created By</th>
            <th>Action</th>
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
                <td>
                    <input type="checkbox" class="row-checkbox form-check-input" value="{{ $attendance['id'] }}">
                </td>
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
                <td>
                    @php
                        $attendanceId = $attendance['id'];
                        $attendanceStatus = $attendance['attendance_status'] ?? 'pending';
                    @endphp
                    @if($attendanceStatus !== 'approved')
                        <div class="gap-1">
                            <a href="{{ route('attendance.edit', $attendanceId) }}" class="" title="Edit">
                                <img src="{{ asset('img/edit.png') }}" alt="Edit" style="width: 20px; height: 20px;" />
                            </a>
                            <a href="{{ route('attendance.delete', $attendanceId) }}" class="" title="Delete" 
                               onclick="return confirm('Are you sure you want to delete this record?')">
                                <img src="{{ asset('img/delete1.png') }}" alt="Delete" style="width: 20px; height: 20px;" />
                            </a>
                        </div>
                    @else
                        <span class="text-muted small">🔒 Approved</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center py-3">
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
        {{-- === MARK ATTENDANCE === --}}
        <div class="p-3 rounded mx-auto mt-3" style="max-width: 400px;">
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
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const enableFilteringBtn = document.getElementById('enableFilteringBtn');
    const advancedFiltering = document.getElementById('advancedFiltering');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const selectedCountNumber = document.getElementById('selectedCountNumber');
    const printSelectedBtn = document.getElementById('printSelectedBtn');
    const downloadSelectedBtn = document.getElementById('downloadSelectedBtn');
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
    
    // Select All functionality
    function updateSelectAll() {
        const visibleCheckboxes = Array.from(rowCheckboxes).filter(cb => 
            !cb.closest('tr').classList.contains('d-none')
        );
        const checkedBoxes = visibleCheckboxes.filter(cb => cb.checked);
        
        selectAllCheckbox.checked = visibleCheckboxes.length > 0 && 
                                   checkedBoxes.length === visibleCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && 
                                        checkedBoxes.length < visibleCheckboxes.length;
        
        // Update count and button states
        selectedCountNumber.textContent = checkedBoxes.length;
        if (checkedBoxes.length > 0) {
            selectedCount.classList.remove('d-none');
            printSelectedBtn.disabled = false;
            downloadSelectedBtn.disabled = false;
        } else {
            selectedCount.classList.add('d-none');
            printSelectedBtn.disabled = true;
            downloadSelectedBtn.disabled = true;
        }
    }
    
    // Select All checkbox
    selectAllCheckbox.addEventListener('change', function() {
        const visibleCheckboxes = Array.from(rowCheckboxes).filter(cb => 
            !cb.closest('tr').classList.contains('d-none')
        );
        visibleCheckboxes.forEach(cb => cb.checked = this.checked);
        updateSelectAll();
    });
    
    // Select All button
    selectAllBtn.addEventListener('click', function() {
        const visibleCheckboxes = Array.from(rowCheckboxes).filter(cb => 
            !cb.closest('tr').classList.contains('d-none')
        );
        const allChecked = visibleCheckboxes.every(cb => cb.checked);
        visibleCheckboxes.forEach(cb => cb.checked = !allChecked);
        updateSelectAll();
    });
    
    // Individual checkboxes
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAll);
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
                row.querySelector('.row-checkbox').checked = false;
            }
        });
        
        updateSelectAll();
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
        
        updateSelectAll();
    }
    
    // Event listeners for filters
    applyFiltersBtn.addEventListener('click', applyFilters);
    clearFiltersBtn.addEventListener('click', clearFilters);
    
    // Print selected records
    printSelectedBtn.addEventListener('click', function() {
        const selectedIds = Array.from(rowCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            alert('Please select at least one record to print.');
            return;
        }
        
        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        const selectedRows = Array.from(rowCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.closest('tr'));
        
        let printContent = `
            <html>
            <head>
                <title>Selected Attendance Records</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 12px; }
                    th { background-color: #f0f0f0; font-weight: bold; }
                    h1 { text-align: center; margin-bottom: 20px; }
                    .print-info { margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <h1>Attendance Records</h1>
                <div class="print-info">
                    <p><strong>Generated:</strong> ${new Date().toLocaleString()}</p>
                    <p><strong>Records Selected:</strong> ${selectedIds.length}</p>
                </div>
                <table>
                    <thead>
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
        `;
        
        selectedRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            printContent += '<tr>';
            // Skip checkbox column (index 0) and action column (last)
            for (let i = 1; i < cells.length - 1; i++) {
                let cellContent = cells[i].textContent.trim();
                // Clean up status cell content
                if (i === 5) { // Status column
                    cellContent = cellContent.replace(/[✓✗⏳]/g, '').replace(/\s+/g, ' ').trim();
                }
                printContent += `<td>${cellContent}</td>`;
            }
            printContent += '</tr>';
        });
        
        printContent += `
                    </tbody>
                </table>
            </body>
            </html>
        `;
        
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.print();
    });
    
    // Download selected records as CSV
    downloadSelectedBtn.addEventListener('click', function() {
        const selectedIds = Array.from(rowCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            alert('Please select at least one record to download.');
            return;
        }
        
        const selectedRows = Array.from(rowCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.closest('tr'));
        
        let csvContent = 'Date,Day Type,Time In,Time Out,Status,Remarks,Created At,Created By\n';
        
        selectedRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowData = [];
            // Skip checkbox column (index 0) and action column (last)
            for (let i = 1; i < cells.length - 1; i++) {
                let cellContent = cells[i].textContent.trim();
                // Clean up status cell content
                if (i === 5) { // Status column
                    cellContent = cellContent.replace(/[✓✗⏳]/g, '').replace(/\s+/g, ' ').trim();
                }
                // Escape commas and quotes in CSV
                if (cellContent.includes(',') || cellContent.includes('"')) {
                    cellContent = '"' + cellContent.replace(/"/g, '""') + '"';
                }
                rowData.push(cellContent);
            }
            csvContent += rowData.join(',') + '\n';
        });
        
        // Create and download file
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `attendance_records_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    });
    
    // Initialize
    updateSelectAll();
});
</script>
</x-app-layout>
