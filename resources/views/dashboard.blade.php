<x-app-layout>
    <div class="py-3">
        <div class="px-4">
            <div class="card">
                <div class="card-body">
                    @if(Auth::user()->role === 'admin')
                        @include('admin.dashboard')
                    @elseif(Auth::user()->role === 'hr' || Auth::user()->role === 'manager')
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
