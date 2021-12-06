<?php

use App\Material;
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
        // factory(App\MaterialInvoice::class, 100)->create();

        factory(App\Invoice::class, 5)->create([    
            'provider_id'   =>  1,
            'created_at'    =>  '2021-01-01 12:00:00'
        ])->each( function ($invoice) {
                $invoice->materialsInvoices()->saveMany( factory(App\MaterialInvoice::class, 10)->create([
                        'invoice_id'    =>  $invoice->id,
                        'created_at'    =>  '2021-01-01 12:00:00'
                    ])->each( function ($materialinvoice) {
                
                        $material = Material::find( $materialinvoice->material_id );
                        $material->update([
                            'stock' =>  $material->stock + $materialinvoice->quantity
                        ]);
                    })
                );
            });
            //  actualizar cada material

        factory(App\Invoice::class, 3)->create([    
            'provider_id'   =>  2,
            'created_at'    =>  '2021-01-01 12:00:00'
        ])->each( function ($invoice) {
                $invoice->materialsInvoices()->saveMany( factory(App\MaterialInvoice::class, 8)->create([
                        'invoice_id'    =>  $invoice->id,
                        'created_at'    =>  '2021-01-01 12:00:00'
                    ])->each( function ($materialinvoice) {
                
                        $material = Material::find( $materialinvoice->material_id );
                        $material->update([
                            'stock' =>  $material->stock + $materialinvoice->quantity
                        ]);
                    })
                );
            });

        factory(App\Invoice::class, 2)->create([    
            'provider_id'   =>  3,
            'created_at'    =>  '2021-01-01 12:00:00'
        ])->each( function ($invoice) {
                $invoice->materialsInvoices()->saveMany( factory(App\MaterialInvoice::class, 5)->create([
                        'invoice_id'    =>  $invoice->id,
                        'created_at'    =>  '2021-01-01 12:00:00'
                    ])->each( function ($materialinvoice) {
                
                        $material = Material::find( $materialinvoice->material_id );
                        $material->update([
                            'stock' =>  $material->stock + $materialinvoice->quantity
                        ]);
                    })
                );
            });

        //  febrero 2021

        factory(App\Invoice::class, 5)->create([    
            'provider_id'   =>  1,
            'created_at'    =>  '2021-02-01 12:00:00'
        ])->each( function ($invoice) {
                $invoice->materialsInvoices()->saveMany( factory(App\MaterialInvoice::class, 10)->create([
                        'invoice_id'    =>  $invoice->id,
                        'created_at'    =>  '2021-02-01 12:00:00'
                    ])->each( function ($materialinvoice) {
                
                        $material = Material::find( $materialinvoice->material_id );
                        $material->update([
                            'stock' =>  $material->stock + $materialinvoice->quantity
                        ]);
                    })
                );
            });
            //  actualizar cada material

        factory(App\Invoice::class, 3)->create([    
            'provider_id'   =>  2,
            'created_at'    =>  '2021-02-01 12:00:00'
        ])->each( function ($invoice) {
                $invoice->materialsInvoices()->saveMany( factory(App\MaterialInvoice::class, 8)->create([
                        'invoice_id'    =>  $invoice->id,
                        'created_at'    =>  '2021-02-01 12:00:00'
                    ])->each( function ($materialinvoice) {
                
                        $material = Material::find( $materialinvoice->material_id );
                        $material->update([
                            'stock' =>  $material->stock + $materialinvoice->quantity
                        ]);
                    })
                );
            });

        factory(App\Invoice::class, 2)->create([    
            'provider_id'   =>  3,
            'created_at'    =>  '2021-02-01 12:00:00'
        ])->each( function ($invoice) {
                $invoice->materialsInvoices()->saveMany( factory(App\MaterialInvoice::class, 5)->create([
                        'invoice_id'    =>  $invoice->id,
                        'created_at'    =>  '2021-02-01 12:00:00'
                    ])->each( function ($materialinvoice) {
                
                        $material = Material::find( $materialinvoice->material_id );
                        $material->update([
                            'stock' =>  $material->stock + $materialinvoice->quantity
                        ]);
                    })
                );
            });

        //  Marzo 2021

            factory(App\Invoice::class, 5)->create([    
                'provider_id'   =>  1,
                'created_at'    =>  '2021-03-01 12:00:00'
            ])->each( function ($invoice) {
                    $invoice->materialsInvoices()->saveMany( factory(App\MaterialInvoice::class, 10)->create([
                            'invoice_id'    =>  $invoice->id,
                            'created_at'    =>  '2021-03-01 12:00:00'
                        ])->each( function ($materialinvoice) {
                    
                            $material = Material::find( $materialinvoice->material_id );
                            $material->update([
                                'stock' =>  $material->stock + $materialinvoice->quantity
                            ]);
                        })
                    );
                });
                //  actualizar cada material
    
            factory(App\Invoice::class, 3)->create([    
                'provider_id'   =>  2,
                'created_at'    =>  '2021-03-01 12:00:00'
            ])->each( function ($invoice) {
                    $invoice->materialsInvoices()->saveMany( factory(App\MaterialInvoice::class, 8)->create([
                            'invoice_id'    =>  $invoice->id,
                            'created_at'    =>  '2021-03-01 12:00:00'
                        ])->each( function ($materialinvoice) {
                    
                            $material = Material::find( $materialinvoice->material_id );
                            $material->update([
                                'stock' =>  $material->stock + $materialinvoice->quantity
                            ]);
                        })
                    );
                });
    
            factory(App\Invoice::class, 2)->create([    
                'provider_id'   =>  3,
                'created_at'    =>  '2021-03-01 12:00:00'
            ])->each( function ($invoice) {
                    $invoice->materialsInvoices()->saveMany( factory(App\MaterialInvoice::class, 5)->create([
                            'invoice_id'    =>  $invoice->id,
                            'created_at'    =>  '2021-03-01 12:00:00'
                        ])->each( function ($materialinvoice) {
                    
                            $material = Material::find( $materialinvoice->material_id );
                            $material->update([
                                'stock' =>  $material->stock + $materialinvoice->quantity
                            ]);
                        })
                    );
                });
    }
}
