<?php

namespace App\Http\Controllers\Admin;

use App\Services\PermissionScannerService;
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
        $roles = $roles->sortByDesc(function ($role) {
            return $role->permissions->count();
        })->values();

        return view('dashboard.role-permission.index', compact('roles'));
    }

    public function add()
    {
        $scanner = new PermissionScannerService();
        $permissions = $scanner->syncAndGetPermissions();
        $adminAccess = $permissions['adminAccess'];
        $groups = $permissions['groups'];

        return view('dashboard.role-permission.add', compact('groups', 'adminAccess'));
    }

    public function store(Request $request)
    {
        if ($request->has('name')) {
            $request->merge(['name' => strtolower($request->name)]);
        }

        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
        ], [
            'name.unique' => 'This role name already exists.',
            'permissions.required' => 'Please select at least one permission.',
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

            return back()->with('error', 'Something went wrong! '.$e->getMessage());
        }
    }

    public function view($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        $scanner = new PermissionScannerService();
        $permissions = $scanner->syncAndGetPermissions();
        $adminAccess = $permissions['adminAccess'];
        $groups = $permissions['groups'];

        return view('dashboard.role-permission.view', compact('role', 'groups', 'adminAccess'));
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        if ($role->name === RoleEnums::Super_Admin->value || $role->name === RoleEnums::User->value) {
            return back()->with('error', 'This role cannot be edited.');
        }
        $scanner = new PermissionScannerService();
        $permissions = $scanner->syncAndGetPermissions();
        $adminAccess = $permissions['adminAccess'];
        $groups = $permissions['groups'];

        return view('dashboard.role-permission.edit', compact('role', 'groups', 'adminAccess'));
    }

    public function update(Request $request, $id)
    {
        if ($request->has('name')) {
            $request->merge(['name' => strtolower($request->name)]);
        }

        $request->validate([
            'name' => 'required|string|unique:roles,name,'.$id,
        ], [
            'name.required' => 'Role name is required.',
            'name.unique' => 'This role name already exists.',
            'permissions.required' => 'Please select at least one permission.',
        ]);

        try {
            $role = Role::findOrFail($id);

            $protectedRoles = array_column(RoleEnums::cases(), 'value');
            if (in_array($role->name, $protectedRoles) && $request->name !== $role->name) {
                return redirect()->back()->with('error', 'This role name cannot be edited.');
            }

            DB::beginTransaction();

            $role->update([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);

            $permissions = $request->input('permissions', []);
            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()->route('admin.role.index')
                ->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Something went wrong! '.$e->getMessage());
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
                ->with('error', 'Something went wrong! '.$e->getMessage());
        }
    }
}
