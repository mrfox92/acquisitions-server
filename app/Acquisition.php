<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acquisition extends Model
{
    use SoftDeletes;    //  para implementar borrado logico

    protected $fillable = ['user_id', 'created_at'];

    //  un usuario adquisicion corresponde a un usuario de la tabla User
    public function user () {
        return $this->belongsTo(User::class);
    }

    //  un usuario adquisicion puede registrar uno o muchos materiales y uno o muchos materiales puede ser
    //  registrado por uno o varios usuarios adquisiciones
    public function materials () {
        return $this->hasMany(Material::class);
    }

    public function invoices () {
        return $this->hasMany(Invoice::class);
    }

    //  un usuario adquisicion puede registrar el ingreso de stock de uno o muchos materiales de una o muchas facturas
    public function materialsInvoices () {
        return $this->hasMany(MaterialInvoice::class);
    }
}
