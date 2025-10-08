<x-app-layout>
    <div class="mx-auto px-4 py-4">
        <h1 class="h2 fw-bold mb-3">Monthly Attendance Report</h1>

        <form method="GET" action="{{ route('hr.reports') }}" class="mb-3 gap-3">
            <input type="number" name="year" value="{{ $year }}" class="border rounded" placeholder="Year">
            <input type="number" name="month" value="{{ $month }}" class="border rounded" placeholder="Month (1-12)">
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded">View</button>
            <a href="{{ route('hr.reports.export', ['year' => $year, 'month' => $month]) }}"
               class="px-4 py-2 bg-success text-white rounded">Export CSV</a>
        </form>

        <table class="border">
            <thead>
                <tr class="bg-light">
                    <th class="border">User ID</th>
                    <th class="border">Name</th>
                    <th class="border">Present</th>
                    <th class="border">Absent</th>
                    <th class="border">Time In Only</th>
                    <th class="border">Total Days</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report as $row)
                    <tr>
                        <td class="border">{{ $row['user_id'] }}</td>
                        <td class="border">{{ $row['name'] }}</td>
                        <td class="border">{{ $row['present'] }}</td>
                        <td class="border">{{ $row['absent'] }}</td>
                        <td class="border">{{ $row['in_only'] }}</td>
                        <td class="border">{{ $row['total_days'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
