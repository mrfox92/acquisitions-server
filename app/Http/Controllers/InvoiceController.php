<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Invoice;
use App\Provider;

class InvoiceController extends Controller
{

    public function __construct() {
        $this->middleware('cors');
        $this->middleware('api.auth')->except(['index', 'show', 'getProviders', 'search', 'getInvoicesProvider']);
        $this->middleware( sprintf('role:%s', \App\Role::ACQUISITION ) )->except(['index', 'show', 'getProviders', 'search', 'getInvoicesProvider']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::with(['provider'])->paginate(10);

        if ( !empty( $invoices ) && $invoices->count() !== 0 ) {

            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'invoices'  =>  $invoices

            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'No hay facturas registradas'

            );

        }

        return response()->json($data, $data['code']);
    }

    public function getProviders () {

        $providers = Provider::all();

        return response()->json([
            'status'    =>  'success',
            'code'      =>  200,
            'providers' =>  $providers
        ], 200);
    }

    //  metodo obtener data del usuario adquisiciones, comprobar a traves del token que sea el mismo usuario logeado actualmente el que realiza la acción
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if ( !empty( $params ) && !empty( $params_array ) ) {

            //  mensajes personalizados validaciones
            $messages = [
                'invoice_number.required'   =>  'El N° de factura es requerido',
                'invoice_number.numeric'    =>  'El N° de factura debe ser un valor numérico',
                'acquisition_id.required'   =>  'El ID usuario adquisiciones es requerido',
                'provider_id.required'      =>  'El ID proveedor es obligatorio, ya que toda factura debe pertenecer a un determinado proveedor',
            ];
            //  validamos los datos
            $validate = Validator::make($params_array, [
                'invoice_number'    =>  'required|numeric',
                'acquisition_id'    =>  'required',
                'provider_id'       =>  'required'               
            ], $messages);

            //  evaluamos si hay fallos en la validacion
            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else{

                //  creamos nuestra factura
                $invoice = new Invoice();
                $invoice->invoice_number = $params_array['invoice_number'];
                $invoice->acquisition_id = $params_array['acquisition_id'];
                $invoice->provider_id = $params_array['provider_id'];
                $invoice->emission_date = (!empty($params_array['emission_date'])) ? $params_array['emission_date'] : null;
                $invoice->expiration_date = (!empty($params_array['expiration_date'])) ? $params_array['expiration_date'] : null;
                //  guardamos los cambios
                $invoice->save();
                //  respondemos la peticion
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  201,
                    'message'   =>  'La factura ha sido registrada exitosamente',
                    'invoice'   =>  $invoice
                );
            }


        } else{

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'No se ha enviado la información correctamente desde el formulario'
            );
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show ( $id ) {

        $invoice = Invoice::with(['provider'])->find( $id );

        if ( is_object( $invoice ) && !empty( $invoice ) ) {

            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'invoice'   =>  $invoice
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'No hemos encontrado factura con ese ID'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function getInvoicesProvider ( $id ) {

        $invoices = Invoice::where('provider_id', $id)->get();

        if ( is_object( $invoices ) && !empty( $invoices ) ) {

            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'invoices'   =>  $invoices
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'No hay facturas registradas para este proveedor'
            );
        }

        return response()->json($data, $data['code']);
    }


    public function search ( Request $request ) {
        $search = $request->input('search', null);

        $invoices = Invoice::with(['provider'])->whereLike('invoice_number', $search)->paginate(10);

        if ( !empty( $invoices ) && $invoices->count() !== 0 ) {
            //  retornamos los datos
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'invoices' =>  $invoices
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        if ( !empty( $params ) && !empty( $params_array ) && !empty( $invoice ) ) {

            //  mensajes personalizados validaciones
            $messages = [
                'invoice_number.required'   =>  'El N° de factura es requerido',
                'invoice_number.numeric'    =>  'El N° de factura debe ser un valor numérico',
                'acquisition_id.required'   =>  'El ID usuario adquisiciones es requerido',
                'provider_id.required'      =>  'El ID proveedor es obligatorio, ya que toda factura debe pertenecer a un determinado proveedor',
            ];
            //  validamos los datos
            $validate = Validator::make($params_array, [
                'invoice_number'    =>  'required|numeric',
                'acquisition_id'    =>  'required',
                'provider_id'       =>  'required'               
            ], $messages);

            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {

                //  limpiamos los datos que no deseamos actualizar
                unset( $params_array['id'] );
                unset( $params_array['created_at'] );
    
                //  actualizamos los datos
                $invoice->fill( $params_array )->save();
                //  respondemos a la petición
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'Los datos de la factura han sido actualizados con éxito',
                    'invoice'   =>  $invoice
                );
            }


        } else {
            
            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'No se ha enviado la información desde el formulario o la factura que intentas actualizar no existe'
            );
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy ( $id ) {
        $invoice = Invoice::find($id);

        if ( !empty( $invoice ) && isset( $invoice->id ) ) {

            try {
                //  eliminamos la factura
                $invoice->delete();

                //  respondemos la peticion
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'La factura ha sido eliminada con éxito',
                    'invoice'   =>  $invoice
                );

            } catch( \Exception $ex ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  500,
                    'error'     =>  $ex->getMessage(),
                    'message'   =>  'La factura no puede eliminarse ya que contiene detalle de materiales ingresados'
                );
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'La factura que intentas eliminar no existe'
            );
        }

        return response()->json($data, $data['code']);
    }
}
