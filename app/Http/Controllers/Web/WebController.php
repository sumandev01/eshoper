<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\Size;
use App\Services\ProductFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        // Get all products based on filters
        $products = $filterService->filter($request);

        // Return only partial view if request is AJAX request from shop page filter section
        if ($request->ajax()) {
            return view('web.layouts.partial.product_list', compact('products'))->render();
        }

        // Get shop sidebar data for filtering products based on filters applied on shop page
        $shopSidebarData = $filterService->shopSidebar();
        // Default full page load
        return view('web.shop', compact('products'), $shopSidebarData);
    }

    public function product($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $sizes = $product->sizes;
        $colors = $product->colors;
        $tags = $product->tags;
        return view('web.single-product', compact('product', 'sizes', 'colors', 'tags'));
    }

    public function getAvailableColors(Request $request)
    {
        // Inventory table theke data ana hocche
        $availableColors = ProductInventory::query()
            ->where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->where('stock', '>', 0)
            ->pluck('color_id') // Color ID gulo nilam
            ->toArray();

        return response()->json([
            'availableColors' => $availableColors
        ]);
    }

    public function checkStock(Request $request)
    {
        $variant = ProductInventory::where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->where('color_id', $request->color_id)
            ->first();

        if ($variant) {
            return response()->json([
                'stock' => $variant->stock,
                'price' => $variant->price
            ]);
        }
        return response()->json([
            'stock' => 0,
            'price' => 0
        ]);
    }

    public function cart()
    {
        return view('web.cart');
    }

    public function addToCart(Request $request)
    {
        if(!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login to add to cart'
            ], 401);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Data received successfully',
            'data' => $request->all()
        ]);
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
