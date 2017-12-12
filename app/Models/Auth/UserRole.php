<?php
/**
 * Created by PhpStorm.
 * User: dmi
 * Date: 11.12.2017
 * Time: 18:45
 */

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'auth.user_roles';

    protected $fillable = [
        'role',
        'user_id',
    ];

    const ROLE_GOD    = 'god';
    const ROLE_CLIENT = 'client';

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}