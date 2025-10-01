<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        // Fetch users from DB, for example:
        $employees = \App\Models\User::all();

        return view('admin.index', compact('employees'));
    }
}
