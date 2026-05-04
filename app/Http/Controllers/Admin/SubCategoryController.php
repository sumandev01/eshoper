<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use App\Repositories\SubCategoryRepository;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    protected $SubCategoryRepo;

    public function __construct(SubCategoryRepository $SubCategoryRepo)
    {
        $this->SubCategoryRepo = $SubCategoryRepo;
    }
    public function index()
    {
        $categories = Category::latest('id')->get();
        $sub_categories = SubCategory::latest('id')->get();
        return view('dashboard.sub-category.index', compact('categories', 'sub_categories'));
    }

    public function store(SubCategoryRequest $request)
    {
        $subCategory = $this->SubCategoryRepo->storeByRequest($request);
        if ($subCategory) {
            return redirect()->route('sub-category.index')->with('success', 'SubCategory created successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to create SubCategory');
        }
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::latest('id')->get();
        return view('dashboard.sub-category.edit', compact('subCategory', 'categories'));
    }

    public function update(SubCategoryRequest $request, SubCategory $subCategory)
    {
        $subCategory = $this->SubCategoryRepo->updateByRequest($request, $subCategory);
        if ($subCategory) {
            return redirect()->route('sub-category.index')->with('success', 'SubCategory updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update SubCategory');
        }
    }

    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();
        return redirect()->route('sub-category.index')->with('success', 'SubCategory deleted successfully');
    }

    public function subCategoryApi()
    {
        $subCategories = SubCategory::get(['id', 'name', 'category_id']);
        return response()->json($subCategories);
    }
}
