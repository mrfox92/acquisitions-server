<?php
namespace App\Helpers;

use Firebase\JWT\JWT;   //  incluimos el package para trabajar con JWT
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth {

    protected $key;

    public function __construct () {
        $this->key = 'esta_es_la_clave_de_jwt_98823';
    }


    public function signup ( $email, $password, $getToken = false ) {

        //  buscar si existe el usuario con sus credenciales email - password

        $user = User::where([
            'email' =>  $email
        ])->first();

        //  comprobamos lo que viene  dentro de user es un objeto y que no sea nulo
        if ( is_object( $user ) && !empty( $user ) && isset( $user->email ) ) {
            //  verificar contraseña
            if ( !password_verify($password, $user->password) ) {
                //  password incorrecta
                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'message'   =>  'Login - password incorrecto'
                );

            } else {
                
                //  Generar el token con los datos del usuario identificado
                //  slug usuario
                $slug_user = $user->name . " " . $user->last_name;
                //  estructuramos la informacion de nuestro token
                $token = array(
                    'sub'   =>  $user->id,
                    'email' =>  $user->email,
                    'name'  =>  $user->name,
                    'last_name' =>  $user->last_name,
                    'slug' =>  \Str::slug($slug_user, '-'),
                    'avatar'    =>  $user->avatar,
                    'iat'       =>  time(),
                    'exp'       =>  time() + ( 7 * 24 * 60 * 60 )   //  1 semana expresada en segundos
                    //  reducir duración del token a 1 hora
                );
    
                //  utilizamos la librería JWT para codificar los datos del token
                //  indicamos el token que queremos codificar
                //  indicamos la clave del servidor para codificar y decodificar los JWT
                //  finalmente indicamos el tipo de encriptación
                $jwt = JWT::encode($token, $this->key, 'HS256');
                //  decodificamos el token
                $decoded = JWT::decode($jwt, $this->key, ['HS256']);

                
                $menu = new MenuFrontend();

                //  retornamos el menu
                $menu = $menu::getMenu( $user->role_id );
    
                //  comprobamos si nuestro parametro para devolver el token viene o no nulo
    
                if ( $getToken ) {
    
                    //  devolvemos los datos decodificados
                    $data = array(
                        'id'        =>  $decoded->sub,
                        'status'    =>  'success',
                        'code'      =>  200,
                        'message'   =>  'Login correcto',
                        'user'      =>  $decoded,
                        'menu'      =>  $menu
                    );

                } else {
                    
                    //  devolvemos el jwt
                    $data = array(
                        'status'    =>  'success',
                        'code'      =>  200,
                        'id'        =>  $user->id,
                        'message'   =>  'Login correcto',
                        'user'      =>  $user,
                        'menu'      =>  $menu,
                        'token'     =>  $jwt
                    );
                }

            }

        } else {
            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'Login - email incorrecto'
            );
        }
    
        //  devolver los datos decodificados o el token, en función de un parametro

        return $data;
    }


    public function checkToken ( $jwt, $getIdentity = false ) {

        //  autenticación será nuestro flag, y por defecto será false
        $auth = false;

        try {
            //  limpiamos las comillas dobles
            $jwt = str_replace('""', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

        } catch ( \UnexpectedValueException $ex ) {
            $auth = false;
        } catch ( \DomainException $ex ) {
            $auth = false;
        }


        //  comprobamos y validamos decoded
        if ( !empty( $decoded ) && is_object( $decoded ) && isset( $decoded->sub ) ) {
            $auth = true;
        } else {
            $auth = false;
        }

        //  verificamos si viene true el flag getIdentity para enviar el token decodificado

        if ( $getIdentity ) {
            return $decoded;
        }


        //  retornamos el valor de autenticación

        return $auth;
    }
}