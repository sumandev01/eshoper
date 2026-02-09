<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\Size;
use App\Services\ProductFilterService;
use App\Services\ProductWebService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WebController extends Controller
{
    private $webService;

    public function __construct(ProductWebService $webService)
    {
        $this->webService = $webService;
    }
    public function root()
    {
        $products = Product::latest('id')->where('status', 1)->get();
        $latestProducts = $products->take(8);
        $categories = Category::latest('id')->withCount('products')->get();
        return view('web.index', compact('products', 'latestProducts', 'categories'));
    }

    public function products(Request $request, ProductFilterService $filterService)
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
        return view('web.products', compact('products'), $shopSidebarData);
    }

    public function productDetails($slug)
    {
        // Make sure the product exists or not
        $product = Product::with(['sizes', 'colors', 'tags', 'details'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Related products by category and random products
        $relatedProducts = $this->webService->getRelatedProductsSinglePage($product);

        // Return view
        return view('web.single-product', [
            'product'         => $product,
            'sizes'           => $product->sizes,
            'colors'          => $product->colors,
            'tags'            => $product->tags,
            'relatedProducts' => $relatedProducts
        ]);
    }

    public function getColorBySize(Request $request)
    {
        $colors = $this->webService->getColorsBySize($request);
        return response()->json([
            'colors' => $colors
        ]);
    }

    // Filters available colors based on the selected size. For single product page.
    public function getAvailableColors(Request $request)
    {
        return $this->webService->singleProductGetColorBySize($request);
    }

    public function getSignleProductVariantBySizeId(Request $request)
    {
        return $this->webService->singleProductGetVariantDetails($request);
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
