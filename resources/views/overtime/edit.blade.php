<x-app-layout>
    <div class="mx-auto px-4 py-4 container">
        <div class="mb-4">
            <h1 class="h2 fw-bold">Edit Overtime Request</h1>
            <p class="text-muted mt-2">Update your overtime request details below.</p>
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
            <form method="POST" action="{{ route('overtime.update', $overtimeRequest) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Overtime Date -->
                <div class="mb-4">
                    <label for="overtime_date" class="d-block small fw-medium text-secondary mb-2">
                        Overtime Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="overtime_date" name="overtime_date" 
                           value="{{ old('overtime_date', $overtimeRequest->overtime_date) }}" 
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
                               value="{{ old('start_time', $overtimeRequest->start_time) }}"
                               class="w-100 px-3 py-2 border" 
                               required>
                    </div>
                    <div>
                        <label for="end_time" class="d-block small fw-medium text-secondary mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="end_time" name="end_time" 
                               value="{{ old('end_time', $overtimeRequest->end_time) }}"
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
                              required>{{ old('reason', $overtimeRequest->reason) }}</textarea>
                    <p class="small text-muted mt-1">Maximum 500 characters</p>
                </div>

                <!-- Supporting Document -->
                <div class="mb-4">
                    <label for="supporting_document" class="d-block small fw-medium text-secondary mb-2">
                        Supporting Document (Optional)
                    </label>
                    @if($overtimeRequest->supporting_document)
                        <div class="mb-2 bg-light rounded border">
                            <span class="small text-muted">Current file: </span>
                            <a href="{{ \Illuminate\Support\Facades\Storage::url($overtimeRequest->supporting_document) }}" 
                               target="_blank" class="text-primary">
                                View Current Document
                            </a>
                        </div>
                    @endif
                    <input type="file" id="supporting_document" name="supporting_document" 
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="w-100 px-3 py-2 border">
                    <p class="small text-muted mt-1">
                        Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 2MB)
                        @if($overtimeRequest->supporting_document)
                            <br><em>Upload a new file to replace the current one</em>
                        @endif
                    </p>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('overtime.show', $overtimeRequest) }}" 
                       class="px-4 py-2 text-secondary bg-secondary">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="py-2 text-white">
                        Update Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>