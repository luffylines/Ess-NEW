<?php

namespace App\Http\Controllers;

use App\Models\WorkSchedule;
use App\Models\User;
use App\Models\Store;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class WorkScheduleController extends Controller
{
    // Show all schedules for manager/HR
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Only managers, HR, and admin can view all schedules
        if (!in_array($user->role, ['manager', 'hr', 'admin'])) {
            abort(403, 'Only managers and HR can manage work schedules.');
        }

        $query = WorkSchedule::with(['employee', 'assignedBy', 'store']);

        // Filter by employee if specified
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('schedule_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('schedule_date', '<=', $request->date_to);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $schedules = $query->orderBy('schedule_date', 'desc')->paginate(15);

        // Get employees for filter dropdown
        $employees = User::where('role', 'employee')->orderBy('name')->get();
        
        // Get stores for bulk creation modal
        $stores = Store::where('active', true)->orderBy('name')->get();

        return view('schedules.index', compact('schedules', 'employees', 'stores'));
    }

    // Show create schedule form
    public function create(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['manager', 'hr', 'admin'])) {
            abort(403, 'Only managers and HR can create work schedules.');
        }

        $employees = User::where('role', 'employee')->orderBy('name')->get();
        $stores = Store::where('active', true)->orderBy('name')->get();
        $selectedEmployeeId = $request->get('employee_id');

        return view('schedules.create', compact('employees', 'stores', 'selectedEmployeeId'));
    }

    // Store new schedule
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['manager', 'hr', 'admin'])) {
            abort(403, 'Only managers and HR can create work schedules.');
        }

        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'schedule_date' => 'required|date|after_or_equal:today',
            'shift_start' => 'required|date_format:H:i',
            'shift_end' => 'required|date_format:H:i',
            'shift_type' => 'required|in:regular,overtime,holiday',
            'store_id' => 'nullable|exists:stores,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if employee already has a schedule for this date
        $existingSchedule = WorkSchedule::where('employee_id', $request->employee_id)
            ->whereDate('schedule_date', $request->schedule_date)
            ->first();

        if ($existingSchedule) {
            return back()->withErrors(['schedule_date' => 'Employee already has a schedule for this date.'])->withInput();
        }

        WorkSchedule::create([
            'employee_id' => $request->employee_id,
            'assigned_by' => $user->id,
            'schedule_date' => $request->schedule_date,
            'shift_start' => $request->shift_start,
            'shift_end' => $request->shift_end,
            'shift_type' => $request->shift_type,
            'store_id' => $request->store_id,
            'notes' => $request->notes,
            'status' => 'assigned',
        ]);

        // Send SMS notification to employee
        $employee = User::find($request->employee_id);
        $schedule = WorkSchedule::where('employee_id', $request->employee_id)
            ->whereDate('schedule_date', $request->schedule_date)
            ->with('store')
            ->first();
            
        if ($employee && $employee->phone && $schedule) {
            try {
                $smsService = new SmsService();
                
                // Prepare schedule data for SMS
                $scheduleData = [
                    'date' => $schedule->schedule_date,
                    'start_time' => $schedule->shift_start,
                    'end_time' => $schedule->shift_end,
                    'store_name' => $schedule->store->name ?? 'Office'
                ];
                
                $result = $smsService->sendScheduleNotification(
                    $employee->phone, 
                    $employee->name, 
                    $scheduleData
                );
                
                if ($result['success']) {
                    $method = $result['method'] ?? 'unknown';
                    if ($method === 'android_usb') {
                        $successMessage = "Work schedule assigned successfully! SMS sent automatically via Android device.";
                    } else {
                        $successMessage = "Work schedule assigned successfully! SMS sent via {$method}.";
                    }
                } else {
                    $successMessage = "Work schedule assigned successfully! SMS failed: " . ($result['error'] ?? 'Unknown error');
                }
            } catch (Exception $e) {
                $successMessage = "Work schedule assigned successfully! SMS error: " . $e->getMessage();
            }
        } else {
            $successMessage = "Work schedule assigned successfully! (SMS not sent - missing phone number or schedule data)";
        }

        return redirect()->route('schedules.index')
            ->with('success', $successMessage);
    }

    // Show schedule details
    public function show(WorkSchedule $schedule)
    {
        $user = Auth::user();
        
        // Allow viewing if user is manager/HR/admin or owns the schedule
        if (!in_array($user->role, ['manager', 'hr', 'admin']) && $schedule->employee_id !== $user->id) {
            abort(403, 'You can only view your own schedules.');
        }

        $schedule->load(['employee', 'assignedBy', 'store']);

        return view('schedules.show', compact('schedule'));
    }

    // Show edit form
    public function edit(WorkSchedule $schedule)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['manager', 'hr', 'admin'])) {
            abort(403, 'Only managers and HR can edit work schedules.');
        }

        // Don't allow editing past schedules
        if ($schedule->isPast()) {
            return back()->with('error', 'Cannot edit past schedules.');
        }

        $employees = User::where('role', 'employee')->orderBy('name')->get();
        $stores = Store::where('active', true)->orderBy('name')->get();

        return view('schedules.edit', compact('schedule', 'employees', 'stores'));
    }

    // Update schedule
    public function update(Request $request, WorkSchedule $schedule)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['manager', 'hr', 'admin'])) {
            abort(403, 'Only managers and HR can update work schedules.');
        }

        // Don't allow editing past schedules
        if ($schedule->isPast()) {
            return back()->with('error', 'Cannot edit past schedules.');
        }

        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'schedule_date' => 'required|date',
            'shift_start' => 'required|date_format:H:i',
            'shift_end' => 'required|date_format:H:i',
            'shift_type' => 'required|in:regular,overtime,holiday',
            'store_id' => 'nullable|exists:stores,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check for conflicts if employee or date changed
        if ($request->employee_id != $schedule->employee_id || $request->schedule_date != $schedule->schedule_date->format('Y-m-d')) {
            $existingSchedule = WorkSchedule::where('employee_id', $request->employee_id)
                ->whereDate('schedule_date', $request->schedule_date)
                ->where('id', '!=', $schedule->id)
                ->first();

            if ($existingSchedule) {
                return back()->withErrors(['schedule_date' => 'Employee already has a schedule for this date.'])->withInput();
            }
        }

        $schedule->update([
            'employee_id' => $request->employee_id,
            'schedule_date' => $request->schedule_date,
            'shift_start' => $request->shift_start,
            'shift_end' => $request->shift_end,
            'shift_type' => $request->shift_type,
            'store_id' => $request->store_id,
            'notes' => $request->notes,
        ]);

        return redirect()->route('schedules.index')
            ->with('success', 'Work schedule updated successfully!');
    }

    // Delete schedule
    public function destroy(WorkSchedule $schedule)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['manager', 'hr', 'admin'])) {
            abort(403, 'Only managers and HR can delete work schedules.');
        }

        $employeeName = $schedule->employee->name;
        $scheduleDate = $schedule->schedule_date->format('M d, Y');
        
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', "Schedule for {$employeeName} on {$scheduleDate} has been deleted.");
    }

    // Employee view - show my schedules
    public function mySchedules(Request $request)
    {
        $user = Auth::user();
        
        $query = WorkSchedule::where('employee_id', $user->id)
            ->with(['assignedBy', 'store']);

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('schedule_date', '>=', $request->date_from);
        } else {
            // Default to show upcoming and current week schedules
            $query->whereDate('schedule_date', '>=', Carbon::now()->startOfWeek());
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('schedule_date', '<=', $request->date_to);
        } else {
            // Default to show next 30 days
            $query->whereDate('schedule_date', '<=', Carbon::now()->addDays(30));
        }

        $schedules = $query->orderBy('schedule_date', 'asc')->get();

        // Get today's schedule
        $todaySchedule = WorkSchedule::where('employee_id', $user->id)
            ->whereDate('schedule_date', Carbon::today())
            ->with('store')
            ->first();

        return view('schedules.my-schedules', compact('schedules', 'todaySchedule'));
    }

    // Employee acknowledge schedule
    public function acknowledge(WorkSchedule $schedule)
    {
        $user = Auth::user();
        
        if ($schedule->employee_id !== $user->id) {
            abort(403, 'You can only acknowledge your own schedules.');
        }

        if ($schedule->status !== 'assigned') {
            return back()->with('error', 'Schedule has already been acknowledged.');
        }

        $schedule->acknowledge();

        return back()->with('success', 'Schedule acknowledged successfully!');
    }

    // Bulk create schedules for multiple employees/dates
    public function bulkCreate(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['manager', 'hr', 'admin'])) {
            abort(403, 'Only managers and HR can create bulk schedules.');
        }

        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:users,id',
            'date_from' => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after_or_equal:date_from',
            'shift_start' => 'required|date_format:H:i',
            'shift_end' => 'required|date_format:H:i',
            'shift_type' => 'required|in:regular,overtime,holiday',
            'store_id' => 'nullable|exists:stores,id',
            'notes' => 'nullable|string|max:1000',
            'skip_weekends' => 'boolean',
        ]);

        $created = 0;
        $skipped = 0;
        $createdSchedules = []; // Track created schedules for SMS

        $startDate = Carbon::parse($request->date_from);
        $endDate = Carbon::parse($request->date_to);

        foreach ($request->employee_ids as $employeeId) {
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                // Skip weekends if option is checked
                if ($request->skip_weekends && $currentDate->isWeekend()) {
                    $currentDate->addDay();
                    continue;
                }

                // Check if employee already has a schedule for this date
                $existingSchedule = WorkSchedule::where('employee_id', $employeeId)
                    ->whereDate('schedule_date', $currentDate)
                    ->first();

                if (!$existingSchedule) {
                    $schedule = WorkSchedule::create([
                        'employee_id' => $employeeId,
                        'assigned_by' => $user->id,
                        'schedule_date' => $currentDate->format('Y-m-d'),
                        'shift_start' => $request->shift_start,
                        'shift_end' => $request->shift_end,
                        'shift_type' => $request->shift_type,
                        'store_id' => $request->store_id,
                        'notes' => $request->notes,
                        'status' => 'assigned',
                    ]);
                    $created++;
                    
                    // Add to SMS queue
                    $createdSchedules[] = $schedule;
                } else {
                    $skipped++;
                }

                $currentDate->addDay();
            }
        }

        // Send SMS notifications for all created schedules
        if (!empty($createdSchedules)) {
            try {
                $smsService = new SmsService();
                $schedules = WorkSchedule::with(['employee', 'store'])
                    ->whereIn('id', collect($createdSchedules)->pluck('id'))
                    ->get();

                // Prepare schedule data for bulk SMS
                $smsScheduleData = [];
                foreach ($schedules as $schedule) {
                    if ($schedule->employee && $schedule->employee->phone) {
                        $smsScheduleData[] = [
                            'employee_phone' => $schedule->employee->phone,
                            'name' => $schedule->employee->name,
                            'date' => $schedule->schedule_date,
                            'start_time' => $schedule->shift_start,
                            'end_time' => $schedule->shift_end,
                            'store_name' => $schedule->store->name ?? 'Office'
                        ];
                    }
                }

                if (!empty($smsScheduleData)) {
                    $results = $smsService->sendBulkScheduleNotifications($smsScheduleData);
                    $successCount = collect($results)->where('success', true)->count();
                    $smsMessage = $successCount > 0 ? " SMS notifications sent to {$successCount} employees." : " SMS notifications failed.";
                } else {
                    $smsMessage = " (No SMS sent - employees missing phone numbers)";
                }
            } catch (Exception $e) {
                $smsMessage = " (SMS error: " . $e->getMessage() . ")";
            }
        } else {
            $smsMessage = "";
        }

        return back()->with('success', "Created {$created} schedules. Skipped {$skipped} existing schedules.{$smsMessage}");
    }
}