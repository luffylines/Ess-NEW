<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayslipController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Fetch payslips for this user
        $payslips = []; // example: fetch from DB

        return view('payslips.index', compact('payslips'));
    }
}