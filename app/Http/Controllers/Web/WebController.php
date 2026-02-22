<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\Size;
use App\Models\SubCategory;
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
        $products = $filterService->filter($request);

        if ($request->ajax()) {
            return view('web.layouts.partial.product_list', compact('products'))->render();
        }

        $allProductIds = Product::where('status', 1)->pluck('id');
        $shopSidebarData = $filterService->shopSidebar();
        [$minPrice, $maxPrice] = $filterService->getPriceRange($allProductIds);

        return view('web.products', compact('products', 'minPrice', 'maxPrice'), $shopSidebarData);
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

    public function contact()
    {
        return view('web.contact');
    }

    public function about()
    {
        return view('web.about');
    }

    // Search suggestions for header search input
public function searchSuggestions(Request $request)
{
    if (!$request->filled('search')) {
        return response()->json([]);
    }

    $products = Product::where('status', 1)
        ->where('name', 'like', '%' . $request->search . '%')
        ->with('media')
        ->select('id', 'name', 'slug', 'price', 'discount', 'media_id')
        ->limit(6)
        ->get()
        ->map(function ($product) {
            return [
                'id'        => $product->id,
                'name'      => $product->name,
                'slug'      => $product->slug,
                'price'     => $product->price,
                'discount'  => $product->discount,
                'thumbnail' => $product->thumbnail,
            ];
        });

    return response()->json($products);
}

    // Category
    public function categoryProducts($slug, ProductFilterService $filterService)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = $filterService->CategoryFilter(request(), $category);

        if (request()->ajax()) {
            return view('web.layouts.partial.product_list', compact('products'))->render();
        }
        // Get all product IDs for the category to fetch sidebar data and price range
        $allProductIds = Product::where('status', 1)
            ->whereHas('details', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            })->pluck('id');

        $sidebarData = $filterService->categorySidebar($allProductIds);
        [$minPrice, $maxPrice] = $filterService->getPriceRange($allProductIds);

        return view('web.category-products', compact('products', 'category', 'minPrice', 'maxPrice'), $sidebarData);
    }

    // Subcategory
    public function subcategoryProducts($slug, ProductFilterService $filterService)
    {
        $subcategory = SubCategory::where('slug', $slug)->firstOrFail();
        $products = $filterService->CategoryFilter(request(), null, $subcategory);

        if (request()->ajax()) {
            return view('web.layouts.partial.product_list', compact('products'))->render();
        }
        // Get all product IDs for the subcategory to fetch sidebar data and price range
        $allProductIds = Product::where('status', 1)
            ->whereHas('details', function ($q) use ($subcategory) {
                $q->where('sub_category_id', $subcategory->id);
            })->pluck('id');

        $sidebarData = $filterService->categorySidebar($allProductIds);
        [$minPrice, $maxPrice] = $filterService->getPriceRange($allProductIds);

        return view('web.subcategory-products', compact('products', 'subcategory', 'minPrice', 'maxPrice'), $sidebarData);
    }
}
