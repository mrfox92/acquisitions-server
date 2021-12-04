<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Role::class, 1)->create(['name' => 'admin']);
        factory(Role::class, 1)->create(['name' => 'acquisition']);
        factory(Role::class, 1)->create(['name' => 'dispatcher']);
        factory(Role::class, 1)->create(['name' => 'guest']);
    }
}
