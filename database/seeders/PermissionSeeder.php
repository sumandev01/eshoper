<?php

namespace Database\Seeders;

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
use App\Enums\Permission\OrderPermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            ...array_column(CategoryPermission::cases(), 'value'),
            ...array_column(SubCategoryPermission::cases(), 'value'),
            ...array_column(BrandPermission::cases(), 'value'),
            ...array_column(SizePermission::cases(), 'value'),
            ...array_column(ColorPermission::cases(), 'value'),
            ...array_column(ProductPermission::cases(), 'value'),
            ...array_column(SliderPermission::cases(), 'value'),
            ...array_column(TagPermission::cases(), 'value'),
            ...array_column(UserPermission::cases(), 'value'),
            ...array_column(UserRolePermission::cases(), 'value'),
            ...array_column(CouponPermission::cases(), 'value'),
            ...array_column(OrderPermission::cases(), 'value'),
        ];

        foreach ($permissions as $permission) {
            Permission::UpdateOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
        }

        Permission::whereNotIn('name', $permissions)->delete();
    }
}
