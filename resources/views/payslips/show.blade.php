<x-app-layout>
    <div class="px-4 py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-primary mb-1">
                    <i class="fas fa-file-invoice-dollar me-2"></i>Payslip Details
                </h1>
                <p class="text-muted mb-0">{{ $payslip->getFormattedPeriod() }}</p>
            </div>
            <div>
                <a href="{{ route('payslip.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i>Back to Payslips
                </a>
                <a href="{{ route('payslips.download', $payslip) }}" 
                   class="btn btn-success download-btn" 
                   id="downloadBtn">
                    <i class="fas fa-download me-1"></i>Download & Email PDF
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show auto-hide-alert" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show auto-hide-alert" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-warning alert-dismissible fade show auto-hide-alert" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Payslip Main Content -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>Payslip {{ $payslip->payslip_number }}
                            </h5>
                            <span class="badge bg-light text-dark">
                                {{ $payslip->generated_date->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Employee Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-2">Employee Information</h6>
                                <p class="mb-1"><strong>Name:</strong> {{ $payslip->employee_name }}</p>
                                <p class="mb-1"><strong>Employee ID:</strong> {{ $payslip->employee_id }}</p>
                                <p class="mb-1"><strong>Position:</strong> {{ $payslip->employee_position }}</p>
                                <p class="mb-0"><strong>Department:</strong> {{ $payslip->employee_department }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-2">Pay Period</h6>
                                <p class="mb-1"><strong>Period:</strong> {{ $payslip->getFormattedPeriod() }}</p>
                                <p class="mb-1"><strong>Generated:</strong> {{ $payslip->generated_date->format('M d, Y') }}</p>
                                <p class="mb-1"><strong>Status:</strong> 
                                    @if($payslip->status === 'viewed')
                                        <span class="badge bg-success">Viewed</span>
                                    @else
                                        <span class="badge bg-secondary">Generated</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Earnings Section -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-success mb-3">
                                <i class="fas fa-plus-circle me-1"></i>Earnings
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-medium">Basic Pay:</td>
                                            <td class="text-end fw-bold">₱{{ number_format($payslip->basic_pay, 2) }}</td>
                                        </tr>
                                        @if($payslip->total_overtime_pay > 0)
                                            <tr>
                                                <td class="fw-medium">Overtime Pay:</td>
                                                <td class="text-end fw-bold">₱{{ number_format($payslip->total_overtime_pay, 2) }}</td>
                                            </tr>
                                        @endif
                                        <tr class="border-top">
                                            <td class="fw-bold text-success">Gross Pay:</td>
                                            <td class="text-end fw-bold text-success">₱{{ number_format($payslip->gross_pay, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Deductions Section -->
                        @if($payslip->total_deductions > 0)
                            <div class="mb-4">
                                <h6 class="fw-bold text-warning mb-3">
                                    <i class="fas fa-minus-circle me-1"></i>Deductions
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless">
                                        <tbody>
                                            @if($payslip->payroll->sss_contribution > 0)
                                                <tr>
                                                    <td class="fw-medium">SSS Contribution:</td>
                                                    <td class="text-end">₱{{ number_format($payslip->payroll->sss_contribution, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payslip->payroll->philhealth_contribution > 0)
                                                <tr>
                                                    <td class="fw-medium">PhilHealth Contribution:</td>
                                                    <td class="text-end">₱{{ number_format($payslip->payroll->philhealth_contribution, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payslip->payroll->pagibig_contribution > 0)
                                                <tr>
                                                    <td class="fw-medium">Pag-IBIG Contribution:</td>
                                                    <td class="text-end">₱{{ number_format($payslip->payroll->pagibig_contribution, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payslip->payroll->withholding_tax > 0)
                                                <tr>
                                                    <td class="fw-medium">Withholding Tax:</td>
                                                    <td class="text-end">₱{{ number_format($payslip->payroll->withholding_tax, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payslip->payroll->late_deductions > 0)
                                                <tr>
                                                    <td class="fw-medium">Late Deductions:</td>
                                                    <td class="text-end">₱{{ number_format($payslip->payroll->late_deductions, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payslip->payroll->undertime_deductions > 0)
                                                <tr>
                                                    <td class="fw-medium">Undertime Deductions:</td>
                                                    <td class="text-end">₱{{ number_format($payslip->payroll->undertime_deductions, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payslip->payroll->absent_deductions > 0)
                                                <tr>
                                                    <td class="fw-medium">Absent Deductions:</td>
                                                    <td class="text-end">₱{{ number_format($payslip->payroll->absent_deductions, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($payslip->payroll->other_deductions > 0)
                                                <tr>
                                                    <td class="fw-medium">Other Deductions:</td>
                                                    <td class="text-end">₱{{ number_format($payslip->payroll->other_deductions, 2) }}</td>
                                                </tr>
                                            @endif
                                            <tr class="border-top">
                                                <td class="fw-bold text-warning">Total Deductions:</td>
                                                <td class="text-end fw-bold text-warning">₱{{ number_format($payslip->total_deductions, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Net Pay Section -->
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold text-primary mb-0">
                                    <i class="fas fa-hand-holding-usd me-2"></i>Net Pay:
                                </h5>
                                <h4 class="fw-bold text-success mb-0">₱{{ number_format($payslip->net_pay, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="col-lg-4">
                <!-- Download Statistics -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Download Statistics
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Download Count:</span>
                            <span class="fw-bold">{{ $payslip->download_count }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>First Downloaded:</span>
                            <span class="fw-bold">
                                {{ $payslip->first_downloaded_at ? $payslip->first_downloaded_at->format('M d, Y') : 'Not yet' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Last Viewed:</span>
                            <span class="fw-bold">
                                {{ $payslip->viewed_at ? $payslip->viewed_at->format('M d, Y H:i') : 'Now' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Payroll Summary -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>Payroll Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Working Days:</span>
                            <span class="fw-bold">{{ $payslip->payroll->working_days }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Days Worked:</span>
                            <span class="fw-bold">{{ $payslip->payroll->days_worked }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Daily Rate:</span>
                            <span class="fw-bold">₱{{ number_format($payslip->payroll->daily_rate, 2) }}</span>
                        </div>
                        @if($payslip->payroll->regular_overtime_hours > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Regular OT Hours:</span>
                                <span class="fw-bold">{{ $payslip->payroll->regular_overtime_hours }}</span>
                            </div>
                        @endif
                        @if($payslip->payroll->holiday_overtime_hours > 0)
                            <div class="d-flex justify-content-between">
                                <span>Holiday OT Hours:</span>
                                <span class="fw-bold">{{ $payslip->payroll->holiday_overtime_hours }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Help Section -->
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-question-circle me-2"></i>Need Help?
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="small mb-2">If you have questions about your payslip, please contact HR.</p>
                        <ul class="small mb-0">
                            <li>Download button automatically emails payslip to you</li>
                            <li>Keep payslips for tax purposes</li>
                            <li>Report any discrepancies to HR immediately</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    
    .table-borderless td {
        border: none;
        padding: 0.25rem 0.5rem;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    @media (max-width: 768px) {
        .d-flex.justify-content-between {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .table-responsive {
            font-size: 0.875rem;
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

            // Enhanced download button functionality
            const downloadBtn = document.getElementById('downloadBtn');
            if (downloadBtn) {
                downloadBtn.addEventListener('click', function(e) {
                    // Change button state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Generating PDF...';
                    this.disabled = true;
                    
                    // Create a temporary form for better browser compatibility
                    const form = document.createElement('form');
                    form.method = 'GET';
                    form.action = this.href;
                    form.style.display = 'none';
                    
                    document.body.appendChild(form);
                    
                    // Submit form instead of direct navigation
                    e.preventDefault();
                    form.submit();
                    
                    // Reset button after delay
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                        document.body.removeChild(form);
                        
                        // Show success message
                        const successAlert = document.createElement('div');
                        successAlert.className = 'alert alert-info alert-dismissible fade show auto-hide-alert';
                        successAlert.innerHTML = `
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>PDF Generated!</strong> Your payslip has been downloaded and sent to your email.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        
                        const flashContainer = document.querySelector('.px-4.py-4 > div:first-child');
                        if (flashContainer) {
                            flashContainer.insertAdjacentElement('afterend', successAlert);
                            
                            // Auto-hide this alert too
                            setTimeout(() => {
                                if (successAlert) {
                                    const bsAlert = new bootstrap.Alert(successAlert);
                                    bsAlert.close();
                                }
                            }, 5000);
                        }
                    }, 2000);
                });
            }
        });

        // CSS for progress bar animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes progressBar {
                from { width: 100%; }
                to { width: 0%; }
            }
            
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
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>