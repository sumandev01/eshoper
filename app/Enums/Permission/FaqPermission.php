<?php

namespace App\Enums\Permission;

enum FaqPermission:string
{
    case VIEW = "faq_view";
    case CREATE = "faq_create";
    case UPDATE = "faq_update";
    case DELETE = "faq_delete";
}
