<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Provider;
use App\Acquisition;

class ProviderController extends Controller
{
    public function __construct () {
        //  middleware autenticación
        $this->middleware('api.auth')->except(['index', 'show']);
        //  middleware comprobación privilegios usuarios adquisiciones
        $this->middleware( sprintf('role:%s', \App\Role::ACQUISITION) )->except(['index', 'show']);
    }

    public function index () {
        $providers = Provider::all();

        return response()->json([
            'status'    =>  'success',
            'code'      =>  200,
            'providers' =>  $providers
        ], 200);
    }

    public function show ( $id ) {

        $provider = Provider::find($id);
        //  evaluamos si viene el proveedor
        if ( is_object($provider) && !empty( $provider ) ) {

            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'provider'  =>  $provider
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'El provedor buscado no existe'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function store (Request $request) {
        //  recoger la data por Post
        $json = $request->input('json', null);
        //  generamos un objeto
        $params = json_decode($json);
        //  generamos un array
        $params_array = json_decode($json, true);
        //  validamos si los parametros del json vienen nulos o el json viene mal formado
        if ( !empty( $params ) && !empty( $params_array ) ) {

            //  personalizar mensajes validaciones
            $messages = [
                'rut.required'          => 'El rut proveedor es requerido',
                'rut.alpha_dash'        =>  'Debe ingresar un rut válido',
                'rut.unique'            =>  'El rut ingresado ya está registrado',
                'name.required'         =>  'El nombre proveedor es requerido',
                'address.required'      =>  'La dirección es obligatoria',
                'url_web.url'           =>  'Debe ingresar una URL de su sitio web válida',
                'phone.numeric'         =>  'Debe ingresar un número de telefono válido',
                'email.required'        =>  'El email es requerido',
                'email.email'           =>  'Debe ingresar un email válido',
                'email.unique'          =>  'El email ingresado ya está registrado'       
            ];
            //  validar los datos
            $validate = Validator::make($params_array, [
                'rut'       =>  'required|alpha_dash|unique:providers,rut',
                'name'      =>  'required',
                'address'   =>  'required',
                'url_web'   =>  'url|nullable',
                'phone'     =>  'numeric|nullable',
                'email'     =>  'required|email|unique:providers,email'
            ], $messages);
            //  verificamos validacion de nuestros datos
            if ( $validate->fails() ) {

                $data = array (
                    'status'    =>  'success',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {

                //  comprobamos si vienen los valores opcionales

                $provider  = new Provider();

                $provider->rut = $params->rut;
                $provider->name = $params->name;
                $provider->address = $params->address;
                $provider->url_web = !empty($params->url_web) ? $params->url_web : null;
                $provider->phone = !empty($params->phone) ? $params->phone : null;
                $provider->email = $params->email;
                //  guardar el proveedor
                $provider->save();
                //  responder la peticion
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'provider'  =>  $provider
                );

            }

        } else {
            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'Error al registrar proveedor, no se ha enviado información'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function update ( Request $request, $id ) {
        $provider = Provider::find($id);

        //  obtenemos la data desde el formulario
        $json = $request->input('json', null);
        $params_array = json_decode( $json, true );
        if ( !empty( $params_array ) && !empty( $provider ) ) {

            //  mensajes personalizados validacion
            $messages = [
                'rut.required'          => 'El rut proveedor es requerido',
                'rut.alpha_dash'        =>  'Debe ingresar un rut válido',
                'rut.unique'            =>  'El rut ingresado pertenece a otro proveedor registrado',
                'name.required'         =>  'El nombre proveedor es requerido',
                'address.required'      =>  'La dirección es obligatoria',
                'url_web.url'           =>  'Debe ingresar una URL de su sitio web válida',
                'phone.numeric'         =>  'Debe ingresar un número de telefono válido',
                'email.required'        =>  'El email es requerido',
                'email.email'           =>  'Debe ingresar un email válido',
                'email.unique'          =>  'El email ingresado ya está registrado para otro proveedor'       
            ];

            //  validar los datos
            $validate = Validator::make($params_array, [
                'rut'       =>  [
                    'required', 
                    'alpha_dash',
                    Rule::unique('providers')->ignore($provider->rut, 'rut')
                ],
                'name'      =>  'required',
                'address'   =>  'required',
                'url_web'   =>  'url|nullable',
                'phone'     =>  'numeric|nullable',
                'email'     =>  [
                    'required',
                    'email',
                    Rule::unique('providers')->ignore($provider->email, 'email')
                ]
            ], $messages);

            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {

                //  eliminar lo que no queremos actualizar
                unset($params_array['id']);
                unset($params_array['created_at']);
                //  validamos los campos opcionales
                $params_array['url_web'] = !empty($params_array['url_web']) ? $params_array['url_web'] : $provider->url_web;
                $params_array['phone'] = !empty($params_array['phone']) ? $params_array['phone'] : $provider->phone;
                //  actualizar el registro en DB
                $provider_updated = Provider::where('id', $provider->id)->updateOrCreate($params_array);
                //  retornar respuesta
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'Datos provedor actualizados exitosamente',
                    'provider_updated'  =>  $provider_updated
                );
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'No se ha enviado la informacion del usuario correctamente'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function destroy ( $id ) {
        //  solo se eliminará un proveedor si no tiene facturas creadas
        //  ya que sino dará errores de restricción de clave foránea en la tabla invoices por la integridad referencial
        //  implementar borrado lógico
        //  comprobar si existe el registro
        $provider = Provider::find($id);
        if ( !empty( $provider ) && isset( $provider->id ) ) {

            try {
                //  Borrar registro
                $provider->delete();
                //  devolver respuesta en caso de exito
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'  =>  'El proveedor ha sido eliminado exitosamente',
                    'provider'  =>  $provider
                );

            } catch (\Exception $ex) {
                //  devolver respuesta en caso de alguna excepcion
                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'message'   =>  'No es posible eliminar el proveedor, ya que tiene facturas registradas en el sistema',
                    'error'     =>  $ex->getMessage()
                );
            }

        } else {
            //  retornamos respuesta en caso de un ID que no existe en BD
            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'  =>  'El proveedor no existe'
            );
        }

        return response()->json($data, $data['code']);
    }
}
