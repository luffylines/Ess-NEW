<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\InviteEmployee;

class EmployeeController extends Controller
{
    public function index()
    {
        // Fetch users from DB, for example:
        $employees = \App\Models\User::all();

        return view('admin.index', compact('employees'));
    }

    public function create()
{
    return view('admin.employees.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:employee,hr,manager',
    ]);

    $token = Str::random(60); // Unique token to send via email
    
    // Generate employee ID based on role
    $employeeId = User::generateEmployeeId($request->role);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'employee_id' => $employeeId,
        'password' => Hash::make(Str::random(10)), // Temporary password
        'remember_token' => $token, // Use this token to validate account setup
    ]);

    // Log employee creation activity (by the admin who created it)
    $admin = \Illuminate\Support\Facades\Auth::user();
    $admin->logActivity(
        'employee_created',
        "Created new employee: {$user->name} (ID: {$employeeId})",
        [
            'employee_id' => $user->id,
            'employee_email' => $user->email,
            'employee_role' => $user->role,
            'generated_employee_id' => $employeeId
        ]
    );

    // Send the email with the invitation link
    try {
        // Verify token was saved
        if (!$user->remember_token) {
            throw new \Exception('Failed to generate invitation token');
        }
        
        Mail::to($user->email)->send(new InviteEmployee($user));
        $message = "Employee added successfully! Employee ID: {$employeeId}. Invitation email sent to {$user->email}.";
    } catch (\Exception $e) {
        Log::error('Failed to send invitation email: ' . $e->getMessage());
        $completionUrl = route('employees.complete', ['token' => $user->remember_token]);
        $message = "Employee added successfully! Employee ID: {$employeeId}. However, email could not be sent. Please provide the completion link manually: {$completionUrl}";
    }

    return redirect()->route('admin.employees.index')->with('success', $message);
}

public function completeForm($token)
{
    $user = User::where('remember_token', $token)->firstOrFail();
    return view('admin.employees.complete', compact('user'));
}

public function completeStore(Request $request, $token)
{
    $user = User::where('remember_token', $token)->firstOrFail();

    $request->validate([
        'password' => 'required|confirmed|min:8',
        'phone' => 'nullable|string|max:20',
        'gender' => 'nullable|in:male,female,other',
        'address' => 'nullable|string|max:500',
    ]);

    $user->update([
        'password' => Hash::make($request->password),
        'remember_token' => null, // Invalidate token
        'phone' => $request->phone,
        'gender' => $request->gender,
        'address' => $request->address,
    ]);

    // Log profile completion activity
    $user->logActivity(
        'profile_completed',
        "Completed profile setup and activated account",
        [
            'employee_id' => $user->employee_id,
            'completion_timestamp' => now()->toISOString()
        ]
    );

    return redirect()->route('login')->with('success', 'Account setup complete. You may now log in.');
}


    // Show edit form
    public function edit($id)
    {
        $employee = User::findOrFail($id);
        return view('admin.employees.edit', compact('employee'));
    }

    // Update employee record
    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $employee->update($request->all());

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully!');
    }
}
