<x-app-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold">Schedule Details</h1>
            <div class="d-flex gap-2">
                @if(Auth::user()->role === 'employee')
                    <a href="{{ route('schedules.my') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to My Schedules
                    </a>
                @else
                    <a href="{{ route('schedules.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to All Schedules
                    </a>
                @endif
            </div>
        </div>

        @include('partials.flash-messages')

        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Work Schedule #{{ $schedule->id }}
                        </h4>
                        <p class="mb-0 opacity-75">
                            {{ $schedule->schedule_date->format('l, F j, Y') }}
                        </p>
                    </div>
                    <div class="text-end">
                        <span class="badge {{ $schedule->status_badge }} fs-6 mb-2">
                            <i class="fas fa-info-circle me-1"></i>{{ ucfirst($schedule->status) }}
                        </span>
                        <br>
                        <span class="badge {{ $schedule->shift_type_badge }} fs-6">
                            <i class="fas fa-tag me-1"></i>{{ ucfirst($schedule->shift_type) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <!-- Employee Information -->
                    <div class="col-md-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-user me-2"></i>Employee Information
                                </h6>
                                <div class="d-flex align-items-center mb-3">
                                    @if($schedule->employee->profile_photo)
                                        <img src="{{ asset('storage/' . $schedule->employee->profile_photo) }}" 
                                             class="rounded-circle me-3" 
                                             width="64" height="64" style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                             style="width: 64px; height: 64px; font-size: 24px; color: white;">
                                            {{ substr($schedule->employee->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-1">{{ $schedule->employee->name }}</h5>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-id-badge me-1"></i>{{ $schedule->employee->employee_id }}
                                        </p>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-envelope me-1"></i>{{ $schedule->employee->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Details -->
                    <div class="col-md-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-clock me-2"></i>Schedule Details
                                </h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <strong class="text-muted">Date:</strong>
                                        <div class="fw-bold">{{ $schedule->schedule_date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $schedule->schedule_date->format('l') }}</small>
                                    </div>
                                    <div class="col-6">
                                        <strong class="text-muted">Shift Time:</strong>
                                        <div class="fw-bold">
                                            {{ \Carbon\Carbon::parse($schedule->shift_start)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($schedule->shift_end)->format('h:i A') }}
                                        </div>
                                        <small class="text-success">{{ $schedule->getWorkingHours() }} working hours</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location & Type -->
                    <div class="col-md-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Location & Type
                                </h6>
                                <div class="mb-3">
                                    <strong class="text-muted">Store Location:</strong>
                                    <div class="fw-bold">
                                        <i class="fas fa-building me-1"></i>{{ $schedule->store ? $schedule->store->name : 'No store assigned' }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <strong class="text-muted">Shift Type:</strong>
                                    <div>
                                        <span class="badge {{ $schedule->shift_type_badge }} fs-6">
                                            {{ ucfirst($schedule->shift_type) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Info -->
                    <div class="col-md-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-user-tie me-2"></i>Assignment Information
                                </h6>
                                <div class="mb-2">
                                    <strong class="text-muted">Assigned By:</strong>
                                    <div class="fw-bold">{{ $schedule->assignedBy->name }}</div>
                                    <small class="text-muted">{{ $schedule->assignedBy->role }}</small>
                                </div>
                                <div class="mb-2">
                                    <strong class="text-muted">Created:</strong>
                                    <div class="fw-bold">{{ $schedule->created_at->format('M d, Y \a\t h:i A') }}</div>
                                </div>
                                @if($schedule->acknowledged_at)
                                    <div class="mb-2">
                                        <strong class="text-muted">Acknowledged:</strong>
                                        <div class="fw-bold text-success">
                                            <i class="fas fa-check me-1"></i>{{ $schedule->acknowledged_at->format('M d, Y \a\t h:i A') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($schedule->notes)
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="text-primary fw-bold mb-3">
                                        <i class="fas fa-sticky-note me-2"></i>Instructions & Notes
                                    </h6>
                                    <div class="bg-light rounded p-3">
                                        <p class="mb-0">{{ $schedule->notes }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Footer -->
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        @if($schedule->isToday())
                            <i class="fas fa-calendar-day text-success me-1"></i>
                            <strong class="text-success">This is today's schedule</strong>
                        @elseif($schedule->isUpcoming())
                            <i class="fas fa-calendar text-info me-1"></i>
                            <span>Upcoming schedule</span>
                        @else
                            <i class="fas fa-calendar-check text-muted me-1"></i>
                            <span>Past schedule</span>
                        @endif
                    </div>
                    
                    <div class="btn-group">
                        @if(Auth::user()->role === 'employee')
                            <!-- Employee Actions -->
                            @if($schedule->status === 'assigned')
                                <form method="POST" action="{{ route('schedules.acknowledge', $schedule) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-2"></i>Acknowledge Schedule
                                    </button>
                                </form>
                            @endif
                        @else
                            <!-- Manager/HR Actions -->
                            @if(!$schedule->isPast())
                                <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <form method="POST" action="{{ route('schedules.destroy', $schedule) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this schedule?')">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">
                                    <i class="fas fa-lock me-1"></i>Past schedules cannot be modified
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>