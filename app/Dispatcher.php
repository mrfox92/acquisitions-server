<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispatcher extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id'];

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function orders () {
        return $this->hasMany(Order::class);
    }
}
