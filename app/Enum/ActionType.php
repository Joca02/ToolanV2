<?php

namespace App\Enum;

enum ActionType: string {
    case FOLLOW = 'follow';
    case UNFOLLOW = 'unfollow';
    case POST_CREATE = 'post_create';
    case POST_DELETE = 'post_delete';
    case LIKE = 'like';
    case COMMENT = 'comment';
    case DEACTIVATE = 'deactivate';
    case REACTIVATE = 'reactivate';
    case REGISTER = 'register';
    case LOGIN = 'login';
}
