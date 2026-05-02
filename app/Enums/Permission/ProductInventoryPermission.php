<?php

namespace App\Enums\Permission;

enum ProductInventoryPermission:string
{
    case VIEW = 'view_product_inventory';
    case CREATE = 'create_product_inventory';
    case UPDATE = 'update_product_inventory';
    case DELETE = 'delete_product_inventory';
}
