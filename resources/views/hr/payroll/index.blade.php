<x-app-layout>
    <div class="px-4 py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-primary mb-1">
                    <i class="fas fa-calculator me-2"></i>Payroll Management
                </h1>
                <p class="text-muted mb-0">Generate and manage employee payrolls</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generatePayrollModal">
                <i class="fas fa-plus me-1"></i>Generate Payroll
            </button>
        </div>

        @include('partials.flash-messages')

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Total Employees</h6>
                                <h4 class="mb-0">{{ $totalEmployees }}</h4>
                            </div>
                            <div class="text-white-50">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-success text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Approved Payrolls</h6>
                                <h4 class="mb-0">{{ $approvedPayrolls }}</h4>
                            </div>
                            <div class="text-white-50">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-warning text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Pending Payrolls</h6>
                                <h4 class="mb-0">{{ $pendingPayrolls }}</h4>
                            </div>
                            <div class="text-white-50">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-info text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Total Disbursed</h6>
                                <h4 class="mb-0">₱{{ number_format($totalDisbursed, 0) }}</h4>
                            </div>
                            <div class="text-white-50">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="employee_filter" class="form-label">Employee</label>
                        <select class="form-select" name="employee_id" id="employee_filter">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" 
                                    {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status_filter" class="form-label">Status</label>
                        <select class="form-select" name="status" id="status_filter">
                            <option value="">All Status</option>
                            <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="period_year" class="form-label">Year</label>
                        <select class="form-select" name="year" id="period_year">
                            <option value="">All Years</option>
                            @for($year = date('Y'); $year >= date('Y') - 2; $year--)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="period_month" class="form-label">Month</label>
                        <select class="form-select" name="month" id="period_month">
                            <option value="">All Months</option>
                            @for($month = 1; $month <= 12; $month++)
                                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('hr.payroll.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-refresh me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Payrolls Table -->
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Payroll Records
                </h5>
                @if($payrolls->where('status', 'pending_approval')->count() > 0)
                    <button class="btn btn-sm btn-success" onclick="bulkApprove()">
                        <i class="fas fa-check-double me-1"></i>Bulk Approve
                    </button>
                @endif
            </div>
            <div class="card-body">
                @if($payrolls->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    @if($payrolls->where('status', 'pending_approval')->count() > 0)
                                        <th width="40">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                    @endif
                                    <th>Employee</th>
                                    <th>Pay Period</th>
                                    <th>Days Worked</th>
                                    <th>Gross Pay</th>
                                    <th>Deductions</th>
                                    <th>Net Pay</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payrolls as $payroll)
                                    <tr>
                                        @if($payroll->status === 'pending_approval')
                                            <td>
                                                <input type="checkbox" name="payroll_ids[]" value="{{ $payroll->id }}" 
                                                    class="form-check-input payroll-checkbox">
                                            </td>
                                        @elseif($payrolls->where('status', 'pending_approval')->count() > 0)
                                            <td></td>
                                        @endif
                                        <td>
                                            <div>
                                                <strong>{{ $payroll->employee_name }}</strong>
                                                <br>
                                                <small class="text-muted">ID: {{ $payroll->employee_id }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::create($payroll->period_year, $payroll->period_month, 1)->format('M Y') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $payroll->days_worked }}/{{ $payroll->working_days }}</span>
                                        </td>
                                        <td class="fw-bold text-success">₱{{ number_format($payroll->gross_pay, 2) }}</td>
                                        <td class="text-danger">₱{{ number_format($payroll->total_deductions, 2) }}</td>
                                        <td class="fw-bold text-primary">₱{{ number_format($payroll->net_pay, 2) }}</td>
                                        <td>
                                            @if($payroll->status === 'pending_approval')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-success">Approved</span>
                                                @if($payroll->approved_at)
                                                    <br><small class="text-muted">{{ $payroll->approved_at->format('M d, Y') }}</small>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('hr.payroll.show', $payroll) }}" 
                                                    class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($payroll->status === 'pending_approval')
                                                    <button class="btn btn-sm btn-success" 
                                                        onclick="approvePayroll({{ $payroll->id }})" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary" 
                                                        onclick="editPayroll({{ $payroll->id }})" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    {{ $payrolls->withQueryString()->links() }}
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calculator fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No payroll records found</h5>
                        <p class="text-muted">Start by generating payroll for your employees.</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generatePayrollModal">
                            <i class="fas fa-plus me-1"></i>Generate First Payroll
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Generate Payroll Modal -->
    <div class="modal fade" id="generatePayrollModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-calculator me-2"></i>Generate Payroll
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('hr.payroll.generate') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payroll_year" class="form-label">Year <span class="text-danger">*</span></label>
                                <select class="form-select" name="year" id="payroll_year" required>
                                    @for($year = date('Y'); $year >= date('Y') - 1; $year--)
                                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payroll_month" class="form-label">Month <span class="text-danger">*</span></label>
                                <select class="form-select" name="month" id="payroll_month" required>
                                    @for($month = 1; $month <= 12; $month++)
                                        <option value="{{ $month }}" {{ $month == date('n') ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="employee_select" class="form-label">Employees</label>
                            <select class="form-select" name="employee_ids[]" id="employee_select" multiple>
                                <option value="">Select Employees (Leave empty for all)</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_id }})</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Leave empty to generate payroll for all employees</small>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> This will calculate payroll based on attendance, overtime requests, and deductions for the selected period.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-cog me-1"></i>Generate Payroll
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Select All Checkbox
        document.getElementById('selectAll')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.payroll-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Bulk Approve Function
        function bulkApprove() {
            const selectedCheckboxes = document.querySelectorAll('.payroll-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                alert('Please select at least one payroll to approve.');
                return;
            }
            
            const payrollIds = Array.from(selectedCheckboxes).map(cb => cb.value);
            
            if (confirm(`Are you sure you want to approve ${payrollIds.length} payroll record(s)?`)) {
                // Create form for bulk approval
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("hr.payroll.bulk-approve") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                payrollIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'payroll_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Single Approve Function
        function approvePayroll(payrollId) {
            if (confirm('Are you sure you want to approve this payroll?')) {
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

        // Edit Payroll Function
        function editPayroll(payrollId) {
            window.location.href = `{{ url('hr/payroll') }}/${payrollId}/edit`;
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

        /* Animation for alert exit */
        .alert.fade {
            animation: slideOutUp 0.3s ease-in;
        }

        @keyframes slideOutUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
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
</x-app-layout>