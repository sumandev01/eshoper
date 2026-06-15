<?php

namespace App\Enums\Permission;

enum SubCategoryPermission:string
{
    case VIEW = 'sub_category_view';
    case CREATE = 'sub_category_create';
    case UPDATE = 'sub_category_update';
    case DELETE = 'sub_category_delete';
}
