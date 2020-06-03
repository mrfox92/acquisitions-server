<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialInvoice extends Model
{

    public function acquisition () {
        return $this->belongsTo(Acquisition::class);
    }

    public function invoice () {
        return $this->belongsTo(Invoice::class);
    }

    public function material () {
        return $this->belongsTo(Material::class);
    }
}
