<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UploadImage;    //  hacemos uso de nuestro helper para subir imagenes
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; //  reglas de validacion utilizar unique de una forma mas controlada
use App\User;
use App\Acquisition;
use App\Dispatcher;

class UserController extends Controller
{
    public function __construct () {
        $this->middleware('cors')->except(['pruebas']);
        $this->middleware('api.auth')->only(['update','destroy']);
        //  crear un middleware para comprobar si quien intenta actualizar su perfil es el mismo usuario
        $this->middleware( sprintf('role:%s', \App\Role::ADMIN) )->only(['updateRoleUser', 'destroy']);
        //  asignamos al middleware que unicamente puedan eliminar usuarios aquel usuario con
        //  privilegios de administrador y que debe estar autenticado en el sistema
    }


    public function index () {

        $users = User::paginate(5);


        if ( !empty( $users ) && is_object( $users ) ) {
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'users'     =>  $users
            );
        } else {
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'message'   =>  'No hay resultados que cargar'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function search ( Request $request ) {

        $search = $request->input('search', null);
        $users = User::whereLike('name', $search)->orWhereLike('last_name', $search)->paginate(5);

        if ( !empty( $users ) && $users->count() !== 0 ) {

            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'users'   =>  $users
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'No hemos encontrado resultados'
            );
        }

        return response()->json($data, $data['code']);


    }

    public function pruebas ( Request $request ) {
        return ('Esto es una prueba de USER-CONTROLLER');
    }

    public function checkToken ( Request $request ) {
        //  obtenemos el token
        $token = $request->header('Authorization');
        // var_dump( $token ); die();
        //  creamos una nueva instancia de nuestro jwtauth
        $jwtAuth =  new \JwtAuth();
        //  verificamos el token
        $verificaToken = $jwtAuth->checkToken( $token );

        //  enviamos la respuesta al usuario
        if ( $verificaToken ) {

            $data = array(
                'status'        =>  'success',
                'code'          =>  200,
                'tokenValid'    =>  $verificaToken
            );

        } else {

            $data = array(
                'status'        =>  'error',
                'code'          =>  401,
                'tokenValid'    =>  $verificaToken
            );
        }

        return response()->json($data, $data['code']);
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

            //  mensajes personalizados register
            $messages = [
                'name.required'         =>  'El nombre usuario es requerido',
                'last_name.required'    =>  'El apelliod usuario es requerido',
                'email.required'        =>  'El correo electrónico es requerido',
                'email.email'           =>  'El formato del correo debe ser un formato válido',
                'email.unique'          =>  'El correo que ha ingresado ya está en uso en el sistema',
                'password.required'     =>  'La contraseña es requerida'
            ];

            //  validar los datos del formulario
            $validate = Validator::make($params_array, [
                'name'      =>  'required',
                'last_name'   =>  'required',
                'email'     =>  'required|email|unique:users', //  comprobar si el usuario ya existe (duplicado)
                'password'  =>  'required'
            ], $messages);

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

                //  creamos un despachador
                Dispatcher::create([
                    'user_id'   =>  $user->id
                ])->save();

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


    public function update ( Request $request, $id ) {

        //  obtenemos el usuario de base de datos
        $user = User::find($id);
        //  comprobamos si el usuario está identificado a traves de nuestro middleware
        //  recoger los datos por POST
        $json = $request->input('json', null);
        //  decodificamos el json en un objeto
        $params = json_decode($json);
        //  decodificamos el json en un array para la validacion
        $params_array = json_decode($json, true);

        //  verificamos si viene el json mal formado o el usuario devuelto es vacio
        if ( !empty( $params_array ) && !empty( $params ) && !empty( $user ) ) {

            //  personalizamos los mensajes para nuestra validacion
            $messages = [
                'name.required'         =>  'El nombre de usuario es requerido',
                'name.alpha'            =>  'El nombre de usuario solo debe contener letras',
                'last_name.required'    =>  'El apellido de usuario es requerido',
                'last_name.alpha'       =>  'El apellido de usuario solo debe contener letras',
                'email.required'        =>  'El email usuario es requerido',
                'email,email'           =>  'Debe ingresar un formato de email válido',
                'email.unique'          =>  'El correo ingresado ya existe y pertenece a otro usuario'
            ];
            //  validamos los datos
            $validate = Validator::make($params_array, [
                'name'  =>  'required|alpha',
                'last_name' =>  'required|alpha',
                'email'     =>  [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($user->id, 'id')    //  agregamos la excepcion del correo propio
                ]
            ], $messages);

            //  validamos en caso de errores de validacion
            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {

                //  quitamos los datos que no queremos actualizar de la peticion
                unset($params_array['id']);
                unset($params_array['role_id']);
                unset($params_array['password']);
                unset($params_array['remember_token']);
                unset($params_array['created_at']);
    
                //  actualizamos el slug
                $slug_user = ($user->last_name !== null) ? $user->name . " " . $user->last_name : $user->name;
                $params_array['slug'] = \Str::slug( $slug_user, '-' );
                //  actualizamos los datos
                $user->fill( $params_array )->save();
                //  devolvemos array con los resultados
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'Usuario actualizado exitosamente',
                    'user'      =>  $user,
                );
            }

        } else {
            //  devolvemos un mensaje de error
            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'Los datos que deseas actualizar no han sido enviados correctamente o el usuario no existe'
            );
        }

