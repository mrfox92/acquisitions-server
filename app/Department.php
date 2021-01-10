<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'created_at'];

    public function offices () {
        return $this->hasMany(Office::class);
    }

    public function scopeWhereLike($query, $column, $value){
        return $query->where($column, 'like', "%$value%");
    }
}
