<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        'role' => 'required|in:employee,hr',
    ]);

    $token = Str::random(60); // Unique token to send via email

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'password' => Hash::make(Str::random(10)), // Temporary password
        'remember_token' => $token, // Use this token to validate account setup
    ]);

    // Send the email with the invitation link
    try {
        Mail::to($user->email)->send(new InviteEmployee($user));
        $message = 'Employee added and invitation email sent successfully!';
    } catch (\Exception $e) {
        $message = 'Employee added successfully! However, email could not be sent. Please provide the completion link manually: ' . route('employees.complete', ['token' => $user->remember_token]);
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

    return redirect()->route('login')->with('success', 'Account setup complete. You may now log in.');
}
}
