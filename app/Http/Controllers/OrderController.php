<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Helpers\JwtAuth;
use App\Order;
use App\Dispatcher;

class OrderController extends Controller
{
    public function __construct () {

        $this->middleware('cors')->except(['index', 'show']);
        $this->middleware('api.auth')->except(['index', 'show', 'search']);
        $this->middleware( sprintf('role:%s', \App\Role::DISPATCHER ) )->except(['index', 'show', 'search']);
    }

    public function index () {

        $orders = Order::with(['dispatcher.user', 'office'])->paginate(10);
        return response()->json([
            'status'    =>  'success',
            'code'      =>  200,
            'orders'    =>  $orders
        ], 200);
    }

    public function show ( $id ) {

        $order = Order::with(['dispatcher', 'office'])->where('id', $id)->first();
        if ( !empty( $order ) && isset( $order->id ) ) {
            
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'order'     =>  $order
            );

        } else{

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'La orden buscada no existe'
            );
        }

        return response()->json($data, $data['code']);
    }


    public function search ( Request $request ) {
        $search = $request->input('search', null);

        $orders = Order::with(['dispatcher.user', 'office'])->whereLike('num_order', $search)->paginate(10);

        if ( !empty( $orders ) && $orders->count() !== 0 ) {
            //  retornamos los datos
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'orders' =>  $orders
            );

        } else {
            //  retornamos un mensaje de error
            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'No hay resultados para tu busqueda'
            );
        }

        //retornamos la respuesta y el codigo de respuesta http
        return response()->json($data, $data['code']);
    }

    public function store ( Request $request ) {

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if ( !empty( $params ) && !empty( $params_array ) ) {

            $messages = [
                'office_id.required'            =>  'Debe seleccionar una oficina de la lista',
                'office_id.numeric'             =>  'El id de la oficina debe ser un valor numérico válido',
                'name_responsible.required'     =>  'El nombre del responsable de retirar material(es) es obligatorio',
            ];

            $validate = Validator::make($params_array, [
                'office_id'         =>  'required|numeric',
                'name_responsible'  =>  'required'
            ], $messages);

            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {

                $token = $request->header('Authorization');
                $jwtAuth = new JwtAuth();

                $user_auth = $jwtAuth->checkToken($token, true);
                //  buscamos la data del usuario autenticado que realiza la peticion de orden
                $user_dispatcher = Dispatcher::where('user_id', $user_auth->sub)->first();

                $order = new Order();
                //  generamos el número de orden a partir de un identificador único e irrepetible
                $order->num_order = time();
                $order->dispatcher_id = $user_dispatcher->id;
                $order->office_id = $params->office_id;
                $order->name_responsible = $params->name_responsible;

                $order->save();

                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'La orden ha sido creada exitosamente',
                    'order'     =>  $order
                );
                

            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'La información del formulario no ha sido enviada'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function update ( Request $request, $id ) {

        $order = Order::with('dispatcher', 'office')->where('id', $id)->first();
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if ( !empty( $params ) && !empty( $params_array ) && !empty( $order ) ) {

            $messages = [
                'office_id.required'            =>  'Debe seleccionar una oficina de la lista',
                'office_id.numeric'             =>  'El id de la oficina debe ser un valor numérico válido',
                'name_responsible.required'     =>  'El nombre del responsable de retirar material(es) es obligatorio',
            ];

            $validate = Validator::make($params_array, [
                'office_id'         =>  'required|numeric',
                'name_responsible'  =>  'required'
            ], $messages);

            if ( $validate->fails() ) {
                
                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {

                //  comprobamos si el usuario es el autor de la orden
                $checkAutorOrder = $this->checkAutorOrder( $request, $order->id );

                if ( $checkAutorOrder ) {

                    //  quitamos los datos que no deseamos actualizar
                    unset( $params_array['id'] );
                    unset( $params_array['dispatcher_id'] );
                    unset( $params_array['status'] );
                    unset( $params_array['created_at'] );

                    //  actualizamos los datos
                    $order->fill( $params_array )->save();
                    //  enviamos la respuesta
                    $data = array(
                        'status'    =>  'success',
                        'code'      =>  200,
                        'message'   =>  'Los datos de la orden de despacho han sido actualizados exitosamente',
                        'order'     =>  $order
                    );
                    
                } else {
                    
                    $data = array(
                        'status'    =>  'error',
                        'code'      =>  401,
                        'message'   =>  'Accion no autorizada, no eres el autor de esta orden'
                    );
                }

            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'La información de orden no ha sido enviada ó la orden que intenta actualizar no existe'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function destroy ( Request $request, $id ) {

        $order = Order::find( $id );

        if ( !empty( $order ) && isset( $order->id ) ) {

            $checkAutorOrder = $this->checkAutorOrder( $request, $order->id );

            if ( $checkAutorOrder ) {

                //  eliminamos la orden
                try {
    
                    $order->delete();
                    
                    $data = array(
                        'status'    =>  'success',
                        'code'      =>  200,
                        'message'   =>  'La orden se ha eliminado exitosamente',
                        'order'     =>  $order
                    );
    
                } catch ( \Exception $ex ) {
    
                    $data = array(
                        'status'    =>  'error',
                        'code'      =>  500,
                        'message'   =>  'La orden no se puede actualizar'
                    );
                }

            } else {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  401,
                    'message'   =>  'Accion no autorizada, no eres el autor de esta orden'
                );
            }


        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'La orden que deseas eliminar no existe'
            );
        }

        return response()->json($data, $data['code']);
    }


    //  comprobamos si el usuario es el autor de la orden
    private function checkAutorOrder ( $request, $id ) {

        $token = $request->header('Authorization');
        
        $jwtAuth = new JwtAuth();

        $user_auth = $jwtAuth->checkToken($token, true);
        //  buscamos la data del usuario autenticado que realiza la peticion de orden
        $user_dispatcher = Dispatcher::where('user_id', $user_auth->sub)->first();
        //  verificamos si el dispatcher_id de la orden es igual al user_dispatcher id
        $order_user = Order::where('dispatcher_id', $user_dispatcher->id)->where('id', $id)->first();

        //  comprobamos si no es null la consulta order user
        if ( !empty( $order_user ) && isset( $order_user->id ) ) {

            return true;
            
        } else {
            
            return false;
        }
        
    }
}
