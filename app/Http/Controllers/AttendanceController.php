<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use PDF;


class AttendanceController extends Controller
{
    // Show attendance records
    public function myAttendance(Request $request)
    {
        $user = Auth::user();

        // Set timezone to Philippines
        $timezone = 'Asia/Manila';

        // Query with search filters
        $query = Attendance::where('user_id', $user->id)
                          ->with('createdByUser');

        // Apply search filters
        if ($request->search_created_by) {
            $query->whereHas('createdByUser', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search_created_by . '%');
            });
        }

        if ($request->date_from) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->where('date', '<=', $request->date_to);
        }

        $attendances = $query->orderBy('date', 'desc')
                           ->paginate(10)
                           ->through(function ($attendance) use ($timezone) {
                               // Calculate attendance status
                               $status = $this->calculateAttendanceStatus($attendance);
                               
                               return [
                                   'id' => $attendance->id,
                                   'date' => Carbon::parse($attendance->date)->format('Y-m-d'),
                                   'day_type' => ucfirst($attendance->day_type ?? 'regular'),
                                   'time_in' => $attendance->time_in ? 
                                       Carbon::parse($attendance->time_in)->setTimezone($timezone)->format('h:i A') : '-',
                                   'time_out' => $attendance->time_out ? 
                                       Carbon::parse($attendance->time_out)->setTimezone($timezone)->format('h:i A') : '-',
                                   'status' => $status,
                                   'remarks' => $attendance->remarks ?? '-',
                                   'created_at' => $attendance->created_at ? 
                                       $attendance->created_at->setTimezone($timezone)->format('Y-m-d H:i') : '-',
                                   'created_by' => $attendance->createdByUser?->name ?? 'System',
                                   'attendance_status' => $attendance->status, // for action buttons
                               ];
                           });

        // Get today's attendance record if exists (in Philippines timezone)
        $today = Carbon::now($timezone)->format('Y-m-d');
        $todayAttendance = Attendance::where('user_id', $user->id)
                                   ->where('date', $today)
                                   ->first();

        return view('attendance.my', compact('attendances', 'todayAttendance'));
    }

    // Helper method to calculate attendance status
    private function calculateAttendanceStatus($attendance)
    {
        if (!$attendance->time_in && !$attendance->time_out) {
            return 'Absent';
        }
        
        if ($attendance->time_in && $attendance->time_out) {
            // Check if late (assuming 8:00 AM start time)
            $expectedTimeIn = Carbon::parse($attendance->date)->setTime(8, 0, 0); // 8:00 AM
            $actualTimeIn = Carbon::parse($attendance->time_in);
            
            if ($actualTimeIn->gt($expectedTimeIn)) {
                return 'Late';
            }
            return 'Present';
        }
        
        if ($attendance->time_in && !$attendance->time_out) {
            return 'Time In Only';
        }
        
        return 'Incomplete';
    }

    // Show form/buttons for Time In/Out
    public function attendanceForm()
    {
        $user = auth()->user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        return view('attendance.form', compact('attendance'));
    }

    // Handle Time In / Time Out submission
    public function submitAttendance(Request $request)
    {
        $user = Auth::user();
        $timezone = 'Asia/Manila';
        $today = Carbon::now($timezone)->format('Y-m-d');

        $attendance = Attendance::firstOrNew([
            'user_id' => $user->id,
            'date' => $today,
        ]);

        if ($attendance->status === 'approved') {
            return back()->with('error', 'Attendance already approved and cannot be edited.');
        }

        $action = $request->input('action');

        if ($action === 'time_in') {
            if ($attendance->time_in) {
                return back()->with('error', 'You already marked Time In today.');
            }
            $attendance->time_in = Carbon::now($timezone);
            $attendance->day_type = 'regular'; // Default day type
            $message = 'Time In marked successfully at ' . Carbon::now($timezone)->format('h:i A');
        } elseif ($action === 'time_out') {
            if (!$attendance->time_in) {
                return back()->with('error', 'Please mark Time In first.');
            }
            if ($attendance->time_out) {
                return back()->with('error', 'You already marked Time Out today.');
            }
            $attendance->time_out = Carbon::now($timezone);
            $message = 'Time Out marked successfully at ' . Carbon::now($timezone)->format('h:i A');
        } else {
            return back()->with('error', 'Invalid action.');
        }

        // Reset approval if any edit
        $attendance->status = 'pending';
        $attendance->save();

    return back()->with('success', $message);
}

    // Show edit form
public function edit($id)
{
    $attendance = Attendance::findOrFail($id);

    // Only allow if not approved
    if ($attendance->status === 'approved') {
        return redirect()->route('attendance.my')->with('error', 'This attendance is approved and cannot be edited.');
    }

    // Make sure this attendance belongs to current user
    if ($attendance->user_id !== auth()->id()) {
        abort(403);
    }

    return view('attendance.edit', compact('attendance'));
}

