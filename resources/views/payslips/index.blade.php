
<x-app-layout>
    <div class="px-4 py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-primary mb-1">
                    <i class="fas fa-file-invoice-dollar me-2"></i>My Payslips
                </h1>
                <p class="text-muted mb-0">View and download your salary payslips (automatically emailed to you)</p>
            </div>
            <div class="text-end">
                <small class="text-muted">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</small>
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-invoice fa-2x me-3"></i>
                            <div>
                                <h4 class="card-title mb-0">{{ $payslips->total() }}</h4>
                                <p class="card-text mb-0">Total Payslips</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-money-bill-wave fa-2x me-3"></i>
                            <div>
                                <h4 class="card-title mb-0">₱{{ number_format($payslips->sum('net_pay'), 2) }}</h4>
                                <p class="card-text mb-0">Total Earnings</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-download fa-2x me-3"></i>
                            <div>
                                <h4 class="card-title mb-0">{{ $payslips->sum('download_count') }}</h4>
                                <p class="card-text mb-0">Downloads</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar fa-2x me-3"></i>
                            <div>
                                @php
                                    $latestPayslip = $payslips->first();
                                @endphp
                                <h6 class="card-title mb-0">{{ $latestPayslip ? $latestPayslip->pay_period_start->format('M Y') : 'N/A' }}</h6>
                                <p class="card-text mb-0">Latest Period</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($payslips->count() > 0)
            <!-- Payslips Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        Payslip Records
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-hashtag me-1"></i>Payslip #</th>
                                    <th><i class="fas fa-calendar me-1"></i>Period</th>
                                    <th><i class="fas fa-money-bill-wave me-1"></i>Basic Pay</th>
                                    <th><i class="fas fa-clock me-1"></i>Overtime</th>
                                    <th><i class="fas fa-calculator me-1"></i>Gross Pay</th>
                                    <th><i class="fas fa-minus-circle me-1"></i>Deductions</th>
                                    <th><i class="fas fa-hand-holding-usd me-1"></i>Net Pay</th>
                                    <th><i class="fas fa-eye me-1"></i>Status</th>
                                    <th class="text-center"><i class="fas fa-cog me-1"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payslips as $payslip)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $payslip->payslip_number }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $payslip->getFormattedPeriod() }}</span>
                                            <br><small class="text-muted">{{ $payslip->pay_period_start->format('Y') }}</small>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold">₱{{ number_format($payslip->basic_pay, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-info fw-bold">₱{{ number_format($payslip->total_overtime_pay, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-primary fw-bold">₱{{ number_format($payslip->gross_pay, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-warning fw-bold">₱{{ number_format($payslip->total_deductions, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold fs-6">₱{{ number_format($payslip->net_pay, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($payslip->status === 'viewed')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-eye me-1"></i>Viewed
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-file me-1"></i>Generated
                                                </span>
                                            @endif
                                            
                                            @if($payslip->is_downloaded)
                                                <br><small class="text-success">
                                                    <i class="fas fa-download me-1"></i>{{ $payslip->download_count }}x
                                                </small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('payslips.show', $payslip) }}" 
                                                   class="btn btn-outline-primary btn-sm"
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('payslips.download', $payslip) }}" 
                                                   class="btn btn-outline-success btn-sm download-btn"
                                                   title="Download & Email PDF"
                                                   data-payslip-id="{{ $payslip->id }}">
                                                    <i class="fas fa-download"></i>
                                                
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if($payslips->hasPages())
                <div class="mt-4">
                    {{ $payslips->links() }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-file-invoice-dollar fa-5x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No Payslips Available</h4>
                    <p class="text-muted mb-4">You don't have any payslips generated yet. Payslips will appear here once HR processes your payroll.</p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Payslips are automatically generated monthly after payroll approval.
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    .btn-group .btn {
        margin: 0 1px;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
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

            // Enhanced download button functionality for table
            const downloadBtns = document.querySelectorAll('.download-btn');
            downloadBtns.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    // Change button state
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    this.disabled = true;
                    
                    // Create form for better compatibility
                    const form = document.createElement('form');
                    form.method = 'GET';
                    form.action = this.href;
                    form.style.display = 'none';
                    document.body.appendChild(form);
                    
                    e.preventDefault();
                    form.submit();
                    
                    // Reset after delay
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                        document.body.removeChild(form);
                    }, 3000);
                });
            });
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
