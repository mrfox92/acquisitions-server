<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Office extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'department_id', 'created_at'];

    public function department () {
        return $this->belongsTo(Department::class);
    }

    public function scopeWhereLike($query, $column, $value)
    {
        return $query->where($column, 'like', "%$value%");
    }
}
