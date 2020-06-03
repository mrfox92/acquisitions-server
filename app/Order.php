<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['num_order', 'dispatcher_id', 'office_id', 'status', 'name_responsible'];
    
    const ENABLED = 1;
    const PROCESING = 2;
    const FINISHED = 3;


    public function dispatcher () {
        return $this->belongsTo(Dispatcher::class);
    }

    public function office () {
        return $this->belongsTo(Office::class);
    }

    public function materialsOrders () {
        return $this->hasMany(MaterialOrder::class);
    }
}
    