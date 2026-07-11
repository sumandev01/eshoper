<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::latest()->get();
        return view('dashboard.blog-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
            'status' => 'required|boolean',
        ]);

        BlogCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.blog_categories.index')->with('success', 'Blog Category created successfully');
    }

    public function update(Request $request, BlogCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $category->id,
            'status' => 'required|boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.blog_categories.index')->with('success', 'Blog Category updated successfully');
    }

    public function destroy(BlogCategory $category)
    {
        if ($category->blogs()->count() > 0) {
            return redirect()->route('admin.blog_categories.index')->with('error', 'Cannot delete category with associated blogs.');
        }
        $category->delete();
        return redirect()->route('admin.blog_categories.index')->with('success', 'Blog Category deleted successfully');
    }
}
