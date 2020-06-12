<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\MaterialInvoice;

class MaterialInvoiceController extends Controller
{
    public function __construct () {

        $this->middleware('api.auth')->except(['index', 'show']);
        $this->middleware( sprintf('role:%s', \App\Role::ACQUISITION) )->except(['index', 'show']);
    }

    public function index () {

        $materialsInvoices = MaterialInvoice::all()->load(['material', 'invoice']);

        return response()->json([
            'status'    =>  'success',
            'code'      =>  200,
            'materialsInvoices' =>  $materialsInvoices
        ], 200);
    }

    public function show ( $id ) {

        $materialInvoice = MaterialInvoice::with(['material', 'invoice'])->where('id', $id)->first();

        if ( !empty( $materialInvoice ) && isset( $materialInvoice ) ) {

            $data = array(
                'status'            =>  'success',
                'code'              =>  200,
                'materialInvoice'   =>  $materialInvoice
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'El detalle del ingreso no existe o no está disponible'
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
                'material_id.required'  =>  'Debe seleccionar un material de la lista',
                'invoice_id.required'   =>  'Debe seleccionar una factura de la lista',
                'quantity.required'     =>  'La cantidad ingresada es obligatoria',
                'quantity.numeric'      =>  'La cantidad ingresada debe ser un número',
                'quantity.min'          =>  'La cantidad ingresada debe ser mayor a :min',
                'unity_cost.required'   =>  'El precio unitario es obligatorio',
                'unity_cost.numeric'    =>  'El precio unitario debe ser valor válido',
                'unity_cost.min'        =>  'El costo unitario debe ser que mayor a :min',
            ];

            $validate = Validator::make($params_array, [
                'material_id'   =>  'required',
                'invoice_id'    =>  'required',
                'quantity'      =>  'required|numeric|min:0',
                'unity_cost'    =>  'required|numeric|min:0',
            ], $messages);

            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {

                //  crear objeto modelo MaterialInvoice
                $materialInvoice = new MaterialInvoice();
                $materialInvoice->material_id = $params->material_id;
                $materialInvoice->invoice_id = $params->invoice_id;
                $materialInvoice->quantity = $params->quantity;
                $materialInvoice->unity_cost = $params->unity_cost;
                //  calcular el costo total a partir del costo unitario y la cantidad de unidades
                $materialInvoice->total_cost = $this->calc_total( $materialInvoice->unity_cost, $materialInvoice->quantity );
                //  calcular el iva
                $materialInvoice->iva = $this->calc_iva( $materialInvoice->total_cost );
                //  Guardamos los datos en BD
                $materialInvoice->save();
                //  respondemos
                $data = array(
                    'status'            =>  'success',
                    'code'              =>  200,
                    'message'           =>  'El ingreso material se ha registrado exitosamente',
                    'materialInvoice'   =>  $materialInvoice
                );
            }


        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'No se ha enviado información'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function update ( Request $request, $id ) {

        $materialInvoice = MaterialInvoice::with(['material', 'invoice'])->where('id', $id)->first();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if ( !empty( $params ) && !empty( $params_array ) && !empty( $materialInvoice ) ) {

            $messages = [
                'material_id.required'  =>  'Debe seleccionar un material de la lista',
                'invoice_id.required'   =>  'Debe seleccionar una factura de la lista',
                'quantity.required'     =>  'La cantidad ingresada es obligatoria',
                'quantity.numeric'      =>  'La cantidad ingresada debe ser un número',
                'quantity.min'          =>  'La cantidad ingresada debe ser mayor a :min',
                'unity_cost.required'   =>  'El precio unitario es obligatorio',
                'unity_cost.numeric'    =>  'El precio unitario debe ser valor válido',
                'unity_cost.min'        =>  'El costo unitario debe ser que mayor a :min',
            ];

            $validate = Validator::make($params_array, [
                'material_id'   =>  'required',
                'invoice_id'    =>  'required',
                'quantity'      =>  'required|numeric|min:0',
                'unity_cost'    =>  'required|numeric|min:0',
            ], $messages);

            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {

                //  quitamos los datos que no queremos actualizar
                unset( $params_array['id'] );
                unset( $params_array['created_at'] );

                $params_array['total_cost'] = $this->calc_total( $params_array['unity_cost'], $params_array['quantity'] );
                $params_array['iva'] = $this->calc_iva( $params_array['total_cost'] );

                //  actualizamos
                $materialInvoice->fill($params_array)->save();
                //  enviamos la respuesta
                $data = array(
                    'status'            =>  'success',
                    'code'              =>  200,
                    'message'           =>  'El ingreso del material ha sido actualizado exitosamente',
                    'materialInvoice'   =>  $materialInvoice
                );
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Los datos del formulario no han sido enviados o el ingreso que desea actualizar no existe'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function destroy( $id ) {
        $materialInvoice = MaterialInvoice::with(['material', 'invoice'])->where('id', $id)->first();

        if ( !empty( $materialInvoice ) && isset( $materialInvoice->id ) ) {

            try {
                //  eliminamos el detalle ingreso material
                $materialInvoice->delete();
                //  retornamos la respuesta
                $data = array(
                    'status'            =>  'success',
                    'code'              =>  200,
                    'message'           =>  'El detalle ingreso material ha sido eliminado exitosamente',
                    'materialInvoice'   =>  $materialInvoice
                );

            } catch ( \Exception $ex ) {
                
                $data = array(
                    'status'    =>  'error',
                    'code'      =>  500,
                    'message'   =>  'El ingreso material no se ha podido eliminar',
                    'error'     =>  $ex->getMessage()
                );
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'El detalle material que intentas eliminar no existe'
            );
        }

        return response()->json($data, $data['code']);
    }

    private function calc_total ( $unity_cost, $quantity ) {

        $total_cost = 0;

        if ( is_numeric( $unity_cost ) && is_numeric( $quantity ) ) {
            $total_cost = $unity_cost * $quantity;
        }

        return $total_cost;
    }

    private function calc_iva ( $total_cost ) {

        $iva = 0;

        if ( is_numeric( $total_cost ) && $total_cost > 0 ) {
            $iva = $total_cost * 0.19;
        }

        return $iva;
    }

    
}
