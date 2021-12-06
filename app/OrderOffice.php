<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderOffice extends Model
{
    use SoftDeletes;
    
    protected $table = 'order_office';
    protected $fillable = ['order_id', 'office_id', 'created_at'];

    //  relacion 1 a muchos inversa
    public function order () {
        return $this->belongsTo(Order::class);
    }

    //  relacion 1 a muchos inversa
    public function office () {
        return $this->belongsTo(Office::class);
    }

}
