<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BlogComment;
use App\Models\Setting;

class BlogCommentController extends Controller
{
    public function index()
    {
        $comments = BlogComment::with(['blog', 'user'])->latest()->paginate(20);
        return view('dashboard.blog-comments.index', compact('comments'));
    }

    public function status(Request $request, $id)
    {
        $comment = BlogComment::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:0,1,2'
        ]);

        $comment->update(['status' => $request->status]);

        return back()->with('success', 'Comment status updated successfully.');
    }

    public function destroy($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'blog_comment_auto_approve' => 'nullable|boolean'
        ]);

        Setting::updateOrCreate(
            ['key_name' => 'blog_comment_auto_approve'],
            ['key_value' => $request->has('blog_comment_auto_approve') ? '1' : '0']
        );

        return back()->with('success', 'Auto approve setting updated successfully.');
    }
}
