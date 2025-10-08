<x-app-layout>
    <div class="mx-auto px-4 py-4 w-50">
        <h1 class="h2 fw-bold mb-3">Delete Attendance Record</h1>

        <p>Are you sure you want to delete the attendance record for <strong>{{ $attendance->date->format('Y-m-d') }}</strong>?</p>

        <form method="POST" action="{{ route('attendance.destroy', $attendance->id) }}" class="mt-3">
            @csrf
            @method('DELETE')

            <button type="submit" class="bg-danger text-white px-4 py-2 rounded">
                Yes, Delete
            </button>
            <a href="{{ route('attendance.my') }}" class="text-muted hover:underline">Cancel</a>
        </form>
    </div>
</x-app-layout>
