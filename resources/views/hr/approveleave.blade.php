<x-app-layout>
    <div class="mx-auto px-4 py-4">
        <h1 class="h2 fw-bold mb-4">Approve Leave Requests</h1>

        @if(session('success'))
            <div class="border px-4 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="border px-4 rounded mb-3">
                {{ session('error') }}
            </div>
        @endif

        <!-- Leave Requests Section -->
        <div class="mb-8">
            <h2 class="h3 fw-semibold mb-3">Pending Leave Requests</h2>
            
            @if(count($leaveRequests) > 0)
                <div class="overflow-x-auto">
                    <table class="bg-white border">
                        <thead class="bg-light">
                            <tr>
                                <th class="border px-4 py-2 text-start">Employee</th>
                                <th class="border px-4 py-2 text-start">Leave Type</th>
                                <th class="border px-4 py-2 text-start">Start Date</th>
                                <th class="border px-4 py-2 text-start">End Date</th>
                                <th class="border px-4 py-2 text-start">Days</th>
                                <th class="border px-4 py-2 text-start">Reason</th>
                                <th class="border px-4 py-2 text-start">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveRequests as $request)
                                <tr>
                                    <td class="border px-4 py-2">{{ $request->user->name }}</td>
                                    <td class="border px-4 py-2">{{ ucfirst(str_replace('_', ' ', $request->leave_type)) }}</td>
                                    <td class="border px-4 py-2">{{ $request->start_date->format('Y-m-d') }}</td>
                                    <td class="border px-4 py-2">{{ $request->end_date->format('Y-m-d') }}</td>
                                    <td class="border px-4 py-2">{{ $request->total_days }}</td>
                                    <td class="border px-4 py-2">{{ $request->reason }}</td>
                                    <td class="border px-4 py-2">
                                        <form method="POST" action="{{ route('hr.approveleave') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                                            <input type="hidden" name="action" value="approve">
                                            <input type="text" name="manager_remarks" placeholder="Remarks (optional)" class="border rounded px-2 py-1 small mb-1 w-100">
                                            <button type="submit" class="text-white px-3 py-1 rounded small w-100 mb-1">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('hr.approveleave') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                                            <input type="hidden" name="action" value="reject">
                                            <input type="text" name="manager_remarks" placeholder="Remarks (optional)" class="border rounded px-2 py-1 small mb-1 w-100">
                                            <button type="submit" class="text-white px-3 py-1 rounded small w-100">
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
                <div class="bg-light border rounded p-3 text-center text-muted">
                    No pending leave requests at this time.
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="border rounded p-3">
            <h3 class="h4 fw-semibold">Leave Requests</h3>
            <p class="h2 fw-bold">{{ count($leaveRequests) }}</p>
            <p class="text-blue-700">Pending Approval</p>
        </div>
    </div>
</x-app-layout>