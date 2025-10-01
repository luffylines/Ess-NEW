<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OvertimeController extends Controller
{
    // Show employee's overtime requests
    public function index()
    {
        $user = Auth::user();
        $overtimeRequests = OvertimeRequest::where('user_id', $user->id)
            ->orderBy('overtime_date', 'desc')
            ->paginate(10);

        return view('overtime.index', compact('overtimeRequests'));
    }

    // Show form to create new overtime request
    public function create()
    {
        return view('overtime.create');
    }

    // Store new overtime request
    public function store(Request $request)
    {
        $request->validate([
            'overtime_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'required|string|max:500',
            'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        // Calculate total hours
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('H:i', $request->end_time);
        $totalHours = $endTime->diffInHours($startTime, false);

        // Handle file upload
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
        ]);

        return redirect()->route('overtime.index')
            ->with('success', 'Overtime request submitted successfully! Your manager will review it shortly.');
    }

    // Show specific overtime request
    public function show(OvertimeRequest $overtimeRequest)
    {
        // Make sure user can only view their own requests
        if ($overtimeRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('overtime.show', compact('overtimeRequest'));
    }

    // Show form to edit overtime request (only if pending)
    public function edit(OvertimeRequest $overtimeRequest)
    {
        // Make sure user can only edit their own pending requests
        if ($overtimeRequest->user_id !== Auth::id() || $overtimeRequest->status !== 'pending') {
            abort(403, 'Cannot edit this overtime request.');
        }

        return view('overtime.edit', compact('overtimeRequest'));
    }

    // Update overtime request
    public function update(Request $request, OvertimeRequest $overtimeRequest)
    {
        // Make sure user can only update their own pending requests
        if ($overtimeRequest->user_id !== Auth::id() || $overtimeRequest->status !== 'pending') {
            abort(403, 'Cannot update this overtime request.');
        }

        $request->validate([
            'overtime_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'required|string|max:500',
            'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        // Calculate total hours
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('H:i', $request->end_time);
        $totalHours = $endTime->diffInHours($startTime, false);

        // Handle file upload
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

    // Delete overtime request (only if pending)
    public function destroy(OvertimeRequest $overtimeRequest)
    {
        // Make sure user can only delete their own pending requests
        if ($overtimeRequest->user_id !== Auth::id() || $overtimeRequest->status !== 'pending') {
            abort(403, 'Cannot delete this overtime request.');
        }

        $overtimeRequest->delete();

        return redirect()->route('overtime.index')
            ->with('success', 'Overtime request deleted successfully!');
    }
}
