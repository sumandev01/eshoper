<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::all();
        $activeMenu = null;
        
        if ($request->has('menu')) {
            $activeMenu = Menu::with('items')->findOrFail($request->menu);
        } elseif ($menus->count() > 0) {
            $activeMenu = Menu::with('items')->first();
        }

        $pages = Page::where('status', 1)->get();
        $categories = Category::all();

        return view('dashboard.menus.index', compact('menus', 'activeMenu', 'pages', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|unique:menus,location',
        ]);

        Menu::create($request->only('name', 'location'));

        return redirect()->back()->with('success', 'Menu created successfully.');
    }

    public function storeItem(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:system,page,category,custom',
        ]);

        $order = $menu->items()->max('order') + 1;

        $menu->items()->create([
            'title' => $request->title,
            'type' => $request->type,
            'reference_id' => $request->reference_id,
            'url' => $request->url,
            'order' => $order,
        ]);

        return redirect()->route('admin.menus.index', ['menu' => $menu->id])->with('success', 'Menu item added.');
    }

    public function updateItem(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string',
        ]);

        $menuItem->update([
            'title' => $request->title,
            'url' => $request->type === 'custom' ? $request->url : $menuItem->url,
        ]);

        return redirect()->back()->with('success', 'Menu item updated.');
    }

    public function destroyItem(MenuItem $menuItem)
    {
        $menuId = $menuItem->menu_id;
        $menuItem->delete();
        return redirect()->route('admin.menus.index', ['menu' => $menuId])->with('success', 'Menu item removed.');
    }
}
