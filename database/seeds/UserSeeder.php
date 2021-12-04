<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        factory(App\User::class, 1)->create([
            'name'  =>  'admin',
            'email' =>  'admin@gmail.com',
            'password'  =>  bcrypt('test1234'),
            'role_id'   =>  \App\Role::ADMIN
        ]);

        factory(App\User::class, 1)->create([
            'name'  =>  'test',
            'email' =>  'test@test.com',
            'password'  =>  bcrypt('test1234'),
            'role_id'   =>  \App\Role::GUEST
        ]);
        // ->each( function ($user) {
        //     factory(App\Acquisition::class, 1)->create(['user_id' => $user->id]);
        // });

        //  creamos 5 usuarios para solicitar ordenes de despacho
        factory(App\User::class, 1)->create([
            'name'  =>  'test 1',
            'email' =>  'test1@test.com',
            'password'  =>  bcrypt('test1234'),
            'role_id'   =>  \App\Role::DISPATCHER

        ])->each( function ($user) {
                factory(App\Dispatcher::class, 1)->create([
                    'user_id' => $user->id
                ]);
            });
        
        factory(App\User::class, 1)->create([
            'name'  =>  'test 2',
            'email' =>  'test2@test.com',
            'password'  =>  bcrypt('test1234'),
            'role_id'   =>  \App\Role::DISPATCHER

        ])->each( function ($user) {
                factory(App\Dispatcher::class, 1)->create([
                    'user_id' => $user->id
                ]);
            });
        
        
        //  crear 2 usuarios adquisiciones que ingresen materiales
        factory(App\User::class, 1)->create([
            'name'  =>  'test 3',
            'email' =>  'test3@test.com',
            'password'  =>  bcrypt('test1234'),
            'role_id'   =>  \App\Role::ACQUISITION

        ])->each( function ($user) {
                factory(App\Acquisition::class, 1)->create([
                    'user_id' => $user->id
                ]);
            });

        factory(App\User::class, 1)->create([
            'name'  =>  'test 4',
            'email' =>  'test4@test.com',
            'password'  =>  bcrypt('test1234'),
            'role_id'   =>  \App\Role::ACQUISITION

        ])->each( function ($user) {
                factory(App\Acquisition::class, 1)->create([
                    'user_id' => $user->id
                ]);
            });

          
        // factory(App\User::class, )->create()
        //     ->each( function ($user) {
        //         factory(App\Acquisition::class, 1)->create(['user_id' => $user->id]);
        //         // factory(App\Dispatcher::class, 1)->create(['user_id' => $user->id]);
        //     });
    }
}
