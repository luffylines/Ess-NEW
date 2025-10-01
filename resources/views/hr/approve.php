<x-app-layout>
    <div class="container">
        <h1>Pending Attendance Approvals</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->user->name }}</td>
                        <td>{{ $attendance->date->format('Y-m-d') }}</td>
                        <td>{{ $attendance->time_in?->format('h:i A') }}</td>
                        <td>{{ $attendance->time_out?->format('h:i A') }}</td>
                        <td>{{ $attendance->remarks }}</td>
                        <td>
                            <form method="POST" action="{{ route('hr.approve') }}">
                                @csrf
                                <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                                
                                <select name="action" required>
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                </select>

                                <input type="text" name="remarks" placeholder="Remarks (optional)">

                                <button type="submit">Submit</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
