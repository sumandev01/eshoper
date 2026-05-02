<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Permission\AdminAccessEnums;
use App\Enums\Permission\BrandPermission;
use App\Enums\Permission\CategoryPermission;
use App\Enums\Permission\ColorPermission;
use App\Enums\Permission\CouponPermission;
use App\Enums\Permission\MediaPermission;
use App\Enums\Permission\OrderPermission;
use App\Enums\Permission\ProductInventoryPermission;
use App\Enums\Permission\SliderPermission;
use App\Enums\Permission\SizePermission;
use App\Enums\Permission\SubCategoryPermission;
use App\Enums\Permission\TagPermission;
use App\Enums\Permission\UserPermission;
use App\Enums\Permission\UserRolePermission;
use App\Enums\Permission\ProductPermission;
use App\Enums\RoleEnums;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('dashboard.role-permission.index', compact('roles'));
    }

    public function add()
    {
        $adminAccess = [
            'Admin Access' => AdminAccessEnums::cases(),
        ];
        $groups = [
            'Media' => MediaPermission::cases(),
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
            'Product Inventory' => ProductInventoryPermission::cases(),
        ];
        return view('dashboard.role-permission.add', compact('groups', 'adminAccess'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
        ], [
            'name.unique' => 'This role name already exists.',
            'permissions.required' => 'Please select at least one permission.'
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
                ->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong! ' . $e->getMessage());
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
        $adminAccess = [
            'Admin Access' => AdminAccessEnums::cases(),
        ];
        $groups = [
            'Media' => MediaPermission::cases(),
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
            'Product Inventory' => ProductInventoryPermission::cases(),
        ];
        return view('dashboard.role-permission.edit', compact('role', 'groups', 'adminAccess'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'permissions' => 'required|array',
        ], [
            'name.required' => 'Role name is required.',
            'name.unique' => 'This role name already exists.',
            'permissions.required' => 'Please select at least one permission.'
        ]);

        try {
            DB::beginTransaction();

            $role = Role::findOrFail($id);

            $role->update([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();

            return redirect()->route('admin.role.index')
                ->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Something went wrong! ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $protectedRoles = [
                RoleEnums::Super_Admin->value,
                RoleEnums::Admin->value,
                RoleEnums::User->value,
            ];
            if (in_array($role->name, $protectedRoles)) {
                return back()->with('error', 'This role cannot be deleted.');
            }
            $role->delete();

            return redirect()->route('admin.role.index')
                ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Something went wrong! ' . $e->getMessage());
        }
    }
}
