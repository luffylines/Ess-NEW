<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\OvertimeRequest;
use App\Models\Payslip;
use App\Models\Holiday;
use App\Models\WorkSchedule;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $message = strtolower($request->input('message'));
        $user = Auth::user();
        
        // Get user context data
        $context = $this->getUserContext($user);
        
        // Generate Rule-Based Response (Instant)
        $response = $this->generateResponse($message, $context, $user);
        
        return response()->json([
            'response' => $response
        ]);
    }
    
    private function getUserContext($user)
    {
        // 1. Leave data
        $leaveBalance = 15;
        $leaveTaken = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_days');
        
        // 2. Attendance data
        $attendanceThisMonth = Attendance::where('user_id', $user->id)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->count();
        
        // 3. Overtime data
        $overtimeHours = OvertimeRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_hours');
            
        // 4. Latest Payslip
        $latestPayslip = Payslip::where('user_id', $user->id)
            ->latest('generated_date')
            ->first();
            
        // 5. Next Holiday
        $nextHoliday = Holiday::where('date', '>=', Carbon::today())
            ->where('is_active', true)
            ->orderBy('date')
            ->first();
            
        // 6. Next Shift (Tomorrow)
        $nextShift = WorkSchedule::where('employee_id', $user->id)
            ->where('schedule_date', Carbon::tomorrow())
            ->with('store')
            ->first();
            
        // 7. Today's Shift
        $todayShift = WorkSchedule::where('employee_id', $user->id)
            ->where('schedule_date', Carbon::today())
            ->with('store')
            ->first();
        
        return [
            'leave_remaining' => $leaveBalance - $leaveTaken,
            'leave_balance' => $leaveBalance,
            'leave_taken' => $leaveTaken,
            'attendance_this_month' => $attendanceThisMonth,
            'overtime_hours_this_month' => $overtimeHours,
            'latest_payslip' => $latestPayslip,
            'next_holiday' => $nextHoliday,
            'next_shift' => $nextShift,
            'today_shift' => $todayShift,
        ];
    }
    
    private function generateResponse($message, $context, $user)
    {
        // 1. Greetings
        if (str_contains($message, 'hello') || str_contains($message, 'hi ') || $message == 'hi' || str_contains($message, 'hey')) {
             return "Hello <strong>{$user->name}</strong>! 👋<br><br>" .
                   "I can help you with your employee information:<br>" .
                   "• 💰 <strong>Payslip</strong> - Check your salary<br>" .
                   "• 📅 <strong>Schedule</strong> - View your shifts<br>" .
                   "• 🏖️ <strong>Leave Balance</strong> - See available days<br>" .
                   "• 🎉 <strong>Holidays</strong> - Upcoming events<br>" .
                   "• ⏰ <strong>Attendance</strong> - Your records";
        }

        // 2. Employee Profile Info
        if (str_contains($message, 'who am i') || str_contains($message, 'my name') || str_contains($message, 'my info') || str_contains($message, 'my profile')) {
            return "<strong>Your Profile Information:</strong><br><br>" .
                   "📛 <strong>Name:</strong> {$user->name}<br>" .
                   "📧 <strong>Email:</strong> {$user->email}<br>" .
                   "📱 <strong>Phone:</strong> " . ($user->phone ?? 'Not set') . "<br>" .
                   "💼 <strong>Role:</strong> " . ucfirst($user->role) . "<br>" .
                   "🆔 <strong>Employee ID:</strong> " . ($user->employee_id ?? 'N/A');
        }

        // 3. Age Query
        if (str_contains($message, 'how old') || str_contains($message, 'my age') || str_contains($message, 'age')) {
            if ($user->date_of_birth) {
                $age = Carbon::parse($user->date_of_birth)->age;
                return "You are <strong>{$age} years old</strong>. 🎂<br>" .
                       "Your date of birth: " . Carbon::parse($user->date_of_birth)->format('F j, Y');
            } else {
                return "Your date of birth is not set in the system. Please contact HR to update your profile. 📅";
            }
        }

        // 4. Contact Info
        if (str_contains($message, 'contact') || str_contains($message, 'phone') || str_contains($message, 'email')) {
            return "<strong>Your Contact Information:</strong><br><br>" .
                   "📧 <strong>Email:</strong> {$user->email}<br>" .
                   "📱 <strong>Phone:</strong> " . ($user->phone ?? 'Not set') . "<br><br>" .
                   "Need to update? Contact HR.";
        }

        // 5. Gratitude
        if (str_contains($message, 'thank')) {
             return "You're welcome, {$user->name}! 😊 Let me know if you need anything else about your employee information.";
        }
        
        // 6. Farewells
        if (str_contains($message, 'bye') || str_contains($message, 'goodbye')) {
             return "Goodbye, {$user->name}! Have a great day! 👋";
        }

        // 7. PAYSLIP & SALARY
        if (str_contains($message, 'payslip') || str_contains($message, 'salary') || str_contains($message, 'pay') || str_contains($message, 'wage')) {
            if ($context['latest_payslip']) {
                $date = Carbon::parse($context['latest_payslip']->generated_date)->format('M d, Y');
                $netPay = number_format($context['latest_payslip']->net_pay, 2);
                $grossPay = number_format($context['latest_payslip']->gross_pay, 2);
                return "<strong>Your Latest Payslip</strong> 💰<br><br>" .
                       "📅 <strong>Date:</strong> {$date}<br>" .
                       "💵 <strong>Gross Pay:</strong> ₱{$grossPay}<br>" .
                       "💰 <strong>Net Pay:</strong> ₱{$netPay}<br><br>" .
                       "Want to download? Visit the Payslips page.";
            } else {
                return "I couldn't find any payslips for you yet. Please check with HR or wait for your first payslip. 📂";
            }
        }

        // 8. SCHEDULE & SHIFT
        if (str_contains($message, 'schedule') || str_contains($message, 'shift') || str_contains($message, 'working hours')) {
            $response = "";
            
            // Today's Shift
            if ($context['today_shift']) {
                $start = Carbon::parse($context['today_shift']->shift_start)->format('g:i A');
                $end = Carbon::parse($context['today_shift']->shift_end)->format('g:i A');
                $location = $context['today_shift']->store ? $context['today_shift']->store->name : 'Office';
                $response .= "<strong>📅 Today's Shift:</strong><br>" .
                            "⏰ {$start} - {$end}<br>" .
                            "📍 {$location}<br><br>";
            } else {
                $response .= "You have no shift scheduled for today. 🏖️<br><br>";
            }
            
            // Tomorrow's Shift
            if ($context['next_shift']) {
                $start = Carbon::parse($context['next_shift']->shift_start)->format('g:i A');
                $end = Carbon::parse($context['next_shift']->shift_end)->format('g:i A');
                $location = $context['next_shift']->store ? $context['next_shift']->store->name : 'Office';
                $response .= "<strong>📅 Tomorrow's Shift:</strong><br>" .
                            "⏰ {$start} - {$end}<br>" .
                            "📍 {$location}";
            } else {
                $response .= "You have no shift scheduled for tomorrow. ✨";
            }
            
            return $response;
        }

        // 9. Tomorrow specifically
        if (str_contains($message, 'tomorrow')) {
            if ($context['next_shift']) {
                $start = Carbon::parse($context['next_shift']->shift_start)->format('g:i A');
                $end = Carbon::parse($context['next_shift']->shift_end)->format('g:i A');
                $location = $context['next_shift']->store ? $context['next_shift']->store->name : 'Office';
                return "<strong>📅 Tomorrow's Shift:</strong><br>" .
                       "⏰ {$start} - {$end}<br>" .
                       "📍 {$location}";
            } else {
                return "You have no shift scheduled for tomorrow. 🏖️";
            }
        }

        // 10. HOLIDAYS
        if (str_contains($message, 'holiday') || str_contains($message, 'event')) {
            if ($context['next_holiday']) {
                $date = Carbon::parse($context['next_holiday']->date)->format('F j (l)');
                $daysUntil = Carbon::today()->diffInDays(Carbon::parse($context['next_holiday']->date));
                return "<strong>🎉 Next Holiday:</strong><br><br>" .
                       "📅 <strong>{$context['next_holiday']->name}</strong><br>" .
                       "📆 Date: {$date}<br>" .
                       "📋 Type: {$context['next_holiday']->type}<br>" .
                       "⏳ In {$daysUntil} days";
            } else {
                return "There are no upcoming holidays scheduled. Keep working! 💪";
            }
        }

        // 11. Leave Balance
        if (str_contains($message, 'leave') && (str_contains($message, 'balance') || str_contains($message, 'left') || str_contains($message, 'remaining'))) {
            return "<strong>🏖️ Your Leave Balance:</strong><br><br>" .
                   "✅ <strong>Remaining:</strong> {$context['leave_remaining']} days<br>" .
                   "📊 <strong>Annual Allowance:</strong> {$context['leave_balance']} days<br>" .
                   "📉 <strong>Days Used:</strong> {$context['leave_taken']} days<br><br>" .
                   "Plan your time off wisely!";
        }
        
        // 12. Attendance
        if (str_contains($message, 'attendance') || str_contains($message, 'present')) {
            return "<strong>📅 Your Attendance This Month:</strong><br><br>" .
                   "✅ <strong>Days Present:</strong> {$context['attendance_this_month']} days<br>" .
                   "📊 Month: " . Carbon::now()->format('F Y') . "<br><br>" .
                   "Keep up the good work!";
        }
        
        // 13. Overtime
        if (str_contains($message, 'overtime') || str_contains($message, 'ot')) {
            if ($context['overtime_hours_this_month'] > 0) {
                return "<strong>⏰ Your Overtime This Month:</strong><br><br>" .
                       "✅ <strong>Approved Hours:</strong> {$context['overtime_hours_this_month']} hours<br>" .
                       "📊 Month: " . Carbon::now()->format('F Y') . "<br><br>" .
                       "Great dedication! 💪";
            } else {
                 return "You don't have any approved overtime hours for this month yet. 🕒";
            }
        }
        
        // 14. Help / Menu
        if (str_contains($message, 'help')) {
             return "<strong>I can help you with:</strong><br><br>" .
                   "• 💰 <strong>Payslip</strong> - 'Show my payslip'<br>" .
                   "• 📅 <strong>Schedule</strong> - 'What is my shift?'<br>" .
                   "• 🎉 <strong>Holidays</strong> - 'Next holiday?'<br>" .
                   "• 🏖️ <strong>Leave Balance</strong> - 'Leave balance'<br>" .
                   "• ⏰ <strong>Attendance</strong> - 'My attendance'<br>" .
                   "• 👤 <strong>Profile</strong> - 'My info'";
        }
        
        // 15. Default (Limited to employee data)
        return "I can only help with your employee information. 😊<br><br>" .
               "Try asking about:<br>" .
               "• 💰 Your <strong>Payslip</strong><br>" .
               "• 📅 Your <strong>Schedule</strong><br>" .
               "• 🎉 <strong>Holidays</strong><br>" .
               "• 🏖️ Your <strong>Leave Balance</strong><br>" .
               "• ⏰ Your <strong>Attendance</strong><br>" .
               "• 👤 Your <strong>Profile Info</strong>";
    }
}
