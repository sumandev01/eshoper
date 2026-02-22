<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Facades\DB;

class ProductFilterService
{
    // =====================
    // Common Filter Logic
    // =====================
    private function applyCommonFilters($query, $request)
    {
        // Price filter
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $min = (float) str_replace(['৳', '$', ','], '', $request->min_price);
            $max = (float) str_replace(['৳', '$', ','], '', $request->max_price);
            $query->whereRaw("
                (CASE 
                    WHEN discount IS NOT NULL AND discount > 0 AND discount < price
                    THEN discount ELSE price
                END) BETWEEN ? AND ?
            ", [$min, $max]);
        }

        // Color filter
        if ($request->filled('colors')) {
            $query->whereHas('colors', function ($q) use ($request) {
                $q->whereIn('color_id', (array) $request->colors);
            });
        }

        // Size filter
        if ($request->filled('sizes')) {
            $query->whereHas('sizes', function ($q) use ($request) {
                $q->whereIn('size_id', (array) $request->sizes);
            });
        }

        // Search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $this->applySorting($query, $request);

        return $query;
    }

    public function applySorting($query, $request)
    {
        match($request->sort) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            default      => $query->latest('id'),
        };
    }

    // =====================
    // Product Page Filter
    // =====================
    public function filter($request)
    {
        $query = Product::where('status', 1);

        // Category filter
        if ($request->filled('categories')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->whereIn('category_id', (array) $request->categories);
            });
        }

        $this->applyCommonFilters($query, $request);

        return $query->paginate(9)->withQueryString();
    }

    // =====================
    // Category / Subcategory Filter
    // =====================
    public function categoryFilter($request, $category = null, $subcategory = null)
    {
        $query = Product::where('status', 1);

        if ($subcategory) {
            $query->whereHas('details', function ($q) use ($subcategory) {
                $q->where('sub_category_id', $subcategory->id);
            });
        } elseif ($category) {
            $query->whereHas('details', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            });
        }

        $this->applyCommonFilters($query, $request);

        return $query->paginate(9)->withQueryString();
    }

    // =====================
    // Sidebar Data
    // =====================
    public function shopSidebar()
    {
        $colorQuery = Color::whereHas('colors', function ($q) {
            $q->where('status', 1);
        })->withCount(['colors' => function ($q) {
            $q->where('status', 1)->select(DB::raw('COUNT(DISTINCT product_id)'));
        }])->latest('id')->get();

        $sizeQuery = Size::whereHas('sizes', function ($q) {
            $q->where('status', 1);
        })->withCount(['sizes' => function ($q) {
            $q->where('status', 1)->select(DB::raw('COUNT(DISTINCT product_id)'));
        }])->latest('id')->get();

        $categoryQuery = Category::whereHas('categories', function ($q) {
            $q->where('status', 1);
        })->withCount(['categories' => function ($q) {
            $q->where('status', 1)->distinct();
        }])->orderByDesc('categories_count')->get();

        return compact('colorQuery', 'sizeQuery', 'categoryQuery');
    }

    public function categorySidebar($productIds)
    {
        $colorQuery = Color::whereHas('colors', function ($q) use ($productIds) {
            $q->whereIn('product_inventories.product_id', $productIds);
        })->withCount(['colors' => function ($q) use ($productIds) {
            $q->whereIn('product_inventories.product_id', $productIds);
        }])->latest('id')->get();

        $sizeQuery = Size::whereHas('sizes', function ($q) use ($productIds) {
            $q->whereIn('product_inventories.product_id', $productIds);
        })->withCount(['sizes' => function ($q) use ($productIds) {
            $q->whereIn('product_inventories.product_id', $productIds);
        }])->latest('id')->get();

        return compact('colorQuery', 'sizeQuery');
    }

    // =====================
    // Price Range
    // =====================
    public function getPriceRange($productIds)
    {
        $productMin = DB::table('products')->whereIn('id', $productIds)
            ->selectRaw('MIN(CASE WHEN discount IS NOT NULL AND discount > 0 AND discount < price THEN discount ELSE price END) as final_price')
            ->value('final_price');

        $productMax = DB::table('products')->whereIn('id', $productIds)
            ->selectRaw('MAX(CASE WHEN discount IS NOT NULL AND discount > 0 AND discount < price THEN discount ELSE price END) as final_price')
            ->value('final_price');

        $inventoryMin = DB::table('product_inventories')->whereIn('product_id', $productIds)
            ->whereNotNull('price')
            ->selectRaw('MIN(CASE WHEN discount IS NOT NULL AND discount > 0 AND discount < price THEN discount ELSE price END) as final_price')
            ->value('final_price');

        $inventoryMax = DB::table('product_inventories')->whereIn('product_id', $productIds)
            ->whereNotNull('price')
            ->selectRaw('MAX(CASE WHEN discount IS NOT NULL AND discount > 0 AND discount < price THEN discount ELSE price END) as final_price')
            ->value('final_price');

        $minPrice = 0;
        $maxPrice = 0;

        if ($productMin !== null && $inventoryMin !== null) {
            $minPrice = min($productMin, $inventoryMin);
            $maxPrice = max($productMax, $inventoryMax);
        } elseif ($productMin !== null) {
            $minPrice = $productMin;
            $maxPrice = $productMax;
        } elseif ($inventoryMin !== null) {
            $minPrice = $inventoryMin;
            $maxPrice = $inventoryMax;
        }

        return [$minPrice, $maxPrice];
    }
}