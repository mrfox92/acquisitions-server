<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialOrder extends Model
{
    public function material () {
        return $this->belongsTo(Material::class);
    }

    public function order () {
        return $this->belongsTo(Order::class);
    }
}
