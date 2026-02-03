<?php

namespace App\Services;

use App\Models\Product;

class ProductFilterService
{
    public function filter($request, $products)
    {
        // 1. Initial product query with active status
        $productsQuery = Product::where('status', 1);
        // 2. Price filter (Remove $ or commas from input before query)
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $min = str_replace(['$', ','], '', $request->min_price);
            $max = str_replace(['$', ','], '', $request->max_price);
            $productsQuery->whereBetween('price', [$min, $max]);
        }

        // 3. Color filter (Support multiple colors using whereIn)
        if ($request->filled('colors')) {
            $productsQuery->whereHas('colors', function ($q) use ($request) {
                $q->whereIn('color_id', (array) $request->colors);
            });
        }

        // 4. Size filter (Support multiple sizes using whereIn)
        if ($request->filled('sizes')) {
            $productsQuery->whereHas('sizes', function ($q) use ($request) {
                $q->whereIn('size_id', (array) $request->sizes);
            });
        }

        // 5. Category filter
        if ($request->filled('category')) {
            $productsQuery->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        // 6. Search filter by product name
        if ($request->filled('search')) {
            $productsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        // 7. Sorting logic (Latest, Price Low to High, Price High to Low)
        $this->applySorting($productsQuery, $request);

        // 9. Get final results with pagination
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
}
