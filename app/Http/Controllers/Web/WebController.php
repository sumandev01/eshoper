<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Services\ProductFilterService;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function root()
    {
        $products = Product::latest('id')->get();
        $categories = Category::latest('id')->withCount('products')->get();
        return view('web.index', compact('products', 'categories'));
    }

    public function shop(Request $request, ProductFilterService $filterService)
    {

        // 1. Get all products based on filters
        $products = $filterService->filter($request, []);
        // 2. Fetch color and size data for sidebar with product count
        $colorQuery = Color::withCount(['colors' => function ($query) {
            $query->distinct();
        }])->latest('id')->get();

        $sizeQuery = Size::withCount(['sizes' => function ($query) {
            $query->distinct();
        }])->latest('id')->get();

        // 3. Return only partial view if request is AJAX
        if ($request->ajax()) {
            return view('web.layouts.partial.product_list', compact('products'))->render();
        }

        // 4. Default full page load
        return view('web.shop', compact('products', 'colorQuery', 'sizeQuery'));
    }

    public function product($slug)
    {
        $product = Product::where('slug', $slug)->first();
        return view('web.single-product', compact('product'));
    }

    public function cart()
    {
        return view('web.cart');
    }

    public function checkout()
    {
        return view('web.checkout');
    }

    public function contact()
    {
        return view('web.contact');
    }

    public function about()
    {
        return view('web.about');
    }
}
