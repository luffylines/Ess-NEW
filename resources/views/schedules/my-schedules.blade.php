<x-app-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold">My Work Schedule</h1>
            <div>
                <span class="text-muted">{{ Auth::user()->name }} ({{ Auth::user()->employee_id }})</span>
            </div>
        </div>

        @include('partials.flash-messages')

        <!-- Today's Schedule Card -->
        @if($todaySchedule)
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-day me-2"></i>Today's Schedule - {{ now()->format('l, M d, Y') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-primary fa-2x me-3"></i>
                                <div>
                                    <div class="fw-bold">
                                        {{ \Carbon\Carbon::parse($todaySchedule->shift_start)->format('h:i A') }} - 
                                        {{ \Carbon\Carbon::parse($todaySchedule->shift_end)->format('h:i A') }}
                                    </div>
                                    <small class="text-muted">{{ $todaySchedule->getWorkingHours() }} working hours</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-success fa-2x me-3"></i>
                                <div>
                                    <div class="fw-bold">{{ $todaySchedule->store ? $todaySchedule->store->name : 'No store assigned' }}</div>
                                    <small class="text-muted">Store location</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-tag text-info fa-2x me-3"></i>
                                <div>
                                    <span class="badge {{ $todaySchedule->shift_type_badge }} fs-6">
                                        {{ ucfirst($todaySchedule->shift_type) }}
                                    </span>
                                    <div>
                                        <span class="badge {{ $todaySchedule->status_badge }} mt-1">
                                            {{ ucfirst($todaySchedule->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($todaySchedule->notes)
                        <div class="mt-3 pt-3 border-top">
                            <strong><i class="fas fa-sticky-note me-1"></i>Instructions:</strong>
                            <div class="mt-1">{{ $todaySchedule->notes }}</div>
                        </div>
                    @endif

                    @if($todaySchedule->status === 'assigned')
                        <div class="mt-3 pt-3 border-top">
                            <form method="POST" action="{{ route('schedules.acknowledge', $todaySchedule) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Acknowledge Schedule
                                </button>
                            </form>
                            <small class="text-muted ms-3">Click to confirm you've seen today's schedule</small>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>No schedule for today.</strong> You don't have a work schedule assigned for today.
            </div>
        @endif

        <!-- Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('schedules.my') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" 
                               value="{{ request('date_from', \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" 
                               value="{{ request('date_to', \Carbon\Carbon::now()->addDays(30)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <a href="{{ route('schedules.my') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Schedule Calendar/List -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>My Upcoming Schedules
                </h5>
            </div>
            <div class="card-body p-0">
                @if($schedules->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($schedules as $schedule)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <div class="fw-bold">{{ $schedule->schedule_date->format('M d') }}</div>
                                        <small class="text-muted">{{ $schedule->schedule_date->format('l') }}</small>
                                        @if($schedule->isToday())
                                            <div><span class="badge bg-success mt-1">TODAY</span></div>
                                        @elseif($schedule->schedule_date->isTomorrow())
                                            <div><span class="badge bg-warning mt-1">TOMORROW</span></div>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <div class="fw-bold">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($schedule->shift_start)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($schedule->shift_end)->format('h:i A') }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="badge bg-info">{{ $schedule->getWorkingHours() }} hrs</span>
                                        <div><span class="badge {{ $schedule->shift_type_badge }} mt-1">{{ ucfirst($schedule->shift_type) }}</span></div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="fw-semibold">{{ $schedule->store ? $schedule->store->name : 'No store assigned' }}</div>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>by {{ $schedule->assignedBy->name }}
                                        </small>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="badge {{ $schedule->status_badge }}">
                                            {{ ucfirst($schedule->status) }}
                                        </span>
                                        @if($schedule->acknowledged_at)
                                            <div class="mt-1">
                                                <small class="text-success">
                                                    <i class="fas fa-check me-1"></i>{{ $schedule->acknowledged_at->format('M d, h:i A') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-1">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if($schedule->status === 'assigned')
                                                    <li>
                                                        <form method="POST" action="{{ route('schedules.acknowledge', $schedule) }}" class="d-inline w-100">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check me-2"></i>Acknowledge
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @if($schedule->notes)
                                    <div class="mt-2 pt-2 border-top">
                                        <small class="text-muted">
                                            <i class="fas fa-sticky-note me-1"></i>
                                            <strong>Notes:</strong> {{ $schedule->notes }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                        <h4>No schedules found</h4>
                        <p class="text-muted">You don't have any work schedules for the selected date range.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>