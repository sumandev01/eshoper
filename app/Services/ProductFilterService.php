<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Facades\DB;

class ProductFilterService
{
    public function filter($request)
    {
        // Initial product query with active status
        $productsQuery = Product::where('status', 1);
        // Price filter (Remove $ or commas from input before query)
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $min = (float) str_replace(['৳', '$', ','], '', $request->min_price);
            $max = (float) str_replace(['৳', '$', ','], '', $request->max_price);

            $productsQuery->whereRaw("
                (
                    CASE 
                        WHEN discount IS NOT NULL 
                            AND discount > 0 
                            AND discount < price
                        THEN discount
                        ELSE price
                    END
                ) BETWEEN ? AND ?
            ", [$min, $max]);
        }


        // Color filter (Support multiple colors using whereIn)
        if ($request->filled('colors')) {
            $productsQuery->whereHas('colors', function ($q) use ($request) {
                $q->whereIn('color_id', (array) $request->colors);
            });
        }

        // Size filter (Support multiple sizes using whereIn)
        if ($request->filled('sizes')) {
            $productsQuery->whereHas('sizes', function ($q) use ($request) {
                $q->whereIn('size_id', (array) $request->sizes);
            });
        }

        // Category filter
        if ($request->filled('categories')) {
            $productsQuery->whereHas('categories', function ($q) use ($request) {
                $q->whereIn('category_id', (array) $request->categories);
            });
        }

        // Search filter by product name
        if ($request->filled('search')) {
            $productsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        // Sorting logic (Latest, Price Low to High, Price High to Low)
        $this->applySorting($productsQuery, $request);

        // Get final results with pagination
        return $productsQuery->paginate(9)->withQueryString();
    }

    public function applySorting($productsQuery, $request)
    {
        if ($request->filled('sort')) {
            if ($request->sort == 'price_low') {
                $productsQuery->orderBy('price', 'asc');
            } elseif ($request->sort == 'price_high') {
                $productsQuery->orderBy('price', 'desc');
            } else {
                $productsQuery->latest('id');
            }
        } else {
            // Default sorting
            $productsQuery->latest('id');
        }
    }

    public function shopSidebar()
    {
        // Fetch colors with product count
        $colorQuery = Color::whereHas('colors', function ($query) {
            $query->where('status', 1);
        })->withCount(['colors' => function ($query) {
            $query->where('status', 1)->select(DB::raw('COUNT(DISTINCT product_id)'));
        }])->latest('id')->get();

        // Fetch sizes with product count
        $sizeQuery = Size::whereHas('sizes', function ($query) {
            $query->where('status', 1);
        })->withCount(['sizes' => function ($query) {
            $query->where('status', 1)->select(DB::raw('COUNT(DISTINCT product_id)'));
        }])->latest('id')->get();

        // Fetch categories with product count
        $categoryQuery = Category::whereHas('categories', function ($query) {
            $query->where('status', 1);
        })->withCount(['categories' => function ($query) {
            $query->where('status', 1)->distinct();
        }])->orderByDesc('categories_count')->get();

        return compact('colorQuery', 'sizeQuery', 'categoryQuery');
    }

    public function CategoryFilter($request, $category, $subcategory = null)
    {
        $productsQuery = Product::where('status', 1);

        // Filter products by category or subcategory
        if ($subcategory) {
            $productsQuery->whereHas('details', function ($q) use ($subcategory) {
                $q->where('sub_category_id', $subcategory->id);
            });
        } else {
            $productsQuery->whereHas('details', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            });
        }

        // Price filter
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $min = (float) str_replace(['৳', '$', ','], '', $request->min_price);
            $max = (float) str_replace(['৳', '$', ','], '', $request->max_price);
            $productsQuery->whereRaw("
            (CASE 
                WHEN discount IS NOT NULL AND discount > 0 AND discount < price
                THEN discount
                ELSE price
            END) BETWEEN ? AND ?
        ", [$min, $max]);
        }

        // Color filter
        if ($request->filled('colors')) {
            $productsQuery->whereHas('colors', function ($q) use ($request) {
                $q->whereIn('color_id', (array) $request->colors);
            });
        }

        // Size filter
        if ($request->filled('sizes')) {
            $productsQuery->whereHas('sizes', function ($q) use ($request) {
                $q->whereIn('size_id', (array) $request->sizes);
            });
        }

        // Search filter
        if ($request->filled('search')) {
            $productsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $this->applySorting($productsQuery, $request);

        return $productsQuery->paginate(9)->withQueryString();
    }

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

    public function categorySidebar($productIds)
    {
        // Fetch colors and sizes available for the given product IDs
        $colorQuery = Color::whereHas('colors', function ($query) use ($productIds) {
            $query->whereIn('product_inventories.product_id', $productIds);
        })->withCount(['colors' => function ($query) use ($productIds) {
            $query->whereIn('product_inventories.product_id', $productIds);
        }])->latest('id')->get();

        // Fetch sizes available for the given product IDs
        $sizeQuery = Size::whereHas('sizes', function ($query) use ($productIds) {
            $query->whereIn('product_inventories.product_id', $productIds);
        })->withCount(['sizes' => function ($query) use ($productIds) {
            $query->whereIn('product_inventories.product_id', $productIds);
        }])->latest('id')->get();

        return compact('colorQuery', 'sizeQuery');
    }
}
