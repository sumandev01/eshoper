<?php

namespace App\Repositories;

use App\Models\SubCategory;

class SubCategoryRepository
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
        $subCategory = SubCategory::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'category_id' => $request->category_id,
            'media_id' => $request->media_id,
        ]);
        return $subCategory;
    }

    public function updateByRequest($request, $subCategory)
    {
        $subCategory->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'category_id' => $request->category_id,
            'media_id' => $request->media_id,
        ]);
        return $subCategory;
    }
}
