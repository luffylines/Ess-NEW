<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestPageController extends Controller
{
    public function about()
    {
        return view('guest.about');
    }

    public function contact()
    {
        return view('guest.contact');
    }

    public function terms()
    {
        return view('guest.terms');
    }

    public function systemInfo()
    {
        return view('guest.system-info');
    }
}
