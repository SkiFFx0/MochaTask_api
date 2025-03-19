<?php

namespace App\Enums;

enum CompanyRole: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case PM = 'pm';
    case MEMBER = 'member';

    // Helper function to check if a role has management permissions
    public function isPrivileged(): bool
    {
        return in_array($this, [self::OWNER, self::ADMIN]);
    }
}
