<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = ['name', 'department_id', 'created_at'];

    public function department () {
        return $this->belongsTo(Department::class);
    }
}
