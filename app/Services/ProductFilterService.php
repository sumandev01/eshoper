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
            $min = str_replace(['৳', '$', ','], '', $request->min_price);
            $max = str_replace(['৳', '$', ','], '', $request->max_price);
            $productsQuery->whereBetween('price', [$min, $max]);
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
                $q->where('category_id', $request->categories);
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
}
