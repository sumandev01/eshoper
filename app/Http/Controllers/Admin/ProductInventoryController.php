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
        $inventories = ProductInventory::where('product_id', $product)
            ->join('sizes', 'product_inventories.size_id', '=', 'sizes.id')
            ->select('product_inventories.*')
            ->orderBy('sizes.name', 'desc')
            ->with(['size', 'color', 'media'])
            ->get();
        $product = Product::get()->where('id', $product)->first();
        $sizes = Size::orderBy('name', 'desc')->get();
        $colors = Color::orderBy('name', 'asc')->get();
        return view('dashboard.product.inventory.index', compact('product', 'sizes', 'colors', 'inventories'));
    }

    public function store(ProductInventoryRequest $request)
    {
        $dataToUpdate = [
            'media_id' => $request->media_id,
            'price' => $request->price ?? 0,
        ];

        if ($request->filled('price')) {
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

        if (!$productInventory->wasRecentlyCreated) {
            // If the record already exists, increment the stock
            $productInventory->increment('stock', $stockValue);
        } else {
            // If it's a new record, set the stock value
            $productInventory->stock = $stockValue;

            $productInventory->save();
        }

        $totalStock = ProductInventory::where('product_id', $request->product_id)->sum('stock');
        Product::where('id', $request->product_id)->update(['stock' => $totalStock]);

        if ($productInventory) {
            return redirect()->back()->with('success', 'Product inventory added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add product inventory.');
        }
    }

    public function update(ProductInventoryRequest $request, ProductInventory $inventory)
    {
        if ($inventory->product_id == $request->product_id) {
            $inventory->update([
                'price' => $request->price,
                'stock' => $request->stock,
                'media_id' => $request->media_id,
            ]);
            $totalStock = ProductInventory::where('product_id', $request->product_id)->sum('stock');
            Product::where('id', $request->product_id)->update(['stock' => $totalStock]);

            return redirect()->back()->with('success', 'Product inventory updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update product inventory.');
    }

    public function destroy(ProductInventory $productInventory)
    {
        $productId = $productInventory->product_id;
        $productInventory->delete();

        // Recalculate total stock after deletion
        $totalStock = ProductInventory::where('product_id', $productId)->sum('stock');
        Product::where('id', $productId)->update(['stock' => $totalStock]);

        return redirect()->back()->with('success', 'Product inventory deleted successfully.');
    }
}
