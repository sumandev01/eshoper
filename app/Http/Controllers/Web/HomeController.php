<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\StoreFeature;
use App\Models\Blog;
use App\Models\ProductReview;
use App\Services\ProductWebService;

class HomeController extends Controller
{
    private $webService;

    public function __construct(ProductWebService $webService)
    {
        $this->webService = $webService;
    }

    public function root()
    {
        $products = Product::active()->withListingDefaults()->latest('id')->take(20)->get();
        $latestProducts = $products->take(8);
        $trendingProducts = $this->webService->getTrendingProducts();
        $categories = Category::has('products')->latest('id')->withCount('products')->get();
        $brands = Brand::with('media')->latest('id')->get();
        $storeFeatures = StoreFeature::orderBy('order')->get();
        $offer1 = Banner::where('position', 1)->where('status', 1)->first();
        $offer2 = Banner::where('position', 2)->where('status', 1)->first();
        
        $blogs = Blog::where('status', 1)->with('category', 'media')->latest('id')->take(6)->get();
        $reviews = ProductReview::with(['user', 'product.media'])->where('status', 1)->where('rating', '>=', 4)->latest('id')->take(6)->get();
        
        return view('web.index', compact('products', 'latestProducts', 'trendingProducts', 'categories', 'brands', 'storeFeatures', 'offer1', 'offer2', 'blogs', 'reviews'));
    }
}
