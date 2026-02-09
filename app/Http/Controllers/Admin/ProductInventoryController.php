<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductInventoryRequest;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        try {
            return DB::transaction(function () use ($request) {

                $useMainPrice = $request->use_main_price === 'on' ? 1 : 0;
                $useMainDiscount = $request->use_main_discount === 'on' ? 1 : 0;

                $finalPrice = ($useMainPrice === 1) ? null : $request->price;
                $finalDiscount = ($useMainDiscount === 1) ? null : $request->discount;

                $dataToUpdate = [
                    'media_id'          => $request->media_id,
                    'price'             => $finalPrice,
                    'discount'          => $finalDiscount,
                    'use_main_price'    => $useMainPrice,
                    'use_main_discount' => $useMainDiscount,
                ];

                $productInventory = ProductInventory::updateOrCreate(
                    [
                        'product_id' => $request->product_id,
                        'size_id'    => $request->size_id,
                        'color_id'   => $request->color_id,
                    ],
                    $dataToUpdate
                );

                $stockValue = $request->stock ?? 0;
                if (!$productInventory->wasRecentlyCreated) {
                    $productInventory->increment('stock', $stockValue);
                } else {
                    $productInventory->stock = $stockValue;
                    $productInventory->save();
                }

                $totalStock = ProductInventory::where('product_id', $request->product_id)->sum('stock');
                Product::where('id', $request->product_id)->update(['stock' => $totalStock]);

                return redirect()->back()->with('success', 'Product inventory added successfully.');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(ProductInventoryRequest $request, ProductInventory $inventory)
    {
        if ($inventory->product_id == $request->product_id) {
            $product = Product::findOrFail($request->product_id);

            $useMainPrice = $request->has('use_main_price') ? 1 : 0;
            $useMainDiscount = $request->has('use_main_discount') ? 1 : 0;

            $finalPrice = ($useMainPrice === 1) ? null : $request->price;
            $finalDiscount = ($useMainDiscount === 1) ? null : $request->discount;

            $inventory->update([
                'price' => $finalPrice,
                'discount' => $finalDiscount,
                'use_main_price' => $useMainPrice,
                'use_main_discount' => $useMainDiscount,
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
