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
            $overtimeRequests = OvertimeRequest::orderBy('overtime_date', 'desc')->paginate(10);
        } else {
            $overtimeRequests = OvertimeRequest::where('user_id', $user->id)
                ->orderBy('overtime_date', 'desc')
                ->paginate(10);
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
    public function show(OvertimeRequest $overtimeRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'employee' && $overtimeRequest->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('overtime.show', compact('overtimeRequest'));
    }

    // Show form to edit an overtime request
    public function edit(OvertimeRequest $overtimeRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'employee' && ($overtimeRequest->user_id !== $user->id || $overtimeRequest->status !== 'pending')) {
            abort(403, 'Unauthorized access.');
        }

        return view('overtime.edit', compact('overtimeRequest'));
    }

    // Update an overtime request
    public function update(Request $request, OvertimeRequest $overtimeRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'employee' && ($overtimeRequest->user_id !== $user->id || $overtimeRequest->status !== 'pending')) {
            abort(403, 'Unauthorized access.');
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

        $documentPath = $overtimeRequest->supporting_document;
        if ($request->hasFile('supporting_document')) {
            $documentPath = $request->file('supporting_document')->store('overtime_documents', 'public');
        }

        $overtimeRequest->update([
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
    public function destroy(OvertimeRequest $overtimeRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'employee' && ($overtimeRequest->user_id !== $user->id || $overtimeRequest->status !== 'pending')) {
            abort(403, 'Unauthorized access.');
        }

        $overtimeRequest->delete();

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
