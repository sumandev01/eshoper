<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Permission\BrandPermission;
use App\Enums\Permission\CategoryPermission;
use App\Enums\Permission\ColorPermission;
use App\Enums\Permission\CouponPermission;
use App\Enums\Permission\OrderPermission;
use App\Enums\Permission\SliderPermission;
use App\Enums\Permission\SizePermission;
use App\Enums\Permission\SubCategoryPermission;
use App\Enums\Permission\TagPermission;
use App\Enums\Permission\UserPermission;
use App\Enums\Permission\UserRolePermission;
use App\Enums\Permission\ProductPermission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        // $groups = [
        //     'Brand' => BrandPermission::cases(),
        //     'Category' => CategoryPermission::cases(),
        //     'SubCategory' => SubCategoryPermission::cases(),
        //     'Size' => SizePermission::cases(),
        //     'Color' => ColorPermission::cases(),
        //     'Slider' => SliderPermission::cases(),
        //     'Coupon' => CouponPermission::cases(),
        //     'Tag' => TagPermission::cases(),
        //     'User' => UserPermission::cases(),
        //     'Role' => UserRolePermission::cases(),
        //     'Order' => OrderPermission::cases(),
        //     'Product' => ProductPermission::cases(),
        // ];
        $roles = Role::with('permissions')->get();
        return view('dashboard.role-permission.index', compact('roles'));
    }

    public function add()
    {
        $groups = [
            'Brand' => BrandPermission::cases(),
            'Category' => CategoryPermission::cases(),
            'SubCategory' => SubCategoryPermission::cases(),
            'Size' => SizePermission::cases(),
            'Color' => ColorPermission::cases(),
            'Slider' => SliderPermission::cases(),
            'Coupon' => CouponPermission::cases(),
            'Tag' => TagPermission::cases(),
            'User' => UserPermission::cases(),
            'Role' => UserRolePermission::cases(),
            'Order' => OrderPermission::cases(),
            'Product' => ProductPermission::cases(),
        ];
        return view('dashboard.role-permission.add', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
        ], [
            'name.unique' => 'এই নামের রোলটি আগেই তৈরি করা হয়েছে।',
            'permissions.required' => 'কমপক্ষে একটি পারমিশন সিলেক্ট করুন।'
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();

            return redirect()->route('admin.role.index')
                ->with('success', 'রোলটি সফলভাবে তৈরি করা হয়েছে।');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'কিছু একটা সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('dashboard.role-permission.view', compact('role'));
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $groups = [
            'Brand' => BrandPermission::cases(),
            'Category' => CategoryPermission::cases(),
            'SubCategory' => SubCategoryPermission::cases(),
            'Size' => SizePermission::cases(),
            'Color' => ColorPermission::cases(),
            'Slider' => SliderPermission::cases(),
            'Coupon' => CouponPermission::cases(),
            'Tag' => TagPermission::cases(),
            'User' => UserPermission::cases(),
            'Role' => UserRolePermission::cases(),
            'Order' => OrderPermission::cases(),
            'Product' => ProductPermission::cases(),
        ];
        return view('dashboard.role-permission.edit', compact('role', 'groups'));
    }

    public function update(Request $request, $id)
    {
        // ১. ভ্যালিডেশন
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'permissions' => 'required|array',
        ], [
            'name.required' => 'রোলের নাম অবশ্যই দিতে হবে।',
            'name.unique' => 'এই নামের রোল অলরেডি আছে।',
            'permissions.required' => 'কমপক্ষে একটি পারমিশন সিলেক্ট করুন।'
        ]);

        try {
            // ট্রানজেকশন শুরু
            DB::beginTransaction();

            // ২. রোল খুঁজে বের করা
            $role = Role::findOrFail($id);

            // ৩. রোলের নাম আপডেট করা
            $role->update([
                'name' => $request->name,
                'guard_name' => 'web' // আপনার গার্ড অনুযায়ী
            ]);

            // ৪. পারমিশনগুলো সিঙ্ক করা
            // syncPermissions আগের সব পারমিশন ডিলিট করে নতুনগুলো সেট করে দিবে
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            // সব ঠিক থাকলে ডাটাবেজে পার্মানেন্টলি সেভ হবে
            DB::commit();

            return redirect()->route('admin.role.index')
                ->with('success', 'রোল এবং পারমিশন সফলভাবে আপডেট করা হয়েছে।');
        } catch (\Exception $e) {
            // কোনো এরর আসলে সব চেঞ্জ বাতিল (Rollback) হয়ে যাবে
            DB::rollBack();

            return back()
                ->withInput() // ইউজার যা ইনপুট দিয়েছিল তা ফর্মে থেকে যাবে
                ->with('error', 'কিছু একটা সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }
}
