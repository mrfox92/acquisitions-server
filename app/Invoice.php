<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{

    use SoftDeletes;

    protected $fillable = ['invoice_number', 'acquisition_id', 'provider_id', 'emission_date', 'expiration_date'];
    
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

    public function scopeWhereLike($query, $column, $value)
    {
        return $query->where($column, 'like', "%$value%");
    }
}
