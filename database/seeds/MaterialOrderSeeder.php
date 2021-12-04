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
        //  agregamos 20 ordenes
        factory(App\Order::class, 20)->create()
            ->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 10)->create(['order_id' => $order->id]) );
            });
    }
}
