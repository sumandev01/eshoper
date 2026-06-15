<?php

namespace App\Enums\Permission;

enum TeamMemberPermission:string
{
    case VIEW = 'team_member_view';
    case CREATE = 'team_member_create';
    case UPDATE = 'team_member_update';
    case DELETE = 'team_member_delete';
}
