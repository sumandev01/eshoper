<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('dashboard.product.index');
    }

    public function add()
    {
        $categories = Category::latest('id')->get();
        $brands = Brand::latest('id')->get();
        return view('dashboard.product.add', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        // Validation and storing logic goes here
    }
}
