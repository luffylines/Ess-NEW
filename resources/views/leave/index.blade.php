@extends('layouts.app')

@section('content')
<div class="mx-auto p-3">
    <div class="bg-white rounded p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h2 fw-bold text-dark">My Leave Requests</h2>
            <a href="{{ route('leave.create') }}" class="bg-primary text-white px-4 py-2 rounded">
                <i class="fas fa-plus mr-2"></i>Request Leave
            </a>
        </div>

        @include('partials.flash-messages')

        @if($leaveRequests->count() > 0)
            <div class="overflow-x-auto">
                <table class="table-auto">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-start small fw-medium text-muted">ID</th>
                            <th class="text-start small fw-medium text-muted">Type</th>
                            <th class="text-start small fw-medium text-muted">Dates</th>
                            <th class="text-start small fw-medium text-muted">Days</th>
                            <th class="text-start small fw-medium text-muted">Status</th>
                            <th class="text-start small fw-medium text-muted">Submitted</th>
                            <th class="text-start small fw-medium text-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($leaveRequests as $request)
                            <tr class="">
                                <td class="py-3 small fw-medium">
                                    #{{ $request->id }}
                                </td>
                                <td class="py-3 small">
                                    {{ ucfirst($request->leave_type) }}
                                </td>
                                <td class="py-3 small">
                                    {{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }} - 
                                    {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                                </td>
                                <td class="py-3 small">
                                    {{ $request->total_days ?? $request->calculateTotalDays() }} days
                                </td>
                                <td class="py-3">
                                    {!! $request->getStatusBadge() !!}
                                </td>
                                <td class="py-3 small text-muted">
                                    {{ $request->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-3 small fw-medium gap-2">
                                    <a href="{{ route('leave.show', $request) }}" class="text-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @if($request->status === 'pending')
                                        <a href="{{ route('leave.edit', $request) }}" class="text-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('leave.destroy', $request) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this leave request?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($leaveRequests->hasPages())
                <div class="mt-4">
                    {{ $leaveRequests->links() }}
                </div>
            @endif
        @else
            <div class="text-center">
                <div class="text-muted mb-3">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3 class="h4 fw-medium mb-2">No Leave Requests</h3>
                <p class="text-muted mb-3">You haven't submitted any leave requests yet.</p>
                <a href="{{ route('leave.create') }}" class="bg-primary text-white py-2 rounded">
                    <i class="fas fa-plus mr-2"></i>Request Your First Leave
                </a>
            </div>
        @endif
    </div>
</div>
@endsection