<?php

use App\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $proveedores = [
            [
                'rut'   =>  '7191242-6',
                'name'  =>  'ALVARO JOSE DEL CAMPO SAEZ',
                'phone' =>  '22048252'
            ],
            [
                'rut'   =>  '76075901-5',
                'name'  =>  'SOC.COMERCIAL ISPLUS LTDA',
                'phone' =>  '222309816'
            ],
            [
                'rut'   =>  '76140062-2',
                'name'  =>  'SOC. COMERCIAL MAX OFFICE LTDA',
                'phone' =>  '27925171'
            ],
            [
                'rut'   =>  '761400622',
                'name'  =>  'SOC.COMERCIAL MAX OFFICE LTDA',
                'phone' =>  '1111111'
            ],
            [
                'rut'   =>  '76271597-k',
                'name'  =>  'MAGENS S.A',
                'phone' =>  '56223994000'
            ],
            [
                'rut'   =>  '762874776',
                'name'  =>  'DISTRIBUIDORA COMERCIAL SURMONTT LTDA'
            ],
            [
                'rut'   =>  '76424515-6',
                'name'  =>  'SOC. COMERCIAL PROOFFICE LTDA',
                'phone' =>  '632463192'
            ],
            [
                'rut'   =>  '77806000-0',
                'name'  =>  'COMERCIAL REDOFFICE SUR LTDA',
                'phone' =>  '652351600'
            ],
            [
                'rut'   =>  '77880470-0',
                'name'  =>  'COMERCIAL WORLDTEC LTDA',
                'phone' =>  '422423310'
            ],
            [
                'rut'   =>  '78260870-3',
                'name'  =>  'Inmobiliaria y comercial rosales lobos Ltda ',
                'phone' =>  '632242829'
            ],
            [
                'rut'   =>  '78382830-8',
                'name'  =>  'SERV.INTEGRALES EN COMP. Y TRANS. LTDA',
                'phone' =>  '226584240'
            ],
            [
                'rut'   =>  '78715730-0',
                'name'  =>  'SOC. COMERCIAL DIMER LTDA',
                'phone' =>  '227724696'
            ],
            [
                'rut'   =>  '965569405',
                'name'  =>  'PROVEEDORES INTEGRALES PRISA S.A',
                'phone' =>  '228206000'
            ],
            [
                'rut'   =>  '96670840-9',
                'name'  =>  'DIMERC S.A.',
                'phone' =>  '223858200'
            ],
        ];

        foreach ($proveedores as $proveedor) {
            factory( Provider::class )->create( $proveedor );
        }
        //  creamos 10 proveedores y creamos 1 factura por cada proveedor
        // factory(App\Provider::class, 10)->create()
        //     ->each( function ($provider) {
        //         $provider->invoices()->saveMany( factory(App\Invoice::class, 1)->create() );
        //     });
    }
}
