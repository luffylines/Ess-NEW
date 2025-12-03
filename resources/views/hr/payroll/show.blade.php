<x-app-layout>
    <div class="px-4 py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-primary mb-1">
                    <i class="fas fa-file-invoice-dollar me-2"></i>Payroll Details
                </h1>
                <p class="text-muted mb-0">{{ $payroll->employee_name }} - {{ \Carbon\Carbon::create($payroll->period_year, $payroll->period_month, 1)->format('F Y') }}</p>
            </div>
            <div>
                <a href="{{ route('hr.payroll.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i>Back to Payroll
                </a>
                @if($payroll->status === 'pending_approval')
                    <a href="{{ route('hr.payroll.edit', $payroll) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <button class="btn btn-info me-2" onclick="recalculatePayroll({{ $payroll->id }})">
                        <i class="fas fa-calculator me-1"></i>Recalculate
                    </button>
                    <button class="btn btn-success" onclick="approvePayroll({{ $payroll->id }})">
                        <i class="fas fa-check me-1"></i>Approve
                    </button>
                @elseif($payroll->status === 'draft')
                    <a href="{{ route('hr.payroll.edit', $payroll) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <button class="btn btn-info me-2" onclick="recalculatePayroll({{ $payroll->id }})">
                        <i class="fas fa-calculator me-1"></i>Recalculate
                    </button>
                @else
                    <button class="btn btn-outline-info me-2" onclick="recalculatePayroll({{ $payroll->id }})">
                        <i class="fas fa-calculator me-1"></i>Recalculate Values
                    </button>
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-check me-1"></i>Approved
                    </span>
                @endif
            </div>
        </div>

        @include('partials.flash-messages')

        <div class="row">
            <!-- Main Payroll Content -->
            <div class="col-lg-8 mb-4">
                <!-- Employee Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Employee Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Name:</strong> {{ $payroll->employee_name }}</p>
                                <p class="mb-2"><strong>Employee ID:</strong> {{ $payroll->employee_id }}</p>
                                <p class="mb-2"><strong>Position:</strong> {{ $payroll->employee_position }}</p>
                                <p class="mb-0"><strong>Department:</strong> {{ $payroll->employee_department }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Pay Period:</strong> {{ \Carbon\Carbon::create($payroll->period_year, $payroll->period_month, 1)->format('F Y') }}</p>
                                <p class="mb-2"><strong>Daily Rate:</strong> ₱{{ number_format($payroll->daily_rate, 2) }}</p>
                                <p class="mb-2"><strong>Working Days:</strong> {{ $payroll->working_days }}</p>
                                <p class="mb-0"><strong>Days Worked:</strong> {{ $payroll->days_worked }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payroll Calculation -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>Payroll Calculation
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Basic Pay -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="fas fa-plus-circle me-1"></i>Earnings
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="fw-medium">Basic Pay:</td>
                                                <td class="text-end fw-bold">₱{{ number_format($payroll->basic_pay, 2) }}</td>
                                            </tr>
                                            @if($payroll->regular_overtime_hours > 0)
                                                <tr>
                                                    <td class="fw-medium">Regular OT ({{ $payroll->regular_overtime_hours }}h):</td>
                                                    <td class="text-end">₱{{ number_format($payroll->calculated_regular_overtime_pay, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payroll->holiday_overtime_hours > 0)
                                                <tr>
                                                    <td class="fw-medium">Holiday OT ({{ $payroll->holiday_overtime_hours }}h):</td>
                                                    <td class="text-end">₱{{ number_format($payroll->calculated_holiday_overtime_pay, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payroll->holiday_pay > 0)
                                                <tr>
                                                    <td class="fw-medium">Holiday Pay:</td>
                                                    <td class="text-end">₱{{ number_format($payroll->holiday_pay, 2) }}</td>
                                                </tr>
                                            @endif
                                            <tr class="border-top">
                                                <td class="fw-bold text-success">Gross Pay:</td>
                                                <td class="text-end fw-bold text-success fs-5">₱{{ number_format($payroll->calculated_gross_pay, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-warning mb-3">
                                    <i class="fas fa-minus-circle me-1"></i>Deductions
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless">
                                        <tbody>
                                            @if($payroll->sss_contribution > 0)
                                                <tr>
                                                    <td class="fw-medium">SSS Contribution:</td>
                                                    <td class="text-end">₱{{ number_format($payroll->sss_contribution, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payroll->philhealth_contribution > 0)
                                                <tr>
                                                    <td class="fw-medium">PhilHealth:</td>
                                                    <td class="text-end">₱{{ number_format($payroll->philhealth_contribution, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payroll->pagibig_contribution > 0)
                                                <tr>
                                                    <td class="fw-medium">Pag-IBIG:</td>
                                                    <td class="text-end">₱{{ number_format($payroll->pagibig_contribution, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payroll->withholding_tax > 0)
                                                <tr>
                                                    <td class="fw-medium">Withholding Tax:</td>
                                                    <td class="text-end">₱{{ number_format($payroll->withholding_tax, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payroll->late_deductions > 0)
                                                <tr>
                                                    <td class="fw-medium">Late Deductions:</td>
                                                    <td class="text-end">₱{{ number_format($payroll->late_deductions, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payroll->undertime_deductions > 0)
                                                <tr>
                                                    <td class="fw-medium">Undertime:</td>
                                                    <td class="text-end">₱{{ number_format($payroll->undertime_deductions, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payroll->absent_deductions > 0)
                                                <tr>
                                                    <td class="fw-medium">Absent Deductions:</td>
                                                    <td class="text-end">₱{{ number_format($payroll->absent_deductions, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payroll->other_deductions > 0)
                                                <tr>
                                                    <td class="fw-medium">Other Deductions:</td>
                                                    <td class="text-end">₱{{ number_format($payroll->other_deductions, 2) }}</td>
                                                </tr>
                                            @endif
                                            <tr class="border-top">
                                                <td class="fw-bold text-warning">Total Deductions:</td>
                                                <td class="text-end fw-bold text-warning fs-5">₱{{ number_format($payroll->total_deductions, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Net Pay -->
                        <div class="bg-light p-4 rounded text-center">
                            <h4 class="fw-bold text-primary mb-2">
                                <i class="fas fa-hand-holding-usd me-2"></i>Net Pay
                            </h4>
                            <h2 class="fw-bold text-success mb-0">₱{{ number_format($payroll->calculated_net_pay, 2) }}</h2>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                @if($payroll->notes)
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-sticky-note me-2"></i>Notes
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $payroll->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header {{ $payroll->status === 'approved' ? 'bg-success' : 'bg-warning' }} text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Payroll Status
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Status:</span>
                            <span class="fw-bold">
                                @if($payroll->status === 'approved')
                                    <span class="text-success">Approved</span>
                                @else
                                    <span class="text-warning">Pending</span>
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Created:</span>
                            <span class="fw-bold">{{ $payroll->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($payroll->approved_at)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Approved:</span>
                                <span class="fw-bold">{{ $payroll->approved_at->format('M d, Y') }}</span>
                            </div>
                        @endif
                        @if($payroll->approved_by)
                            <div class="d-flex justify-content-between">
                                <span>Approved By:</span>
                                <span class="fw-bold">{{ $payroll->approver?->name ?? 'System' }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Attendance Summary -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>Attendance Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Working Days:</span>
                            <span class="fw-bold">{{ $payroll->working_days }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Days Worked:</span>
                            <span class="fw-bold">{{ $payroll->days_worked }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Days Absent:</span>
                            <span class="fw-bold text-danger">{{ $payroll->working_days - $payroll->days_worked }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Attendance Rate:</span>
                            <span class="fw-bold">{{ number_format(($payroll->days_worked / $payroll->working_days) * 100, 1) }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Overtime Summary -->
                @if($payroll->regular_overtime_hours > 0 || $payroll->holiday_overtime_hours > 0)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-clock me-2"></i>Overtime Summary
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($payroll->regular_overtime_hours > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Regular OT Hours:</span>
                                    <span class="fw-bold">{{ $payroll->regular_overtime_hours }}h</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Regular OT Pay:</span>
                                    <span class="fw-bold">₱{{ number_format($payroll->calculated_regular_overtime_pay, 2) }}</span>
                                </div>
                            @endif
                            @if($payroll->holiday_overtime_hours > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Holiday OT Hours:</span>
                                    <span class="fw-bold">{{ $payroll->holiday_overtime_hours }}h</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Holiday OT Pay:</span>
                                    <span class="fw-bold">₱{{ number_format($payroll->calculated_holiday_overtime_pay, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                @if($payroll->status === 'pending_approval')
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-success" onclick="approvePayroll({{ $payroll->id }})">
                                    <i class="fas fa-check me-1"></i>Approve Payroll
                                </button>
                                <button class="btn btn-outline-primary" onclick="editPayroll({{ $payroll->id }})">
                                    <i class="fas fa-edit me-1"></i>Edit Payroll
                                </button>
                                <button class="btn btn-outline-danger" onclick="deletePayroll({{ $payroll->id }})">
                                    <i class="fas fa-trash me-1"></i>Delete Payroll
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function approvePayroll(payrollId) {
            if (confirm('Are you sure you want to approve this payroll? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/hr/payroll/${payrollId}/approve`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function recalculatePayroll(payrollId) {
            if (confirm('Are you sure you want to recalculate this payroll? This will update overtime pay based on current rates.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/hr/payroll/${payrollId}/recalculate`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function editPayroll(payrollId) {
            window.location.href = `/hr/payroll/${payrollId}/edit`;
        }

        function deletePayroll(payrollId) {
            if (confirm('Are you sure you want to delete this payroll? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/hr/payroll/${payrollId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <style>
        /* Enhanced Alert Styling */
        .alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left-color: #28a745;
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left-color: #dc3545;
            color: #721c24;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left-color: #ffc107;
            color: #856404;
        }

        .alert i {
            font-size: 1.1em;
        }

        /* Animation for alert entrance */
        .alert.fade.show {
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.auto-hide-alert');
            
            alerts.forEach(function(alert) {
                // Add a progress bar for visual feedback
                const progressBar = document.createElement('div');
                progressBar.style.cssText = `
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    height: 3px;
                    background: rgba(0, 0, 0, 0.2);
                    width: 100%;
                    animation: progressBar 5s linear forwards;
                `;
                alert.style.position = 'relative';
                alert.appendChild(progressBar);

                // Auto-hide after 5 seconds
                setTimeout(function() {
                    if (alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });
        });

        // CSS for progress bar animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes progressBar {
                from { width: 100%; }
                to { width: 0%; }
            }
        `;
        document.head.appendChild(style);
    </script>

    <style>
    .table-borderless td {
        border: none;
        padding: 0.25rem 0.5rem;
    }
    
    .card {
        transition: transform 0.2s ease-in-out;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    @media (max-width: 768px) {
        .d-flex.justify-content-between {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .table-responsive {
            font-size: 0.875rem;
        }
    }
    </style>
</x-app-layout>