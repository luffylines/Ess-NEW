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
use App\Models\Chat;
use App\Models\ChatMessage;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                \Log::error('Chatbot: User not authenticated');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $rawMessage = $request->input('message');
            if (!$rawMessage) {
                \Log::error('Chatbot: No message provided');
                return response()->json(['error' => 'Message is required'], 400);
            }

            \Log::info('Chatbot: Processing message for user ' . $user->id . ': ' . $rawMessage);

            $message = strtolower($rawMessage);
            
            // Find or create a chat for this user (AI Assistant chat)
            $chat = Chat::firstOrCreate([
                'user_id' => $user->id,
                'hr_user_id' => null, // For AI chat, no HR user
                'status' => 'active'
            ], [
                'last_message_at' => now()
            ]);

            // Save user message
            $userMessage = ChatMessage::create([
                'chat_id' => $chat->id, // Use the chat record ID
                'user_id' => $user->id,
                'message' => $rawMessage,
                'sender_type' => 'user',
                'is_read' => true
            ]);

            \Log::info('Chatbot: User message saved with ID: ' . $userMessage->id);

            // Get user context data
            $context = $this->getUserContext($user);
            
            // Generate Rule-Based Response (Instant)
            $response = $this->generateResponse($message, $context, $user);
            
            // Save bot response
            $botMessage = ChatMessage::create([
                'chat_id' => $chat->id, // Use the same chat ID
                'user_id' => $user->id,
                'message' => $response,
                'sender_type' => 'bot',
                'is_read' => true
            ]);

            // Update chat last message time
            $chat->update(['last_message_at' => now()]);

            \Log::info('Chatbot: Bot response saved with ID: ' . $botMessage->id);

            return response()->json([
                'response' => $response
            ]);
        } catch (\Exception $e) {
            \Log::error('Chatbot error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return response()->json([
                'response' => 'Sorry, I encountered an error. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getHistory()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                \Log::error('Chat History: User not authenticated');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            \Log::info('Chat History: Loading for user ' . $user->id);

            $messages = ChatMessage::where('user_id', $user->id)
                ->orderBy('created_at', 'asc')
                ->get(['message', 'sender_type', 'created_at']);

            \Log::info('Chat History: Found ' . $messages->count() . ' messages');
                
            return response()->json($messages);
        } catch (\Exception $e) {
            \Log::error('Chat history error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return response()->json(['error' => 'Failed to load history'], 500);
        }
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
        // Handle confirmation responses (yes, oo, sige)
        if (str_contains($message, 'yes') || str_contains($message, 'oo') || str_contains($message, 'sige') || str_contains($message, 'okay') || str_contains($message, 'ok')) {
            // Check if user previously asked about something specific
            $recentMessages = ChatMessage::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->pluck('message');
            
            $recentText = $recentMessages->join(' ');
            
            if (str_contains($recentText, 'payslip')) {
                return $this->getPayslipGuide();
            } elseif (str_contains($recentText, 'shift') || str_contains($recentText, 'schedule')) {
                return $this->getShiftGuide();
            } elseif (str_contains($recentText, 'holiday')) {
                return $this->getHolidayGuide();
            } elseif (str_contains($recentText, 'leave')) {
                return $this->getLeaveGuide();
            } elseif (str_contains($recentText, 'attendance')) {
                return $this->getAttendanceGuide();
            } else {
                return "I'd be happy to help! What would you like to know more about? 😊";
            }
        }

        // 7. Greetings
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
                       "Want to see how to download your payslip? <a href='#' onclick='sendMessage(\"yes, show me payslip tutorial\")' class='chat-link'>Yes, show me!</a><br><br>" .
                       "<a href='/payslips' class='btn btn-primary btn-sm' style='color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;'>📄 Go to My Payslips</a>";
            } else {
                return "I couldn't find any payslips for you yet. Please check with HR or wait for your first payslip. 📂";
            }
        }

        // Guide for Payslip
        if (str_contains($message, 'how to download payslip')) {
            return "<strong>How to Download Your Payslip:</strong><br><br>" .
                   "1. Go to the <strong>My Payslips</strong> page from the sidebar.<br>" .
                   "2. Find the month you want to view.<br>" .
                   "3. Click the <strong>Download PDF</strong> button or the Print icon.<br>" .
                   "4. Your payslip will save as a PDF file to your device. 📄";
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
                            "📍 {$location}<br><br>";
            } else {
                $response .= "You have no shift scheduled for tomorrow. ✨<br><br>";
            }

            $response .= "Want to know how to request a shift change? <a href='#' onclick='sendMessage(\"yes, show me shift tutorial\")' class='chat-link'>Yes, tell me</a><br><br>";
            $response .= "<a href='/schedule' class='btn btn-primary btn-sm' style='color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;'>📅 View My Schedule</a>";
            
            return $response;
        }

        // Guide for Shift Change
        if (str_contains($message, 'how to change shift')) {
            return "<strong>How to Change Your Shift:</strong><br><br>" .
                   "1. Inform your <strong>Store Manager</strong> or Supervisor about your request.<br>" .
                   "2. Once approved, the manager will update it in the system.<br>" .
                   "3. You will see the updated schedule here in your dashboard. 📅";
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
                       "⏳ In {$daysUntil} days<br><br>" .
                       "Want to know how holiday pay works? <a href='#' onclick='sendMessage(\"yes, show me holiday tutorial\")' class='chat-link'>Yes, guide me</a><br><br>" .
                       "<a href='/holidays' class='btn btn-primary btn-sm' style='color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;'>🎉 View All Holidays</a>";
            } else {
                return "There are no upcoming holidays scheduled. Keep working! 💪";
            }
        }

        // Guide for Holiday Pay
        if (str_contains($message, 'how holiday pay works')) {
            return "<strong>How Holiday Pay Works:</strong><br><br>" .
                   "• <strong>Regular Holiday:</strong> Paid 200% of your daily rate if you worked, or 100% if unworked.<br>" .
                   "• <strong>Special Non-Working Day:</strong> Paid 130% if worked, or 0% if unworked.<br><br>" .
                   "<em>Note: Policies may vary based on your contract. Check your payslip for details.</em> 💵";
        }

        // 11. Leave Balance
        if (str_contains($message, 'leave') && (str_contains($message, 'balance') || str_contains($message, 'left') || str_contains($message, 'remaining'))) {
            return "<strong>🏖️ Your Leave Balance:</strong><br><br>" .
                   "✅ <strong>Remaining:</strong> {$context['leave_remaining']} days<br>" .
                   "📊 <strong>Annual Allowance:</strong> {$context['leave_balance']} days<br>" .
                   "📉 <strong>Days Used:</strong> {$context['leave_taken']} days<br><br>" .
                   "Need help applying for leave? <a href='#' onclick='sendMessage(\"yes, show me leave tutorial\")' class='chat-link'>Yes, show me</a><br><br>" .
                   "<a href='/leave-requests' class='btn btn-primary btn-sm' style='color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;'>🏖️ Apply for Leave</a>";
        }

        // Guide for Leave Application
        if (str_contains($message, 'how to apply leave')) {
            return "<strong>How to Apply for Leave:</strong><br><br>" .
                   "1. Click on <strong>Leave Requests</strong> in the sidebar.<br>" .
                   "2. Click the <strong>New Request</strong> button.<br>" .
                   "3. Select the <strong>Leave Type</strong> and choose your <strong>Dates</strong>.<br>" .
                   "4. Add a reason and click <strong>Submit</strong>.<br>" .
                   "5. Wait for your manager's approval. You'll get an email notification! 📩";
        }

        // 12. Attendance
        if (str_contains($message, 'attendance') || str_contains($message, 'present')) {
            return "<strong>📅 Your Attendance This Month:</strong><br><br>" .
                   "✅ <strong>Days Present:</strong> {$context['attendance_this_month']} days<br>" .
                   "📊 Month: " . Carbon::now()->format('F Y') . "<br><br>" .
                   "Want to know how to time in/out? <a href='#' onclick='sendMessage(\"yes, show me attendance tutorial\")' class='chat-link'>Yes, explain</a><br><br>" .
                   "<a href='/attendance' class='btn btn-primary btn-sm' style='color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;'>⏰ View My Attendance</a>";
        }

        // Guide for Time In/Out
        if (str_contains($message, 'how to time in')) {
            return "<strong>How to Time In and Time Out:</strong><br><br>" .
                   "1. Go to the <strong>Dashboard</strong>.<br>" .
                   "2. Click the <strong>Clock In</strong> button when you arrive.<br>" .
                   "3. When your shift ends, click the <strong>Clock Out</strong> button.<br>" .
                   "4. Ensure your GPS/Location is enabled if required by your store. 📍";
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

    // Tutorial/Guide methods
    private function getPayslipGuide()
    {
        return "<strong>📄 How to Access & Download Your Payslip:</strong><br><br>" .
               "<strong>Step 1:</strong> Click <strong>My Payslips</strong> from the sidebar menu on the left<br>" .
               "<strong>Step 2:</strong> Find the month you want to view<br>" .
               "<strong>Step 3:</strong> Click the <strong>Download PDF</strong> button or Print icon<br>" .
               "<strong>Step 4:</strong> Your payslip will save as a PDF file to your device 📥<br><br>" .
               "<a href='/payslips' class='btn btn-primary btn-sm' style='color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;'>📄 Go to My Payslips Now</a>";
    }

    private function getShiftGuide()
    {
        return "<strong>📅 How to View & Manage Your Shifts:</strong><br><br>" .
               "<strong>Step 1:</strong> Click <strong>My Schedule</strong> from the sidebar menu<br>" .
               "<strong>Step 2:</strong> View your schedule for the week/month<br>" .
               "<strong>Step 3 (Request Change):</strong> Contact your <strong>Store Manager</strong> or Supervisor<br>" .
               "<strong>Step 4:</strong> Once approved, changes will appear in your schedule<br><br>" .
               "<a href='/schedule' class='btn btn-primary btn-sm' style='color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;'>📅 View My Schedule Now</a>";
    }

    private function getHolidayGuide()
    {
        return "<strong>🎉 How Holiday Pay Works:</strong><br><br>" .
               "<strong>Regular Holiday:</strong> Paid 200% if you worked, or 100% if unworked<br>" .
               "<strong>Special Non-Working Day:</strong> Paid 130% if worked, or 0% if unworked<br><br>" .
               "<strong>Where to view:</strong><br>" .
               "<strong>Step 1:</strong> Go to <strong>Dashboard</strong> - see upcoming holidays<br>" .
               "<strong>Step 2:</strong> Check your payslip to see holiday pay calculations<br><br>" .
               "<em>Note: Policies may vary based on your contract</em> 💵<br><br>" .
               "<a href='/holidays' class='btn btn-primary btn-sm' style='color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;'>🎉 View Holidays</a>";
    }

    private function getLeaveGuide()
    {
        return "<strong>🏖️ How to Apply for Leave:</strong><br><br>" .
               "<strong>Step 1:</strong> Click <strong>Leave Requests</strong> from the sidebar<br>" .
               "<strong>Step 2:</strong> Click the <strong>New Request</strong> button<br>" .
               "<strong>Step 3:</strong> Select <strong>Leave Type</strong> (Sick Leave, Vacation, etc.)<br>" .
               "<strong>Step 4:</strong> Choose your <strong>Dates</strong><br>" .
               "<strong>Step 5:</strong> Add a reason and click <strong>Submit</strong><br>" .
               "<strong>Step 6:</strong> Wait for your manager's approval - you'll get an email! 📩<br><br>" .
               "<a href='/leave-requests' class='btn btn-primary btn-sm' style='color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;'>🏖️ Apply for Leave Now</a>";
    }

    private function getAttendanceGuide() 
    {
        return "<strong>⏰ How to Time In and Time Out:</strong><br><br>" .
               "<strong>Step 1:</strong> Go to the <strong>Dashboard</strong><br>" .
               "<strong>Step 2 (Time In):</strong> Click the <strong>Clock In</strong> button when you arrive<br>" .
               "<strong>Step 3 (Time Out):</strong> Click the <strong>Clock Out</strong> button when you leave<br>" .
               "<strong>Important:</strong> Ensure your GPS/Location is enabled if required by your store 📍<br><br>" .
               "<strong>To view your records:</strong><br>" .
               "Click <strong>My Attendance</strong> from the sidebar to see your daily records<br><br>" .
               "<a href='/attendance' class='btn btn-primary btn-sm' style='color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;'>⏰ View My Attendance</a>";
    }
}
