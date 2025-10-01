<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Attendance;

class HrAttendanceController extends Controller
{
     // Show all pending attendance for approval
    public function pendingAttendance()
    {
        $attendances = Attendance::where('status', 'pending')
            ->with('user') // eager load user
            ->orderBy('date', 'desc')
            ->get();

        return view('hr.attendance.pending', compact('attendances'));
    }

    // Approve or reject attendance
    public function approveAttendance(Request $request)
{
    $request->validate([
        'attendance_id' => 'required|exists:attendances,id',
        'action' => 'required|in:approved,rejected',
        'remarks' => 'nullable|string|max:255',
    ]);

    $attendance = Attendance::findOrFail($request->attendance_id);

    $attendance->status = $request->input('action'); // 'approved' or 'rejected'
    $attendance->remarks = $request->input('remarks');
    $attendance->save();

    return back()->with('success', 'Attendance has been updated.');
}

    public function monitorAttendance(Request $request)
{
    // Optionally filter by date, user, etc.
    $query = Attendance::with('user')->orderBy('date', 'desc');

    if ($request->filled('date_from')) {
        $query->where('date', '>=', $request->input('date_from'));
    }
    if ($request->filled('date_to')) {
        $query->where('date', '<=', $request->input('date_to'));
    }
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->input('user_id'));
    }

    $attendances = $query->paginate(20);

    // You might also pass list of users for filter dropdown
    $users = \App\Models\User::orderBy('name')->get();

    return view('hr.monitor', compact('attendances', 'users'));
}

    ////////////////////////////////////////////////////
    public function monthlyReport(Request $request)
{
    // Default to current month
    $year = $request->input('year', now()->year);
    $month = $request->input('month', now()->month);

    $attendances = Attendance::with('user')
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->get();

    // Group by user
    $report = $attendances->groupBy('user_id')->map(function ($rows, $userId) {
        $presentCount = $rows->filter(fn($a) => $a->time_in && $a->time_out)->count();
        $absentCount = $rows->filter(fn($a) => !$a->time_in && !$a->time_out)->count();
        $inOnlyCount = $rows->filter(fn($a) => $a->time_in && !$a->time_out)->count();

        return [
            'user_id' => $userId,
            'name' => $rows->first()->user->name,
            'present' => $presentCount,
            'absent' => $absentCount,
            'in_only' => $inOnlyCount,
            'total_days' => $rows->count(),
        ];
    });

    return view('hr.reports', compact('report', 'year', 'month'));
}

public function exportMonthlyReport(Request $request)
{
    $year = $request->input('year', now()->year);
    $month = $request->input('month', now()->month);

    $attendances = Attendance::with('user')
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->get();

    $report = $attendances->groupBy('user_id')->map(function ($rows, $userId) {
        $presentCount = $rows->filter(fn($a) => $a->time_in && $a->time_out)->count();
        $absentCount = $rows->filter(fn($a) => !$a->time_in && !$a->time_out)->count();
        $inOnlyCount = $rows->filter(fn($a) => $a->time_in && !$a->time_out)->count();

        return [
            'user_id' => $userId,
            'name' => $rows->first()->user->name,
            'present' => $presentCount,
            'absent' => $absentCount,
            'in_only' => $inOnlyCount,
            'total_days' => $rows->count(),
        ];
    });

    // Create CSV
    $filename = "attendance_report_{$year}_{$month}.csv";
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function() use ($report) {
        $file = fopen('php://output', 'w');
        // Header row
        fputcsv($file, ['User ID', 'Name', 'Present', 'Absent', 'Time In Only', 'Total Days']);
        foreach ($report as $row) {
            fputcsv($file, [
                $row['user_id'],
                $row['name'],
                $row['present'],
                $row['absent'],
                $row['in_only'],
                $row['total_days'],
            ]);
        }
        fclose($file);
    };

    return Response::stream($callback, 200, $headers);
    }
}
