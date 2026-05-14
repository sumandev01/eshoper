<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'product_id' => 'required',
            'rating' => 'required|numeric|min:1|max:5',
            'review_text' => 'required',
        ]);
        dd($request->all());
    }
}
