<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialOrder extends Model
{
    protected $fillable = ['material_id', 'order_id', 'quantity'];
    
    public function material () {
        return $this->belongsTo(Material::class);
    }

    public function order () {
        return $this->belongsTo(Order::class);
    }
}
