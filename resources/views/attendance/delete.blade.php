<x-app-layout>
    <div class="container mx-auto px-4 py-6 max-w-md">
        <h1 class="text-2xl font-bold mb-4">Delete Attendance Record</h1>

        <p>Are you sure you want to delete the attendance record for <strong>{{ $attendance->date->format('Y-m-d') }}</strong>?</p>

        <form method="POST" action="{{ route('attendance.destroy', $attendance->id) }}" class="mt-4">
            @csrf
            @method('DELETE')

            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Yes, Delete
            </button>
            <a href="{{ route('attendance.my') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </form>
    </div>
</x-app-layout>
