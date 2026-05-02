<?php

namespace Database\Seeders;

use App\Enums\Permission\AdminAccessEnums;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\Permission\CategoryPermission;
use App\Enums\Permission\SubCategoryPermission;
use App\Enums\Permission\BrandPermission;
use App\Enums\Permission\ColorPermission;
use App\Enums\Permission\SizePermission;
use App\Enums\Permission\ProductPermission;
use App\Enums\Permission\SliderPermission;
use App\Enums\Permission\TagPermission;
use App\Enums\Permission\UserPermission;
use App\Enums\Permission\UserRolePermission;
use App\Enums\Permission\CouponPermission;
use App\Enums\Permission\MediaPermission;
use App\Enums\Permission\OrderPermission;
use App\Enums\Permission\ProductInventoryPermission;
use App\Enums\RoleEnums;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $userAndRolePermissions = [
            ...array_column(UserPermission::cases(), 'value'),
            ...array_column(UserRolePermission::cases(), 'value'),
        ];

        $otherPermissions = [
            ...array_column(AdminAccessEnums::cases(), 'value'),
            ...array_column(MediaPermission::cases(), 'value'),
            ...array_column(CategoryPermission::cases(), 'value'),
            ...array_column(SubCategoryPermission::cases(), 'value'),
            ...array_column(BrandPermission::cases(), 'value'),
            ...array_column(SizePermission::cases(), 'value'),
            ...array_column(ColorPermission::cases(), 'value'),
            ...array_column(ProductPermission::cases(), 'value'),
            ...array_column(ProductInventoryPermission::cases(), 'value'),
            ...array_column(SliderPermission::cases(), 'value'),
            ...array_column(TagPermission::cases(), 'value'),
            ...array_column(CouponPermission::cases(), 'value'),
            ...array_column(OrderPermission::cases(), 'value'),
        ];

        $allPermissions = array_merge($userAndRolePermissions, $otherPermissions);

        foreach ($allPermissions as $permission) {
            Permission::UpdateOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
        }

        Permission::whereNotIn('name', $allPermissions)->delete();

        $superAdminRole = Role::firstOrCreate(
            ['name' => RoleEnums::Super_Admin->value],
            ['guard_name' => 'web']
        );
        $superAdminRole->syncPermissions(Permission::all());

        $adminRole = Role::updateOrCreate(
            ['name' => RoleEnums::Admin->value],
            ['guard_name' => 'web']
        );
        $adminRole->syncPermissions($otherPermissions);
        
        Role::updateOrCreate(
            ['name' => RoleEnums::User->value],
            ['guard_name' => 'web']
        );
    }
}
