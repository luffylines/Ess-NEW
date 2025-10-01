<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Monthly Attendance Report</h1>

        <form method="GET" action="{{ route('hr.reports') }}" class="mb-4 space-x-4">
            <input type="number" name="year" value="{{ $year }}" class="border rounded p-2" placeholder="Year">
            <input type="number" name="month" value="{{ $month }}" class="border rounded p-2" placeholder="Month (1-12)">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">View</button>
            <a href="{{ route('hr.reports.export', ['year' => $year, 'month' => $month]) }}"
               class="px-4 py-2 bg-green-600 text-white rounded">Export CSV</a>
        </form>

        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">User ID</th>
                    <th class="border p-2">Name</th>
                    <th class="border p-2">Present</th>
                    <th class="border p-2">Absent</th>
                    <th class="border p-2">Time In Only</th>
                    <th class="border p-2">Total Days</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report as $row)
                    <tr>
                        <td class="border p-2">{{ $row['user_id'] }}</td>
                        <td class="border p-2">{{ $row['name'] }}</td>
                        <td class="border p-2">{{ $row['present'] }}</td>
                        <td class="border p-2">{{ $row['absent'] }}</td>
                        <td class="border p-2">{{ $row['in_only'] }}</td>
                        <td class="border p-2">{{ $row['total_days'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
