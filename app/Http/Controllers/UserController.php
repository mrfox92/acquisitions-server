<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UploadImage;    //  hacemos uso de nuestro helper para subir imagenes
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\User;

class UserController extends Controller
{
    public function __construct () {
        $this->middleware('api.auth')->only(['destroy']);
        $this->middleware( sprintf('role:%s', \App\Role::ADMIN) )->only(['destroy']);
        //  asignamos al middleware que unicamente puedan eliminar usuarios aquel usuario con
        //  privilegios de administrador y que debe estar autenticado en el sistema
    }

    public function pruebas ( Request $request ) {
        return ('Esto es una prueba de USER-CONTROLLER');
    }

    public function register ( Request $request ) {

        //  recoger datos del usuario
        $json = $request->input('json', null);

        //  decodificar json y pasar la data a un objeto
        $params = json_decode($json);
        //  decodificar json y pasar la data a un array
        $params_array = json_decode($json, true);

        //  si es que el objeto y el array no vienen vacíos o con un json mal formado

        if ( !empty( $params )  && !empty( $params_array ) ) {
            //  limpiar datos con la funcion trim para limpiar de espacios al inicio y al final de una cadena
            $params_array = array_map('trim', $params_array);

            //  validar los datos del formulario
            $validate = Validator::make($params_array, [
                'name'      =>  'required|alpha',
                'last_name'   =>  'required|alpha',
                'email'     =>  'required|email|unique:users', //  comprobar si el usuario ya existe (duplicado)
                'password'  =>  'required'
            ]);

            if ( $validate->fails() ) {

                $data = array (
                    'status'    =>  'error',
                    'code'      =>  400,
                    'message'   =>  'Usuario no ha sido creado',
                    'errors'    =>  $validate->errors()
                );

            } else {

                //  cifrar la contraseña del usuario

                // $user_password = password_hash( $params->password, PASSWORD_BCRYPT, ['cost' => 4] );
                // $user_password = hash('sha256', $params->password);
                //  encriptamos nuestra contraseña
                $user_password = bcrypt($params->password);

                $slug = $params_array['name'] . " " . $params_array['last_name'];

                //  crear el usuario en base de datos

                $user = new User();

                $user->name = $params_array['name'];
                $user->last_name = $params_array['last_name'];
                $user->slug =   Str::slug($slug, '-');
                $user->email = $params_array['email'];
                $user->password = $user_password;

                //  guardamos los datos del usuario
                $user->save();

                //  ordenamos nuestro array de respuesta
                $data = array (
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'Usuario se ha creado correctamente',
                    'user'      =>  $user
                );
            }

        } else {

            $data = array (
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'Los datos enviados no son correctos'
            );
        }


        return response()->json($data, $data['code']);  

        
    }

    public function login ( Request $request ) {

        $jwtAuth =  new \JwtAuth();

        //  recibimos el formulario
        $json = $request->input('json', null);
        //  decodificamos el json a un objeto
        $params = json_decode( $json );
        //  decodificamos el json a un array
        $params_array = json_decode( $json, true );
        //  comprobamos que no venga malformado el json
        if ( !empty( $params ) && !empty( $params_array ) ) {

            //  creamos los mensaje personalizados para las reglas de validación
            $messages = [
                'email.required'    =>  'El correo electrónico es requerido',
                'email.email'       =>  'El formato del correo debe ser un formato válido',
                'password.required' =>  'La contraseña es requerida'
            ];
            //  validamos los datos desde el params_array
            $validate = Validator::make($params_array, [
                'email' =>  'required|email',
                'password'  =>  'required'
            ], $messages);
            //  verificamos si hay errores en la validación
            if ( $validate->fails() ) {
                //  estructuramos nuestra respuesta
                $signup = array(
                    'status'    =>  'error',
                    'code'      =>  404,
                    'message'   =>  'El usuario no se ha podido identificar',
                    'errors'    =>  $validate->errors()
                );
                
            } else {
    
                //  devolver token o datos
                $signup = $jwtAuth->signup($params->email, $params->password);
    
                //  comprobamos si viene el getToken
                if ( !empty( $params->getToken ) ) {
    
                    $signup = $jwtAuth->signup($params->email, $params->password, true);
    
                }
            }
        } else {
            
            $signup = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'Los datos enviados no son correctos'
            );
        }

        //  retornamos nuestra respuesta en formato json

