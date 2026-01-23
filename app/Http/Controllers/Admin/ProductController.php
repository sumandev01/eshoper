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
        return view('dashboard.product.add', compact('categories', 'brands', 'tags'));
    }

    public function store(ProductRequest $request, ProductRepository $productRepository)
    {
        $product = $productRepository->storeByRequest($request);
        if ($product) {
            return redirect()->route('product.index')->with('success', 'Product created successfully.');
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
        $product->load('galleries');
        $categories = Category::latest('id')->get();
        $subCategories = Category::latest('id')->get();
        $brands = Brand::latest('id')->get();
        $tags = Tag::latest('id')->get();
        $media = Media::latest('id')->get();
        return view('dashboard.product.edit', compact('product', 'categories', 'brands', 'tags', 'media'));
    }

    public function update(ProductRequest $request, Product $product, ProductRepository $productRepository)
    {
        
        $updated = $productRepository->updateByRequest($request, $product);
        if ($updated) {
            return redirect()->route('product.index')->with('success', 'Product updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update product. Please try again.');
    }
}
