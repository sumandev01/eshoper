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
use Illuminate\Support\Facades\Storage;

class WebController extends Controller
{
    public function root()
    {
        $products = Product::latest('id')->where('status', 1)->get();
        $latestProducts = $products->take(8);
        $categories = Category::latest('id')->withCount('products')->get();
        return view('web.index', compact('products', 'latestProducts', 'categories'));
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
        $categoryId = $product->details->category_id ?? null;
        $relatedProducts = Product::where('id', '!=', $product->id)
        ->when($categoryId, function($query) use ($categoryId) {
            return $query->whereHas('details', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        })->inRandomOrder()->limit(8)->get();
        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::where('id', '!=', $product->id)->inRandomOrder()->limit(8)->get();
        }

        return view('web.single-product', compact('product', 'sizes', 'colors', 'tags', 'relatedProducts'));
    }

public function getColorBySize(Request $request)
{
    $inventories = ProductInventory::where('product_id', $request->productId)
        ->where('size_id', $request->sizeId)
        ->with('color', 'media')
        ->get();

    $colors = $inventories->map(function ($inventory) {
        $mediaUrl = '';
        if ($inventory->first() && $inventory->first()->media && $inventory->first()->media->src) {
            $mediaUrl = Storage::url($inventory->first()->media->src);
        };
        return [
            'id' => $inventory->color_id,
            'name' => $inventory->color->name ?? 'N/A',
            'image' => $mediaUrl,
        ];
    })->unique('id')->values();

    return response()->json([
        'colors' => $colors
    ]);
}

    public function getAvailableColors(Request $request)
    {
        // Inventory table theke data ana hocche
        $availableColors = ProductInventory::query()
            ->where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->where('stock', '>', 0)
            ->pluck('color_id') // Color IDs of available colors
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

        $mediaUrl = '';
        if ($variant && $variant->media && $variant->media->src) {
            $mediaUrl = Storage::url($variant->media->src);
        }

        if ($variant) {
            return response()->json([
                'stock' => $variant->stock,
                'price' => $variant->price,
                'image' => $mediaUrl,
                'inventory_id' => $variant->id
            ]);
        }
        return response()->json([
            'stock' => 0,
            'price' => 0,
            'image' => '',
            'inventory_id' => ''
        ]);
    }

    public function cart()
    {
        return view('web.cart');
    }

    public function addToCart(Request $request)
    {
        // if(!Auth::check()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Please login to add to cart'
        //     ], 401);
        // }
        return response()->json([
            'status' => 'success',
            'message' => 'Add to cart',
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
