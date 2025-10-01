<x-app-layout>
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <h1 class="text-2xl font-bold mb-4">Generate Shift Schedule</h1>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif

        {{-- === FORM TO GENERATE SHIFT SCHEDULE === --}}
        <form method="POST" action="{{ route('attendance.generateShiftSchedule') }}">
            @csrf
            <div class="flex flex-col gap-4">

                <!-- Employee ID -->
                <div>
                    <label for="employee_id" class="text-sm font-medium">Employee ID:</label>
                    <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id') }}"
                           class="border rounded px-3 py-2 text-sm" required>
                    @error('employee_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Time In -->
                <div>
                    <label for="time_in" class="text-sm font-medium">Time In:</label>
                    <input type="time" id="time_in" name="time_in" value="{{ old('time_in') }}"
                           class="border rounded px-3 py-2 text-sm" required>
                    @error('time_in') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Time Out -->
                <div>
                    <label for="time_out" class="text-sm font-medium">Time Out:</label>
                    <input type="time" id="time_out" name="time_out" value="{{ old('time_out') }}"
                           class="border rounded px-3 py-2 text-sm" required>
                    @error('time_out') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Date From -->
                <div>
                    <label for="date_from" class="text-sm font-medium">From:</label>
                    <input type="date" name="date_from" id="date_from" value="{{ old('date_from') }}"
                           class="border rounded px-3 py-2 text-sm" required>
                    @error('date_from') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="text-sm font-medium">To:</label>
                    <input type="date" name="date_to" id="date_to" value="{{ old('date_to') }}"
                           class="border rounded px-3 py-2 text-sm" required>
                    @error('date_to') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Remarks -->
                <div>
                    <label for="remarks" class="text-sm font-medium">Remarks:</label>
                    <textarea name="remarks" id="remarks" rows="3" class="border rounded px-3 py-2 text-sm">{{ old('remarks') }}</textarea>
                    @error('remarks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300 text-sm">
                        Generate Shift Schedule
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
