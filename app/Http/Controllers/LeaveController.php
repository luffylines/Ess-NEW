<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    // Show employee's leave requests
    public function index()
    {
        $user = Auth::user();
        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('leave.index', compact('leaveRequests'));
    }

    // Show form to create new leave request
    public function create()
    {
        $leaveTypes = LeaveRequest::getLeaveTypes();
        return view('leave.create', compact('leaveTypes'));
    }

    // Store new leave request
    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        // Calculate total days
        $totalDays = LeaveRequest::calculateTotalDays($request->start_date, $request->end_date);

        // Handle file upload
        $documentPath = null;
        if ($request->hasFile('supporting_document')) {
            $documentPath = $request->file('supporting_document')->store('leave_documents', 'public');
        }

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'total_days' => $totalDays,
            'supporting_document' => $documentPath,
        ]);

        return redirect()->route('leave.index')
            ->with('success', 'Leave request submitted successfully! Your manager will review it shortly.');
    }

    // Show specific leave request
    public function show(LeaveRequest $leave)
    {
        // Make sure user can only view their own requests
        if ($leave->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $leaveRequest = $leave; // Pass as leaveRequest for view consistency
        return view('leave.show', compact('leaveRequest'));
    }

    // Show form to edit leave request (only if pending)
    public function edit(LeaveRequest $leave)
    {
        // Make sure user can only edit their own requests
        if ($leave->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $leaveTypes = LeaveRequest::getLeaveTypes();
        $leaveRequest = $leave; // Pass as leaveRequest for view consistency
        return view('leave.edit', compact('leaveRequest', 'leaveTypes'));
    }

    // Update leave request
    public function update(Request $request, LeaveRequest $leave)
    {
        // Make sure user can only update their own pending requests
        if ($leave->user_id !== Auth::id() || $leave->status !== 'pending') {
            abort(403, 'Cannot update this leave request.');
        }

        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        // Calculate total days
        $totalDays = LeaveRequest::calculateTotalDays($request->start_date, $request->end_date);

        // Handle file upload
        $documentPath = $leave->supporting_document;
        if ($request->hasFile('supporting_document')) {
            $documentPath = $request->file('supporting_document')->store('leave_documents', 'public');
        }

        $leave->update([
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'total_days' => $totalDays,
            'supporting_document' => $documentPath,
        ]);

        return redirect()->route('leave.index')
            ->with('success', 'Leave request updated successfully!');
    }

    // Delete leave request (only if pending)
    public function destroy(LeaveRequest $leave)
    {
        // Make sure user can only delete their own pending requests
        if ($leave->user_id !== Auth::id() || $leave->status !== 'pending') {
            abort(403, 'Cannot delete this leave request.');
        }

        $leave->delete();

        return redirect()->route('leave.index')
            ->with('success', 'Leave request deleted successfully!');
    }
}
