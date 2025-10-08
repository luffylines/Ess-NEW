<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold h3 text-dark">
            {{ __('Employees Management') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="container-fluid mx-auto">
            <div class="bg-white shadow-sm">
                <div class="p-4">
                    
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-3 p-3 border rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Header with Add Button -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="h4 fw-medium text-dark">
                            Employees List
                        </h3>
                        <a href="{{ route('admin.employees.create') }}" 
                           class="align-items-center px-4 py-2 bg-success border fw-semibold small text-dark">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Employee
                        </a>
                    </div>

                    <!-- Employees Table -->
                    <div class="overflow-x-auto">
                        <table class="divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-start small fw-medium text-white">ID</th>
                                    <th class="text-start small fw-medium text-white">Name</th>
                                    <th class="text-start small fw-medium text-white">Email</th>
                                    <th class="text-start small fw-medium text-white">Role</th>
                                    <th class="text-start small fw-medium text-white">Phone</th>
                                    <th class="text-start small fw-medium text-white">Status</th>
                                    <th class="text-start small fw-medium text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black dark:divide-white">
                                @forelse($employees as $employee)
                                    <tr class="dark:hover:bg-gray-700">
                                        <td class="py-3 small fw-medium">
                                            {{ $employee->id }}
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <x-user-avatar :user="$employee" size="sm" />
                                                <div class="ml-4">
                                                    <div class="small fw-medium">
                                                        {{ $employee->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 small text-muted">
                                            {{ $employee->email }}
                                        </td>
                                        <td class="py-3">
                                            <span class="align-items-center rounded-circle small fw-medium">
                                                {{ ucfirst($employee->role) }}
                                            </span>
                                        </td>
                                        <td class="py-3 small text-muted">
                                            {{ $employee->phone ?? 'Not provided' }}
                                        </td>
                                        <td class="py-3">
                                            @if($employee->remember_token)
                                                <span class="align-items-center rounded-circle small fw-medium">
                                                    Pending Setup
                                                </span>
                                            @else
                                                <span class="align-items-center rounded-circle small fw-medium">
                                                    Active
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 small fw-medium gap-2">
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</a>
                                            @if($employee->remember_token)
                                                <button class="text-primary dark:text-blue-400" 
                                                        onclick="resendInvitation({{ $employee->id }})">
                                                    Resend Invitation
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-3 text-center small text-muted">
                                            No employees found. <a href="{{ route('employees.create') }}" class="text-primary">Add your first employee</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function resendInvitation(employeeId) {
            // You can implement resend invitation functionality here
            alert('Resend invitation functionality can be implemented here');
        }
    </script>
</x-app-layout>
