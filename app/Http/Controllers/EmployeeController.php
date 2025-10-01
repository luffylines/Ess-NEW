<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        // Fetch users from DB, for example:
        $users = \App\Models\user::all();

        return view('admin.index', compact('users'));
    }
}
