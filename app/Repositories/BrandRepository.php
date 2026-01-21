<?php

namespace App\Repositories;

use App\Models\Brand;

class BrandRepository
{
    /**
     * Fetch all data.
     */
    public function all()
    {
        // Logic goes here
    }

    /**
     * Find data by ID.
     */
    public function find($id)
    {
        // Logic goes here
    }

    public function storeByRequest($request)
    {
        $brand = Brand::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'media_id' => $request->media_id,
        ]);
        return $brand;
    }

    public function updateByRequest($request, $brand)
    {
        $brand->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'media_id' => $request->media_id,
        ]);
        return $brand;
    }
}
