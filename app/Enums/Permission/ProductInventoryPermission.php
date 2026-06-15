<?php

namespace App\Enums\Permission;

enum ProductInventoryPermission:string
{
    case VIEW = 'product_inventory_view';
    case CREATE = 'product_inventory_create';
    case UPDATE = 'product_inventory_update';
    case DELETE = 'product_inventory_delete';
}
