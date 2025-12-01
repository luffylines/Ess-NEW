<x-app-layout>
    <div class="container mx-auto px-4 py-4">
        <div class="mb-4">
            <h1 class="h2 fw-bold">Submit Overtime Request</h1>
            <p class="mt-2">Fill out the form below to request overtime approval from your manager.</p>
        </div>

        @include('partials.flash-messages')

        <div class="bg-white rounded shadow p-4">
            <form method="POST" action="{{ route('overtime.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Overtime Date -->
                <div class="mb-4">
                    <label for="overtime_date" class="form-label fw-semibold text-secondary">
                        Overtime Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" id="overtime_date" name="overtime_date" 
                           value="{{ old('overtime_date') }}" 
                           min="{{ date('Y-m-d') }}"
                           class="form-control" 
                           required>
                </div>

                <!-- Time Range -->
                <div class="row mb-4">
                    <div class="col">
                        <label for="start_time" class="form-label fw-semibold text-secondary">
                            Start Time <span class="text-danger">*</span>
                        </label>
                        <input type="time" id="start_time" name="start_time" 
                               value="{{ old('start_time') }}"
                               class="form-control" 
                               required>
                    </div>
                    <div class="col">
                        <label for="end_time" class="form-label fw-semibold text-secondary">
                            End Time <span class="text-danger">*</span>
                        </label>
                        <input type="time" id="end_time" name="end_time" 
                               value="{{ old('end_time') }}"
                               class="form-control" 
                               required>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mb-4">
                    <label for="reason" class="form-label fw-semibold text-secondary">
                        Reason for Overtime <span class="text-danger">*</span>
                    </label>
                    <textarea id="reason" name="reason" rows="4" 
                              class="form-control" 
                              placeholder="Please provide a detailed reason for your overtime request..."
                              required>{{ old('reason') }}</textarea>
                    <div class="form-text">Maximum 500 characters</div>
                </div>

                <!-- Supporting Document -->
                <div class="mb-4">
                    <label for="supporting_document" class="form-label fw-semibold text-secondary">
                        Supporting Document (Optional)
                    </label>
                    <input type="file" id="supporting_document" name="supporting_document" 
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="form-control">
                    <div class="form-text">
                        Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 2MB)
                    </div>
                </div>

                <!-- Submit Buttons -->
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ route('overtime.index') }}" class="btn btn-danger">
                            Cancel
                        </a>
                    <button type="submit" 
                            class="btn btn-primary">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Panel -->
        <div class="mt-4 bg-primary bg-opacity-10 border rounded p-3">
            <h3 class="small fw-semibold mb-2">📋 Important Information</h3>
            <ul class="small mb-1">
                <li>• Your manager will receive a notification to review this request</li>
                <li>• You can edit or cancel this request while it's pending</li>
                <li>• Approved overtime will be automatically added to your payroll</li>
                <li>• Please submit requests at least 24 hours in advance when possible</li>
            </ul>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                setTimeout(() => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(errorAlert);
                    bsAlert.close();
                }, 3000);
            }
        });
    </script>
    
</x-app-layout>
