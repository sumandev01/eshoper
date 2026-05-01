<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('web.dashboard.index');
    }

    public function profile()
    {
        return view('web.dashboard.profile');
    }

    public function address()
    {
        return view('web.dashboard.address');
    }
}
