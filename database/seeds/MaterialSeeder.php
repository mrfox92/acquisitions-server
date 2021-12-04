<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use App\Material;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $materiales = [
            
            [
                'bar_code'  => '010343885295',
                'name'      =>  'Cartucho Epson 664  negro',
                'slug'      =>  Str::slug('Cartucho Epson 664  negro'),
                'stock'     =>  2,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '010343885301',
                'name'  =>   'Cartucho Epson 664 Cian',
                'slug'  =>  Str::slug('Cartucho Epson 664 Cian'),
                'stock' => 3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '010343885318',
                'name'  =>   'Cartucho Epson 664 Magenta',
                'slug'  =>  Str::slug('Cartucho Epson 664 Magenta'),
                'stock' => 4,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '010343885325',
                'name'  =>   'Cartucho Epson 664 Yellow',
                'slug'  =>  Str::slug('Cartucho Epson 664 Yellow'),
                'stock' => 3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '041333001098',
                'name'  =>   'PILAS AAA',
                'slug'  =>  Str::slug('PILAS AAA'),
                'stock' => 12,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '041333760643',
                'name'  =>   'PILAS AA',
                'slug'  =>  Str::slug('PILAS AA'),
                'stock' => 12,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '092399311195',
                'name'  =>   'TAMPON DACTILAR ',
                'slug'  =>  Str::slug('TAMPON DACTILAR '),
                'stock' => 0,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '1',
                'name'  =>   'CINTA ADHESIVA EMBALAJE ',
                'slug'  =>  Str::slug('CINTA ADHESIVA EMBALAJE '),
                'stock' => 44,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '10',
                'name'  =>   'BOTELLA DE TINTA AZUL',
                'slug'  =>  Str::slug('BOTELLA DE TINTA AZUL'),
                'stock' => 8,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '11',
                'name'  =>   'SOBRES DE CARTA ',
                'slug'  =>  Str::slug('SOBRES DE CARTA '),
                'stock' => 383,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '12',
                'name'  =>   'SOBRES AMERICANOS ',
                'slug'  =>  Str::slug('SOBRES AMERICANOS '),
                'stock' => 1105,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '13',
                'name'  =>   'LIBRO DE ASISTENCIA 200 HOJAS',
                'slug'  =>  Str::slug('LIBRO DE ASISTENCIA 200 HOJAS'),
                'stock' => 2,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '14',
                'name'  =>   'LIBRO DE ASISTENCIA 50 HOJAS',
                'slug'  =>  Str::slug('LIBRO DE ASISTENCIA 50 HOJAS'),
                'stock' => 2,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '15',
                'name'  =>   'TARJETAS AMERICANAS',
                'slug'  =>  Str::slug('TARJETAS AMERICANAS'),
                'stock' => 0,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '16',
                'name'  =>   'ALFILERES 28MM',
                'slug'  =>  Str::slug('ALFILERES 28MM'),
                'stock' => 5, 
                'unity_type' => Material::PACKAGE
            ],
            [
                'bar_code' => '17',
                'name'  =>   'NOTA ADHESIVAS',
                'slug'  =>  Str::slug('NOTA ADHESIVAS '),
                'stock' => 5,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '17807210011723',
                'name'  =>   'MARCADOR PARA PIZARRA AZUL',
                'slug'  =>  Str::slug('MARCADOR PARA PIZARRA AZUL'),
                'stock' => 24,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '17807210011730',
                'name'  =>   'MARCADOR PARA PIZARRA VERDE',
                'slug'  =>  Str::slug('MARCADOR PARA PIZARRA VERDE'),
                'stock' => 11,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '1pce278a',
                'name'  =>   'TONER HP 78A',
                'slug'  =>  Str::slug('TONER HP 78A'),
                'stock' => 0,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '1pce285a',
                'name'  =>   'TONER HP 85A',
                'slug'  =>  Str::slug('TONER HP 85A'),
                'stock' => 2,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '2',
                'name'  =>   'LAPIZ PASTA ROJO ',
                'slug'  =>  Str::slug('LAPIZ PASTA ROJO '),
                'stock' => 12,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '2006572007032',
                'name'  =>   'SEPARADORES DE CARTA',
                'slug'  =>  Str::slug('SEPARADORES DE CARTA'),
                'stock' => 22,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '216279',
                'name'  =>   'OPOLINA CARTA ',
                'slug'  =>  Str::slug('OPOLINA CARTA '),
                'stock' => 188,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '216280',
                'name'  =>   'OPOLINA OFICIO',
                'slug'  =>  Str::slug('OPOLINA OFICIO'),
                'stock' => 865,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '3 ',
                'name'  =>   'SOBRES OFICIO ',
                'slug'  =>  Str::slug('SOBRES OFICIO '),
                'stock' => 227,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '3100853735870',
                'name'  =>   'CUCHILLO CARTONERO',
                'slug'  =>  Str::slug('CUCHILLO CARTONERO'),
                'stock' => 1,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '3303170027899',
                'name'  =>   'cartucho Canon 211 color',
                'slug'  =>  Str::slug('cartucho Canon 211 color'),
                'stock' => 0,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4',
                'name'  =>   'SOBRE MEDIO OFICIO',
                'slug'  =>  Str::slug('SOBRE MEDIO OFICIO'),
                'stock' => 113,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4100015110001',
                'name'  =>   'APRETA  PAPELES 19MM',
                'slug'  =>  Str::slug('APRETA  PAPELES 19MM'),
                'stock' => 7, 
                'unity_type' => Material::PACKAGE
            ],
            [
                'bar_code' => '4100015154005',
                'name'  =>   'ACOCLIPS METALICOS ',
                'slug'  =>  Str::slug('ACOCLIPS METALICOS '),
                'stock' => 4,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4562195133315',
                'name'  =>   'CALCULADORA ',
                'slug'  =>  Str::slug('CALCULADORA '),
                'stock' => 1,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4710007715062',
                'name'  =>   'Cartucho hp 950 Negro',
                'slug'  =>  Str::slug('Cartucho hp 950 Negro'),
                'stock' => 3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4710007715079',
                'name'  =>   'Cartucho hp 951 Cian',
                'slug'  =>  Str::slug('Cartucho hp 951 Cian'),
                'stock' => 4,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4710007715086',
                'name'  =>   'Cartucho hp 951 Yellow',
                'slug'  =>  Str::slug('Cartucho hp 951 Yellow'),
                'stock' => 4,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4710007715093',
                'name'  =>   'Cartucho hp 951 Magenta',
                'slug'  =>  Str::slug('Cartucho hp 951 Magenta'),
                'stock' => 4,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4717222104015',
                'name'  =>   'ACOCLIPS PASTICOS',
                'slug'  =>  Str::slug('ACOCLIPS PASTICOS'),
                'stock' => 0,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4893055023102',
                'name'  =>   'CORCHETES 23/10',
                'slug'  =>  Str::slug('CORCHETES 23/10'),
                'stock' => 19,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4902505087493',
                'name'  =>   'MARCADOR PERMANENTE ROJO',
                'slug'  =>  Str::slug('MARCADOR PERMANENTE ROJO'),
                'stock' => 0,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4960999904641',
                'name'  =>   'cartucho Canon 151 negro',
                'slug'  =>  Str::slug('cartucho Canon 151 negro'),
                'stock' => 5,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4960999905273',
                'name'  =>   'Cartucho Canon 151 Cian',
                'slug'  =>  Str::slug('Cartucho Canon 151 Cian'),
                'stock' => 2,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4960999905419',
                'name'  =>   'Cartucho Canon 151 Yellow',
                'slug'  =>  Str::slug('Cartucho Canon 151 Yellow'),
                'stock' => 3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4960999905426',
                'name'  =>   'Cartucho Canon 151 Magenta',
                'slug'  =>  Str::slug('Cartucho Canon 151 Magenta'),
                'stock' => 3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '4960999974408',
                'name'  =>   'cartucho Canon 145 negro',
                'slug'  =>  Str::slug('cartucho Canon 145 negro'),
                'stock' => 0,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '5',
                'name'  =>   'TONER BROTHER TN-410',
                'slug'  =>  Str::slug('TONER BROTHER TN-410'),
                'stock' => 3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '505057210094',
                'name'  =>   'APRETA  PAPELES 50 MM',
                'slug'  =>  Str::slug('APRETA  PAPELES 50 MM'),
                'stock' => 2,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '6',
                'name'  =>   'TARJETA OPALINA ',
                'slug'  =>  Str::slug('TARJETA OPALINA '),
                'stock' => 880,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '6922402300035',
                'name'  =>   'APRETA  PAPELES 25MM',
                'slug'  =>  Str::slug('APRETA  PAPELES 25MM'),
                'stock' => 7, 
                'unity_type' => Material::PACKAGE
            ],
            [
                'bar_code' => '6922402300042',
                'name'  =>   'APRETA  PAPELES 32MM',
                'slug'  =>  Str::slug('APRETA  PAPELES 32MM'),
                'stock' => 7, 
                'unity_type' => Material::PACKAGE
            ],
            [
                'bar_code' => '6922402300059',
                'name'  =>   'APRETA  PAPELES 41 MM',
                'slug'  =>  Str::slug('APRETA  PAPELES 41 MM'),
                'stock' => 6,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '6927873611116',
                'name'  =>   'CLIPS 28MM',
                'slug'  =>  Str::slug('CLIPS 28MM'),
                'stock' => 14,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '6935482021684',
                'name'  =>   'cartucho Canon 210 negro',
                'slug'  =>  Str::slug('cartucho Canon 210 negro'),
                'stock' => 2,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7',
                'name'  =>   'SEPARADORES DE OFICIO',
                'slug'  =>  Str::slug('SEPARADORES DE OFICIO'),
                'stock' => 44,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7289462089812',
                'name'  =>   'DISPENSADOR DE CINTA ADHESIVA',
                'slug'  =>  Str::slug('DISPENSADOR DE CINTA ADHESIVA'),
                'stock' => 2,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7805070404269',
                'name'  =>   'SCOTCH 18MMX30M',
                'slug'  =>  Str::slug('SCOTCH 18MMX30M'),
                'stock' => 4,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7806505018150',
                'name'  =>   'CUADERNO UNIVERSITARIO',
                'slug'  =>  Str::slug('CUADERNO UNIVERSITARIO'),
                'stock' => 17,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7806505040908',
                'name'  =>   'CLIPS 33MM',
                'slug'  =>  Str::slug('CLIPS 33MM'),
                'stock' => 10,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7806505985162',
                'name'  =>   'ARCHIVADOR OFICIO',
                'slug'  =>  Str::slug('ARCHIVADOR OFICIO'),
                'stock' => 79,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7806505993853',
                'name'  =>   'ARCHIVADOR DE CARTA',
                'slug'  =>  Str::slug(  'ARCHIVADOR DE CARTA'),
                'stock' =>   17
                , 'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7806610133106',
                'name'  =>   'VISORES ',
                'slug'  =>  Str::slug(  'VISORES '),
                'stock' =>   0,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7806610144614',
                'name'  =>   'CARPETAS DE CARTULINA',
                'slug'  =>  Str::slug(  'CARPETAS DE CARTULINA'),
                'stock' =>   40,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7806610333063',
                'name'  =>   'CAJA DE ARCHIVOS',
                'slug'  =>  Str::slug(  'CAJA DE ARCHIVOS'),
                'stock' =>   91,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807219036164',
                'name'  =>   'REGLA DE 30CM',
                'slug'  =>  Str::slug(  'REGLA DE 30CM'),
                'stock' =>   6,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807245218008', 
                'name'  =>   'CARPETAS COLGANTES',
                'slug'  =>  Str::slug(  'CARPETAS COLGANTES'),
                'stock' =>   7,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807247100219',
                'name'  => 'LIBRO DE ACTA ',
                'slug'  =>  Str::slug('LIBRO DE ACTA '),
                'stock' =>   10,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807247100519',
                'name'  => 'LIBRETA DE CORRESPONDENCIA ',
                'slug'  =>  Str::slug('LIBRETA DE CORRESPONDENCIA '),
                'stock' =>   13,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807265010026',
                'name'  => 'TIJERA 14 CM',
                'slug'  =>  Str::slug('TIJERA 14 CM'),
                'stock' =>   3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807265034664',
                'name'  => 'MARCADOR PARA PIZARRA ROJO',
                'slug'  =>  Str::slug('MARCADOR PARA PIZARRA ROJO'),
                'stock' =>   17,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807265035388',
                'name'  => 'MARCADOR PARA PIZARRA NEGRO',
                'slug'  =>  Str::slug('MARCADOR PARA PIZARRA NEGRO'),
                'stock' =>   12,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807265038242',
                'name'  => 'PERFORADORA ',
                'slug'  =>  Str::slug('PERFORADORA '),
                'stock' =>   1,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807265038426',
                'name'  => 'CORCHETERA',
                'slug'  =>  Str::slug('CORCHETERA'),
                'stock' =>   0,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807265980503',
                'name'  => 'PEGAMENTO EN BARRA ',
                'slug'  =>  Str::slug('PEGAMENTO EN BARRA '),
                'stock' =>   14,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807265984204',
                'name'  => 'DESTACADOR COLOR CELESTE',
                'slug'  =>  Str::slug('DESTACADOR COLOR CELESTE'),
                'stock' =>   20,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807265986376',
                'name'  => 'DESTACADOR COLOR VERDE',
                'slug'  =>  Str::slug('DESTACADOR COLOR VERDE'),
                'stock' =>   9,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7807265988608',
                'name'  => 'CORCHETES 26/6',
                'slug'  =>  Str::slug('CORCHETES 26/6'),
                'stock' =>   35,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7808718510766',
                'name'  => 'LAPIZ PASTA AZUL',
                'slug'  =>  Str::slug('LAPIZ PASTA AZUL'),
                'stock' =>   68,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7861186201710',
                'name'  => 'LAPIZ PASTA NEGRO',
                'slug'  =>  Str::slug('LAPIZ PASTA NEGRO'),
                'stock' =>   54,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7891173022882',
                'name'  => 'RESMA CARTA',
                'slug'  =>  Str::slug('RESMA CARTA'),
                'stock' =>   30,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7891173022929',
                'name'  => 'RESMA OFICIO',
                'slug'  =>  Str::slug('RESMA OFICIO'),
                'stock' =>   30,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '7891360617297',
                'name'  => 'LÁPIZ GRAFITO',
                'slug'  =>  Str::slug('LÁPIZ GRAFITO'),
                'stock' =>   11,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '8',
                'name'  => 'BOTELLA DE TINTA VIOLETA',
                'slug'  =>  Str::slug('BOTELLA DE TINTA VIOLETA'),
                'stock' =>   5,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '808736847056',
                'name'  => 'Cartucho hp 75 Color',
                'slug'  =>  Str::slug('Cartucho hp 75 Color'),
                'stock' =>   0,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '829160399805',
                'name'  => 'Cartucho hp 94 Negro',
                'slug'  =>  Str::slug('Cartucho hp 94 Negro'),
                'stock' =>   2,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '829160399812',
                'name'  => 'Cartucho hp 95 Color',
                'slug'  =>  Str::slug('Cartucho hp 95 Color'),
                'stock' =>   1,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '8414034620202',
                'name'  => 'GOMA',
                'slug'  =>  Str::slug('GOMA'),
                'stock' =>   22,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '8854556000180',
                'name'  => 'CORRECTOR  ',
                'slug'  =>  Str::slug('CORRECTOR  '),
                'stock' =>   11,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '886112670115',
                'name'  => 'cartucho hp 662 negro',
                'slug'  =>  Str::slug('cartucho hp 662 negro'),
                'stock' =>   3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '886112670122',
                'name'  => 'Cartucho hp 662 Color',
                'slug'  =>  Str::slug('Cartucho hp 662 Color'),
                'stock' =>   5,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '886112841133',
                'name'  => 'Cartucho hp 711 Cian',
                'slug'  =>  Str::slug('Cartucho hp 711 Cian'),
                'stock' =>   4,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '886112841140',
                'name'  => 'Cartucho hp 711 Magenta',
                'slug'  =>  Str::slug('Cartucho hp 711 Magenta'),
                'stock' =>   4,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '886112841157',
                'name'  => 'Cartucho hp 711 Yellow',
                'slug'  =>  Str::slug('Cartucho hp 711 Yellow'),
                'stock' =>   4,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '886112890667',
                'name'  => 'cartucho hp 711 negro',
                'slug'  =>  Str::slug('cartucho hp 711 negro'),
                'stock' =>   3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '88929689509',
                'name'  => 'Cartucho hp 954 Cian',
                'slug'  =>  Str::slug('Cartucho hp 954 Cian'),
                'stock' =>   0,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '889296895091',
                'name'  => 'Cartucho hp 954 Cian',
                'slug'  =>  Str::slug('Cartucho hp 954 Cian'),
                'stock' =>   3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '88929689511',
                'name'  => 'Cartucho hp 954 Magenta',
                'slug'  =>  Str::slug('Cartucho hp 954 Magenta'),
                'stock' =>   1,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '889296895114',
                'name'  => 'Cartucho hp 954 Magenta',
                'slug'  =>  Str::slug('Cartucho hp 954 Magenta'),
                'stock' =>   3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '88929689513',
                'name'  => 'Cartucho hp 954 Yellow',
                'slug'  =>  Str::slug('Cartucho hp 954 Yellow'),
                'stock' =>   1,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '889296895138',
                'name'  => 'Cartucho hp 954 Yellow',
                'slug'  =>  Str::slug('Cartucho hp 954 Yellow'),
                'stock' =>   3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '88929689515',
                'name'  => 'cartucho hp 954 negro',
                'slug'  =>  Str::slug('cartucho hp 954 negro'),
                'stock' =>   10,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '889296895152',
                'name'  => 'Cartucho hp 954 negro',
                'slug'  =>  Str::slug('Cartucho hp 954 negro'),
                'stock' =>   3,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '9',
                'name'  => 'BOTELLA DE TINTA ROJA ',
                'slug'  =>  Str::slug('BOTELLA DE TINTA ROJA '),
                'stock' =>   10,
                'unity_type' => Material::UNITY
            ],
            [
                'bar_code' => '9555022836447',
                'name'  => 'CHINCHES PUSH PIN ',
                'slug'  =>  Str::slug('CHINCHES PUSH PIN '),
                'stock' =>   1,
                'unity_type' => Material::UNITY
            ],
        ];

        //  agregamos 100 materiales, registramos a su vez los 100 ingresos
        foreach ($materiales as $material) {
            # code...
            factory( Material::class )->create( $material );
        }
    }
}
