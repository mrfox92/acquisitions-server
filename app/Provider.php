<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use SoftDeletes;

    protected $fillable = ['rut', 'name', 'address', 'url_web', 'phone', 'email'];

    public function invoices () {
        return $this->hasMany(Invoice::class);
    }

    public function scopeWhereLike($query, $column, $value)
    {
        return $query->where($column, 'like', "%$value%");
    }

    public function scopeOrWhereLike($query, $column, $value)
    {
        return $query->orWhere($column, 'like', "%$value%");
    }
}
