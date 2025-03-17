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

    protected static function assignUserToCompany($companyId, $userId, CompanyRole $role)
    {
        $existingOwnerCount = self::where('company_id', $companyId)
            ->where('role', CompanyRole::OWNER)
            ->count();

        if ($existingOwnerCount === 1 && $role !== CompanyRole::OWNER) {
            return response()->json(['error' => 'Cannot remove the last owner'], 403);
        }

        return self::updateOrCreate(
            ['company_id' => $companyId, 'user_id' => $userId],
            ['role' => $role]
        );
    }
}
