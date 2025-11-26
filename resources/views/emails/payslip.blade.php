<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Payslip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .payslip-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .detail-value {
            color: #333;
        }
        .net-pay {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .cta {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 20px 0;
            font-weight: bold;
        }
        .attachment-note {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ config('app.name', 'Company') }}</h1>
            <p>Employee Self-Service System</p>
        </div>

        <div class="greeting">
            Dear {{ $employeeName }},
        </div>

        <p>Your payslip for <strong>{{ $period }}</strong> is ready for download.</p>

        <div class="payslip-details">
            <h3 style="margin-top: 0; color: #007bff;">Payslip Summary</h3>
            <div class="detail-row">
                <span class="detail-label">Employee:</span>
                <span class="detail-value">{{ $payslip->employee_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Employee ID:</span>
                <span class="detail-value">{{ $payslip->employee_id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Pay Period:</span>
                <span class="detail-value">{{ $period }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Basic Pay:</span>
                <span class="detail-value">₱{{ number_format($payslip->basic_pay, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Overtime Pay:</span>
                <span class="detail-value">₱{{ number_format($payslip->total_overtime_pay, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Gross Pay:</span>
                <span class="detail-value">₱{{ number_format($payslip->gross_pay, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Deductions:</span>
                <span class="detail-value">₱{{ number_format($payslip->total_deductions, 2) }}</span>
            </div>
            <hr>
            <div class="detail-row">
                <span class="detail-label">Net Pay:</span>
                <span class="detail-value net-pay">₱{{ $netPay }}</span>
            </div>
        </div>

        <div class="attachment-note">
            <strong><i>📎 PDF Attachment:</i></strong> Your detailed payslip is attached as a PDF file. You can download and save it for your records.
        </div>

        <p>You can also access your payslips anytime through the employee portal:</p>
        <a href="{{ url('/payslips') }}" class="cta">Access Employee Portal</a>

        <p><strong>Important Notes:</strong></p>
        <ul>
            <li>Keep this payslip for your records and tax purposes</li>
            <li>If you have any questions about your payslip, please contact HR</li>
            <li>This email is automatically generated - please do not reply</li>
        </ul>

        <div class="footer">
            <p>Thank you for your hard work and dedication!</p>
            <p><strong>{{ config('app.name', 'Company') }} Employee Self-Service System</strong></p>
            <p style="font-size: 12px; color: #999;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>