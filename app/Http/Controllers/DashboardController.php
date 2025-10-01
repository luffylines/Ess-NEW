<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return view('admin.dashboard');
        } elseif ($user->role === 'hr') {
            return view('hr.dashboard');
        } else {
            return view('dashboard');
        }
    }
}
