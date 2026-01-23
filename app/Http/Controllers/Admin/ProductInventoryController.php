<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductInventoryRequest;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductInventoryController extends Controller
{
    public function index($product)
    {
        $inventories = ProductInventory::latest('id')->where('product_id', $product)->get();
        $product = Product::get()->where('id', $product)->first();
        $sizes = Size::latest('id')->get();
        $colors = Color::latest('id')->get();
        return view('dashboard.product.inventory.index', compact('product', 'sizes', 'colors', 'inventories'));
    }

    public function store(ProductInventoryRequest $request)
    {
        $dataToUpdate = [
            'media_id' => $request->media_id,
        ];
        
        if($request->filled('price')){
            $dataToUpdate['price'] = $request->price;
        };

        $productInventory = ProductInventory::updateOrCreate(
            [
                'product_id' => $request->product_id,
                'size_id' => $request->size_id,
                'color_id' => $request->color_id,
            ],
            $dataToUpdate
        );

        $stockValue = $request->stock ?? 0;

        if(!$productInventory->wasRecentlyCreated){
            // If the record already exists, increment the stock
            $productInventory->increment('stock', $stockValue);
        } else {
            // If it's a new record, set the stock value
            $productInventory->stock = $stockValue;
            if(!$request->filled('price')){
                $productInventory->price = 0;
            }
            $productInventory->save();
        }

        if ($productInventory) {
            return redirect()->back()->with('success', 'Product inventory added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add product inventory.');
        }
    }
}
