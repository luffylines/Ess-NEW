<x-app-layout>
    <div class="mx-auto px-4 py-4 w-50">
        <h1 class="h2 fw-bold mb-3">Edit Attendance ({{ $attendance->date->format('Y-m-d') }})</h1>

        @if(session('error'))
            <div class="mb-3">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="mb-3">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('attendance.update', $attendance->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="time_in" class="d-block text-secondary fw-semibold mb-2">Time In:</label>
                <input type="time" name="time_in" id="time_in" class="w-100 border rounded px-3 py-2"
                    value="{{ old('time_in', $attendance->time_in ? $attendance->time_in->format('H:i') : '') }}" 
                    @if($attendance->status === 'approved') disabled @endif>
            </div>

            <div class="mb-3">
                <label for="time_out" class="d-block text-secondary fw-semibold mb-2">Time Out:</label>
                <input type="time" name="time_out" id="time_out" class="w-100 border rounded px-3 py-2"
                    value="{{ old('time_out', $attendance->time_out ? $attendance->time_out->format('H:i') : '') }}" 
                    @if($attendance->status === 'approved') disabled @endif>
            </div>

            @if($attendance->status !== 'approved')
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded w-100">
                    Update Attendance
                </button>
            @else
                <p class="text-danger fw-semibold">This attendance record is approved and cannot be edited.</p>
            @endif
        </form>

        <div class="mt-3">
            <a href="{{ route('attendance.my') }}" class="text-muted">Back to Attendance</a>
        </div>
    </div>
</x-app-layout>
