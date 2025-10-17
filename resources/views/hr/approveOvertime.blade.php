@extends('layouts.app')

@section('content')
    <div class="mx-auto px-4 py-4">
        <h1 class="h2 fw-bold mb-4">Approve Overtime Requests</h1>

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

        <!-- Overtime Requests Section -->
        <div class="mb-8">
            <h2 class="h3 fw-semibold mb-3">Pending Overtime Requests</h2>
            
            @if(count($overtimeRequests) > 0)
                <div class="overflow-x-auto">
                    <table class="bg-white border">
                        <thead class="bg-light">
                            <tr>
                                <th class="border px-4 py-2 text-start">Employee</th>
                                <th class="border px-4 py-2 text-start">Date</th>
                                <th class="border px-4 py-2 text-start">Start Time</th>
                                <th class="border px-4 py-2 text-start">End Time</th>
                                <th class="border px-4 py-2 text-start">Hours</th>
                                <th class="border px-4 py-2 text-start">Reason</th>
                                <th class="border px-4 py-2 text-start">Actions</th>
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
                                        <form method="POST" action="{{ route('hr.approveOvertime') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                                            <input type="hidden" name="action" value="approve">
                                            <input type="text" name="manager_remarks" placeholder="Remarks (optional)" class="border rounded px-2 py-1 small mb-1 w-100">
                                            <button type="submit" class="text-white px-3 py-1 rounded small w-100 mb-1">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('hr.approveOvertime') }}" class="d-inline">
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
                    No pending overtime requests at this time.
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="border rounded p-3">
            <h3 class="h4 fw-semibold">Overtime Requests</h3>
            <p class="h2 fw-bold">{{ count($overtimeRequests) }}</p>
            <p class="text-orange-700">Pending Approval</p>
        </div>
    </div>
@endsection