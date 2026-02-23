<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index() {
        $user = auth('web')->user();
        $wishlists = $user->wishlists()->with('product')->get();
        return view('web.wishlist', compact('wishlists'));
    }

    public function wishlistToggle(Request $request)
    {
        if (!auth('web')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login first.'
            ], 401);
        }
        $user = auth('web')->user();
        $productId = $request->product_id;

        if (!$productId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product ID is required.'
            ], 400);
        }
        // Already Wishlisted check
        $wishlist = Wishlist::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
        } else {
            Wishlist::create([
                'user_id'    => $user->id,
                'product_id' => $productId
            ]);
            $status = 'added';
        }

        $wishlistCount = $user->wishlists()->count();

        return response()->json([
            'status'        => 'success',
            'action'        => $status,
            'wishlistCount' => $wishlistCount,
            'message'       => $status === 'added' ? 'Added to wishlist.' : 'Removed from wishlist.'
        ]);
    }

    public function removeFromWishlist($id)
    {
        $wishlistItem = Wishlist::where('id', $id)->first();
        if ($wishlistItem) {
            $wishlistItem->delete();
        }
        return redirect()->route('wishlist')->with('success', 'Item removed from wishlist.');
    }
}
