<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Approve Overtime Requests</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Overtime Requests Section -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Pending Overtime Requests</h2>
            
            @if(count($overtimeRequests) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2 text-left">Employee</th>
                                <th class="border px-4 py-2 text-left">Date</th>
                                <th class="border px-4 py-2 text-left">Start Time</th>
                                <th class="border px-4 py-2 text-left">End Time</th>
                                <th class="border px-4 py-2 text-left">Hours</th>
                                <th class="border px-4 py-2 text-left">Reason</th>
                                <th class="border px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overtimeRequests as $request)
                                <tr>
                                    <td class="border px-4 py-2">{{ $request->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $request->overtime_date->format('Y-m-d') }}</td>
                                    <td class="border px-4 py-2">{{ $request->start_time->format('H:i') }}</td>
                                    <td class="border px-4 py-2">{{ $request->end_time->format('H:i') }}</td>
                                    <td class="border px-4 py-2">{{ $request->total_hours }}</td>
                                    <td class="border px-4 py-2">{{ $request->reason }}</td>
                                    <td class="border px-4 py-2">
                                        <form method="POST" action="{{ route('hr.approveOvertime') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                                            <input type="hidden" name="action" value="approve">
                                            <input type="text" name="manager_remarks" placeholder="Remarks (optional)" class="border rounded px-2 py-1 text-sm mb-1 w-full">
                                            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 w-full mb-1">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('hr.approveOvertime') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                                            <input type="hidden" name="action" value="reject">
                                            <input type="text" name="manager_remarks" placeholder="Remarks (optional)" class="border rounded px-2 py-1 text-sm mb-1 w-full">
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 w-full">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-gray-100 border border-gray-300 rounded p-4 text-center text-gray-600">
                    No pending overtime requests at this time.
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="bg-orange-100 border border-orange-300 rounded p-4">
            <h3 class="text-lg font-semibold text-orange-800">Overtime Requests</h3>
            <p class="text-2xl font-bold text-orange-900">{{ count($overtimeRequests) }}</p>
            <p class="text-orange-700">Pending Approval</p>
        </div>
    </div>
</x-app-layout>
