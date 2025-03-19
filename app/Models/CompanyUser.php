<?php

namespace App\Models;

use App\Enums\CompanyRole;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
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

    //TODO Add inability to remove owner
    protected static function assignUserToCompanyAndAddRole($companyId, $userId, CompanyRole $role)
    {
        return self::create([
            'company_id' => $companyId,
            'user_id' => $userId,
            'role' => $role
        ]);
    }

    protected static function removeUserFromCompany($companyId, $userId)
    {
        return self::query()
            ->where('company_id', $companyId->id)
            ->where('user_id', $userId->id)
            ->delete();
    }
}
