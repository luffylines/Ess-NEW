<x-app-layout>
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <h1 class="text-2xl font-bold mb-4">My Attendance</h1>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif

        {{-- === TOOLBAR ABOVE TABLE === --}}
        <div class="flex flex-wrap justify-between items-center mb-4 gap-2 text-sm">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('attendance.generateShiftSchedule') }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition-colors">
                    Generate
                </a>
                <button class="bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700 transition-colors">New</button>
                <button class="btn btn-primary px-4 py-2 rounded hover:bg-blue-600 transition-all duration-300">Advance Sched</button>
                <button id="selectAllBtn" class="btn btn-secondary px-4 py-2 rounded hover:bg-gray-700 transition-all duration-300">Select All</button>
                <button class="btn btn-info px-4 py-2 rounded hover:bg-teal-700 transition-all duration-300">Refresh</button>
                <button id="enableFilteringBtn" class="btn btn-dark px-4 py-2 rounded hover:bg-indigo-700 transition-all duration-300">Enable Filtering</button>
                <a href="{{ route('attendance.pdf') }}" target="_blank" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors flex items-center gap-1">
                    <img src="{{ asset('img/pdf.png') }}" alt="PDF" class="w-4 h-4"> PDF Print All
                </a>
                <button id="printSelectedBtn" class="bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700 transition-colors" disabled>
                    <i class="fas fa-print"></i> Print Selected
                </button>
                <button id="downloadSelectedBtn" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition-colors" disabled>
                    <i class="fas fa-download"></i> Download Selected
                </button>
            </div>
            <div id="selectedCount" class="text-sm text-gray-600 hidden">
                Selected: <span id="selectedCountNumber">0</span> records
            </div>
        </div>

        {{-- === SEARCH & FILTER FORM === --}}
        <form method="GET" action="{{ route('attendance.my') }}" class="flex flex-wrap gap-3 items-end mb-4">
            {{-- Search by Created By --}}
            <div>
                <label for="search_created_by" class="text-sm font-medium">Search by Created:</label>
                <input type="text" name="search_created_by" id="search_created_by" value="{{ request('search_created_by') }}"
                       class="border rounded px-2 py-1 text-sm" placeholder="Enter Created By..." />
            </div>

            {{-- Date From --}}
            <div>
                <label for="date_from" class="text-sm font-medium">From:</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                       class="border rounded px-2 py-1 text-sm" />
            </div>

            {{-- Date To --}}
            <div>
                <label for="date_to" class="font-medium">To:</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                       class="border rounded px-2 py-1 text-sm" />
            </div>

                        {{-- Submit and Clear buttons --}}
            <div class="flex gap-2">
                <!-- Search Button -->
                <button type="submit" class="text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-300 text-sm" style="background-color: #3b82f6;">
                    Search
                </button>

                <!-- Clear Button -->
                <a href="{{ route('attendance.my') }}" class="text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-300 text-sm" style="background-color: #6b7280;">
                    Clear
                </a>
            </div>
        </form>

        {{-- === ADVANCED FILTERING SECTION === --}}
        <div id="advancedFiltering" class="bg-gray-50 border rounded-lg p-4 mb-4 hidden">
            <h3 class="text-lg font-semibold mb-3">Advanced Filtering</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" id="filterDate" class="w-full px-3 py-1 border rounded text-sm">
                </div>
                
                <!-- Day Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Day Type</label>
                    <select id="filterDayType" class="w-full px-3 py-1 border rounded text-sm">
                        <option value="">All Day Types</option>
                        <option value="Regular">Regular</option>
                        <option value="Holiday">Holiday</option>
                        <option value="Rest Day">Rest Day</option>
                        <option value="Overtime">Overtime</option>
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="filterStatus" class="w-full px-3 py-1 border rounded text-sm">
                        <option value="">All Statuses</option>
                        <option value="Present">Present</option>
                        <option value="Late">Late</option>
                        <option value="Absent">Absent</option>
                        <option value="Time In Only">Time In Only</option>
                        <option value="Incomplete">Incomplete</option>
                    </select>
                </div>
                
                <!-- Created By Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                    <input type="text" id="filterCreatedBy" placeholder="Enter name..." class="w-full px-3 py-1 border rounded text-sm">
                </div>
                
                <!-- Time In Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time In From</label>
                    <input type="time" id="filterTimeInFrom" class="w-full px-3 py-1 border rounded text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time In To</label>
                    <input type="time" id="filterTimeInTo" class="w-full px-3 py-1 border rounded text-sm">
                </div>
                
                <!-- Remarks Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                    <input type="text" id="filterRemarks" placeholder="Search remarks..." class="w-full px-3 py-1 border rounded text-sm">
                </div>
                
                <!-- Filter Actions -->
                <div class="flex items-end space-x-2">
                    <button id="applyFiltersBtn" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700 text-sm">
                        Apply Filters
                    </button>
                    <button id="clearFiltersBtn" class="bg-gray-500 text-white px-4 py-1 rounded hover:bg-gray-600 text-sm">
                        Clear All
                    </button>
                </div>
            </div>
        </div>

        {{-- === ATTENDANCE TABLE === --}}
