<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payslip->employee_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        
        .payslip-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .document-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
        }
        
        .employee-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .employee-left, .employee-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 40%;
            padding-right: 10px;
        }
        
        .info-value {
            display: table-cell;
            width: 60%;
        }
        
        .payroll-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .payroll-table th {
            background-color: #f8f9fa;
            padding: 12px 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            color: #333;
        }
        
        .payroll-table td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            text-align: right;
        }
        
        .payroll-table td.description {
            text-align: left;
        }
        
        .earnings-row {
            background-color: #e8f5e8;
        }
        
        .deductions-row {
            background-color: #ffe8e8;
        }
        
        .total-row {
            background-color: #e8f4fd;
            font-weight: bold;
        }
        
        .net-pay-row {
            background-color: #d4edda;
            font-weight: bold;
            font-size: 14px;
        }
        
        .amount {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 123, 255, 0.1);
            z-index: -1;
            font-weight: bold;
        }
        
        @page {
            margin: 15mm;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ config('app.name', 'PAYSLIP') }}</div>
    
    <div class="payslip-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ config('app.name', 'Company Name') }}</div>
            <div>Employee Self-Service System</div>
            <div class="document-title">PAYSLIP</div>
            <div>{{ $payslip->getFormattedPeriod() }}</div>
        </div>

        <!-- Employee Information -->
        <div class="employee-info">
            <div class="employee-left">
                <div class="info-section">
                    <div class="section-title">Employee Information</div>
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $payslip->employee_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Employee ID:</span>
                        <span class="info-value">{{ $payslip->employee_id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Position:</span>
                        <span class="info-value">{{ $payslip->employee_position ?? 'Employee' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Department:</span>
                        <span class="info-value">{{ $payslip->employee_department ?? 'Salon' }}</span>
                    </div>
                </div>
            </div>
            <div class="employee-right">
                <div class="info-section">
                    <div class="section-title">Pay Period Details</div>
                    <div class="info-row">
                        <span class="info-label">Period:</span>
                        <span class="info-value">{{ $payslip->getFormattedPeriod() }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Generated:</span>
                        <span class="info-value">{{ $payslip->generated_date->format('M d, Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payslip #:</span>
                        <span class="info-value">{{ $payslip->payslip_number }}</span>
                    </div>
                    @if($payslip->payroll)
                    <div class="info-row">
                        <span class="info-label">Daily Rate:</span>
                        <span class="info-value">₱{{ number_format($payslip->payroll->daily_rate, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payroll Breakdown -->
        <table class="payroll-table">
            <thead>
                <tr>
                    <th style="width: 70%;">Description</th>
                    <th style="width: 30%;">Amount (₱)</th>
                </tr>
            </thead>
            <tbody>
                <!-- Earnings Section -->
                <tr class="earnings-row">
                    <td colspan="2" style="text-align: center; font-weight: bold; background-color: #d4edda;">EARNINGS</td>
                </tr>
                <tr class="earnings-row">
                    <td class="description">Basic Pay</td>
                    <td class="amount">{{ number_format($payslip->basic_pay, 2) }}</td>
                </tr>
                @if($payslip->total_overtime_pay > 0)
                <tr class="earnings-row">
                    <td class="description">Overtime Pay</td>
                    <td class="amount">{{ number_format($payslip->total_overtime_pay, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td class="description"><strong>Gross Pay</strong></td>
                    <td class="amount">{{ number_format($payslip->gross_pay, 2) }}</td>
                </tr>

                <!-- Deductions Section -->
                @if($payslip->total_deductions > 0)
                <tr class="deductions-row">
                    <td colspan="2" style="text-align: center; font-weight: bold; background-color: #f8d7da;">DEDUCTIONS</td>
                </tr>
                @if($payslip->payroll && $payslip->payroll->sss_contribution > 0)
                <tr class="deductions-row">
                    <td class="description">SSS Contribution</td>
                    <td class="amount">{{ number_format($payslip->payroll->sss_contribution, 2) }}</td>
                </tr>
                @endif
                @if($payslip->payroll && $payslip->payroll->philhealth_contribution > 0)
                <tr class="deductions-row">
                    <td class="description">PhilHealth Contribution</td>
                    <td class="amount">{{ number_format($payslip->payroll->philhealth_contribution, 2) }}</td>
                </tr>
                @endif
                @if($payslip->payroll && $payslip->payroll->pagibig_contribution > 0)
                <tr class="deductions-row">
                    <td class="description">Pag-IBIG Contribution</td>
                    <td class="amount">{{ number_format($payslip->payroll->pagibig_contribution, 2) }}</td>
                </tr>
                @endif
                @if($payslip->payroll && $payslip->payroll->withholding_tax > 0)
                <tr class="deductions-row">
                    <td class="description">Withholding Tax</td>
                    <td class="amount">{{ number_format($payslip->payroll->withholding_tax, 2) }}</td>
                </tr>
                @endif
                @if($payslip->payroll && $payslip->payroll->late_deductions > 0)
                <tr class="deductions-row">
                    <td class="description">Late Deductions</td>
                    <td class="amount">{{ number_format($payslip->payroll->late_deductions, 2) }}</td>
                </tr>
                @endif
                @if($payslip->payroll && $payslip->payroll->undertime_deductions > 0)
                <tr class="deductions-row">
                    <td class="description">Undertime Deductions</td>
                    <td class="amount">{{ number_format($payslip->payroll->undertime_deductions, 2) }}</td>
                </tr>
                @endif
                @if($payslip->payroll && $payslip->payroll->absent_deductions > 0)
                <tr class="deductions-row">
                    <td class="description">Absent Deductions</td>
                    <td class="amount">{{ number_format($payslip->payroll->absent_deductions, 2) }}</td>
                </tr>
                @endif
                @if($payslip->payroll && $payslip->payroll->other_deductions > 0)
                <tr class="deductions-row">
                    <td class="description">Other Deductions</td>
                    <td class="amount">{{ number_format($payslip->payroll->other_deductions, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td class="description"><strong>Total Deductions</strong></td>
                    <td class="amount">{{ number_format($payslip->total_deductions, 2) }}</td>
                </tr>
                @endif

                <!-- Net Pay -->
                <tr class="net-pay-row">
                    <td class="description"><strong>NET PAY</strong></td>
                    <td class="amount" style="font-size: 16px;">{{ number_format($payslip->net_pay, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Additional Information -->
        @if($payslip->payroll)
        <div class="info-section">
            <div class="section-title">Attendance Summary</div>
            <div class="employee-info">
                <div class="employee-left">
                    <div class="info-row">
                        <span class="info-label">Working Days:</span>
                        <span class="info-value">{{ $payslip->payroll->working_days }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Days Worked:</span>
                        <span class="info-value">{{ $payslip->payroll->days_worked }}</span>
                    </div>
                </div>
                <div class="employee-right">
                    @if($payslip->payroll->regular_overtime_hours > 0 || $payslip->payroll->holiday_overtime_hours > 0)
                    <div class="info-row">
                        <span class="info-label">Regular OT Hours:</span>
                        <span class="info-value">{{ $payslip->payroll->regular_overtime_hours }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Holiday OT Hours:</span>
                        <span class="info-value">{{ $payslip->payroll->holiday_overtime_hours }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Important:</strong> This payslip is computer-generated and does not require a signature.</p>
            <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }} | Employee Self-Service System</p>
            <p>For payroll inquiries, please contact Human Resources Department.</p>
        </div>
    </div>
</body>
</html>