<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index(Request $request)
    {
        // Summary Widget Statistics
        $totalCount    = ProductReview::count();
        $pendingCount  = ProductReview::where('status', 0)->count();
        $approvedCount = ProductReview::where('status', 1)->count();
        $rejectedCount = ProductReview::where('status', 2)->count();
        $avgRating     = number_format(ProductReview::avg('rating') ?? 0, 1);

        // Query with Eager Loading (Fixes N+1 problem)
        $query = ProductReview::with(['product', 'user']);

        // Status Filter
        if ($request->has('status') && $request->status !== 'all' && $request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Rating Filter
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest('id')->paginate(15)->withQueryString();

        // Auto-fix: If page is out of bounds due to filter change, redirect to page 1
        if ($reviews->currentPage() > $reviews->lastPage() && $reviews->lastPage() > 0) {
            return redirect()->to($request->fullUrlWithQuery(['page' => 1]));
        }

        return view('dashboard.product-review.index', compact(
            'reviews',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'avgRating'
        ));
    }

    public function update(Request $request, $id = null)
    {
        $targetId = $id ?? $request->comment_id;

        $request->validate([
            'status' => 'required|in:0,1,2',
        ]);

        $review = ProductReview::findOrFail($targetId);
        $review->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Review status updated successfully.');
    }

    public function destroy($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