<table class="table-auto w-full border-collapse border border-gray-300 text-sm" id="attendanceTable">
    <thead>
        <tr class="bg-gray-100 text-left">
            <th class="border border-gray-300 px-4 py-2">
                <input type="checkbox" id="selectAllCheckbox" class="form-checkbox">
            </th>
            <th class="border border-gray-300 px-4 py-2">Date</th>
            <th class="border border-gray-300 px-4 py-2">Day Type</th>
            <th class="border border-gray-300 px-4 py-2">Time In</th>
            <th class="border border-gray-300 px-4 py-2">Time Out</th>
            <th class="border border-gray-300 px-4 py-2">Status</th>
            <th class="border border-gray-300 px-4 py-2">Remarks</th>
            <th class="border border-gray-300 px-4 py-2">Created At</th>
            <th class="border border-gray-300 px-4 py-2">Created By</th>
            <th class="border border-gray-300 px-4 py-2">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attendances as $attendance)
            <tr class="hover:bg-gray-50 attendance-row" 
                data-date="{{ $attendance['date'] }}"
                data-day-type="{{ $attendance['day_type'] }}"
                data-time-in="{{ $attendance['time_in'] }}"
                data-time-out="{{ $attendance['time_out'] }}"
                data-status="{{ $attendance['status'] }}"
                data-remarks="{{ $attendance['remarks'] }}"
                data-created-at="{{ $attendance['created_at'] }}"
                data-created-by="{{ $attendance['created_by'] }}">
                <td class="border border-gray-300 px-4 py-2">
                    <input type="checkbox" class="row-checkbox form-checkbox" value="{{ $attendance['id'] }}">
                </td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance['date'] }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance['day_type'] }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance['time_in'] }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance['time_out'] }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    @php
                        $status = $attendance['status'];
                        $attendanceStatus = $attendance['attendance_status'] ?? 'pending';
                    @endphp
                    
                    {{-- Display attendance status with approval status --}}
                    <div class="flex flex-col">
                        @if($status === 'Present')
                            <span class="text-green-600 font-medium">{{ $status }}</span>
                        @elseif($status === 'Late')
                            <span class="text-orange-600 font-medium">{{ $status }}</span>
                        @elseif($status === 'Absent')
                            <span class="text-red-600 font-medium">{{ $status }}</span>
                        @else
                            <span class="text-gray-600 font-medium">{{ $status }}</span>
                        @endif
                        
                        {{-- Show approval status --}}
                        @if($attendanceStatus === 'approved')
                            <span class="text-xs text-green-500">✓ Approved</span>
                        @elseif($attendanceStatus === 'rejected')
                            <span class="text-xs text-red-500">✗ Rejected</span>
                        @else
                            <span class="text-xs text-yellow-500">⏳ Pending HR</span>
                        @endif
                    </div>
                </td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance['remarks'] }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance['created_at'] }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance['created_by'] }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    @php
                        $attendanceId = $attendance['id'];
                        $attendanceStatus = $attendance['attendance_status'] ?? 'pending';
                    @endphp
                    @if($attendanceStatus !== 'approved')
                        <div class="flex space-x-1">
                            <a href="{{ route('attendance.edit', $attendanceId) }}" class="inline-block" title="Edit">
                                <img src="{{ asset('img/edit.png') }}" alt="Edit" class="w-5 h-5 hover:opacity-70" />
                            </a>
                            <a href="{{ route('attendance.delete', $attendanceId) }}" class="inline-block" title="Delete" 
                               onclick="return confirm('Are you sure you want to delete this record?')">
                                <img src="{{ asset('img/delete1.png') }}" alt="Delete" class="w-5 h-5 hover:opacity-70" />
                            </a>
                        </div>
                    @else
                        <span class="text-gray-400 text-xs">🔒 Approved</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center py-8 text-gray-500">
                    @if(request()->hasAny(['search_created_by', 'date_from', 'date_to']))
                        <div class="flex flex-col items-center">
                            <img src="{{ asset('img/no-results.png') }}" alt="No Results" class="w-16 h-16 mb-3 opacity-50">
                            <p class="text-lg font-medium">No search results found</p>
                            <p class="text-sm">Try adjusting your search criteria</p>
                            <a href="{{ route('attendance.my') }}" class="mt-3 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                                Clear Search
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col items-center">
                            <img src="{{ asset('img/no-data.png') }}" alt="No Data" class="w-16 h-16 mb-3 opacity-50">
                            <p class="text-lg font-medium">No attendance records found</p>
                            <p class="text-sm">Start by marking your attendance below</p>
                        </div>
                    @endif
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- === PAGINATION === --}}
@if($attendances->hasPages())
    <div class="mt-4">
        {{ $attendances->appends(request()->query())->links() }}
    </div>
