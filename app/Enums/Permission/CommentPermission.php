<?php
namespace App\Enums\Permission;

enum CommentPermission:string
{
    case VIEW = 'comment_view';
    case REPLY = 'comment_reply';
    case UPDATE = 'comment_update';
    case DELETE = 'comment_delete';
}