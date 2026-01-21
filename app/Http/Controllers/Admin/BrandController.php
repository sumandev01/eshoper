<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Repositories\BrandRepository;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected $brandRepo;

    public function __construct(BrandRepository $brandRepo) {
        $this->brandRepo = $brandRepo;
    }
    public function index() {
        $brands = Brand::latest('id')->get();
        $categories = Category::latest('id')->get();
        $sub_categories = SubCategory::latest('id')->get();
        return view('dashboard.brand.index', compact('brands', 'categories'));
    }

    public function store(BrandRequest $request) {
        $brands = $this->brandRepo->storeByRequest($request);
        if ($brands) {
            return redirect()->route('brand.index')->with('success', 'Brand created successfully');
        }else {
            return redirect()->back()->with('error', 'Failed to create brand');
        }
    }

    public function edit(Brand $brand) {
        
        $categories = Category::latest('id')->get();
        $sub_categories = SubCategory::latest('id')->get();
        return view('dashboard.brand.edit', compact('brand', 'categories', 'sub_categories'));
    }

    public function update(BrandRequest $request, Brand $brand) {
        $brands = $this->brandRepo->updateByRequest($request, $brand);
        if ($brands) {
            return redirect()->route('brand.index')->with('success', 'Brand updated successfully');
        }else {
            return redirect()->back()->with('error', 'Failed to update brand');
        }
    }

    public function destroy(Brand $brand) {
        $brand->delete();
        return redirect()->route('brand.index')->with('success', 'Brand deleted successfully');
    }
}
