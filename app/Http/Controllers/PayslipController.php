<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Payroll;
use App\Models\Payslip;
use App\Models\User;
use App\Mail\PayslipEmail;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipController extends Controller
{
    use AuthorizesRequests;

    // Employee: View their payslips
    public function index()
    {
        $user = Auth::user();
        
        $payslips = Payslip::where('user_id', $user->id)
            ->with('payroll')
            ->orderBy('pay_period_year', 'desc')
            ->orderBy('pay_period_month', 'desc')
            ->paginate(12);

        return view('payslips.index', compact('payslips'));
    }

    // Employee: View specific payslip
    public function show(Payslip $payslip)
    {
        // Check if user owns this payslip
        if ($payslip->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this payslip');
        }
        
        $payslip->markAsViewed();
        
        return view('payslips.show', compact('payslip'));
    }

    // Employee: Download payslip PDF
    public function download(Payslip $payslip)
    {
        // Check if user owns this payslip
        if ($payslip->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this payslip');
        }

        $payslip->markAsDownloaded();

        try {
            // Load the PDF view
            $pdf = Pdf::loadView('payslips.pdf', [
                'payslip' => $payslip
            ])->setPaper('A4');

            $filename = 'Payslip_' . str_replace(' ', '_', $payslip->employee_name) . '_' . $payslip->pay_period_year . '_' . str_pad($payslip->pay_period_month, 2, '0', STR_PAD_LEFT) . '.pdf';

            // Generate PDF content
            $pdfContent = $pdf->output();
            
            // Log successful PDF generation
            Log::info('PDF generated successfully for payslip ID: ' . $payslip->id . ', Size: ' . strlen($pdfContent) . ' bytes');

            // Send email with PDF attachment immediately (in background process)
            try {
                Mail::to($payslip->user->email)->send(new PayslipEmail($payslip, $pdfContent));
                Log::info('Email sent successfully for payslip ID: ' . $payslip->id);
            } catch (\Exception $e) {
                // Silently fail email - don't interrupt download
                Log::warning('Failed to send payslip email: ' . $e->getMessage());
            }

            // Return PDF download with proper headers for all devices
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Length', strlen($pdfContent))
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0')
                ->header('Accept-Ranges', 'bytes')
                ->header('X-Robots-Tag', 'noindex, nofollow');
                
        } catch (\Exception $e) {
            Log::error('PDF generation failed for payslip ID: ' . $payslip->id . ', Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    // Debug: Test download functionality
    public function testDownload()
    {
        try {
            // Create a simple test PDF
            $html = '
                <html>
                <body>
                    <h1>PDF Download Test</h1>
                    <p>This is a test PDF to verify download functionality.</p>
                    <p>Generated at: ' . now() . '</p>
                </body>
                </html>
            ';
            
            $pdf = Pdf::loadHTML($html)->setPaper('A4');
            $pdfContent = $pdf->output();
            
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="test-download.pdf"')
                ->header('Content-Length', strlen($pdfContent));
                
        } catch (\Exception $e) {
            return response('PDF test failed: ' . $e->getMessage(), 500);
        }
    }

    // HR/Manager: Payroll management index
    public function payrollIndex(Request $request)
    {
        // Check HR/Manager access
        if (!in_array(Auth::user()->role, ['hr', 'manager', 'admin'])) {
            abort(403, 'Only HR/Managers can manage payrolls');
        }
        
        // Get filter parameters
        $employeeId = $request->get('employee_id');
        $status = $request->get('status');
        $year = $request->get('year');
        $month = $request->get('month');
        
        // Build payrolls query
        $payrollsQuery = Payroll::with(['user', 'approvedBy']);
        
        if ($employeeId) {
            $payrollsQuery->where('user_id', $employeeId);
        }
        
        if ($status) {
            $payrollsQuery->where('status', $status);
        }
        
        if ($year) {
            $payrollsQuery->where('pay_period_year', $year);
        }
        
        if ($month) {
            $payrollsQuery->where('pay_period_month', $month);
        }
        
        $payrolls = $payrollsQuery->orderBy('created_at', 'desc')->paginate(15);
        
        // Get all employees for filter dropdown
        $employees = User::where('role', 'employee')->orderBy('name')->get();
        
        // Statistics
        $totalEmployees = User::where('role', 'employee')->count();
        $approvedPayrolls = Payroll::where('status', 'approved')->count();
        $pendingPayrolls = Payroll::where('status', 'pending_approval')->count();
        $totalDisbursed = Payroll::where('status', 'approved')->sum('net_pay');
        
        return view('hr.payroll.index', compact(
            'payrolls', 
            'employees', 
            'totalEmployees', 
            'approvedPayrolls', 
            'pendingPayrolls', 
            'totalDisbursed'
        ));
    }

    // HR/Manager: Generate payroll for employees
    public function generatePayroll(Request $request)
    {
        // Check HR/Manager access
        if (!in_array(Auth::user()->role, ['hr', 'manager', 'admin'])) {
            abort(403, 'Only HR/Managers can generate payrolls');
        }
        
        $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:users,id'
        ]);

        $year = $request->year;
        $month = $request->month;
        
        // Get employees to process
        if ($request->employee_ids && !empty($request->employee_ids)) {
            $employees = User::whereIn('id', $request->employee_ids)->where('role', 'employee')->get();
        } else {
            // Generate for all employees
            $employees = User::where('role', 'employee')->get();
        }

        $generated = 0;
        $errors = [];

        foreach ($employees as $employee) {
            try {
                // Check if payroll already exists
                $existing = Payroll::where('user_id', $employee->id)
                    ->where('pay_period_year', $year)
                    ->where('pay_period_month', $month)
                    ->first();

                if ($existing) {
                    $errors[] = "Payroll for {$employee->name} already exists for this period.";
                    continue;
                }

                $payroll = Payroll::calculatePayroll($employee->id, $year, $month);
                $payroll->status = 'pending_approval';
                
                if ($payroll->save()) {
                    $generated++;
                } else {
                    $errors[] = "Failed to save payroll for {$employee->name}.";
                }
            } catch (\Exception $e) {
                $errors[] = "Failed to generate payroll for {$employee->name}: " . $e->getMessage();
            }
        }

        $message = "Successfully generated {$generated} payroll(s) out of {$employees->count()} employee(s).";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(' ', $errors);
            return redirect()->route('hr.payroll.index')->with('error', $message);
        }

        return redirect()->route('hr.payroll.index')->with('success', $message);
    }

    // HR/Manager: Generate payroll for all employees
    public function generateAllPayrolls(Request $request)
    {
        // Check HR/Manager access
        if (!in_array(Auth::user()->role, ['hr', 'manager', 'admin'])) {
            abort(403, 'Only HR/Managers can generate payrolls');
        }
        
        $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
            'daily_rate' => 'required|numeric|min:0'
        ]);

        $employees = User::where('role', 'employee')->get();
        $generated = 0;
        $skipped = 0;

        foreach ($employees as $employee) {
            // Check if payroll already exists
            $existing = Payroll::where('user_id', $employee->id)
                ->forPeriod($request->year, $request->month)
                ->first();

            if ($existing) {
                $skipped++;
                continue;
            }

            $payroll = Payroll::calculatePayroll(
                $employee->id, 
                $request->year, 
                $request->month, 
                $request->daily_rate
            );

            $payroll->status = 'pending_approval';
            $payroll->save();
            $generated++;
        }

        return back()->with('success', "Generated {$generated} payrolls. Skipped {$skipped} existing payrolls.");
    }

    // HR/Manager: Approve payroll
    public function approvePayroll(Payroll $payroll)
    {
        // Check HR/Manager access
        if (!in_array(Auth::user()->role, ['hr', 'manager', 'admin'])) {
            abort(403, 'Only HR/Managers can approve payrolls');
        }
        
        if (!$payroll->isPending()) {
            return back()->with('error', 'Only pending payrolls can be approved!');
        }

        $payroll->approve(Auth::id());

        // Generate payslip
        $payslip = Payslip::createFromPayroll($payroll);

        return back()->with('success', 'Payroll approved and payslip generated!');
    }

    // HR/Manager: Bulk approve payrolls
    public function bulkApprove(Request $request)
    {
        // Check HR/Manager access
        if (!in_array(Auth::user()->role, ['hr', 'manager', 'admin'])) {
            abort(403, 'Only HR/Managers can approve payrolls');
        }
        
        $request->validate([
            'payroll_ids' => 'required|array',
            'payroll_ids.*' => 'exists:payrolls,id'
        ]);

        $payrolls = Payroll::whereIn('id', $request->payroll_ids)
            ->where('status', 'pending_approval')
            ->get();

        $approved = 0;
        foreach ($payrolls as $payroll) {
            $payroll->approve(Auth::id());
            Payslip::createFromPayroll($payroll);
            $approved++;
        }

        return back()->with('success', "Approved {$approved} payrolls and generated payslips!");
    }

    // HR/Manager: View payroll details
    public function showPayroll(Payroll $payroll)
    {
        // Check HR/Manager access
        if (!in_array(Auth::user()->role, ['hr', 'manager', 'admin'])) {
            abort(403, 'Only HR/Managers can view payroll details');
        }
        
        $payroll->load(['user', 'approvedBy', 'payslips']);
        
        return view('hr.payroll.show', compact('payroll'));
    }

    // HR/Manager: Delete payroll (only drafts)
    public function deletePayroll(Payroll $payroll)
    {
        // Check HR/Manager access
        if (!in_array(Auth::user()->role, ['hr', 'manager', 'admin'])) {
            abort(403, 'Only HR/Managers can delete payrolls');
        }
        
        if ($payroll->status !== 'draft') {
            return back()->with('error', 'Only draft payrolls can be deleted!');
        }

        $payroll->delete();
        
        return back()->with('success', 'Payroll deleted successfully!');
    }

    // HR/Manager: Payslip management
    public function payslipManagement()
    {
        // Check HR/Manager access
        if (!in_array(Auth::user()->role, ['hr', 'manager', 'admin'])) {
            abort(403, 'Only HR/Managers can manage payslips');
        }
        
        $payslips = Payslip::with(['user', 'payroll'])
            ->orderBy('generated_date', 'desc')
            ->paginate(20);
            
        return view('hr.payslips.index', compact('payslips'));
    }
}