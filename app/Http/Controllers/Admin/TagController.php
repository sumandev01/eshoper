<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::latest('id')->get();
        return view('dashboard.tag.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:100|unique:tags,name',
            ],
            [
                'name.required' => 'Name is required',
                'name.string' => 'Name must be a string',
                'name.max' => 'Name must not exceed 100 characters',
                'name.unique' => 'Name already exists',
            ]
        );
        $tags = Tag::create([
            'name' => $request->name
        ]);
        if ($tags) {
            return redirect()->route('tag.index')->with('success', 'Tag created successfully');
        }else {
            return redirect()->back()->with('error', 'Failed to create tag');
        }
    }

    public function edit(Tag $tag)
    {
        return response()->json($tag);
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate(
            [
                'name' => 'required|string|max:100|unique:tags,name,' . $tag->id,
            ],
            [
                'name.required' => 'Name is required',
                'name.string' => 'Name must be a string',
                'name.max' => 'Name must not exceed 100 characters',
                'name.unique' => 'Name already exists',
            ]
        );
        $tag->update([
            'name' => $request->name
        ]);
        if ($tag) {
            return redirect()->route('tag.index')->with('success', 'Tag updated successfully');
        }else {
            return json_encode(['error' => 'Failed to update tag']);
        }
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tag.index')->with('success', 'Tag deleted successfully');
    }
}
