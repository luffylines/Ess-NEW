<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Attendance Monitoring</h1>

        <form method="GET" action="{{ route('hr.attendance.monitor') }}" class="mb-4 space-x-4">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded p-2">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded p-2">
            <select name="user_id" class="border rounded p-2">
                <option value="">All Employees</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
        </form>

        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Employee</th>
                    <th class="border p-2">Date</th>
                    <th class="border p-2">Time In</th>
                    <th class="border p-2">Time Out</th>
                    <th class="border p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $att)
                    <tr>
                        <td class="border p-2">{{ $att->user->name }}</td>
                        <td class="border p-2">{{ $att->date->format('Y-m-d') }}</td>
                        <td class="border p-2">{{ $att->time_in?->format('h:i A') ?? '-' }}</td>
                        <td class="border p-2">{{ $att->time_out?->format('h:i A') ?? '-' }}</td>
                        <td class="border p-2">
                            @if($att->time_in && $att->time_out) Present
                            @elseif($att->time_in && !$att->time_out) Time In only
                            @else Absent
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $attendances->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