@endif
        {{-- === MARK ATTENDANCE === --}}
        <div class="bg-gray-100 p-4 rounded max-w-md mx-auto mt-6">
            <h2 class="text-xl font-semibold mb-3">Mark Attendance</h2>
            @if($todayAttendance)
                @if(!$todayAttendance->time_in)
                    <form method="POST" action="{{ route('attendance.submit') }}">
                        @csrf
                        <button type="submit" name="action" value="time_in" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full">
                            Mark Time In
                        </button>
                    </form>
                @elseif(!$todayAttendance->time_out)
                    <form method="POST" action="{{ route('attendance.submit') }}">
                        @csrf
                        <button type="submit" name="action" value="time_out" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 w-full">
                            Mark Time Out
                        </button>
                    </form>
                @else
                    <p class="text-green-700 font-semibold">You have completed attendance for today.</p>
                @endif
            @else
                <form method="POST" action="{{ route('attendance.submit') }}">
                    @csrf
                    <button type="submit" name="action" value="time_in" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full">
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
        const isHidden = advancedFiltering.classList.contains('hidden');
        if (isHidden) {
            advancedFiltering.classList.remove('hidden');
            enableFilteringBtn.textContent = 'Hide Filtering';
            enableFilteringBtn.classList.add('bg-red-600');
            enableFilteringBtn.classList.remove('btn-dark');
        } else {
            advancedFiltering.classList.add('hidden');
            enableFilteringBtn.textContent = 'Enable Filtering';
            enableFilteringBtn.classList.remove('bg-red-600');
            enableFilteringBtn.classList.add('btn-dark');
        }
    });
    
    // Select All functionality
    function updateSelectAll() {
        const visibleCheckboxes = Array.from(rowCheckboxes).filter(cb => 
            !cb.closest('tr').classList.contains('hidden')
        );
        const checkedBoxes = visibleCheckboxes.filter(cb => cb.checked);
        
        selectAllCheckbox.checked = visibleCheckboxes.length > 0 && 
                                   checkedBoxes.length === visibleCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && 
                                        checkedBoxes.length < visibleCheckboxes.length;
        
        // Update count and button states
        selectedCountNumber.textContent = checkedBoxes.length;
        if (checkedBoxes.length > 0) {
            selectedCount.classList.remove('hidden');
            printSelectedBtn.disabled = false;
            downloadSelectedBtn.disabled = false;
            printSelectedBtn.classList.remove('opacity-50');
            downloadSelectedBtn.classList.remove('opacity-50');
        } else {
            selectedCount.classList.add('hidden');
            printSelectedBtn.disabled = true;
            downloadSelectedBtn.disabled = true;
            printSelectedBtn.classList.add('opacity-50');
            downloadSelectedBtn.classList.add('opacity-50');
        }
    }
    
    // Select All checkbox
    selectAllCheckbox.addEventListener('change', function() {
        const visibleCheckboxes = Array.from(rowCheckboxes).filter(cb => 
            !cb.closest('tr').classList.contains('hidden')
        );
        visibleCheckboxes.forEach(cb => cb.checked = this.checked);
        updateSelectAll();
    });
    
    // Select All button
    selectAllBtn.addEventListener('click', function() {
        const visibleCheckboxes = Array.from(rowCheckboxes).filter(cb => 
            !cb.closest('tr').classList.contains('hidden')
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
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
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
            row.classList.remove('hidden');
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
