<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispatcher extends Model
{

    protected $fillable = ['user_id'];

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function orders () {
        return $this->hasMany(Order::class);
    }
}
