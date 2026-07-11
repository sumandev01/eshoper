<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with('category')->where('status', 1);

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $blogs = $query->latest()->paginate(9);

        $categories = \App\Models\BlogCategory::withCount(['blogs' => function($q) {
            $q->where('status', 1);
        }])->where('status', 1)->get();

        $recentBlogs = Blog::where('status', 1)->latest()->take(5)->get();

        return view('web.blog.index', compact('blogs', 'categories', 'recentBlogs'));
    }


    public function show($slug)
    {
        $blog = Blog::with('category')->where('slug', $slug)->where('status', 1)->firstOrFail();
        
        // Increment views
        $blog->increment('views');

        // Fetch recent blogs
        $recentBlogs = Blog::where('status', 1)->where('id', '!=', $blog->id)->latest()->take(5)->get();

        $categories = \App\Models\BlogCategory::withCount(['blogs' => function($q) {
            $q->where('status', 1);
        }])->where('status', 1)->get();

        $comments = \App\Models\BlogComment::where('blog_id', $blog->id)
            ->where('status', 1)
            ->whereNull('parent_id')
            ->with(['replies' => function($q) {
                $q->where('status', 1)->latest();
            }])
            ->latest()
            ->get();

        return view('web.blog.show', compact('blog', 'recentBlogs', 'categories', 'comments'));
    }

    public function storeComment(Request $request, $blogId)
    {
        $rules = [
            'comment' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:blog_comments,id'
        ];

        if (!auth()->check()) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        $request->validate($rules);

        $blog = Blog::findOrFail($blogId);
        
        $autoApprove = \App\Models\Setting::where('key_name', 'blog_comment_auto_approve')->value('key_value') == '1';

        \App\Models\BlogComment::create([
            'blog_id' => $blog->id,
            'user_id' => auth()->check() ? auth()->id() : null,
            'name' => auth()->check() ? auth()->user()->name : $request->name,
            'email' => auth()->check() ? auth()->user()->email : $request->email,
            'comment' => $request->comment,
            'parent_id' => $request->parent_id,
            'status' => $autoApprove ? 1 : 0
        ]);

        if ($autoApprove) {
            return back()->with('success', 'Your comment has been published.');
        } else {
            return back()->with('success', 'Your comment has been submitted and is waiting for approval.');
        }
    }
}
