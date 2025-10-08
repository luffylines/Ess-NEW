<x-app-layout>
    <div class="mx-auto px-4 py-4 container">
        <div class="mb-4">
            <h1 class="h2 fw-bold">Submit Overtime Request</h1>
            <p class="text-muted mt-2">Fill out the form below to request overtime approval from your manager.</p>
        </div>

        @if($errors->any())
            <div class="border px-4 rounded mb-3">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded shadow p-4">
            <form method="POST" action="{{ route('overtime.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Overtime Date -->
                <div class="mb-4">
                    <label for="overtime_date" class="d-block small fw-medium text-secondary mb-2">
                        Overtime Date <span class="text-red-500">*</span>
                    </label>
                        <input type="date" id="overtime_date" name="overtime_date" 
                            value="{{ old('overtime_date') }}" 
                            min="{{ date('Y-m-d') }}"
                            class="w-100 px-3 py-2 border" 
                            required>
                    </div>

                <!-- Time Range -->
                <div class="row mb-4">
                    <div>
                        <label for="start_time" class="d-block small fw-medium text-secondary mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="start_time" name="start_time" 
                               value="{{ old('start_time') }}"
                               class="w-100 px-3 py-2 border" 
                               required>
                    </div>
                    <div>
                        <label for="end_time" class="d-block small fw-medium text-secondary mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="end_time" name="end_time" 
                               value="{{ old('end_time') }}"
                               class="w-100 px-3 py-2 border" 
                               required>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mb-4">
                    <label for="reason" class="d-block small fw-medium text-secondary mb-2">
                        Reason for Overtime <span class="text-red-500">*</span>
                    </label>
                    <textarea id="reason" name="reason" rows="4" 
                              class="w-100 px-3 py-2 border" 
                              placeholder="Please provide a detailed reason for your overtime request..."
                              required>{{ old('reason') }}</textarea>
                    <p class="small text-muted mt-1">Maximum 500 characters</p>
                </div>

                <!-- Supporting Document -->
                <div class="mb-4">
                    <label for="supporting_document" class="d-block small fw-medium text-secondary mb-2">
                        Supporting Document (Optional)
                    </label>
                    <input type="file" id="supporting_document" name="supporting_document" 
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="w-100 px-3 py-2 border">
                    <p class="small text-muted mt-1">
                        Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 2MB)
                    </p>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('overtime.index') }}" 
                       class="px-4 py-2 text-secondary bg-secondary">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="py-2 text-white">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Panel -->
        <div class="mt-4 bg-primary bg-opacity-10 border rounded p-3">
            <h3 class="small fw-medium mb-2">📋 Important Information</h3>
            <ul class="small mb-1">
                <li>• Your manager will receive a notification to review this request</li>
                <li>• You can edit or cancel this request while it's pending</li>
                <li>• Approved overtime will be automatically added to your payroll</li>
                <li>• Please submit requests at least 24 hours in advance when possible</li>
            </ul>
        </div>
    </div>
</x-app-layout>