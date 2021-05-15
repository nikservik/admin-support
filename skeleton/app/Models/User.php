<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Auth;
use Nikservik\SimpleSupport\Traits\SimpleSupport;
use Nikservik\Users\Admin\AdminRoles;

class User extends Auth
{
    use SimpleSupport;
    use HasFactory;
    use AdminRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
