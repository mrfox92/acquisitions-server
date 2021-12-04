<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        //  eliminamos los directorios
        Storage::deleteDirectory('materials');
        Storage::deleteDirectory('users');

        //  creamos los directorios
        Storage::makeDirectory('materials');
        Storage::makeDirectory('users');

        $this->call( RoleSeeder::class );
        $this->call( UserSeeder::class );
        $this->call( DepartmentSeeder::class );
        $this->call( OfficeSeeder::class );
        $this->call( ProviderSeeder::class );
        $this->call( MaterialSeeder::class );
        // $this->call( MaterialInvoiceSeeder::class );
        // $this->call( MaterialOrderSeeder::class );
        
    }
}
