<?php

namespace App\Enums\Permission;

enum SubCategoryPermission:string
{
    case CREATE = 'create_sub_category';
    case VIEW = 'view_sub_category';
    case UPDATE = 'update_sub_category';
    case DELETE = 'delete_sub_category';
}
