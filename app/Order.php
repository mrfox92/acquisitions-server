<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = ['dispatcher_id', 'status', 'name_responsible', 'created_at'];
    
    const ENABLED = 1;
    const PROCESING = 2;
    const FINISHED = 3;
    const CANCELED = 4;


    public function dispatcher () {
        return $this->belongsTo(Dispatcher::class);
    }

    
    public function materialsOrders () {
        return $this->hasMany(MaterialOrder::class);
    }
    
    public function scopeWhereLike($query, $column, $value) {
        return $query->where($column, 'like', "%$value%");
    }

    //  relacion muchos a muchos
    public function offices () {
        return $this->belongsToMany(Office::class, 'order_office')->withPivot('order_id', 'office_id');
    }

}
    