<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ADMIN = 1;
    const ACQUISITION = 2;
    const DISPATCHER = 3;
}
