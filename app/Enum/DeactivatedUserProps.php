<?php

namespace App\Enum;

enum DeactivatedUserProps: string {
    case NAME='Deactivated User';
    case DESCRIPTION='Deactivated user';
    case PROFILE_PICTURE='uploads/profile_pictures/default.png';
    case USERNAME="";
}
