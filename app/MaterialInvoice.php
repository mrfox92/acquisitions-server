<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialInvoice extends Model
{
    // public $table = 'material_invoices';
    protected $fillable = ['material_id', 'invoice_id', 'quantity', 'unity_cost', 'iva', 'total_cost'];

    public function acquisition () {
        return $this->belongsTo(Acquisition::class);
    }
    //  material invoice relaciona los materiales con las facturas a las que pertenecen
    public function invoice () {
        return $this->belongsTo(Invoice::class);
    }

    public function material () {
        return $this->belongsTo(Material::class);
    }
}
