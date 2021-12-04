<?php

use App\Office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $offices = [
            //  Alcaldía
            [
                'department_id' =>  1,
                'name'          =>  'Asesoría jurídica'
            ],
            [
                'department_id' =>  1,
                'name'          =>  'Concejo'
            ],
            [
                'department_id' =>  1,
                'name'          =>  'COSOC'
            ],
            //  Control
            [
                'department_id' =>  2,
                'name'          =>  'Oficina control'
            ],
            //  Secretaría municipal
            [
                'department_id' =>  3,
                'name'          =>  'OIRS'
            ],
            [
                'department_id' =>  3,
                'name'          =>  'Oficina de partes'
            ],
            [
                'department_id' =>  3,
                'name'          =>  'Transparencia'
            ],
            //  Dirección de administración y finanzas
            [
                'department_id' =>  4,
                'name'          =>  'Personal'
            ],
            [
                'department_id' =>  4,
                'name'          =>  'Tránsito'
            ],
            [
                'department_id' =>  4,
                'name'          =>  'Adquisiciones'
            ],
            [
                'department_id' =>  4,
                'name'          =>  'Tesorería'
            ],
            [
                'department_id' =>  4,
                'name'          =>  'Informática'
            ],
            [
                'department_id' =>  4,
                'name'          =>  'Contabilidad'
            ],
            [
                'department_id' =>  4,
                'name'          =>  'Rentas'
            ],
            //  DIDECO
            [
                'department_id' =>  5,
                'name'          =>  'OMJ'
            ],
            [
                'department_id' =>  5,
                'name'          =>  'Oficina de deportes'
            ],
            [
                'department_id' =>  5,
                'name'          =>  'Prensa y medios'
            ],
            [
                'department_id' =>  5,
                'name'          =>  'Seguridad ciudadana'
            ],
            //  Departamento de salud
            [
                'department_id' =>  6,
                'name'          =>  'CESFAM'
            ],
            [
                'department_id' =>  6,
                'name'          =>  'CECOSF'
            ],
            [
                'department_id' =>  6,
                'name'          =>  'Postas rurales'
            ],
            //  Dirección de obras
            [
                'department_id' =>  7,
                'name'          =>  'Oficina dirección de obras'
            ],
            //  SECPLAN
            [
                'department_id' =>  8,
                'name'          =>  'Emergencia'
            ],
            [
                'department_id' =>  8,
                'name'          =>  'Medioambiente'
            ],
            [
                'department_id' =>  8,
                'name'          =>  'Entidad patrocinante'
            ],
            [
                'department_id' =>  8,
                'name'          =>  'Oficina proyectos'
            ],
            [
                'department_id' =>  8,
                'name'          =>  'Fomento productivo y turismo'
            ],
            [
                'department_id' =>  8,
                'name'          =>  'PRODESAL'
            ],
            [
                'department_id' =>  8,
                'name'          =>  'PDTI'
            ],
            [
                'department_id' =>  8,
                'name'          =>  'Oficina indígena'
            ],
            //  Departamento social
            [
                'department_id' =>  9,
                'name'          =>  'OMIL'
            ],
            [
                'department_id' =>  9,
                'name'          =>  'Oficina de la mujer'
            ],
            [
                'department_id' =>  9,
                'name'          =>  'Area social'
            ],
            [
                'department_id' =>  9,
                'name'          =>  'Programas sociales'
            ],
            //  Departamento de educación
            [
                'department_id' =>  10,
                'name'          =>  'Establecimientos educacionales'
            ],
            [
                'department_id' =>  10,
                'name'          =>  'Casa de la cultura'
            ],
            [
                'department_id' =>  10,
                'name'          =>  'Biblioteca'
            ],
            [
                'department_id' =>  10,
                'name'          =>  'Extra escolar'
            ],
            [
                'department_id' =>  10,
                'name'          =>  'Jardines infantiles'
            ],
        ];

        foreach ($offices as $office) {
            factory( Office::class )->create( $office );
        }
    }
}
