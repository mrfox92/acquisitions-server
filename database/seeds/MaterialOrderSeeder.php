<?php

use Illuminate\Database\Seeder;

class MaterialOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  agregamos 20 ordenes oficina id 1
        factory(App\Order::class, 23)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-01-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 10)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-01-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  1,
                    'created_at'    =>  '2021-01-01 12:00:00'
                ]);
            });

        
        //  agregamos 20 ordenes oficina id 2
        factory(App\Order::class, 18)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-01-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 10)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-01-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  2,
                    'created_at'    =>  '2021-01-01 12:00:00'
                ]);
            });
        

        //  agregamos 20 ordenes oficina id 2
        factory(App\Order::class, 30)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-01-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-01-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  3,
                    'created_at'    =>  '2021-01-01 12:00:00'
                ]);
            });

        factory(App\Order::class, 10)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-01-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-01-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  4,
                    'created_at'    =>  '2021-01-01 12:00:00'
                ]);
            });

        factory(App\Order::class, 21)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-01-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-01-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  5,
                    'created_at'    =>  '2021-01-01 12:00:00'
                ]);
            });
        
        //  febrero 2021

        factory(App\Order::class, 5)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-02-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-02-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  1,
                    'created_at'    =>  '2021-02-01 12:00:00'
                ]);
            });

        
        //  agregamos 20 ordenes oficina id 2
        factory(App\Order::class, 15)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-02-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 7)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-02-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  2,
                    'created_at'    =>  '2021-02-01 12:00:00'
                ]);
            });
        

        //  agregamos 20 ordenes oficina id 2
        factory(App\Order::class, 12)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-02-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-02-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  3,
                    'created_at'    =>  '2021-02-01 12:00:00'
                ]);
            });

        factory(App\Order::class, 3)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-02-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-02-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  4,
                    'created_at'    =>  '2021-02-01 12:00:00'
                ]);
            });

        factory(App\Order::class, 7)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-02-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-02-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  5,
                    'created_at'    =>  '2021-02-01 12:00:00'
                ]);
            });

        //  marzo 2021

        factory(App\Order::class, 33)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-03-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 10)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-03-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  1,
                    'created_at'    =>  '2021-03-01 12:00:00'
                ]);
            });

        
        //  agregamos 20 ordenes oficina id 2
        factory(App\Order::class, 11)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-03-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 10)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-03-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  2,
                    'created_at'    =>  '2021-03-01 12:00:00'
                ]);
            });
        

        //  agregamos 20 ordenes oficina id 2
        factory(App\Order::class, 4)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-03-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-03-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  3,
                    'created_at'    =>  '2021-03-01 12:00:00'
                ]);
            });

        factory(App\Order::class, 18)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-03-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-03-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  4,
                    'created_at'    =>  '2021-03-01 12:00:00'
                ]);
            });

        factory(App\Order::class, 20)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2021-03-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2021-03-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  5,
                    'created_at'    =>  '2021-03-01 12:00:00'
                ]);
            });


        /** Año 2020 **/
        //  agregamos 20 ordenes oficina id 1
        factory(App\Order::class, 5)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-01-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-01-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  1,
                    'created_at'    =>  '2020-01-01 12:00:00'
                ]);
            });


        //  agregamos 20 ordenes oficina id 2
        factory(App\Order::class, 11)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-01-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 6)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-01-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  2,
                    'created_at'    =>  '2020-01-01 12:00:00'
                ]);
            });


        //  agregamos 20 ordenes oficina id 2
        factory(App\Order::class, 12)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-01-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 4)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-01-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  3,
                    'created_at'    =>  '2020-01-01 12:00:00'
                ]);
            });

        factory(App\Order::class, 15)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-01-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-01-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  4,
                    'created_at'    =>  '2020-01-01 12:00:00'
                ]);
            });

        factory(App\Order::class, 17)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-01-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-01-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  5,
                    'created_at'    =>  '2020-01-01 12:00:00'
                ]);
            });

        //  febrero 2021

        factory(App\Order::class, 21)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-02-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-02-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  1,
                    'created_at'    =>  '2020-02-01 12:00:00'
                ]);
            });


        //  agregamos 20 ordenes oficina id 2
        factory(App\Order::class, 7)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-02-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 7)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-02-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  2,
                    'created_at'    =>  '2020-02-01 12:00:00'
                ]);
            });


        //  agregamos 20 ordenes oficina id 2
        factory(App\Order::class, 5)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-02-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-02-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  3,
                    'created_at'    =>  '2020-02-01 12:00:00'
                ]);
            });

        factory(App\Order::class, 5)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-02-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-02-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  4,
                    'created_at'    =>  '2020-02-01 12:00:00'
                ]);
            });

        factory(App\Order::class, 1)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-02-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-02-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  5,
                    'created_at'    =>  '2020-02-01 12:00:00'
                ]);
            });

        //  marzo 2021

        factory(App\Order::class, 23)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-03-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 10)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-03-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  1,
                    'created_at'    =>  '2020-03-01 12:00:00'
                ]);
            });


        //  agregamos 20 ordenes oficina id 2
        factory(App\Order::class, 9)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-03-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 10)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-03-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  2,
                    'created_at'    =>  '2020-03-01 12:00:00'
                ]);
            });


        //  agregamos 20 ordenes oficina id 2
        factory(App\Order::class, 3)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-03-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-03-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  3,
                    'created_at'    =>  '2020-03-01 12:00:00'
                ]);
            });

        factory(App\Order::class, 17)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-03-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-03-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  4,
                    'created_at'    =>  '2020-03-01 12:00:00'
                ]);
            });

        factory(App\Order::class, 26)->create([
            'status'    => App\Order::FINISHED,
            'created_at'    =>  '2020-03-01 12:00:00'
        ])->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 5)->create([
                    'order_id'      => $order->id,
                    'created_at'    =>  '2020-03-01 12:00:00'
                    ]));
                App\OrderOffice::create([
                    'order_id'      =>  $order->id,
                    'office_id'     =>  5,
                    'created_at'    =>  '2020-03-01 12:00:00'
                ]);
            });
        
        /** Año 2020 **/



    }
}
