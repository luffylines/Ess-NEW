<x-app-layout>
    <div class="container">
        <h1>Attendance Form</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('attendance.submit') }}">
            @csrf

            <div>
                <label>Time In:</label>
                <input type="time" name="time_in" value="{{ $attendance?->time_in?->format('H:i') }}">
            </div>

            <div>
                <label>Time Out:</label>
                <input type="time" name="time_out" value="{{ $attendance?->time_out?->format('H:i') }}">
            </div>

            <button type="submit">Submit</button>
        </form>

        @if($attendance)
            <p>Status: <strong>{{ ucfirst($attendance->status) }}</strong></p>
        @endif
    </div>
</x-app-layout>
