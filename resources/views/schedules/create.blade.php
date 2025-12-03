<x-app-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold">Assign Work Schedule</h1>
            <a href="{{ route('schedules.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Schedules
            </a>
        </div>

        @include('partials.flash-messages')

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-plus me-2"></i>New Work Schedule
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('schedules.store') }}">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Employee Selection -->
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label fw-semibold">
                                <i class="fas fa-user me-1"></i>Employee <span class="text-danger">*</span>
                            </label>
                            <select name="employee_id" id="employee_id" class="form-select" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                            {{ (old('employee_id') == $employee->id || $selectedEmployeeId == $employee->id) ? 'selected' : '' }}>
                                        {{ $employee->name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Schedule Date -->
                        <div class="col-md-6">
                            <label for="schedule_date" class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1"></i>Schedule Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="schedule_date" id="schedule_date" class="form-control" 
                                   value="{{ old('schedule_date') }}" min="{{ date('Y-m-d') }}" required>
                            @error('schedule_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Shift Times -->
                        <div class="col-md-6">
                            <label for="shift_start" class="form-label fw-semibold">
                                <i class="fas fa-clock me-1"></i>Shift Start Time <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="shift_start" id="shift_start" class="form-control" 
                                   value="{{ old('shift_start', '08:00') }}" required>
                            @error('shift_start')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="shift_end" class="form-label fw-semibold">
                                <i class="fas fa-clock me-1"></i>Shift End Time <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="shift_end" id="shift_end" class="form-control" 
                                   value="{{ old('shift_end', '17:00') }}" required>
                            @error('shift_end')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Shift Type -->
                        <div class="col-md-6">
                            <label for="shift_type" class="form-label fw-semibold">
                                <i class="fas fa-tag me-1"></i>Shift Type <span class="text-danger">*</span>
                            </label>
                            <select name="shift_type" id="shift_type" class="form-select" required>
                                <option value="regular" {{ old('shift_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="overtime" {{ old('shift_type') == 'overtime' ? 'selected' : '' }}>Overtime</option>
                                <option value="holiday" {{ old('shift_type') == 'holiday' ? 'selected' : '' }}>Holiday</option>
                            </select>
                            @error('shift_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Store Location -->
                        <div class="col-md-6">
                            <label for="store_id" class="form-label fw-semibold">
                                <i class="fas fa-map-marker-alt me-1"></i>Store Location
                            </label>
                            <select name="store_id" id="store_id" class="form-select">
                                <option value="">Select Store Location</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('store_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                            <label for="notes" class="form-label fw-semibold">
                                <i class="fas fa-sticky-note me-1"></i>Notes & Instructions
                            </label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" 
                                      placeholder="Additional instructions, special requirements, or notes for this schedule">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Working Hours Preview -->
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Schedule Summary</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Working Hours:</strong>
                                        <div id="working-hours-display" class="fw-bold text-primary">8.0 hours</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Assign Schedule
                                </button>
                                <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const shiftStart = document.getElementById('shift_start');
            const shiftEnd = document.getElementById('shift_end');
            const workingHoursDisplay = document.getElementById('working-hours-display');

            function calculateHours() {
                if (!shiftStart.value || !shiftEnd.value) return;

                // Calculate shift duration
                const start = new Date('2000-01-01 ' + shiftStart.value);
                let end = new Date('2000-01-01 ' + shiftEnd.value);
                
                // Handle overnight shifts
                if (end <= start) {
                    end.setDate(end.getDate() + 1);
                }

                const totalMinutes = (end - start) / (1000 * 60);
                const workingHours = totalMinutes / 60;

                // Update display
                workingHoursDisplay.textContent = workingHours.toFixed(1) + ' hours';

                // Color coding
                if (workingHours < 6) {
                    workingHoursDisplay.className = 'fw-bold text-danger';
                } else if (workingHours <= 8) {
                    workingHoursDisplay.className = 'fw-bold text-success';
                } else {
                    workingHoursDisplay.className = 'fw-bold text-warning';
                }
            }

            // Add event listeners
            [shiftStart, shiftEnd].forEach(input => {
                input.addEventListener('change', calculateHours);
                input.addEventListener('input', calculateHours);
            });

            // Initial calculation
            calculateHours();
        });
    </script>
</x-app-layout>