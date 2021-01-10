<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Department;

class DepartmentController extends Controller
{
    public function __construct () {

        $this->middleware('cors');
        $this->middleware('api.auth')->except(['index', 'show', 'search']);
        $this->middleware( sprintf('role:%s', \App\Role::ACQUISITION ) )->except(['index', 'show', 'search']);
    }

    public function index () {

        $departments = Department::paginate(10);

        $data = array(
            'status'        =>  'success',
            'code'          =>  200,
            'departments'   =>  $departments
        ); 

        return response()->json($data, $data['code']);

    }

    public function show ( $id ) {

        $department = Department::find($id);
        if ( is_object( $department ) && !empty( $department ) ) {

            $data = array(
                'status'        =>  'success',
                'code'          =>  200,
                'department'    =>  $department
            );

        } else {

            $data = array(
                'status'        =>  'error',
                'code'          =>  404,
                'message'       =>  'El departamento no ha sido encontrado'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function search ( Request $request ) {
        $search = $request->input('search', null);

        $departments = Department::whereLike('name', $search)->paginate(10);

        if ( !empty( $departments ) && $departments->count() !== 0 ) {
            //  retornamos los datos
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'departments' =>  $departments
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
        //  obtenemos la data desde post
        $json = $request->input('json', null);
        //  decodificamos el input json en un objeto
        $params = json_decode($json);
        //  decodificamos el input json en un array
        $params_array = json_decode($json, true);
        //  validamos que el json no venga vacío o mal formado
        if ( !empty( $params ) && !empty( $params_array ) ) {

            //  personalizamos los mensajes del json
            $messages = [
                'name.required'     =>  'El nombre departamento es requerido'
            ];
            //  validamos los datos del json
            $validate = Validator::make($params_array, [
                'name'  =>  'required'
            ], $messages);

            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {

                //  creamos el depto en base de datos
                $department = new Department();
                $department->name = $params_array['name'];
                $department->save();
                //  respondemos a la peticion
                $data = array(
                    'status'        =>  'success',
                    'code'          =>  200,
                    'message'       =>  'El Departamento ha sido creado exitosamente',
                    'department'    =>  $department
                );

            }
        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'No se han enviado datos desde el formulario'
            );
        }

        return response()->json( $data, $data['code'] );

    }

    public function update (Request $request, $id) {
        //  obtenemos el departamento
        $department = Department::where('id', $id)->first();
        //  obtenemos la data desde el formulario
        $json = $request->input('json', null);
        //  decodificamos el json
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        //  validamos la data del json que no venga mal formada
        if ( !empty( $params ) && !empty( $params_array ) && !empty( $department ) ) {

            $messages = [
                'name.required'     =>  'El nombre departamento es obligatorio'
            ];
            //  personalizamos los mensajes de validacion
            $validate = Validator::make($params_array, [
                'name'  =>  'required'
            ], $messages);
            //  evaluamos si la validacion falla
            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );
            } else {

                //  quitamos los datos que no deseamos sean actualizados que podrían venir desde el formulario
                unset( $params_array['id'] );
                unset( $params_array['created_at'] );
                //  actualizamos los datos
                // $department_updated = Department::where('id', $department->id)->update($params_array);
                $department->fill($params_array)->save();
                //  respondemos la peticion con un cod 200
                $data = array(
                    'status'        =>  'success',
                    'code'          =>  200,
                    'message'       =>  'El departamento ha sido actualizado exitosamente',
                    'department'    =>  $department
                );

            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'No se ha enviado información desde el formulario o el departamento que intentas actualizar no existe'
            );
        }

        return response()->json($data, $data['code']);

    }

    public function destroy ($id) {
        //  buscamos el registro en BD
        $department = Department::find($id);
        //  evaluamos si el departamento viene nulo
        if ( !empty( $department ) && isset( $department->id ) ) {

            try {
                $department->delete();

                $data = array(
                    'status'        =>  'success',
                    'code'          =>  200,
                    'message'       =>  'Departamento eliminado exitosamente',
                    'department'    =>  $department
                );

            } catch( \Exception $ex ) {

                $data = array(
                    'status'        =>  'error',
                    'code'          =>  500,
                    'message'       =>  'El Departamento no ha podido ser eliminado',
                    'error'         =>  $ex->getMessage()
                );
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'El departamento no existe'
            );
        }

        return response()->json($data, $data['code']);
    }
}
