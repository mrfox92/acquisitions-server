<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialOrder extends Model
{
    use SoftDeletes;
    protected $fillable = ['material_id', 'order_id', 'quantity'];
    
    public function material () {
        return $this->belongsTo(Material::class);
    }

    public function order () {
        return $this->belongsTo(Order::class);
    }
}
