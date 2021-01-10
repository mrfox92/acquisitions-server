<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialInvoice extends Model
{
    use SoftDeletes;
    // public $table = 'material_invoices';
    protected $fillable = ['material_id', 'invoice_id', 'quantity', 'unity_cost', 'iva', 'total_cost'];

    public function acquisition () {
        return $this->belongsTo(Acquisition::class);
    }
    //  material invoice relaciona los materiales con las facturas a las que pertenecen
    public function invoice () {
        return $this->belongsTo(Invoice::class)->select('id', 'invoice_number', 'provider_id', 'acquisition_id', 'emission_date', 'expiration_date', 'created_at');
    }

    public function material () {
        return $this->belongsTo(Material::class)->select('id', 'bar_code', 'acquisition_id', 'name', 'slug', 'unity_type', 'stock', 'picture', 'created_at');
    }
}
