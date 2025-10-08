<x-app-layout>
    <div class="mx-auto px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold">My Overtime Requests</h1>
            <a href="{{ route('overtime.create') }}" class="text-white px-4 py-2 rounded">
                <i class="fas fa-plus mr-2"></i>New Overtime Request
            </a>
        </div>

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

        <div class="bg-white rounded shadow">
            @if($overtimeRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="divide-y divide-gray-200">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-start small fw-medium text-muted">Date</th>
                                <th class="text-start small fw-medium text-muted">Time</th>
                                <th class="text-start small fw-medium text-muted">Hours</th>
                                <th class="text-start small fw-medium text-muted">Reason</th>
                                <th class="text-start small fw-medium text-muted">Status</th>
                                <th class="text-start small fw-medium text-muted">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($overtimeRequests as $request)
                                <tr>
                                    <td class="py-3 small">
                                        {{ $request->overtime_date->format('M d, Y') }}
                                    </td>
                                    <td class="py-3 small">
                                        {{ $request->start_time->format('H:i') }} - {{ $request->end_time->format('H:i') }}
                                    </td>
                                    <td class="py-3 small">
                                        {{ $request->total_hours }} hrs
                                    </td>
                                    <td class="py-3 small">
                                        {{ \Illuminate\Support\Str::limit($request->reason, 50) }}
                                    </td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 small fw-semibold rounded-circle">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 small fw-medium">
                                        <a href="{{ route('overtime.show', $request) }}" class="text-primary mr-3">View</a>
                                        @if($request->status === 'pending')
                                            <a href="{{ route('overtime.edit', $request) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <form method="POST" action="{{ route('overtime.destroy', $request) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="border">
                    {{ $overtimeRequests->links() }}
                </div>
            @else
                <div class="text-center">
                    <i class="text-muted mb-3"></i>
                    <h3 class="h4 fw-medium mb-2">No overtime requests yet</h3>
                    <p class="text-muted mb-3">You haven't submitted any overtime requests.</p>
                    <a href="{{ route('overtime.create') }}" class="text-white px-4 py-2 rounded">
                        Submit Your First Request
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>