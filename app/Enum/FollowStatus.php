<?php
namespace App\Enum;
enum FollowStatus: string
{
    case FOLLOWING = 'FOLLOWING';
    case FOLLOWING_ME = 'FOLLOWING ME';
    case NOT_FOLLOWING = 'NOT FOLLOWING';
}
