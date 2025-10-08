<x-app-layout>
    <div class="mx-auto px-4 py-4">
        <h1 class="h2 fw-bold mb-3">Attendance Monitoring</h1>

        <form method="GET" action="{{ route('hr.monitor') }}" class="mb-3 gap-3">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded">
            <select name="user_id" class="border rounded">
                <option value="">All Employees</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded">Filter</button>
        </form>

        <table class="border">
            <thead>
                <tr class="bg-light">
                    <th class="border">Employee</th>
                    <th class="border">Date</th>
                    <th class="border">Time In</th>
                    <th class="border">Time Out</th>
                    <th class="border">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $att)
                    <tr>
                        <td class="border">{{ $att->user->name }}</td>
                        <td class="border">{{ $att->date->format('Y-m-d') }}</td>
                        <td class="border">{{ $att->time_in?->format('h:i A') ?? '-' }}</td>
                        <td class="border">{{ $att->time_out?->format('h:i A') ?? '-' }}</td>
                        <td class="border">
                            @if($att->time_in && $att->time_out) Present
                            @elseif($att->time_in && !$att->time_out) Time In only
                            @else Absent
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $attendances->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
