<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg shadow">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(Auth::user()->role === 'admin')
                        @include('admin.dashboard')
                    @elseif(Auth::user()->role === 'hr')
                        @include('hr.dashboard')
                    @else
                        {{-- Default Employee Dashboard Content --}}
                        <div class="container">
                            <h1>Welcome Employee, {{ Auth::user()->name }}!</h1>
                            <p>This is your dashboard.</p>
                            {{-- Add employee-specific content here --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
