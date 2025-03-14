<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company_User extends Model
{
    protected $table = 'company_user';

    protected $fillable = [
        'company_id',
        'user_id',
        'role'
    ];
}
