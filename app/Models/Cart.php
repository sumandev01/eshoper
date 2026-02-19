<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productDetails()
    {
        return $this->belongsTo(ProductDetails::class);
    }

    public function productInventory()
    {
        return $this->belongsTo(ProductInventory::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function getCartImageAttribute()
    {
        if ($this->product_inventory_id) {
            $inventoryMedia = $this->productInventory->media;

            if ($inventoryMedia) {
                return $inventoryMedia->src;
            }
        }

        $productMedia = $this->product->media ?? null;

        if ($productMedia) {
            return $productMedia->src;
        }
    }

    public function getCartPriceAttribute()
    {
        if ($this->product_inventory_id) {
            $inventory = $this->productInventory;

            $basePrice = ($inventory->use_main_price == 1 || $inventory->price === null) ? (float)$this->product->price : (float)$inventory->price;

            if ($inventory->use_main_discount == 1) {
                $discountPrice = (float)($this->product->discount ?? 0);
            } else {
                $discountPrice = (float)($inventory->discount ?? 0);
            }

            return ($discountPrice > 0 && $discountPrice < $basePrice) ? $discountPrice : $basePrice;
        }

        $product = $this->product;
        if ($product) {
            return ($product->discount > 0 && $product->discount < $product->price) ? (float)$product->discount : (float)$product->price;
        }

        return 0;
    }

    public function getProductStockAttribute()
    {
        //dd($this->product_inventory_id, $this->productInventory, $this->productInventory?->quantity);
        if ($this->product_inventory_id) {
            $productStock = $this->productInventory->stock;
        } else {
            $productStock = $this->product->stock;
        }

        return $productStock;
    }
}
