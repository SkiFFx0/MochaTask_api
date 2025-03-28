<?php

namespace App\Models;

use App\Enums\CompanyRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyUser extends Pivot
{
    protected $table = 'company_user';

    protected $fillable = [
        'company_id',
        'user_id',
        'role'
    ];

    protected $casts = [
        'role' => CompanyRole::class, // Auto-converts role to Enum
    ];

    protected static function setCompanyUserRole($companyId, $userId, CompanyRole $role)
    {
        return self::create([
            'company_id' => $companyId,
            'user_id' => $userId,
            'role' => $role
        ]);
    }

    protected static function unsetCompanyUserRole($companyId, $userId, $role)
    {
        return self::query()
            ->where('company_id', $companyId->id)
            ->where('user_id', $userId->id)
            ->where('role', $role)
            ->delete();
    }

    protected static function unsetCompanyUser($companyId, $userId)
    {
        return self::query()
            ->where('company_id', $companyId->id)
            ->where('user_id', $userId->id)
            ->delete();
    }

    public function scopePrivileged(Builder $query): void
    {
        $query->whereIn('role', [CompanyRole::OWNER, CompanyRole::ADMIN]);
    }
}
