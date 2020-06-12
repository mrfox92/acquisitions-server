<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\MaterialOrder;

class MaterialOrderController extends Controller
{
    public function __construct () {
        //  middleware autenticación
        $this->middleware('api.auth')->except(['index', 'show']);
        //  middleware comprobación privilegios usuarios adquisiciones
        $this->middleware( sprintf('role:%s', \App\Role::DISPATCHER) )->except(['index', 'show']);
    }

    public function index () {

       $materialsOrders = MaterialOrder::all()->load(['material', 'order']);
       
       return response()->json([
           'status'             =>  'success',
           'code'               =>  200,
           'materialsOrders'    =>  $materialsOrders
       ], 200);

    }

    public function show ( $id ) {

        $materialOrder = MaterialOrder::with(['material', 'order'])->where('id', $id)->first();

        if ( !empty( $materialOrder ) && isset( $materialOrder->id ) ) {

            $data = array(
                'status'        =>  'success',
                'code'          =>  200,
                'materialOrder' =>  $materialOrder
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'El material despachado con la orden buscada no existe'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function store ( Request $request ) {

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if ( !empty( $params ) && !empty( $params_array ) ) {

            $messages = [

                'material_id.required'   =>     'Debe seleccinar un material de la lista',
                'order_id.required'      =>     'Debe seleccionar una orden de la lista',
                'quantity.required'      =>     'La cantidad de stock retirado es obligatoria',
                'quantity.numeric'       =>     'La cantidad de stock debe ser un valor numérico',
                'quantity.min'           =>     'La cantidad de stock a retirar mínima debe ser mayor a :min'
            ];

            $validate = Validator::make($params_array, [
                'material_id'   =>  'required',
                'order_id'      =>  'required',
                'quantity'      =>  'required|numeric|min:0'
            ], $messages);

            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {
                // Pendiente: verificamos si la orden seleccinada pertenece solo a las ordenes creadas por el usuario autenticado
                $materialOrder = new MaterialOrder();
                $materialOrder->material_id = $params_array['material_id'];
                $materialOrder->order_id = $params_array['order_id'];
                $materialOrder->quantity = $params_array['quantity'];

                //  guardamos en base de datos
                $materialOrder->save();

                //  respondemos a la peticion
                $data = array(
                    'status'        =>  'success',
                    'code'          =>  200,
                    'message'       =>  'El material se ha añadido a la orden de despacho exitosamente',
                    'materialOrder' =>  $materialOrder
                );

            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'No se ha enviado información desde el formulario'
            );
        }

        return response()->json($data, $data['code']);
    }

    //  verificamos si el order_id corresponde a una orden de la autoria del usuario que require actualizar
    public function update ( Request $request, $id ) {

        $materialOrder = MaterialOrder::with(['material', 'order'])->where('id', $id)->first();

        $json = $request->input('json', null);

        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if ( !empty( $params ) && !empty( $params_array ) && !empty( $materialOrder ) ) {

            $messages = [
                'material_id.required'   =>     'Debe seleccinar un material de la lista',
                'order_id.required'      =>     'Debe seleccionar una orden de la lista',
                'quantity.required'      =>     'La cantidad de stock retirado es obligatoria',
                'quantity.numeric'       =>     'La cantidad de stock debe ser un valor numérico',
                'quantity.min'           =>     'La cantidad de stock a retirar mínima debe ser mayor a :min'
            ];

            $validate = Validator::make($params_array, [
                'material_id'   =>  'required',
                'order_id'      =>  'required',
                'quantity'      =>  'required|numeric|min:0'
            ], $messages);

            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {
                //  quitamos los datos que no deseamos actualizar
                unset( $params_array['id'] );
                unset( $params_array['created'] );

                //  actualizamos los datos
                $materialOrder->fill( $params_array )->save();
                //  enviamos la respuesta
                $data = array(
                    'status'        =>  'success',
                    'code'          =>  200,
                    'message'       =>  'El material a despachar ha sido actualizado exitosamente',
                    'materialOrder' =>  $materialOrder
                );
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'No se ha enviado correctamente la información desde el formulario o el detalle del material a actualizar no existe'
            );
        }

        return response()->json($data, $data['code']);
    }


    //  crear otro método para listar todo el detalle de una orden
    //  considerar crear una especie de carrito de la cual se puedan agregar materiales
    //  agregar la cantidad a despachar, dentro del stock disponible para ese material en inventario
    //  Una vez que se esté seguro de todo lo que se necesita crear la orden
    //  una vez creada la orden de despacho. Agregar el detalle materiales a despachar y descontar del stock
    //  total
    public function order ( $id ) {

        $orderLines = MaterialOrder::with(['material', 'order'])->where('order_id', $id)->get();

        if ( !empty( $orderLines ) && is_null( $orderLines ) ) {

            $data = array(
                'status'        =>  'success',
                'code'          =>  200,
                'orderLines'    =>  $orderLines
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'La orden ingresada no existe'
            );
        }

        return response()->json($data, $data['code']);
    }
    

    //  Debemos considerar que si se elimina el Material de la orden de despacho
    //  Debemos restablecer el stock del material a despachar.
    //  Otra consideración es que no se descuente del stock hasta que el estado del despacho cambie a concluido.
    //  Otra consideración puede ser descontar y reservar el stock a retirar para la orden creada. De esa manera
    //  evitar que otra persona realice otra orden de despacho y la cantidad de materiales esté disponible ocasionando
    //  un problema a nivel de la estructura de la lógica de la aplicación
    public function destroy ( $id ) {

        $materialOrder = MaterialOrder::with(['material', 'order'])->where('id', $id)->first();

        if ( !empty( $materialOrder ) && isset( $materialOrder->id ) ) {

            try {
                //  eliminamos el material de la orden
                $materialOrder->delete();
                //  enviamos la respuesta a la solicitud
                $data = array(
                    'status'        =>  'success',
                    'code'          =>  200,
                    'message'       =>  'Se ha eliminado el material de la orden de despacho exitosamente',
                    'materialOrder' =>  $materialOrder
                );

            } catch ( \Exception $ex ) {
                
                $data = array(
                    'status'    =>  'error',
                    'code'      =>  500,
                    'message'   =>  'No es posible eliminar el detalle despacho material',
                    'error'     =>  $ex->getMessage()
                );
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'El detalle del material no existe'
            );
        }

        return response()->json($data, $data['code']);
    }
}
