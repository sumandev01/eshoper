<?php
namespace App\Enums\Permission;

enum CommentPermission:string
{
    case VIEW = 'view_comment';
    case REPLY = 'reply_comment';
    case UPDATE = 'update_comment';
    case DELETE = 'delete_comment';
}