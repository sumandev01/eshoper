<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $reviews = ProductReview::latest('id')->paginate(10);
        return view('dashboard.comment.index', compact('reviews'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|exists:product_reviews,id',
            'status' => 'required|in:0,1,2',
        ]);

        ProductReview::whereId($request->comment_id)->update(['status' => $request->status]);

        return redirect()->route('admin.comment.index')->with('success', 'Updated successfully.');
    }

    public function destroy()
    {
        // Logic to delete a comment
    }
}
