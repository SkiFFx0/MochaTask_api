<?php

namespace App\Models;

use App\Enums\CompanyRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyUser extends Pivot
{
    use HasFactory;

    protected $table = 'company_user';

    protected $fillable = [
        'company_id',
        'user_id',
        'role',
        'is_privileged',
    ];

    protected $casts = [
        'role' => CompanyRole::class, // Auto-converts role to Enum
    ];

    protected static function setCompanyUserRole($companyId, $userId, CompanyRole $role)
    {
        return self::create([
            'company_id' => $companyId,
            'user_id' => $userId,
            'role' => $role,
            'is_privileged' => $role === CompanyRole::OWNER || $role === CompanyRole::ADMIN,
        ]);
    }

    protected static function unsetCompanyUser($companyId, $userId)
    {
        return self::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->delete();
    }

    public function scopePrivileged(Builder $query): void
    {
        $query->where('is_privileged', true);
    }
}
