<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //  un usuario pertenece a un role y un usuario puede tener solo un role asignado
    public function role () {
        return $this->belongsTo(Role::class);
    }

    public function acquisition () {
        return $this->hasOne(Acquisition::class);
    }

    public function dispatcher () {
        return $this->hasOne(Dispatcher::class);
    }
}
