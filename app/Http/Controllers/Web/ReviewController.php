<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\ProductReviewReply;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);
        $userId = auth('web')->id();
        $userName = auth('web')->user()->name;
        try {
            ProductReview::create([
                'user_id' => $userId,
                'name' => $userName,
                'product_id' => $request->input('product_id'),
                'rating' => $request->input('rating'),
                'review_text' => $request->input('review'),
                'status' => 0, // Assuming 1 means approved, 0 means pending review
            ]);

            return redirect()->route('user.orderProducts')->with('success', 'Review submitted for approval.');
        } catch (\Exception $e) {
            return redirect()->route('user.orderProducts')->with('error', 'Failed to submit review: ' . $e->getMessage());
        }
    }

    public function reply(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|integer|exists:product_reviews,id',
            'reply_text' => 'required|string|max:1000',
        ]);

        $userId = auth('web')->id();
        $productReviewId = $request->input('comment_id');
        $replyReview = $request->input('reply_text');

        try {
            $replyReview = ProductReviewReply::create([
                'user_id' => $userId,
                'product_review_id' => $productReviewId,
                'reply_text' => $replyReview,
                'status' => 1,
            ]);

            return redirect()->back()->with('success', 'Successfully submitted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to submit reply: ' . $e->getMessage());
        }
    }
}
