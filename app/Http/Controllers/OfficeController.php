<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Office;
use App\Department;

class OfficeController extends Controller
{
    public function __construct () {
        $this->middleware('cors');
        $this->middleware('api.auth')->except(['index', 'show', 'getDeptos', 'search']);

        $this->middleware( sprintf('role:%s', \App\Role::ACQUISITION) )->except(['index', 'show', 'getDeptos', 'search']);
    }

    public function index () {

        $offices = Office::with(['department'])->paginate(10);

        $data = array(
            'status'    =>  'success',
            'code'      =>  200,
            'offices'   =>  $offices
        );

        return response()->json($data, $data['code']);
    }

    public function getDeptos () {
        $departments = Department::all();

        $data = array(
            'status'        =>  'success',
            'code'          =>  200,
            'departments'   =>  $departments
        );

        return response()->json($data, $data['code']);
    }

    public function show ( $id ) {

        $office = Office::with('department')->where('id', $id)->first();

        if ( !empty( $office ) && isset( $office->id ) ) {

            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'office'    =>  $office
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Oficina no encontrada'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function search ( Request $request ) {

        $search = $request->input('search', null);

        $offices = Office::with(['department'])->whereLike('name', $search)->paginate(10);

        if ( !empty( $offices ) && $offices->count() !== 0 ) {

            $data = array(
                'status'    =>  'success',
                'code'      =>   200,
                'offices'   =>  $offices
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>   404,
                'message'   =>  'Busqueda sin resultados'
            );
        }

        return response()->json($data, $data['code']);

    }

    public function store ( Request $request ) {

        //  obtenemos la data desde el form
        $json = $request->input('json', null);
        //  decodificamos el json con la data
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        //  validamos los datos
        if ( !empty( $params ) && !empty( $params_array ) ) {

            $messages = [
                'name.required'             =>  'El nombre de la oficina es obligatorio',
                'department_id.required'    =>  'Debe seleccionar un departamento de la lista'
            ];
            //  validamos los datos
            $validate = Validator::make($params_array, [
                'name'  =>  'required',
                'department_id' =>  'required'
            ], $messages);
            
            if ( $validate->fails() ) {
                
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {

                //  creamos los datos en BD
                $office = Office::create([
                    'name'  =>  $params->name,
                    'department_id' =>  $params->department_id
                ]);

                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'Oficina creada exitosamente',
                    'office'    =>  $office
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

    public function update ( Request $request, $id ) {

        //  buscamos el id de la oficina para comprobar si existe
        $office = Office::find($id);
        //  obtenemos la data del form
        $json = $request->input('json', null);
        //  decodificamos la data del json
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        if ( !empty( $params ) && !empty( $params_array ) && !empty( $office ) ) {

            $messages = [
                'name.required'             =>  'El nombre de la oficina es obligatorio',
                'department_id.required'    =>  'Debes seleccionar un departamento de la lista'  
            ];
            $validate = Validator::make($params_array, [
                'name'          =>  'required',
                'department_id' =>  'required'
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
                unset( $params_array['department'] );
                unset( $params_array['created_at'] );

                //  actualizamos los datos
                $office->fill($params_array)->save();

                //  respondemos
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'Los datos de la oficina han sido actualizados exitosamente',
                    'office'    =>  $office
                );

            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'No se ha recibido la informacion del formulario o la oficina que deseas actualizar no existe'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function destroy ( $id ) {

        //  buscamos y comprobamos si existe el registro en base de datos
        $office = Office::find($id);
        //  verificamos si office viene con la data
        if ( !empty( $office ) && isset( $office->id ) ) {

            try {
                $office->delete();

                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'La oficina ha sido eliminada exitosamente',
                    'office'    =>  $office
                );

            } catch ( \Exception $ex ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  500,
                    'message'   =>  'Error al eliminar la oficina'
                );
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'La oficina que intentas eliminar no existe'
            );
        }
        
        return response()->json($data, $data['code']);
    }
}
