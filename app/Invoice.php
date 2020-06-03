<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function acquisition () {
        return $this->belongsTo(Acquisition::class);
    }

    //  una factura pertenece a un proveedor, y un proveedor puede tener una o muchas facturas
    public function provider () {
        return $this->belongsTo(Provider::class);
    }

    public function materialsInvoices () {
        return $this->hasMany(MaterialInvoice::class);
    }
}
