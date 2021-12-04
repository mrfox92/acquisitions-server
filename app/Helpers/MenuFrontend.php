<?php
namespace App\Helpers;

use App\Role;

class MenuFrontend {

    public static function getMenu ($role = Role::GUEST) {


        //  menu administrador
        //  menu adquisiciones
        //  menu despachadores
        //  menu invitado
        
        $menu = array([
            'titulo'    => 'Mantenimientos',
            'icono'     => 'mdi mdi-book-open',
            'submenu'   => []
        ]);

        // $submenu = array();


        switch ( $role ) {
            case Role::ADMIN:
                array_push( $menu[0]['submenu'], [ 'titulo'   => 'Usuarios', 'url' => '/usuarios' ]);
                array_push( $menu[0]['submenu'], [ 'titulo'    => 'Adquisiciones', 'url'   => '/adquisiciones' ]);
                array_push( $menu[0]['submenu'], [ 'titulo'    => 'Despachadores', 'url'   => '/despachadores' ] );
                array_push( $menu[0]['submenu'], [ 'titulo'    => 'Departamentos', 'url'   => '/departamentos' ] );
                array_push( $menu[0]['submenu'], [ 'titulo'    => 'Oficinas', 'url'        => '/oficinas' ] );
                
                break;
            case Role::ACQUISITION:

                // array_push( $menu[0]['submenu'], [ 'titulo'    => 'Adquisiciones', 'url'   => '/adquisiciones' ]);
                array_push( $menu[0]['submenu'], [ 'titulo'    => 'Proveedores', 'url'         => '/proveedores' ] );
                array_push( $menu[0]['submenu'], [ 'titulo'    => 'Facturas', 'url'            => '/facturas' ] );
                array_push( $menu[0]['submenu'], [ 'titulo'    => 'Materiales', 'url'          => '/materiales' ] );
                array_push( $menu[0]['submenu'], [ 'titulo'    => 'Ingresos Materiales', 'url' => '/ingresos' ] );
                array_push( $menu[0]['submenu'], [ 'titulo'    => 'Ordenes Despacho', 'url'    => '/ordenes' ] );

                break;

            case Role::DISPATCHER:

                // array_push( $menu[0]['submenu'], [ 'titulo'    => 'Despachadores', 'url'    => '/despachadores' ] );
                array_push( $menu[0]['submenu'], [ 'titulo'    => 'Mis Ordenes', 'url'          => '/mis-ordenes' ] );
                // array_push( $menu[0]['submenu'], [ 'titulo'    => 'Ordenes Despacho', 'url'    => '/ordenes' ] );
                array_push( $menu[0]['submenu'], [ 'titulo'    => 'Inventario', 'url'    => '/inventario' ] );
                
                break;

            case Role::GUEST:
                
                // array_push( $menu[0]['submenu'], [ 'titulo'    => 'Materiales', 'url'  => '/materiales' ] );
                break;
            
            default:
            //  TODO: borrar al final
                // array_push( $menu[0]['submenu'], [ 'titulo'    => 'Materiales', 'url'  => '/materiales' ] );
                break;
        }

        return $menu;
        
    }
}
