<x-app-layout>
    <div class="container mx-auto px-4 py-6 max-w-md">
        <h1 class="text-2xl font-bold mb-4">Edit Attendance ({{ $attendance->date->format('Y-m-d') }})</h1>

        @if(session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('attendance.update', $attendance->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="time_in" class="block text-gray-700 font-semibold mb-2">Time In:</label>
                <input type="time" name="time_in" id="time_in" class="w-full border border-gray-300 rounded px-3 py-2"
                    value="{{ old('time_in', $attendance->time_in ? $attendance->time_in->format('H:i') : '') }}" 
                    @if($attendance->status === 'approved') disabled @endif>
            </div>

            <div class="mb-4">
                <label for="time_out" class="block text-gray-700 font-semibold mb-2">Time Out:</label>
                <input type="time" name="time_out" id="time_out" class="w-full border border-gray-300 rounded px-3 py-2"
                    value="{{ old('time_out', $attendance->time_out ? $attendance->time_out->format('H:i') : '') }}" 
                    @if($attendance->status === 'approved') disabled @endif>
            </div>

            @if($attendance->status !== 'approved')
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
                    Update Attendance
                </button>
            @else
                <p class="text-red-600 font-semibold">This attendance record is approved and cannot be edited.</p>
            @endif
        </form>

        <div class="mt-4">
            <a href="{{ route('attendance.my') }}" class="text-gray-600 hover:underline">Back to Attendance</a>
        </div>
    </div>
</x-app-layout>
