<x-app-layout>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold">My Overtime Requests</h1>
            <a href="{{ route('overtime.create') }}" class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i> New Overtime Request
            </a>
        </div>

        @include('partials.flash-messages')

        <div class="card shadow-sm rounded-3">
            @if($overtimeRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="text-uppercase text-secondary small">
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th scope="col">Hours</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overtimeRequests as $request)
                                <tr class="align-middle">
                                    <td class="text-nowrap">{{ $request->overtime_date ? $request->overtime_date->format('M d, Y') : 'N/A' }}</td>
                                    <td class="text-nowrap font-monospace">
                                        {{ ($request->start_time ? \Carbon\Carbon::parse($request->start_time)->format('h:i A') : 'N/A') }} - {{ ($request->end_time ? \Carbon\Carbon::parse($request->end_time)->format('h:i A') : 'N/A') }}
                                    </td>
                                    <td class="fw-semibold text-primary">{{ number_format($request->total_hours, 2) }} hr</td>
                                    <td class="text-truncate" style="max-width: 200px;" title="{{ $request->reason }}">
                                        {{ \Illuminate\Support\Str::limit($request->reason, 50) }}
                                    </td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-warning text-dark',
                                                'approved' => 'bg-success text-white',
                                                'rejected' => 'bg-danger text-white',
                                            ];
                                        @endphp
                                        <span class="badge rounded-pill {{ $statusClasses[$request->status] ?? 'bg-secondary' }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('overtime.show', $request->id) }}" class="text-primary text-decoration-none me-3">View</a>
                                        @if($request->status === 'pending')
                                            <a href="{{ route('overtime.edit', $request->id) }}" class="text-warning text-decoration-none me-3">Edit</a>
                                            <form method="POST" action="{{ route('overtime.destroy', $request->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this request?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-danger text-decoration-none">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer border-0 d-flex justify-content-center">
                    {{ $overtimeRequests->links() }}
                </div>
            @else
                <div class="text-center py-5 px-3">
                    <i class="fas fa-clock fa-5x text-secondary mb-4"></i>
                    <h3 class="fw-semibold text-secondary mb-2">No overtime requests yet</h3>
                    <p class="text-muted mb-4">You haven't submitted any overtime requests.</p>
                    <a href="{{ route('overtime.create') }}" class="btn btn-primary btn-lg">
                        Submit Your First Request
                    </a>
                </div>
            @endif
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const alert = document.getElementById('alert-message');
        if (alert) {
            // Automatically dismiss after 3 seconds (3000ms)
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }, 3000);
        }
    });
</script>
</x-app-layout>
