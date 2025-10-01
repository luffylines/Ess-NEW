<x-app-layout>
    <div class="container mx-auto px-4 py-6 max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Submit Overtime Request</h1>
            <p class="text-gray-600 mt-2">Fill out the form below to request overtime approval from your manager.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('overtime.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Overtime Date -->
                <div class="mb-6">
                    <label for="overtime_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Overtime Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="overtime_date" name="overtime_date" 
                           value="{{ old('overtime_date') }}" 
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                </div>

                <!-- Time Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="start_time" name="start_time" 
                               value="{{ old('start_time') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="end_time" name="end_time" 
                               value="{{ old('end_time') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mb-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Overtime <span class="text-red-500">*</span>
                    </label>
                    <textarea id="reason" name="reason" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              placeholder="Please provide a detailed reason for your overtime request..."
                              required>{{ old('reason') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Maximum 500 characters</p>
                </div>

                <!-- Supporting Document -->
                <div class="mb-6">
                    <label for="supporting_document" class="block text-sm font-medium text-gray-700 mb-2">
                        Supporting Document (Optional)
                    </label>
                    <input type="file" id="supporting_document" name="supporting_document" 
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">
                        Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 2MB)
                    </p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('overtime.index') }}" 
                       class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Panel -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-blue-800 mb-2">📋 Important Information</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Your manager will receive a notification to review this request</li>
                <li>• You can edit or cancel this request while it's pending</li>
                <li>• Approved overtime will be automatically added to your payroll</li>
                <li>• Please submit requests at least 24 hours in advance when possible</li>
            </ul>
        </div>
    </div>
</x-app-layout>