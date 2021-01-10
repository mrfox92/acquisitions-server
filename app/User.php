<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'name', 'last_name', 'slug', 'email', 'avatar' ,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at'
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

    //  funciones utilizando el scope del modelo

    public function scopeWhereLike($query, $column, $value) {
        return $query->where($column, 'like', "%$value%");
    }

    public function scopeOrWhereLike($query, $column, $value) {
        return $query->orWhere($column, 'like', "%$value%");
    }
}