        return response()->json($signup, 200);
    }


    public function update ( Request $request ) {

        //  comprobamos si el usuario está identificado a traves de nuestro middleware
        //  recoger los datos por POST
        $json = $request->input('json', null);
        //  decodificamos el json en un objeto
        $params = json_decode($json);
        //  decodificamos el json en un array para la validacion
        $params_array = json_decode($json, true);

        //  comprobamos si existe checktoken
        if ( $checkToken && !empty( $params_array ) ) {
            
            //  capturamos la data del usuario registrado
            $user = $jwtAuth->checkToken($token, true);
            // var_dump($user); die();
            //  personalizamos los mensajes para nuestra validacion
            $messages = [
                'name.required'         =>  'El nombre de usuario es requerido',
                'name.alpha'            =>  'El nombre de usuario solo debe contener letras',
                'last_name.required'    =>  'El apellido de usuario es requerido',
                'last_name.alpha'       =>  'El apellido de usuario solo debe contener letras',
                'email.required'        =>  'El email usuario es requerido',
                'email,email'           =>  'Debe ingresar un formato de email válido',
                'email.unique'          =>  'El correo ingresado ya existe'
            ];
            //  validamos los datos
            $validate = Validator::make($params_array, [
                'name'  =>  'required|alpha',
                'last_name' =>  'required|alpha',
                'email'     =>  'required|email|unique:users,'.$user->sub    //  agregamos excepcion de nuestro correo
            ], $messages);
            //  quitamos los datos que no queremos actualizar de la peticion
            unset($params_array['id']);
            unset($params_array['role_id']);
            unset($params_array['password']);
            unset($params_array['remember_token']);
            unset($params_array['created_at']);

            //  actualizamos el slug
            // $slug_user = $user->name
            //  actualizamos los datos
            $user_update = User::where('id', $user->sub)->update( $params_array );
            //  devolvemos array con los resultados
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'message'   =>  'Usuario actualizado exitosamente',
                'user'      =>  $user,
                'changes'   =>  $params_array
            );

        } else {
            //  devolvemos un mensaje de error
            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'El usuario no está identificado'
            );
        }

        //  retornamos la respuesta
        return response()->json($data, $data['code']);
    }

    public function upload ( Request $request ) {

        //  personalizamos los mensajes de validacion
        $messages = [
            'file0.required'    =>  'La imagen es requerida',
            'file0.image'       =>  'El archivo a subir debe ser una imagen',
            'file0.mimes'       =>  'El formato debe ser una imagen valida'
        ];

        //  validamos los datos que vienen desde el formulario
        $validate = Validator::make($request->all(),[
            'file0' =>  'required|image|mimes:jpg,png,jpeg,gif'
        ], $messages);

        //  validamos si el archivo file0 no viene o si la validacion falla

        if ( !$request->file('file0') || $validate->fails() ) {
            //  mensaje de error
            $data = array(
                'status'    =>  'error',
                'code'      =>  400, 
                'message'   =>  'Error al subir imagen',
                'errors'    =>  $validate->errors()
            );
            
        } else {
            //  recoger datos de la peticion
            //  llamamos a nuestro helper para guardar la imagen y retornar el nombre para guardar en base de datos
            $avatar = UploadImage::uploadFile('file0', 'users');
            //  devolver el resultado
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'avatar'    =>  $avatar
            );

        }


        return response()->json($data, $data['code']);
    }


    public function getImage($imageName) {
        //  sprintf devuelve un string formateado
        $file = sprintf('storage/users/%s', $imageName);
        //  comprobamos si existe en disco el archivo
        if ( \File::exists( $file ) ) {
            //  retornamos la imagen
            return \Image::make($file)->response();
        } else {
            //  retornamos una respuesta en caso de algun error
            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Archivo no entontrado'
            );

            return response()->json($data, $data['code']);
        }
    }

    public function detail ($id) {
        //  buscamos el usuario por su id
        $user = User::find($id);

        //  verificamos que el usuario exista
        if ( is_object( $user ) && !empty( $user ) ) {

            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'user'      =>  $user
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Usuario no existe'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function destroy ($id) {
        $user = User::find($id);

        if ( !empty( $user ) && isset( $user->id ) ) {

            try {
                $user->delete();

                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'El usuario a sido eliminado exitosamente',
                    'user'      =>  $user
                );
            } catch ( \Exception $ex ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  500,
                    'message'   =>  'El usuario no se ha podido eliminar',
                    'error'     =>  $ex->getMessage()
                );
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'El usuario que se intenta eliminar no existe'
            );
        }

        return response()->json($data, $data['code']);
    }
}
