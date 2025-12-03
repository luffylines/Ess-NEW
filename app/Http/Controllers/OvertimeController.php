<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OvertimeController extends Controller
{
    // Show overtime requests
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'employee') {
            $overtimeRequests = OvertimeRequest::where('user_id', $user->id)
                ->orderBy('overtime_date', 'desc')
                ->paginate(10);
        } else {
            $overtimeRequests = OvertimeRequest::orderBy('overtime_date', 'desc')->paginate(10);
        }

        return view('overtime.index', compact('overtimeRequests'));
    }

    // Show form to create a new overtime request
    public function create()
    {
        return view('overtime.create');
    }

    // Store a new overtime request
    public function store(Request $request)
    {
        $request->validate([
            'overtime_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:500',
            'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('H:i', $request->end_time);
        
        // Handle overnight shifts (when end time is on next day)
        if ($endTime->lessThanOrEqualTo($startTime)) {
            $endTime->addDay();
        }

        $totalHours = $endTime->diffInHours($startTime, false);
        
        // Use more precise calculation for minutes
        $totalMinutes = $startTime->diffInMinutes($endTime, false);
        $totalHours = round($totalMinutes / 60, 2);
        
                $documentPath = null;
                if ($request->hasFile('supporting_document')) {
                    $documentPath = $request->file('supporting_document')->store('overtime_documents', 'public');
                }

        OvertimeRequest::create([
            'user_id' => Auth::id(),
            'overtime_date' => $request->overtime_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_hours' => $totalHours,
            'reason' => $request->reason,
            'supporting_document' => $documentPath,
            'status' => 'pending',
        ]);

        return redirect()->route('overtime.index')
            ->with('success', 'Overtime request submitted successfully! Your manager will review it shortly.');
    }

    // Show a specific overtime request
    public function show(OvertimeRequest $overtime)
    {
        $user = Auth::user();

        // Allow viewing if:
        // 1. User is admin/hr/manager (can view any), OR
        // 2. User owns the record
        if (in_array($user->role, ['admin', 'hr', 'employee', 'manager'])) {
            // Admin/HR/Manager can view any record
        } elseif ($user->role === 'employee' && $overtime->user_id !== $user->id) {
            abort(403, 'You can only view your own overtime requests.');
        }

        // Load the related user and approver data
        $overtime->load(['user', 'approvedBy']);

        // Pass as overtimeRequest for consistency with the view
        $overtimeRequest = $overtime;
        return view('overtime.show', compact('overtimeRequest'));
    }

    // Show form to edit an overtime request
    public function edit(OvertimeRequest $overtime)
    {
        $user = Auth::user();

        // Allow editing if:
        // 1. User is admin/hr/manager (can edit any), OR
        // 2. User owns the record AND it's pending
        if (in_array($user->role, ['admin', 'hr', 'employee', 'manager'])) {
            // Admin/HR/Manager can edit any record
        } elseif ($user->role === 'employee' && $overtime->user_id !== $user->id) {
            abort(403, 'You can only edit your own overtime requests.');
        } elseif ($user->role === 'employee' && $overtime->status !== 'pending') {
            abort(403, 'You can only edit pending overtime requests.');
        }

        // Load the related user data
        $overtime->load('user');

        // Pass as overtimeRequest for consistency with the view
        $overtimeRequest = $overtime;
        return view('overtime.edit', compact('overtimeRequest'));
    }

    // Update an overtime request
    public function update(Request $request, OvertimeRequest $overtime)
    {
        $user = Auth::user();

        // Allow updating if:
        // 1. User is admin/hr/manager (can update any), OR
        // 2. User owns the record AND it's pending
        if (in_array($user->role, ['admin', 'hr', 'employee', 'manager'])) {
            // Admin/HR/Manager can update any record
        } elseif ($user->role === 'employee' && $overtime->user_id !== $user->id) {
            abort(403, 'You can only update your own overtime requests.');
        } elseif ($user->role === 'employee' && $overtime->status !== 'pending') {
            abort(403, 'You can only update pending overtime requests.');
        }

        $request->validate([
            'overtime_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:500',
            'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('H:i', $request->end_time);
        
        // Handle overnight shifts (when end time is on next day)
        if ($endTime->lessThanOrEqualTo($startTime)) {
            $endTime->addDay();
        }

        $totalHours = $endTime->diffInHours($startTime, false);
        
        // Use more precise calculation for minutes
        $totalMinutes = $startTime->diffInMinutes($endTime, false);
        $totalHours = round($totalMinutes / 60, 2);

        $documentPath = $overtime->supporting_document;
        if ($request->hasFile('supporting_document')) {
            $documentPath = $request->file('supporting_document')->store('overtime_documents', 'public');
        }

        $overtime->update([
            'overtime_date' => $request->overtime_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_hours' => $totalHours,
            'reason' => $request->reason,
            'supporting_document' => $documentPath,
        ]);

        return redirect()->route('overtime.index')
            ->with('success', 'Overtime request updated successfully!');
    }

    // Delete an overtime request
    public function destroy(OvertimeRequest $overtime)
    {
        $user = Auth::user();

        // Allow deletion if:
        // 1. User is admin/hr/manager (can delete any), OR
        // 2. User owns the record AND it's pending
        if (in_array($user->role, ['admin', 'hr', 'employee', 'manager'])) {
            // Admin/HR/Manager can delete any record
        } elseif ($user->role === 'employee' && $overtime->user_id !== $user->id) {
            abort(403, 'You can only delete your own overtime requests.');
        } elseif ($user->role === 'employee' && $overtime->status !== 'pending') {
            abort(403, 'You can only delete pending overtime requests.');
        }

        $overtime->delete();

        return redirect()->route('overtime.index')
            ->with('success', 'Overtime request deleted successfully!');
    }

    // Fix negative overtime hours for existing records
    public function fixNegativeHours()
    {
        // Check if user is admin/hr
        if (!in_array(Auth::user()->role, ['admin', 'hr', 'manager'])) {
            abort(403, 'Unauthorized access.');
        }

        $negativeOvertimes = OvertimeRequest::where('total_hours', '<', 0)->get();
        $fixed = 0;

        foreach ($negativeOvertimes as $overtime) {
            $startTime = Carbon::createFromFormat('H:i', $overtime->start_time);
            $endTime = Carbon::createFromFormat('H:i', $overtime->end_time);
            
            // Handle overnight shifts
            if ($endTime->lessThanOrEqualTo($startTime)) {
                $endTime->addDay();
            }
            
            // Recalculate with proper method
            $totalMinutes = $endTime->diffInMinutes($startTime, false);
            $totalHours = round($totalMinutes / 60, 2);
            
            if ($totalHours > 0) {
                $overtime->update(['total_hours' => $totalHours]);
                $fixed++;
            }
        }

        return redirect()->back()->with('success', "Fixed {$fixed} overtime records with negative hours.");
    }
}
