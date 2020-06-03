<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $fillable = ['name'];
    const ADMIN = 1;
    const ACQUISITION = 2;
    const DISPATCHER = 3;
}
