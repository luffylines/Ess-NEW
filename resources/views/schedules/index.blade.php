<x-app-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold">Work Schedule Management</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Assign New Schedule
                </a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkCreateModal">
                    <i class="fas fa-calendar-plus me-2"></i>Bulk Assign
                </button>
            </div>
        </div>

        @include('partials.flash-messages')

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('schedules.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select name="employee_id" id="employee_id" class="form-select">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="acknowledged" {{ request('status') == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="missed" {{ request('status') == 'missed' ? 'selected' : '' }}>Missed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Schedules Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Work Schedules
                </h5>
            </div>
            <div class="card-body p-0">
                @if($schedules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Employee</th>
                                    <th>Date</th>
                                    <th>Shift Time</th>
                                    <th>Hours</th>
                                    <th>Type</th>
                                    <th>Store Location</th>
                                    <th>Status</th>
                                    <th>Assigned By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($schedule->employee->profile_photo)
                                                    <img src="{{ asset('storage/' . $schedule->employee->profile_photo) }}" 
                                                         class="rounded-circle me-2" 
                                                         width="32" height="32" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                         style="width: 32px; height: 32px; font-size: 14px; color: white;">
                                                        {{ substr($schedule->employee->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold">{{ $schedule->employee->name }}</div>
                                                    <small class="text-muted">{{ $schedule->employee->employee_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $schedule->schedule_date->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $schedule->schedule_date->format('l') }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">
                                                {{ \Carbon\Carbon::parse($schedule->shift_start)->format('h:i A') }} - 
                                                {{ \Carbon\Carbon::parse($schedule->shift_end)->format('h:i A') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $schedule->getWorkingHours() }} hrs</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $schedule->shift_type_badge }}">
                                                {{ ucfirst($schedule->shift_type) }}
                                            </span>
                                        </td>
                                        <td>{{ $schedule->store ? $schedule->store->name : 'No store assigned' }}</td>
                                        <td>
                                            <span class="badge {{ $schedule->status_badge }}">
                                                {{ ucfirst($schedule->status) }}
                                            </span>
                                            @if($schedule->acknowledged_at)
                                                <br><small class="text-muted">{{ $schedule->acknowledged_at->format('M d, h:i A') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $schedule->assignedBy->name }}</div>
                                            <small class="text-muted">{{ $schedule->created_at->format('M d, h:i A') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(!$schedule->isPast())
                                                    <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-outline-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('schedules.destroy', $schedule) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                onclick="return confirm('Are you sure you want to delete this schedule?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer d-flex justify-content-center">
                        {{ $schedules->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                        <h4>No schedules found</h4>
                        <p class="text-muted">No work schedules match your current filters.</p>
                        <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create First Schedule
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bulk Create Modal -->
    <div class="modal fade dark-mode" id="bulkCreateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('schedules.bulk-create') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Bulk Schedule Assignment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="bulk_employee_ids" class="form-label">Select Employees</label>
                                <select name="employee_ids[]" id="bulk_employee_ids" class="form-select" multiple required size="8">
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_id }})</option>
                                    @endforeach
                                </select>
                                <small class="">Hold Ctrl to select multiple employees</small>
                            </div>
                            <div class="col-md-6">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label for="bulk_date_from" class="form-label">Date From</label>
                                        <input type="date" name="date_from" id="bulk_date_from" class="form-control" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="bulk_date_to" class="form-label">Date To</label>
                                        <input type="date" name="date_to" id="bulk_date_to" class="form-control" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="bulk_shift_start" class="form-label">Start Time</label>
                                        <input type="time" name="shift_start" id="bulk_shift_start" class="form-control" value="08:00" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="bulk_shift_end" class="form-label">End Time</label>
                                        <input type="time" name="shift_end" id="bulk_shift_end" class="form-control" value="17:00" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="bulk_shift_type" class="form-label">Shift Type</label>
                                <select name="shift_type" id="bulk_shift_type" class="form-select" required>
                                    <option value="regular">Regular</option>
                                    <option value="overtime">Overtime</option>
                                    <option value="holiday">Holiday</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="bulk_store_id" class="form-label">Store Location</label>
                                <select name="store_id" id="bulk_store_id" class="form-select">
                                    <option value="">Select Store Location</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="bulk_notes" class="form-label">Notes</label>
                                <textarea name="notes" id="bulk_notes" class="form-control" rows="2" placeholder="Additional instructions or notes"></textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="skip_weekends" id="skip_weekends" value="1" checked>
                                    <label class="form-check-label" for="skip_weekends">
                                        Skip weekends (Saturday & Sunday)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Schedules</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Set default dates for bulk create
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const nextWeek = new Date(today);
            nextWeek.setDate(today.getDate() + 7);
            
            document.getElementById('bulk_date_from').valueAsDate = today;
            document.getElementById('bulk_date_to').valueAsDate = nextWeek;
        });
        //dark mode modal fixes
        document.addEventListener('DOMContentLoaded', () => {
        const bulkModal = document.getElementById('bulkCreateModal');
        bulkModal.addEventListener('show.bs.modal', () => {
            bulkModal.classList.add('dark-mode'); // ensures dark-mode class is always applied when opened
        });
    });
    </script>

    <style>
            /* All styles apply only to the modal with class .dark-mode */
    .dark-mode .modal-content {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    .dark-mode label,
    .dark-mode .form-label {
        color: #000000 !important;
    }
    .dark-mode .form-control,
    .dark-mode .form-select,
    .dark-mode textarea {
        background-color: #ffffff !important;
        color: #000000 !important;
        border-color: #444 !important;
    }
    .dark-mode .form-control::placeholder,
    .dark-mode textarea::placeholder {
        color: #555555 !important;
    }
    .dark-mode select option {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    .dark-mode .form-check-label {
        color: #000000 !important;
    }
    </style>

</x-app-layout>