// Handle update submission
public function update(Request $request, $id)
{
    $attendance = Attendance::findOrFail($id);

    if ($attendance->status === 'approved') {
        return redirect()->route('attendance.my')->with('error', 'This attendance is approved and cannot be edited.');
    }

    if ($attendance->user_id !== auth()->id()) {
        abort(403);
    }

    // Validate inputs (e.g., time_in and time_out are optional, but if present must be valid times)
    $data = $request->validate([
        'time_in' => 'nullable|date_format:H:i',
        'time_out' => 'nullable|date_format:H:i|after_or_equal:time_in',
        'remarks' => 'nullable|string|max:255',
    ]);

    // Update times: parse to Carbon and store with attendance date
    if (!empty($data['time_in'])) {
        $attendance->time_in = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $data['time_in']);
    } else {
        $attendance->time_in = null;
    }

    if (!empty($data['time_out'])) {
        $attendance->time_out = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $data['time_out']);
    } else {
        $attendance->time_out = null;
    }

    $attendance->remarks = $data['remarks'] ?? null;

    // Reset status to pending on edit
    $attendance->status = 'pending';

    $attendance->save();

    return redirect()->route('attendance.my')->with('success', 'Attendance updated successfully.');
}
public function deleteConfirm($id)
{
    $user = auth()->user();
    $attendance = Attendance::where('id', $id)->where('user_id', $user->id)->firstOrFail();

    return view('attendance.delete', compact('attendance'));
}

public function destroy($id)
{
    $user = auth()->user();
    $attendance = Attendance::where('id', $id)->where('user_id', $user->id)->firstOrFail();

    if ($attendance->status === 'approved') {
        return back()->with('error', 'Approved attendance records cannot be deleted.');
    }

    $attendance->delete();

    return redirect()->route('attendance.my')->with('success', 'Attendance record deleted successfully.');
}
    public function generatePDF()
    {
        // Get attendances, for example for the logged in user
        $attendances = auth()->user()->attendances()->orderBy('date', 'desc')->get();

        // Share data with a view, e.g. resources/views/attendance/pdf.blade.php
        $pdf = PDF::loadView('attendance.pdf', compact('attendances'));

        // Download or stream the PDF
        return $pdf->download('attendance.pdf');
    }
    // Search attendance records
    public function my(Request $request)
{
    $user = auth()->user();
    
    // Start with base query
    $query = Attendance::where('user_id', $user->id);
    
    // Apply search filters
    if ($request->filled('search_created_by')) {
        $query->where('created_by', 'LIKE', '%' . $request->search_created_by . '%');
    }
    
    if ($request->filled('date_from')) {
        $query->whereDate('date', '>=', $request->date_from);
    }
    
    if ($request->filled('date_to')) {
        $query->whereDate('date', '<=', $request->date_to);
    }
    
    // Get filtered results
    $attendances = $query->orderBy('date', 'desc')->get();
    
    // Get today's attendance for the marking section
    $todayAttendance = Attendance::where('user_id', $user->id)
        ->whereDate('date', today())
        ->first();
    
    return view('attendance.my', compact('attendances', 'todayAttendance'));
}

public function generateShiftSchedule(Request $request)
{
    // Validate the input
    $request->validate([
        'time_in' => 'required|date_format:H:i',
        'time_out' => 'required|date_format:H:i|after:time_in',
        'date_from' => 'required|date',
        'date_to' => 'required|date|after_or_equal:date_from',
        'employee_id' => 'required|exists:users,id', // Ensure employee exists in users table
        'remarks' => 'nullable|string|max:255', // Validate remarks (optional)
    ]);

    // Get the inputs
    $timeIn = $request->input('time_in');
    $timeOut = $request->input('time_out');
    $employeeId = $request->input('employee_id');
    $remarks = $request->input('remarks', null); // Use null if no remarks are provided
    $dateFrom = Carbon::parse($request->input('date_from'));
    $dateTo = Carbon::parse($request->input('date_to'));

    // Find the employee from the database
    $employee = User::findOrFail($employeeId);

    // Determine the creator's name (employee or HR)
    $creatorName = (auth()->user()->role === 'HR') ? auth()->user()->name : $employee->name;

    // Start with the 'date_from'
    $date = $dateFrom->copy();

    // Loop through the date range and create attendance records
    while ($date <= $dateTo) {
        // Create the attendance record for each day in the range
        Attendance::create([
            'user_id' => $employee->id,  // Employee ID
            'date' => $date->format('Y-m-d'),  // Date of the shift
            'time_in' => $date->format('Y-m-d') . ' ' . $timeIn,  // Combine date with time_in
            'time_out' => $date->format('Y-m-d') . ' ' . $timeOut,  // Combine date with time_out
            'status' => 'pending',  // Default status
            'remarks' => $remarks,  // Store the remarks (optional)
            'created_by' => auth()->user()->id,  // The user creating the attendance (HR/Admin)
        ]);

        // Move to the next day
        $date->addDay();
    }

    // Redirect back with success message
    return redirect()->route('attendance.my')->with('success', 'Shift schedule generated successfully!');
}

public function showGenerateShiftScheduleForm()
{
    return view('attendance.generateShiftSchedule');
}

}