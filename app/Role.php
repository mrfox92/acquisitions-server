<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];
    const ADMIN = 1;
    const ACQUISITION = 2;
    const DISPATCHER = 3;
    const GUEST = 4;

    public function users() {
        $this->hasMany( User::class );
    }
}
