<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = ['rut', 'name', 'address', 'url_web', 'phone', 'email'];

    public function invoices () {
        return $this->hasMany(Invoice::class);
    }
}
