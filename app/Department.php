<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    public function offices () {
        return $this->hasMany(Office::class);
    }
}
