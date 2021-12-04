<?php

use Illuminate\Database\Seeder;

class MaterialInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  registramos los 100 ingresos
        factory(App\MaterialInvoice::class, 100)->create();
    }
}
