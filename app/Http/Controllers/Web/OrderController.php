<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // return view('web.orders.index');
    }

    public function store(Request $request)
    {
        dd($request->all());
        // return view('web.orders.index');
    }
}
