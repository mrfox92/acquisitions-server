<?php

namespace App\Http\Controllers;

use App\Department;
use App\Dispatcher;
use App\Helpers\JwtAuth;
use App\Material;
use App\MaterialOrder;
use App\Office;
use App\Order;
use App\OrderOffice;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DispatcherController extends Controller
{
    public function index () {
        $materials = Material::where('stock', '!=', 0)->paginate(20);


        if ( !empty( $materials ) && is_object( $materials ) ) {
 
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'materials' =>  $materials
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'No hay resultados que cargar'
            );
        }

        return response()->json($data, $data['code']);

    }

    public function getOrders(Request $request) {

        $token = $request->header('Authorization');

        $jwtAuth = new JwtAuth();

        $decoded = $jwtAuth->checkToken($token, true);

        //  buscar en tabla despachador
        $dispatcher = Dispatcher::where('user_id', $decoded->sub )->first();

        $orders = Order::with(['offices', 'materialsOrders.material'])
            ->where('dispatcher_id', $dispatcher->id )
            ->where('status', '!=', Order::ENABLED)
            ->get();

        if ( !empty( $orders ) && isset( $orders ) ) {

            $data = array(
                'status'        =>  'success',
                'code'          =>  200,
                'orders'          =>  $orders
            ); 

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'No se han encontrado ordenes'
            );
        }

        // $data = array(
        //     'status'        =>  'success',
        //     'code'          =>  200,
        //     'orders'          =>  $dispatcher
        // ); 

        return response()->json($data, $data['code']);
    }

    public function getSearchResults( $search ) {

        if( $search ) {

            $materials = Material::where('name', 'LIKE', '%' . $search . '%')->get();

            $data = array(
                'status'      =>  'success',
                'code'        =>  200,
                'materials'   =>  $materials
            ); 

        } else {

            $materials = [];

            $data = array(
                'status'      =>  'success',
                'code'        =>  404,
                'materials'   =>  $materials
            ); 
        }

        return response()->json($data, $data['code']);

    }

    public function deptosList () {

        $departments = Department::all();

        $data = array(
            'status'        =>  'success',
            'code'          =>  200,
            'departments'   =>  $departments
        ); 

        return response()->json($data, $data['code']);
    }

    public function officesList ( $id ) {
        $offices = Office::where('department_id', $id)->get();
        
        $data = array(
            'status'        =>  'success',
            'code'          =>  200,
            'offices'       =>  $offices
        ); 

        return response()->json($data, $data['code']);
    }

    public function show ( $id ) {

        $material = Material::find( $id );

        if ( is_object( $material ) && !empty( $material ) ) {

            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'material'  =>  $material
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Material no encontrado'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function getDispatcher( Request $request ) {

        //  obtenemos el token
        $token = $request->header('Authorization');

        $jwtAuth = new JwtAuth();
        $userDecoded = $jwtAuth->checkToken( $token, true );

        $dispatcher = Dispatcher::where('user_id', $userDecoded->sub)->first();

        if ( !empty( $dispatcher ) && isset( $dispatcher->id ) ) {

            $data = array(
                'status'        =>  'success',
                'code'          =>  200,
                'dispatcher'    =>  true
            );

        } else {

            $data = array(
                'status'        =>  'error',
                'code'          =>  404,
                'message'       =>  'usuario adquisiciones no encontrado',
                'dispatcher'    =>  false
            );
        }

        return response()->json($data, $data['code']);
    }

    public function order ( $id ) {

        $orden = Order::with(['materialsOrders.material'])->where('id', $id)->first();

        if ( is_object( $orden ) && !empty( $orden ) ) {

            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'orden'  =>  $orden
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Orden no encontrado'
            );
        }

        return response()->json($data, $data['code']);
    }


    public function store( Request $request ) {
        //  obtenemos la data desde el form
        $json = $request->input('json', null);
        //  obtenemos el token
        $token = $request->header('Authorization');
        //  decodificamos el json con la data
        $params = json_decode($json);
        //  decodificamos en un arreglo
        $params_array = json_decode($json, true);
        //  validamos los datos
        if ( !empty( $params ) && !empty( $params_array ) ) {


            $jwtAuth = new JwtAuth();
            $userDecoded = $jwtAuth->checkToken( $token, true );

            $dispatcher = Dispatcher::where('user_id', $userDecoded->sub)->first();

            if ( !empty( $params->orden->id ) && isset( $params->orden->id ) ) {

                //  si viene el id de la orden, buscamos la orden y agregamos su detalle
                $orden = Order::find( $params->orden->id );

            } else {

                //  si no existe, creamos la orden y agregamos su detalle
                //  instancia de nueva orden
                $orden = Order::create([
                    'dispatcher_id' =>  $dispatcher->id
                ]);
            }

            //  agregar detalle orden
            $orderDetail = MaterialOrder::create([
                'material_id'   =>  $params->idProducto,
                'order_id'      =>  $orden->id,
                'quantity'      =>  $params->qty
            ]);

            //  buscar producto y descontar material
            $material = Material::find( $params->idProducto );

            $material->stock = ($material->stock - $params->qty);
            $material->save();
            $orderDetail->material = $material;

            $data = array(
                'status'            =>  'success',
                'code'              =>  200,
                'message'           =>  'Material agregado con éxito',
                'user'              =>  $userDecoded,
                'orden'             =>  $orden,
                'material'          =>  $material,
                'orderDetail'       =>  $orderDetail
            );

        } else {
            $data = array(
                'status'    =>  'success',
                'code'      =>  400,
                'message'   =>  'No hemos recibido información desde el formulario'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function processingOrder( Request $request ) {

        //  obtenemos la data desde el form
        $json = $request->input('json', null);
        //  decodificamos el json con la data
        $params = json_decode($json);
        //  decodificamos en un arreglo
        $params_array = json_decode($json, true);
        //  validamos los datos
        if ( !empty( $params ) && !empty( $params_array ) ) {

            //  TODO: obtener orden
            $order = Order::find( $params->id );

            if ( !empty( $order )  && isset( $order->id ) ) {

                //  actualizar name_responsible
                $order->name_responsible = $params->responsable;
                //  actualizar status 
                $order->status = Order::PROCESING;
                //  grabar cambios
                $order->save();
                //  agregar order_office
                $order_office = OrderOffice::create([
                    'order_id'  =>  $order->id,
                    'office_id' =>  $params->office_id
                ]);

                //  enviar orden actualizada
    
                $data = array(
                    'status'            =>  'success',
                    'code'              =>  200,
                    'message'           =>  'La orden ha sido creada y está siendo procesada',
                    'orden'             =>  $order,
                    'order_office'      =>  $order_office
                );

            } else {
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  404,
                    'message'   =>  'La orden a procesar no ha sido encontrada'
                );
            }

        } else {
            $data = array(
                'status'    =>  'success',
                'code'      =>  400,
                'message'   =>  'No hemos recibido información desde el formulario'
            );
        }

        return response()->json($data, $data['code']);

    }


    public function deleteItem( $id ) {
        //  buscar material por su id

        $orderDetail = MaterialOrder::find( $id );
        //  agregar qty a material
        //  eliminar elemento

        if ( !empty( $orderDetail ) && isset( $orderDetail->id ) ) {


            $material = Material::find( $orderDetail->material_id );

            $material->stock = $material->stock + $orderDetail->quantity;

            $material->save();

            try {

                
                //  finalmente eliminamos el registro
                $orderDetail_deleted = $orderDetail->delete();

                $data = array(
                    'status'        =>  'success',
                    'code'          =>  200,
                    'message'       =>  'El material se ha eliminado exitosamente del detalle de la orden',
                    'orderDetail'   =>  $orderDetail,
                    'eliminado'     =>  $orderDetail_deleted
                );

            } catch ( \Exception $ex ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  500,
                    'message'   =>  'El material no se ha podido eliminar de la orden',
                    'error'     =>  $ex->getMessage()
                );
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'El item que desea eliminar no existe'
            );
        }

        return response()->json($data, $data['code']);

    }


}
