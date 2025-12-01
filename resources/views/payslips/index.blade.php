
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

        @include('partials.flash-messages')

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
        document.addEventListener('DOMContentLoaded', function() {
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

        // CSS for download button effects
    </script>
</x-app-layout>
