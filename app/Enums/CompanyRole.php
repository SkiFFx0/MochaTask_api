<?php

namespace App\Enums;

use App\Models\CompanyUser;

enum CompanyRole: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case MEMBER = 'member';
}
