<x-app-layout>
    <div class="px-4 py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-warning mb-1">
                    <i class="fas fa-edit me-2"></i>Edit Payroll
                </h1>
                <p class="text-muted mb-0">{{ $payroll->user->name }} - {{ \Carbon\Carbon::create($payroll->pay_period_year, $payroll->pay_period_month, 1)->format('F Y') }}</p>
            </div>
            <div>
                <a href="{{ route('hr.payroll.show', $payroll) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Details
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('hr.payroll.update', $payroll) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Left Column - Earnings -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-plus-circle me-2"></i>Earnings
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="basic_pay" class="form-label">Basic Pay</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" 
                                           class="form-control @error('basic_pay') is-invalid @enderror" 
                                           id="basic_pay" 
                                           name="basic_pay" 
                                           value="{{ old('basic_pay', $payroll->basic_pay) }}" 
                                           step="0.01" 
                                           min="0" 
                                           required>
                                </div>
                                @error('basic_pay')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="regular_overtime_hours" class="form-label">Regular Overtime Hours</label>
                                <input type="number" 
                                       class="form-control @error('regular_overtime_hours') is-invalid @enderror" 
                                       id="regular_overtime_hours" 
                                       name="regular_overtime_hours" 
                                       value="{{ old('regular_overtime_hours', $payroll->regular_overtime_hours) }}" 
                                       step="0.5" 
                                       min="0">
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="text-muted">Rate: ₱{{ number_format($payroll->regular_overtime_rate ?: ($payroll->daily_rate / 8 * 1.25), 2) }}/hour</small>
                                    <strong class="text-success" id="regular_ot_amount">₱0.00</strong>
                                </div>
                                @error('regular_overtime_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="holiday_overtime_hours" class="form-label">Holiday Overtime Hours</label>
                                <input type="number" 
                                       class="form-control @error('holiday_overtime_hours') is-invalid @enderror" 
                                       id="holiday_overtime_hours" 
                                       name="holiday_overtime_hours" 
                                       value="{{ old('holiday_overtime_hours', $payroll->holiday_overtime_hours) }}" 
                                       step="0.5" 
                                       min="0">
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="text-muted">Rate: ₱{{ number_format($payroll->holiday_overtime_rate ?: ($payroll->daily_rate / 8 * 2.5), 2) }}/hour</small>
                                    <strong class="text-warning" id="holiday_ot_amount">₱0.00</strong>
                                </div>
                                @error('holiday_overtime_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-medium">Total Overtime Pay:</span>
                                    <strong class="text-primary" id="total_overtime_amount">₱0.00</strong>
                                </div>
                            </div>

                            <div class="bg-light p-3 rounded">
                                <h6 class="fw-bold text-success mb-2">Earnings Summary</h6>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Basic Pay:</span>
                                    <strong id="basic_pay_display">₱{{ number_format($payroll->basic_pay, 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Overtime:</span>
                                    <strong class="text-primary" id="total_overtime_summary">₱{{ number_format($payroll->total_overtime_pay, 2) }}</strong>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-success">Estimated Gross Pay:</strong>
                                    <strong class="text-success" id="gross_pay_preview">₱{{ number_format($payroll->gross_pay, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Deductions -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-minus-circle me-2"></i>Deductions
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Government Contributions (Read-only) -->
                            <div class="mb-3">
                                <label class="form-label">Government Contributions</label>
                                <div class="row">
                                    <div class="col-4">
                                        <label class="form-label small">SSS</label>
                                        <input type="text" class="form-control form-control-sm" value="₱{{ number_format($payroll->sss_contribution, 2) }}" readonly>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small">PhilHealth</label>
                                        <input type="text" class="form-control form-control-sm" value="₱{{ number_format($payroll->philhealth_contribution, 2) }}" readonly>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small">Pag-IBIG</label>
                                        <input type="text" class="form-control form-control-sm" value="₱{{ number_format($payroll->pagibig_contribution, 2) }}" readonly>
                                    </div>
                                </div>
                                <small class="text-muted">Government contributions are automatically calculated</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Withholding Tax</label>
                                <input type="text" class="form-control" value="₱{{ number_format($payroll->withholding_tax, 2) }}" readonly>
                                <small class="text-muted">Automatically calculated based on BIR tables</small>
                            </div>

                            <hr>

                            <!-- Editable Deductions -->
                            <div class="mb-3">
                                <label for="late_deductions" class="form-label">Late Deductions</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" 
                                           class="form-control @error('late_deductions') is-invalid @enderror" 
                                           id="late_deductions" 
                                           name="late_deductions" 
                                           value="{{ old('late_deductions', $payroll->late_deductions) }}" 
                                           step="0.01" 
                                           min="0">
                                </div>
                                @error('late_deductions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="undertime_deductions" class="form-label">Undertime Deductions</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" 
                                           class="form-control @error('undertime_deductions') is-invalid @enderror" 
                                           id="undertime_deductions" 
                                           name="undertime_deductions" 
                                           value="{{ old('undertime_deductions', $payroll->undertime_deductions) }}" 
                                           step="0.01" 
                                           min="0">
                                </div>
                                @error('undertime_deductions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="absent_deductions" class="form-label">Absent Deductions</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" 
                                           class="form-control @error('absent_deductions') is-invalid @enderror" 
                                           id="absent_deductions" 
                                           name="absent_deductions" 
                                           value="{{ old('absent_deductions', $payroll->absent_deductions) }}" 
                                           step="0.01" 
                                           min="0">
                                </div>
                                @error('absent_deductions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="other_deductions" class="form-label">Other Deductions</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" 
                                           class="form-control @error('other_deductions') is-invalid @enderror" 
                                           id="other_deductions" 
                                           name="other_deductions" 
                                           value="{{ old('other_deductions', $payroll->other_deductions) }}" 
                                           step="0.01" 
                                           min="0">
                                </div>
                                <small class="text-muted">Loans, advances, etc.</small>
                                @error('other_deductions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="bg-light p-3 rounded">
                                <h6 class="fw-bold text-warning mb-2">Deductions Summary</h6>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">Government Contributions:</span>
                                    <span class="small">₱{{ number_format($payroll->sss_contribution + $payroll->philhealth_contribution + $payroll->pagibig_contribution, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">Withholding Tax:</span>
                                    <span class="small">₱{{ number_format($payroll->withholding_tax, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">Variable Deductions:</span>
                                    <strong class="text-info" id="variable_deductions_total">₱0.00</strong>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-warning">Total Deductions:</strong>
                                    <strong class="text-warning" id="total_deductions_preview">₱{{ number_format($payroll->total_deductions, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Net Pay Summary -->
            <div class="card shadow-sm mb-4">
                <div class="card-body bg-primary text-white text-center">
                    <h3 class="mb-2">
                        <i class="fas fa-hand-holding-usd me-2"></i>Estimated Net Pay
                    </h3>
                    <h1 class="mb-2" id="net_pay_preview">₱{{ number_format($payroll->net_pay, 2) }}</h1>
                    
                    <!-- Calculation Breakdown -->
                    <div class="row mt-3 text-start">
                        <div class="col-md-6">
                            <div class="bg-white bg-opacity-10 p-3 rounded">
                                <h6 class="mb-2"><i class="fas fa-plus-circle me-1"></i>Total Earnings</h6>
                                <div class="d-flex justify-content-between small">
                                    <span>Basic Pay:</span>
                                    <span id="calc_basic_pay">₱{{ number_format($payroll->basic_pay, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span>Regular OT:</span>
                                    <span id="calc_regular_ot">₱{{ number_format($payroll->regular_overtime_hours * $payroll->regular_overtime_rate, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span>Holiday OT:</span>
                                    <span id="calc_holiday_ot">₱{{ number_format($payroll->holiday_overtime_hours * $payroll->holiday_overtime_rate, 2) }}</span>
                                </div>
                                <hr class="my-2 bg-white bg-opacity-25">
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Gross Pay:</span>
                                    <span id="calc_gross_pay">₱{{ number_format($payroll->gross_pay, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-white bg-opacity-10 p-3 rounded">
                                <h6 class="mb-2"><i class="fas fa-minus-circle me-1"></i>Total Deductions</h6>
                                <div class="d-flex justify-content-between small">
                                    <span>Government:</span>
                                    <span>₱{{ number_format($payroll->sss_contribution + $payroll->philhealth_contribution + $payroll->pagibig_contribution, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span>Tax:</span>
                                    <span>₱{{ number_format($payroll->withholding_tax, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span>Others:</span>
                                    <span id="calc_other_deductions">₱{{ number_format($payroll->late_deductions + $payroll->undertime_deductions + $payroll->absent_deductions + $payroll->other_deductions, 2) }}</span>
                                </div>
                                <hr class="my-2 bg-white bg-opacity-25">
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total Deductions:</span>
                                    <span id="calc_total_deductions">₱{{ number_format($payroll->total_deductions, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <small class="opacity-75 mt-2 d-block">This will be recalculated when you save</small>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mb-4">
                <a href="{{ route('hr.payroll.show', $payroll) }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-times me-1"></i>Cancel
                </a>
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save me-1"></i>Update Payroll
                </button>
            </div>
        </form>
    </div>

    <!-- JavaScript for live calculations -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get form elements
            const basicPayInput = document.getElementById('basic_pay');
            const regularOtInput = document.getElementById('regular_overtime_hours');
            const holidayOtInput = document.getElementById('holiday_overtime_hours');
            const lateDeductionsInput = document.getElementById('late_deductions');
            const undertimeDeductionsInput = document.getElementById('undertime_deductions');
            const absentDeductionsInput = document.getElementById('absent_deductions');
            const otherDeductionsInput = document.getElementById('other_deductions');
            
            // Get preview elements
            const grossPayPreview = document.getElementById('gross_pay_preview');
            const totalDeductionsPreview = document.getElementById('total_deductions_preview');
            const netPayPreview = document.getElementById('net_pay_preview');
            const regularOtAmount = document.getElementById('regular_ot_amount');
            const holidayOtAmount = document.getElementById('holiday_ot_amount');
            const totalOvertimeAmount = document.getElementById('total_overtime_amount');
            const basicPayDisplay = document.getElementById('basic_pay_display');
            const totalOvertimeSummary = document.getElementById('total_overtime_summary');
            const variableDeductionsTotal = document.getElementById('variable_deductions_total');
            
            // Calculation breakdown elements
            const calcBasicPay = document.getElementById('calc_basic_pay');
            const calcRegularOt = document.getElementById('calc_regular_ot');
            const calcHolidayOt = document.getElementById('calc_holiday_ot');
            const calcGrossPay = document.getElementById('calc_gross_pay');
            const calcOtherDeductions = document.getElementById('calc_other_deductions');
            const calcTotalDeductions = document.getElementById('calc_total_deductions');
            
            // Fixed values from PHP
            const regularOtRate = {{ $payroll->regular_overtime_rate ?? 0 }};
            const holidayOtRate = {{ $payroll->holiday_overtime_rate ?? 0 }};
            const dailyRate = {{ $payroll->daily_rate ?? 600 }};
            const fixedDeductions = {{ $payroll->sss_contribution + $payroll->philhealth_contribution + $payroll->pagibig_contribution + $payroll->withholding_tax }};
            
            // Debug output
            console.log('Daily Rate:', dailyRate);
            console.log('Regular OT Rate:', regularOtRate);
            console.log('Holiday OT Rate:', holidayOtRate);
            
            // Fallback calculation if rates are zero
            const fallbackRegularRate = regularOtRate || (dailyRate / 8 * 1.25);
            const fallbackHolidayRate = holidayOtRate || (dailyRate / 8 * 2.5);
            
            console.log('Fallback Regular Rate:', fallbackRegularRate);
            console.log('Fallback Holiday Rate:', fallbackHolidayRate);
            
            function updateCalculations() {
                // Get current values
                const basicPay = parseFloat(basicPayInput.value) || 0;
                const regularOtHours = parseFloat(regularOtInput.value) || 0;
                const holidayOtHours = parseFloat(holidayOtInput.value) || 0;
                const lateDeductions = parseFloat(lateDeductionsInput.value) || 0;
                const undertimeDeductions = parseFloat(undertimeDeductionsInput.value) || 0;
                const absentDeductions = parseFloat(absentDeductionsInput.value) || 0;
                const otherDeductions = parseFloat(otherDeductionsInput.value) || 0;
                
                // Calculate overtime pay amounts (use fallback rates if needed)
                const effectiveRegularRate = regularOtRate || fallbackRegularRate;
                const effectiveHolidayRate = holidayOtRate || fallbackHolidayRate;
                
                const regularOvertimePay = regularOtHours * effectiveRegularRate;
                const holidayOvertimePay = holidayOtHours * effectiveHolidayRate;
                const totalOvertimePay = regularOvertimePay + holidayOvertimePay;
                
                // Calculate gross pay
                const grossPay = basicPay + totalOvertimePay;
                
                // Calculate total deductions
                const variableDeductions = lateDeductions + undertimeDeductions + absentDeductions + otherDeductions;
                const totalDeductions = fixedDeductions + variableDeductions;
                
                // Calculate net pay
                const netPay = grossPay - totalDeductions;
                
                // Update overtime amount displays
                regularOtAmount.textContent = '₱' + regularOvertimePay.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                holidayOtAmount.textContent = '₱' + holidayOvertimePay.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                totalOvertimeAmount.textContent = '₱' + totalOvertimePay.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                // Update summary displays
                basicPayDisplay.textContent = '₱' + basicPay.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                totalOvertimeSummary.textContent = '₱' + totalOvertimePay.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                variableDeductionsTotal.textContent = '₱' + variableDeductions.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                // Update detailed breakdown
                calcBasicPay.textContent = '₱' + basicPay.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                calcRegularOt.textContent = '₱' + regularOvertimePay.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                calcHolidayOt.textContent = '₱' + holidayOvertimePay.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                calcGrossPay.textContent = '₱' + grossPay.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                calcOtherDeductions.textContent = '₱' + variableDeductions.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                calcTotalDeductions.textContent = '₱' + totalDeductions.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                // Update previews
                grossPayPreview.textContent = '₱' + grossPay.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                totalDeductionsPreview.textContent = '₱' + totalDeductions.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                netPayPreview.textContent = '₱' + netPay.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                // Color coding for net pay with animation
                if (netPay < 0) {
                    netPayPreview.className = 'mb-0 text-danger animate-pulse';
                } else {
                    netPayPreview.className = 'mb-0 animate-glow';
                }
                
                // Add visual feedback for calculations
                [regularOtAmount, holidayOtAmount, totalOvertimeAmount, variableDeductionsTotal].forEach(element => {
                    element.classList.add('animate-update');
                    setTimeout(() => element.classList.remove('animate-update'), 200);
                });
            }
            
            // Add event listeners to all inputs
            [basicPayInput, regularOtInput, holidayOtInput, lateDeductionsInput, 
             undertimeDeductionsInput, absentDeductionsInput, otherDeductionsInput].forEach(input => {
                input.addEventListener('input', updateCalculations);
                input.addEventListener('change', updateCalculations);
            });
            
            // Initial calculation
            updateCalculations();
        });
    </script>

    <!-- Custom CSS for animations and styling -->
    <style>
        .animate-update {
            background-color: #ffeaa7 !important;
            transition: background-color 0.3s ease;
            border-radius: 4px;
            padding: 2px 6px;
        }
        
        .animate-pulse {
            animation: pulse 1.5s infinite;
        }
        
        .animate-glow {
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        @keyframes glow {
            from { text-shadow: 0 0 5px rgba(40, 167, 69, 0.4); }
            to { text-shadow: 0 0 10px rgba(40, 167, 69, 0.8); }
        }
        
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .card {
            transition: transform 0.2s ease-in-out;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .bg-light {
            border-left: 4px solid #28a745;
        }
        
        .card-header.bg-warning + .card-body .bg-light {
            border-left: 4px solid #ffc107;
        }
        
        /* Highlight changed values */
        .value-changed {
            background: linear-gradient(45deg, #fff3cd, #ffeeba);
            border-radius: 4px;
            padding: 2px 6px;
            animation: valueChange 0.6s ease-out;
        }
        
        @keyframes valueChange {
            0% { transform: scale(1.1); background-color: #28a745; color: white; }
            100% { transform: scale(1); }
        }
    </style>
</x-app-layout>