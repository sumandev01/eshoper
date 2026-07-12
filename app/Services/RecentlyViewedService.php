<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class RecentlyViewedService
{
    protected $sessionKey = 'recently_viewed_products';
    protected $limit = 12;

    /**
     * Add a product ID to the recently viewed history.
     */
    public function add($productId)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            // Sync without detaching to update the pivot timestamp or insert new
            $user->recentlyViewedProducts()->syncWithoutDetaching([
                $productId => ['updated_at' => now(), 'created_at' => now()]
            ]);

            // Optional cleanup: keep only latest $limit items per user
            $count = $user->recentlyViewedProducts()->count();
            if ($count > $this->limit) {
                // Get the oldest items beyond the limit and detach them
                $oldestIds = $user->recentlyViewedProducts()
                    ->orderBy('recently_viewed_products.updated_at', 'desc')
                    ->skip($this->limit)
                    ->take($count - $this->limit)
                    ->pluck('products.id')
                    ->toArray();
                
                if (!empty($oldestIds)) {
                    $user->recentlyViewedProducts()->detach($oldestIds);
                }
            }
        } else {
            $recent = Session::get($this->sessionKey, []);
            
            // Remove the product if it already exists to move it to the top
            $recent = array_filter($recent, function($id) use ($productId) {
                return $id != $productId;
            });
            
            // Add to beginning of array
            array_unshift($recent, $productId);
            
            // Enforce limit
            $recent = array_slice($recent, 0, $this->limit);
            
            Session::put($this->sessionKey, $recent);
        }
    }

    /**
     * Get the recently viewed products.
     * @param array $excludeIds Product IDs to exclude (like current cart/wishlist items)
     */
    public function get($excludeIds = [])
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $query = $user->recentlyViewedProducts();
            
            if (!empty($excludeIds)) {
                $query->whereNotIn('products.id', $excludeIds);
            }
            
            return $query->take($this->limit)->get();
        } else {
            $recentIds = Session::get($this->sessionKey, []);
            
            if (empty($recentIds)) {
                return collect([]);
            }

            if (!empty($excludeIds)) {
                $recentIds = array_diff($recentIds, $excludeIds);
            }

            // Fetch products and maintain the exact order of the session array
            $idsString = implode(',', $recentIds);
            
            if (empty($idsString)) {
                return collect([]);
            }

            return Product::whereIn('id', $recentIds)
                ->orderByRaw("FIELD(id, {$idsString})")
                ->with(['media']) // Load necessary relations for slider
                ->get();
        }
    }

    /**
     * Sync guest session history to user database history upon login.
     */
    public function sync()
    {
        if (!Auth::guard('web')->check()) {
            return;
        }

        $recentIds = Session::get($this->sessionKey, []);
        
        if (!empty($recentIds)) {
            $user = Auth::guard('web')->user();
            
            // We want to add these items. Since they were viewed as a guest,
            // we attach them with current timestamp, maintaining their order by giving them slightly different times.
            $syncData = [];
            $now = now();
            // The array is ordered newest first. We want newest to have the latest timestamp.
            // Reverse so oldest gets added first, newest gets added last (closest to now).
            $reversedIds = array_reverse($recentIds);
            
            foreach ($reversedIds as $index => $id) {
                $syncData[$id] = [
                    'created_at' => $now->copy()->subSeconds(count($reversedIds) - $index),
                    'updated_at' => $now->copy()->subSeconds(count($reversedIds) - $index)
                ];
            }

            $user->recentlyViewedProducts()->syncWithoutDetaching($syncData);

            // Cleanup limit
            $count = $user->recentlyViewedProducts()->count();
            if ($count > $this->limit) {
                $oldestIds = $user->recentlyViewedProducts()
                    ->orderBy('recently_viewed_products.updated_at', 'desc')
                    ->skip($this->limit)
                    ->take($count - $this->limit)
                    ->pluck('products.id')
                    ->toArray();
                
                if (!empty($oldestIds)) {
                    $user->recentlyViewedProducts()->detach($oldestIds);
                }
            }

            // Clear session after successful sync
            Session::forget($this->sessionKey);
        }
    }
}
