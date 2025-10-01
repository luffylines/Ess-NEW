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
                <button class="btn btn-secondary px-4 py-2 rounded hover:bg-gray-700 transition-all duration-300">Select All</button>
                <button class="btn btn-info px-4 py-2 rounded hover:bg-teal-700 transition-all duration-300">Refresh</button>
                <button class="btn btn-dark px-4 py-2 rounded hover:bg-indigo-700 transition-all duration-300">Enable Filtering</button>
                <a href="{{ route('attendance.pdf') }}" target="_blank" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors flex items-center gap-1">
                <img src="{{ asset('img/pdf.png') }}" alt="PDF" class="w-4 h-4"> PDF Print All
                </a>
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




        {{-- === ATTENDANCE TABLE === --}}
<table class="table-auto w-full border-collapse border border-gray-300 text-sm">
    <thead>
        <tr class="bg-gray-100 text-left">
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
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance->date ?? $attendance['date'] }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ ucfirst($attendance->day_type ?? $attendance['day_type'] ?? 'Regular') }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance->time_in ?? $attendance['time_in'] ?? '-' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance->time_out ?? $attendance['time_out'] ?? '-' }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    @php
                        $status = $attendance->status ?? $attendance['status'] ?? 'pending';
                    @endphp
                    @if($status === 'approved')
                        <span class="text-green-600 font-medium">Approved</span>
                    @elseif($status === 'pending')
                        <span class="text-yellow-500 font-medium">Pending HR</span>
                    @else
                        <span class="text-gray-600">{{ ucfirst($status) }}</span>
                    @endif
                </td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance->remarks ?? $attendance['remarks'] ?? '-' }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    @php
                        $createdAt = $attendance->created_at ?? $attendance['created_at'];
                    @endphp
                    {{ \Carbon\Carbon::parse($createdAt)->format('Y-m-d H:i') }}
                </td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance->created_by ?? $attendance['created_by'] ?? 'System' }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    @php
                        $attendanceId = $attendance->id ?? $attendance['id'];
                        $attendanceStatus = $attendance->status ?? $attendance['status'];
                    @endphp
                    @if($attendanceStatus !== 'approved')
                        <a href="{{ route('attendance.edit', $attendanceId) }}" class="inline-block mr-2" title="Edit">
                            <img src="{{ asset('img/edit.png') }}" alt="Edit" class="w-5 h-5 inline" />
                        </a>
                        <a href="{{ route('attendance.delete', $attendanceId) }}" class="inline-block" title="Delete" onclick="return confirm('Are you sure you want to delete this record?')">
                            <img src="{{ asset('img/delete1.png') }}" alt="Delete" class="w-5 h-5 inline" />
                        </a>
                    @else
                        <span class="text-gray-400">Locked</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center py-8 text-gray-500">
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
</x-app-layout>
