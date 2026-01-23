<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Repositories\CategoryRepository;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }
    public function index()
    {
        $categories = Category::latest('id')->get();
        return view('dashboard.category.index', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        $Category = $this->categoryRepo->storeByRequest($request);
        if ($Category) {
            return redirect()->route('category.index')->with('success', 'Category created successfully');
        }else {
            return redirect()->back()->with('error', 'Failed to create category');
        }
    }

    public function edit(Category $category)
    {
        return view('dashboard.category.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category = $this->categoryRepo->updateByRequest($request, $category);
        if ($category) {
            return redirect()->route('category.index')->with('success', 'Category updated successfully');
        }else {
            return redirect()->back()->with('error', 'Failed to update category');
        }
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category deleted successfully');
    }

    public function categoryApi()
    {
        $categories = Category::get(['id', 'name'])->toArray();
        return response()->json($categories);
    }
}
