<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SubCategory;
use App\Services\ProductFilterService;
use App\Services\ProductWebService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    private $webService;

    public function __construct(ProductWebService $webService)
    {
        $this->webService = $webService;
    }

    public function shop(Request $request, ProductFilterService $filterService)
    {
        $products = $filterService->filter($request);

        if ($request->ajax()) {
            return view('web.components.product_list', compact('products'))->render();
        }

        $shopSidebarData = $filterService->shopSidebar();
        [$minPrice, $maxPrice] = $filterService->getPriceRange();

        return view('web.shop.shop', compact('products', 'minPrice', 'maxPrice'), $shopSidebarData);
    }

    public function categories()
    {
        return view('web.shop.categories');
    }

    public function productDetails($slug, \App\Services\RecentlyViewedService $recentlyViewedService)
    {
        $product = Product::with(['sizes', 'colors', 'tags', 'details', 'media', 'inventories.media', 'inventories.color', 'inventories.size'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Add to Recently Viewed
        $recentlyViewedService->add($product->id);

        // Related products by category and random products
        $relatedProducts = $this->webService->getRelatedProductsSinglePage($product);
        $productReview = ProductReview::whereProductId($product->id)->whereStatus(1)->get();

        $totalReviews = $productReview->count();
        $totalRatingSum = $productReview->sum('rating');
        $finalRating = 0;
        if ($totalReviews > 0) {
            $finalRating = round($totalRatingSum / $totalReviews);
        } else {
            $finalRating = 0;
        }

        $inventoriesJson = $product->inventories->map(function ($inventory) use ($product) {
            $imageUrl = null;
            if ($inventory->media) {
                $imageUrl = $inventory->media->medium_url;
            }

            return [
                'id' => $inventory->id,
                'size_id' => $inventory->size_id,
                'color_id' => $inventory->color_id,
                'color_name' => $inventory->color->name ?? 'N/A',
                'color_code' => $inventory->color->color_code ?? '#000',
                'stock' => $inventory->stock,
                'price' => $inventory->use_main_price == 1 ? $product->price : $inventory->price,
                'discount' => $inventory->use_main_discount == 1 ? $product->discount : $inventory->discount,
                'image' => $imageUrl,
            ];
        });

        // Return view
        return view('web.shop.single-product', [
            'product'         => $product,
            'sizes'           => $product->sizes,
            'colors'          => $product->colors,
            'tags'            => $product->tags,
            'relatedProducts' => $relatedProducts,
            'productReview'   => $productReview,
            'finalRating'     => $finalRating,
            'inventoriesJson' => $inventoriesJson
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

    // Search suggestions for header search input
    public function searchSuggestions(Request $request)
    {
        if (!$request->filled('search')) {
            return response()->json([]);
        }

        $products = Product::active()
            ->where('name', 'like', '%' . $request->search . '%')
            ->select('id', 'name', 'slug', 'price', 'discount', 'media_id')
            ->with('media:id,src')
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
        $category = Category::whereSlug($slug)->firstOrFail();
        $products = $filterService->filter(request(), $category);

        if (request()->ajax()) {
            return view('web.components.product_list', compact('products'))->render();
        }

        $sidebarData = $filterService->shopSidebar($category);
        [$minPrice, $maxPrice] = $filterService->getPriceRange($category);

        return view('web.shop.category-products', compact('products', 'category', 'minPrice', 'maxPrice'), $sidebarData);
    }

    // Subcategory
    public function subcategoryProducts($slug, ProductFilterService $filterService)
    {
        $subcategory = SubCategory::whereSlug($slug)->firstOrFail();
        $products = $filterService->filter(request(), null, $subcategory);

        if (request()->ajax()) {
            return view('web.components.product_list', compact('products'))->render();
        }

        // We can reuse category sidebar logic for subcategories if needed, 
        // or just use global sidebar. Here we use the subcategory's parent category if needed.
        $category = $subcategory->category; 
        $sidebarData = $filterService->shopSidebar(null, $subcategory);
        [$minPrice, $maxPrice] = $filterService->getPriceRange(null, $subcategory);

        return view('web.shop.subcategory-products', compact('products', 'subcategory', 'minPrice', 'maxPrice'), $sidebarData);
    }
}
