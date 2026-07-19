<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Facades\DB;

class ProductFilterService
{
    /**
     * Helper to get the effective price SQL expression (considering discounts).
     */
    protected function getEffectivePriceSql($table = 'products')
    {
        return "(CASE 
            WHEN {$table}.discount IS NOT NULL AND {$table}.discount > 0 AND {$table}.discount < {$table}.price 
            THEN {$table}.discount 
            ELSE {$table}.price 
        END)";
    }

    /**
     * Effective price SQL for inventory rows.
     * Correctly resolves use_main_price and use_main_discount flags.
     * Requires 'products as products_p' to be joined in the query.
     */
    protected function getInventoryEffectivePriceSql()
    {
        // Step 1: resolve the actual price (own or main)
        $resolvedPrice    = "(CASE WHEN product_inventories.use_main_price = 1 THEN products_p.price ELSE product_inventories.price END)";
        // Step 2: resolve the actual discount (own or main)
        $resolvedDiscount = "(CASE WHEN product_inventories.use_main_discount = 1 THEN products_p.discount ELSE product_inventories.discount END)";

        // Step 3: if resolved discount is valid (> 0 and < price), use discount; else use price
        return "(CASE 
            WHEN {$resolvedDiscount} IS NOT NULL AND {$resolvedDiscount} > 0 AND {$resolvedDiscount} < {$resolvedPrice}
            THEN {$resolvedDiscount}
            ELSE {$resolvedPrice}
        END)";
    }

    /**
     * Unified filter method for all product listing pages.
     */
    public function filter($request, $category = null, $subcategory = null)
    {
        $productsQuery = Product::withListingDefaults()
            ->active();

        // Apply Category/Subcategory context
        if ($category || $subcategory) {
            $productsQuery->whereHas('details', function ($q) use ($category, $subcategory) {
                if ($subcategory) {
                    $q->where('sub_category_id', $subcategory->id);
                } else {
                    $q->where('category_id', $category->id);
                }
            });
        }

        // Price Filter
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $min = (float) preg_replace('/[^\d.]/', '', $request->min_price);
            $max = (float) preg_replace('/[^\d.]/', '', $request->max_price);

            $productsQuery->where(function ($query) use ($min, $max) {
                // Case 1: Products WITHOUT variants → filter by main product price
                $query->where(function ($q) use ($min, $max) {
                    $q->doesntHave('inventories')
                      ->whereRaw("{$this->getEffectivePriceSql()} BETWEEN ? AND ?", [$min, $max]);
                })
                // Case 2: Products WITH variants → filter by inventory effective price
                // (respects use_main_price & use_main_discount flags)
                ->orWhereHas('inventories', function ($q) use ($min, $max) {
                    $q->join('products as products_p', 'products_p.id', '=', 'product_inventories.product_id')
                      ->whereRaw($this->getInventoryEffectivePriceSql() . " BETWEEN ? AND ?", [$min, $max]);
                });
            });
        }

        $hasColors = $request->filled('colors');
        $hasSizes = $request->filled('sizes');

        if ($hasColors && $hasSizes) {
            // Strict matching: Product must have BOTH requested size and color in the SAME inventory variant.
            $productsQuery->whereHas('inventories', function($q) use ($request) {
                $q->whereIn('color_id', (array) $request->colors)
                  ->whereIn('size_id', (array) $request->sizes);
            });
        } elseif ($hasColors) {
            $productsQuery->whereHas('colors', fn($q) => $q->whereIn('color_id', (array) $request->colors));
        } elseif ($hasSizes) {
            $productsQuery->whereHas('sizes', fn($q) => $q->whereIn('size_id', (array) $request->sizes));
        }

        // Dynamic Category filter (from sidebar checkboxes)
        if ($request->filled('categories')) {
            $productsQuery->whereHas('details', fn($q) => $q->whereIn('category_id', (array) $request->categories));
        }

        // Brand filter
        if ($request->filled('brands')) {
            $productsQuery->whereHas('details', fn($q) => $q->whereIn('brand_id', (array) $request->brands));
        }

        // Search filter
        if ($request->filled('search')) {
            $productsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $this->applySorting($productsQuery, $request);

        return $productsQuery->paginate(12)->withQueryString();
    }

    /**
     * Handles sorting based on effective price or recency.
     */
    public function applySorting($productsQuery, $request)
    {
        $sort = $request->get('sort');
        $priceSql = $this->getEffectivePriceSql();

        if ($sort === 'price_low') {
            $productsQuery->orderByRaw("$priceSql ASC");
        } elseif ($sort === 'price_high') {
            $productsQuery->orderByRaw("$priceSql DESC");
        } else {
            $productsQuery->latest('id');
        }
    }

    /**
     * Gets the min and max prices for the current context (category or global).
     * Now uses pure SQL for maximum performance.
     */
    public function getPriceRange($category = null, $subcategory = null)
    {
        $query = DB::table('products')->where('status', 1);
        
        if ($category || $subcategory) {
            $query->join('product_details', 'products.id', '=', 'product_details.product_id');
            if ($subcategory) {
                $query->where('product_details.sub_category_id', $subcategory->id);
            } else {
                $query->where('product_details.category_id', $category->id);
            }
        }

        $priceExpr = $this->getEffectivePriceSql('products');
        $stats = $query->selectRaw("MIN($priceExpr) as min_p, MAX($priceExpr) as max_p")->first();

        return [(float)($stats->min_p ?? 0), (float)($stats->max_p ?? 0)];
    }

    /**
     * Generates sidebar counts for colors, sizes, and categories.
     */
    public function shopSidebar($category = null, $subcategory = null)
    {
        // Define a base subquery for active products to avoid loading IDs
        $activeProductsSubquery = function($query) use ($category, $subcategory) {
            $query->select('id')->from('products')->where('status', 1);
            if ($category || $subcategory) {
                $query->whereIn('id', function($q) use ($category, $subcategory) {
                    $q->select('product_id')->from('product_details');
                    if ($subcategory) {
                        $q->where('sub_category_id', $subcategory->id);
                    } else {
                        $q->where('category_id', $category->id);
                    }
                });
            }
        };

        // Fetch colors with counts
        $colorQuery = Color::whereHas('colors', fn($q) => $q->whereIn('product_id', $activeProductsSubquery))
            ->withCount(['colors' => fn($q) => $q->whereIn('product_id', $activeProductsSubquery)])
            ->latest('id')->get();

        // Fetch sizes with counts
        $sizeQuery = Size::whereHas('sizes', fn($q) => $q->whereIn('product_id', $activeProductsSubquery))
            ->withCount(['sizes' => fn($q) => $q->whereIn('product_id', $activeProductsSubquery)])
            ->latest('id')->get();

        // Fetch categories with counts
        $categoryQuery = Category::whereHas('categories', fn($q) => $q->where('status', 1))
            ->withCount(['categories' => fn($q) => $q->where('status', 1)])
            ->orderByDesc('categories_count')->get();

        // Fetch brands
        $brandQuery = Brand::whereIn('id', function($q) use ($activeProductsSubquery) {
            $q->select('brand_id')->from('product_details')
              ->whereIn('product_id', $activeProductsSubquery)
              ->whereNotNull('brand_id');
        })->latest('id')->get();

        return compact('colorQuery', 'sizeQuery', 'categoryQuery', 'brandQuery');
    }
}
