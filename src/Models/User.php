<?php

namespace Laraning\DAL\Models;

use Spatie\Permission\Traits\HasRoles;
use Laraning\DAL\Services\UserServices;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use UserServices;
    use HasRoles;

    protected $guard_name = 'wave';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeCanBeNotified($query)
    {
        return $query->where('allow_notifications', true);
    }
}
