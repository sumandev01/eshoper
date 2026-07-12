<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\ProductTag;
use App\Models\Tag;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function index()
    {
        $products = Product::latest('id')->get();
        $categories = Category::latest('id')->get();
        $subCategories = Category::latest('id')->get();
        $brands = Brand::latest('id')->get();
        $tags = Tag::latest('id')->get();
        $media = Media::latest('id')->get();
        return view('dashboard.product.index', compact('products', 'categories', 'brands', 'tags', 'media'));
    }

    public function add()
    {
        $categories = Category::latest('id')->get();
        $brands = Brand::latest('id')->get();
        $tags = Tag::latest('id')->get();
        $sizes = \App\Models\Size::orderBy('name', 'desc')->get();
        $colors = \App\Models\Color::orderBy('name', 'asc')->get();
        return view('dashboard.product.add', compact('categories', 'brands', 'tags', 'sizes', 'colors'));
    }

    public function store(ProductRequest $request, ProductRepository $productRepository)
    {
        $product = $productRepository->storeByRequest($request);
        if ($product) {
            return redirect()->route('admin.product.index')->with('success', 'Product created successfully.');
        }
        return redirect()->back()->with('error', 'Failed to create product. Please try again.');

        // Validation and storing logic goes here
    }

    public function view(Product $product)
    {
        $product = Product::get()->where('id', $product->id)->first();
        $categories = Category::latest('id')->get();
        $subCategories = Category::latest('id')->get();
        $brands = Brand::latest('id')->get();
        $tags = Tag::latest('id')->get();
        $media = Media::latest('id')->get();
        $product_details = ProductDetails::latest('id')->get();
        return view('dashboard.product.view', compact('product', 'categories', 'brands', 'tags', 'media', 'product_details'));
    }

    public function edit(Product $product)
    {
        $product->load(['galleries', 'inventories.media']);
        $categories = Category::latest('id')->get();
        $subCategories = Category::latest('id')->get();
        $brands = Brand::latest('id')->get();
        $tags = Tag::latest('id')->get();
        $media = Media::latest('id')->get();
        $sizes = \App\Models\Size::orderBy('name', 'desc')->get();
        $colors = \App\Models\Color::orderBy('name', 'asc')->get();
        
        $existingVariants = $product->inventories->map(function ($inv) {
            return [
                'size_id' => $inv->size_id,
                'color_id' => $inv->color_id,
                'stock' => $inv->stock,
                'price' => $inv->price,
                'discount' => $inv->discount,
                'use_main_price' => $inv->use_main_price,
                'use_main_discount' => $inv->use_main_discount,
                'media_id' => $inv->media_id,
                'image' => $inv->media ? \Illuminate\Support\Facades\Storage::url($inv->media->src) : null,
            ];
        })->values()->toArray();

        return view('dashboard.product.edit', compact('product', 'categories', 'brands', 'tags', 'media', 'sizes', 'colors', 'existingVariants'));
    }

    public function update(ProductRequest $request, Product $product, ProductRepository $productRepository)
    {

        $updated = $productRepository->updateByRequest($request, $product);
        if ($updated) {
            return redirect()->route('admin.product.index')->with('success', 'Product updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update product. Please try again.');
    }

    public function updateTrendy(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->is_trending = $request->is_trending === 'true' ? 1 : 0;
        $product->save();

        return response()->json([
            'success' => true,
            //'is_trending' => $product->is_trending,
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        if ($product) {
            return redirect()->back()->with('success', 'Product deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete product. Please try again.');
    }
}
