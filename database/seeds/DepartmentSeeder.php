<?php

use App\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            [
                'name'  =>  'Alcaldía'
            ],
            [
                'name'  =>  'Control'
            ],
            [
                'name'  =>  'Secretaría municipal'
            ],
            [
                'name'  =>  'Dirección de administración y finanzas'
            ],
            [
                'name'  =>  'DIDECO'
            ],
            [
                'name'  =>  'Departamento de salud'
            ],
            [
                'name'  =>  'Dirección de obras'
            ],
            [
                'name'  =>  'SECPLAN'
            ],
            [
                'name'  =>  'Departamento social'
            ],
            [
                'name'  =>  'Departamento de educación'
            ]
        ];

        foreach ($departments as $department) {
            
            factory( Department::class )->create( $department );
        }
    }
}
