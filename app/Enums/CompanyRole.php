<?php

namespace App\Enums;

enum CompanyRole: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case PM = 'pm';
    case MEMBER = 'member';
}
