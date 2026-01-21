<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
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

    /**
     * Store data.
     */
    public function storeByRequest($request)
    {
        $category = Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'media_id' => $request->media_id,
        ]);
        return $category;
    }
    public function updateByRequest($request, $category)
    {
        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'media_id' => $request->media_id,
        ]);
        return $category;
    }
}