        //  retornamos la respuesta
        return response()->json($data, $data['code']);
    }

    public function updateRoleUser ( Request $request, $id ) {
        //  obtenemos el usuario de base de datos
        $user = User::find($id);
        //  comprobamos si el usuario está identificado a traves de nuestro middleware
        //  recoger los datos por POST
        $json = $request->input('json', null);
        //  decodificamos el json en un objeto
        $params = json_decode($json);
        //  decodificamos el json en un array para la validacion
        $params_array = json_decode($json, true);

        //  verificamos si viene el json mal formado o el usuario devuelto es vacio
        if ( !empty( $params_array ) && !empty( $params ) && !empty( $user ) ) {

            //  personalizamos los mensajes para nuestra validacion
            $messages = [
                'role_id.required' =>  'El rol de usuario es requerido',
            ];
            //  validamos los datos
            $validate = Validator::make($params_array, [
                'role_id'  =>  'required',
            ], $messages);

            //  validamos en caso de errores de validacion
            if ( $validate->fails() ) {

                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'errors'    =>  $validate->errors()
                );

            } else {

                //  quitamos los datos que no queremos actualizar de la peticion
                unset($params_array['id']);
                unset($params_array['password']);
                unset($params_array['remember_token']);
                unset($params_array['created_at']);

                //  enviar el role_id del user sin actualizar a una funcion
                $this->dropUserRole( $user->role_id, $user->id );
                //  eliminar de la tabla respectiva el registro donde exista el user_id que corresponde al usuario
                //  actualizamos los datos
                $user->fill( $params_array )->save();
                //  crear una funcion para crear un registro segun el nuevo role asignado, sea para adquisiciones o para despachos
                $this->createUserRole( $user->role_id, $user->id );
                //  devolvemos array con los resultados
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'Role usuario actualizado exitosamente',
                    'user'      =>  $user,
                );
            }

        } else {
            //  devolvemos un mensaje de error
            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'Los datos que deseas actualizar no han sido enviados correctamente o el usuario no existe'
            );
        }

        return response()->json($data, $data['code']);
    }


    private function createUserRole( $role_id, $user_id ) {

        switch ($role_id) {
            case \App\Role::ACQUISITION:
                Acquisition::create([
                    'user_id'   =>  $user_id
                ])->save();
                break;
            case \App\Role::DISPATCHER:
                Dispatcher::create([
                    'user_id'   =>  $user_id
                ])->save();
            case \App\Role::ADMIN:
                break;

        }
    }

    private function dropUserRole( $role_id, $user_id ) {

        switch ($role_id) {
            case \App\Role::ACQUISITION:
                Acquisition::where('user_id', $user_id)->delete();
            break;
            case \App\Role::DISPATCHER:
                Dispatcher::where('user_id', $user_id)->delete();
            break;
            case \App\Role::ADMIN:
            break;
        }
    }

    public function upload ( Request $request, $id ) {

        //  obtener data usuario para actualizar su avatar
        $user = User::find($id);

        //  comprobar que el usuario existe

        if ( !empty( $user ) && !is_null( $user ) ) {

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

                //  comprobar si existe la imagen
                if ( !is_null( $user->avatar ) ) {
                    //  eliminar imagen actual del directorio de imagenes users
                    \Storage::delete('users/'.$user->avatar);
                }
                //  subimos la imagen nueva
                $avatar = UploadImage::uploadFile('file0', 'users');
                //  ahora añadimos la imagen nueva a la request
                $request->merge(['avatar' => $avatar]);

                //  actualizo la imagen del usuario
                $user->fill( $request->input() )->save();
                //  devolver el resultado
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'message'   =>  'Imagen de usuario ha sido actualizado exitosamente',
                    'avatar'    =>  $avatar,
                    'user'      =>  $user
                );
    
            }
        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Usuario no existe'
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

                //  implementar borrado lógico
                $avatar_user = $user->avatar;
                //  finalmente eliminamos el registro
                $user_deleted = $user->delete();

                if ( $user_deleted ) {

                    if ( !is_null( $avatar_user ) ) {
                        //  eliminar imagen actual del directorio de imagenes users
                        \Storage::delete('users/'.$avatar_user);
                    }
                }

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
