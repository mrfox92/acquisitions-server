<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        Storage::deleteDirectory('materials');
        Storage::deleteDirectory('users');

        Storage::makeDirectory('materials');
        Storage::makeDirectory('users');

        //  creamos los roles

        factory(App\Role::class, 1)->create(['name' => 'admin']);
        factory(App\Role::class, 1)->create(['name' => 'acquisition']);
        factory(App\Role::class, 1)->create(['name' => 'dispatcher']);

        //  crear usuario admin
        factory(App\User::class, 1)->create([
            'name'  =>  'admin',
            'email' =>  'admin@gmail.com',
            'password'  =>  bcrypt('test1234'),
            'role_id'   =>  \App\Role::ADMIN
        ])
        ->each( function ($user) {
            factory(App\Acquisition::class, 1)->create(['user_id' => $user->id]);
        });

        //  creamos 10 usuarios para solicitar ordenes de despacho
        factory(App\User::class, 10)->create()
            ->each( function ($user) {
                factory(App\Dispatcher::class, 1)->create(['user_id' => $user->id]);
            });
        
        //  crear 2 usuarios adquisiciones que ingresen materiales
        factory(App\User::class, 2)->create()
            ->each( function ($user) {
                factory(App\Acquisition::class, 1)->create(['user_id' => $user->id]);
                factory(App\Dispatcher::class, 1)->create(['user_id' => $user->id]);
            });
        
        //  creamos 15 departamentos del municipio
        //  creamos 3 oficinas por cada departamento
        factory(App\Department::class, 15)->create()
            ->each( function ($department) {
                $department->offices()->saveMany( factory(App\Office::class, 3)->create() );
            });

        //  creamos 10 proveedores y creamos 1 factura por cada proveedor
        factory(App\Provider::class, 10)->create()
            ->each( function ($provider) {
                $provider->invoices()->saveMany( factory(App\Invoice::class, 1)->create() );
            });
        
        //  agregamos 100 materiales, registramos a su vez los 100 ingresos
        factory(App\Material::class, 100)->create();

        //  registramos los 100 ingresos
        factory(App\MaterialInvoice::class, 100)->create();

        //  agregamos 20 ordenes
        factory(App\Order::class, 20)->create()
            ->each( function ($order) {
                $order->materialsOrders()->saveMany( factory(App\MaterialOrder::class, 10)->create(['order_id' => $order->id]) );
            });

        //  agregamos el detalle de las 20 ordenes, con 10 materiales cada una
        //  20 x 10 = 200 registros orden materiales
        // factory(App\MaterialOrder::class, 10)->create();
        
    }
}
