<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    protected $fillable = ['bar_code', 'acquisition_id', 'name', 'slug', 'unity_type', 'stock', 'picture'];

    //  tipos de unidades de materiales
    const UNITY = 1;
    const PACKAGE = 2;
    const REAM = 3;
    const SET = 4;
    const BOX = 5;

    //  uno o muchos materiales pueden ser ingresados por uno o muchos usuarios adquisiciones
    public function acquisition () {
        return $this->belongsTo(Acquisition::class);
    }


    //   un mismo material puede tener uno o muchos ingresos ya que puede venir en varias facturas distintas con diferente stock
    public function materialsInvoices () {
        return $this->hasMany(MaterialInvoice::class);
    }

    // una orden despacho de material puede tener uno o muchos materiales a despachar
    public function materialsOrders () {
        return $this->hasMany(MaterialOrder::class);
    }
}
