<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UploadImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; //  reglas de validacion utilizar unique de una forma mas controlada
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Material;
use App\Acquisition;
use App\Helpers\JwtAuth;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    //  aplicamos el middleware de autenticacion y creamos excepcion para las rutas index y show
    public function __construct () {
        //  middleware auth
        $this->middleware('cors');
        $this->middleware('api.auth')->except(['index', 'show', 'getImage', 'search', 'getMaterials']);
        //  middleware role
        $this->middleware(sprintf('role:%s', Role::ACQUISITION))->except(['index', 'show', 'getImage', 'search', 'getMaterials']);
    }

    public function index () {

        $materials = Material::with(['acquisition.user'])->paginate(25);

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
    public function getMaterials () {
        $materials = Material::all();

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


    public function search ( Request $request ) {
        $search = $request->input('search', null);

        $materials = Material::whereLike('bar_code', $search)->orWhereLike('name', $search)->paginate(10);

        if ( !empty( $materials ) && $materials->count() !== 0 ) {
            //  retornamos los datos
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'materials' =>  $materials
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

        //  al registrar un nuevo material el stock debe ser 0 inicialmente o por defecto
        //  añadir campo codigo de barra
        //  luego de creado un materíal permitir registrar stock material
        //  las busquedas tendran filtros: por codigo de barra o por nombre material
        //  al encontrar un material permitirá ver detalle material o ingresar stock
        //  al buscar y no encontrar material advierte de busqueda sin resultados y sugiere regitro nuevo material
        
        //  validar que el usuario que realiza la creación tenga los privilegios necesarios
        //  recogemos los datos que vienen por post
        $token = $request->header('Authorization');
        //  comprobamos la data del token
        $jwtAuth = new JwtAuth();

        $json = $request->input('json', null);
        //  decodificamos los datos en un objeto
        $params = json_decode($json);
        //  decodificamos los datos en un array
        $params_array = json_decode($json, true);

        if ( !empty( $params )  && !empty( $params_array ) ) {

            //  sacamos el id adquisicion a partir del id usuario
            //  mensajes validacion
            $messages = [
                'name.required'         =>  'El nombre es obligatorio',
                'unity_type.required'   =>  'El tipo unidad material es oblitagorio',
                'bar_code.required'     =>  'El código de barras es obligatorio',
                'bar_code.unique'       =>  'El código de barras ingresado ya ha sido registrado en el sistema'
            ];
            //  creamos nuestras reglas de validacion
            $validate = Validator::make($params_array, [
                'name'          =>  'required',
                'unity_type'    =>  'required',
                'bar_code'      =>  'required|unique:materials'
            ], $messages);
            //  validamos los datos
            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {
                //  decodificamos el token para obtener data usuario autenticado
                $user = $jwtAuth->checkToken($token, true);
                //  obtenemos el acquisition id del usuario
                $user_acquisition = Acquisition::where('user_id', $user->sub)->first();
                //  creamos una nueva instancia de material
                $material = new Material();
                // var_dump($material); die();
                //  asignamos los valores a las propiedades
                $material->bar_code = $params_array['bar_code'];
                $material->acquisition_id = $user_acquisition->id;
                $material->name = $params_array['name'];
                $material->slug = Str::slug($params_array['name'], '-');
                $material->unity_type = $params_array['unity_type'];

                //  guardamos en DB el nuevo material
                $material->save();
                //  devolvemos resultado
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'material'    =>  $material
                );

            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'Los datos no se han enviado correctamente'
            );
        }

        //  retornamos la respuesta
        return response()->json($data);
        
    }


    public function update (Request $request, $id) {

        //  obtenemos el material a partir de su id
        $material = Material::find($id);
        //  obtenemos el token desde la cabecera de la petición
        $token = $request->header('Authorization');
        //  comprobamos la data del token
        $jwtAuth = new JwtAuth();
        //  Recoger los datos desde el formulario en un json
        $json = $request->input('json', null);
        //  sacamos los datos en un objeto
        $params = json_decode($json);
        //  sacamos los datos en un array
        $params_array = json_decode($json, true);

        //  validamos por si la data viene null por un json mal formado
        if ( !empty($params) && !empty($params_array) && !empty($material) ) {

            //  Mensajes validaciones
            $messages = [
                'name.required'         =>  'El nombre es obligatorio',
                'unity_type.required'   =>  'El tipo unidad material es oblitagorio',
                'bar_code.required'     =>  'El código de barras es obligatorio',
                'bar_code.unique'       =>  'El código de barras ingresado pertenece a un material ya registrado'
            ];
            //  validar los datos
            $validate = Validator::make($params_array, [
                'name'          =>  'required',
                'unity_type'    =>  'required',
                'bar_code'      =>  [
                    'required',
                    Rule::unique('materials')->ignore($material->bar_code, 'bar_code')
                ]
            ], $messages);

            //  evaluamos las validaciones
            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {

                //  quitar lo que no queremos actualizar
                unset($params_array['id']);
                unset($params_array['created_at']);
                //  decodificamos la data del token
                $user_auth = $jwtAuth->checkToken($token, true);
                //  sacamos el id usuario adquisiciones
                $user_acquisition = Acquisition::where('user_id', $user_auth->sub)->first();
                //  asignamos el id adquisiciones que está autenticado y realizando la modificacion
                $params_array['acquisition_id'] = $user_acquisition->id;
                //  actualizamos el slug
                $params_array['slug'] = Str::slug($params_array['name'], '-');
                //  sacar el acquisition_id a partir del usuario autenticado y con role validado
                //  actualizar el registro(Material)
                // $material_updated = Material::where('id', $material->id)->update($params_array);
                $material->fill( $params_array )->save();
                //  respondemos
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'El material ha sido actualizado exitosamente',
                    'material'  =>  $material
                );
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'No se ha podido actualizar el material'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function upload ( Request $request, $id ) {

        //  obtener data material para actualizar su imagen
        $material = Material::find($id);

        if ( !empty( $material ) && !is_null( $material ) ) {

            $messages = [
                'file0.required'    =>  'La imagen es requerida',
                'file0.image'       =>  'El archivo a subir debe ser una imagen',
                'file0.mimes'       =>  'El formato debe ser una imagen valida'
            ];
    
            //  validamos los datos que vienen desde el formulario
            $validate = Validator::make($request->all(),[
                'file0' =>  'required|image|mimes:jpg,png,jpeg,gif'
            ], $messages);
    
            if ( !$request->file('file0') || $validate->fails() ) {
                //  mensaje de error
                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400, 
                    'message'   =>  'Error al subir imagen',
                    'errors'    =>  $validate->errors()
                );
                
            } else {

                
                //  comprobar si existe la imagen
                if ( !is_null( $material->picture ) ) {
                    //  eliminar imagen actual del directorio de imagenes users
                    Storage::delete('materials/'.$material->picture);
                }
                //  subimos la imagen nueva
                $picture = UploadImage::uploadFile('file0', 'materials');
                //  ahora añadimos la imagen nueva a la request
                $request->merge(['picture' => $picture]);

                //  actualizo la imagen del usuario
                $material->fill( $request->input() )->save();
                //  devolver el resultado
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'La imagen ha sido agregada exitosamente',
                    'picture'   =>  $picture,
                    'material'  =>  $material
                );
                //  recoger datos de la peticion
                //  llamamos a nuestro helper para guardar la imagen y retornar el nombre para guardar en base de datos
                // $picture = UploadImage::uploadFile('file0', 'materials');
                // //  devolver el resultado
                // $data = array(
                //     'status'    =>  'success',
                //     'code'      =>  200,
                //     'message'   =>  'La imagen ha sido agregada exitosamente',
                //     'picture'    =>  $picture
                // );
    
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Material indicado no existe'
            );

        }

        return response()->json($data, $data['code']);
    }

    public function getImage ( $imageName ) {
        //  reconstruimos la ruta a la imagen
        $file = sprintf('storage/materials/%s', $imageName);
        //  verificamos si existe la imagen
        if ( File::exists($file) ) {
            //  retornamos la imagen
            return Image::make($file)->response();
        } else {
            
            return response()->json([
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Archivo no entontrado'
            ], 404);
        }

    }

    public function destroy( $id ) {
        //  buscamos el material
        // $material = Material::where('id', $id)->withTrashed()->first();
        $material = Material::find($id);
        //  verificamos si material devuelto esta vacio
        if ( !empty( $material ) && isset( $material->id ) ) {

            try {
                //  eliminamos el registro
                $material->delete();
                //  respuesta
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'El material fue eliminado exitosamente',
                    'material'  =>  $material
                );

            } catch (\Exception $ex ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'message'   =>  'El material no puede ser eliminado',
                    'error'     =>  $ex->getMessage()
                );
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'El material no existe'
            );
        }

        return response()->json($data, $data['code']);
    }
}
