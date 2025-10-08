<x-app-layout>
    <div class="mx-auto px-4 py-4 container-fluid">
        <h1 class="h2 fw-bold mb-3">Generate Shift Schedule</h1>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-3">{{ session('error') }}</div>
        @endif

        {{-- === FORM TO GENERATE SHIFT SCHEDULE === --}}
        <form method="POST" action="{{ route('attendance.generateShiftSchedule') }}">
            @csrf
            <div class="d-flex d-flex flex-column gap-3">

                <!-- Employee ID -->
                <div>
                    <label for="employee_id" class="small fw-medium">Employee ID:</label>
                    <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id') }}"
                           class="border rounded px-3 py-2 small" required>
                    @error('employee_id') <span class="small">{{ $message }}</span> @enderror
                </div>

                <!-- Time In -->
                <div>
                    <label for="time_in" class="small fw-medium">Time In:</label>
                    <input type="time" id="time_in" name="time_in" value="{{ old('time_in') }}"
                           class="border rounded px-3 py-2 small" required>
                    @error('time_in') <span class="small">{{ $message }}</span> @enderror
                </div>

                <!-- Time Out -->
                <div>
                    <label for="time_out" class="small fw-medium">Time Out:</label>
                    <input type="time" id="time_out" name="time_out" value="{{ old('time_out') }}"
                           class="border rounded px-3 py-2 small" required>
                    @error('time_out') <span class="small">{{ $message }}</span> @enderror
                </div>

                <!-- Date From -->
                <div>
                    <label for="date_from" class="small fw-medium">From:</label>
                    <input type="date" name="date_from" id="date_from" value="{{ old('date_from') }}"
                           class="border rounded px-3 py-2 small" required>
                    @error('date_from') <span class="small">{{ $message }}</span> @enderror
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="small fw-medium">To:</label>
                    <input type="date" name="date_to" id="date_to" value="{{ old('date_to') }}"
                           class="border rounded px-3 py-2 small" required>
                    @error('date_to') <span class="small">{{ $message }}</span> @enderror
                </div>

                <!-- Remarks -->
                <div>
                    <label for="remarks" class="small fw-medium">Remarks:</label>
                    <textarea name="remarks" id="remarks" rows="3" class="border rounded px-3 py-2 small">{{ old('remarks') }}</textarea>
                    @error('remarks') <span class="small">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-3">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded small">
                        Generate Shift Schedule
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
