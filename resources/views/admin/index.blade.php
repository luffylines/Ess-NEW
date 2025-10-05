<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Employees Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-4 p-4 text-green-700 bg-green-100 border border-green-300 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium  text-black dark:text-white">
                            Employees List
                        </h3>
                        <a href="{{ route('admin.employees.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Employee
                        </a>
                    </div>

                    <!-- Employees Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white dark:text-black uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white dark:text-black uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white dark:text-black uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white dark:text-black uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white dark:text-black uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white dark:text-black uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white dark:text-black uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-blue dark:bg-gray-800 divide-y divide-black dark:divide-white">
                                @forelse($employees as $employee)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $employee->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <x-user-avatar :user="$employee" size="sm" />
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $employee->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $employee->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $employee->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                                   ($employee->role === 'hr' ? 'bg-white-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                                   'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200') }}">
                                                {{ ucfirst($employee->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $employee->phone ?? 'Not provided' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($employee->remember_token)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    Pending Setup
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Active
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</a>
                                            @if($employee->remember_token)
                                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400" 
                                                        onclick="resendInvitation({{ $employee->id }})">
                                                    Resend Invitation
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No employees found. <a href="{{ route('employees.create') }}" class="text-blue-600 hover:text-blue-800">Add your first employee</a>
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
