<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

use App\Acquisition;
use App\Dispatcher;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        //  verificar que el usuario autenticado tenga los privilegios correspondientes
        $token = $request->header('Authorization');
        //  comprobamos la data del token
        $jwtAuth = new \JwtAuth();
        //  decodificamos el token para la data del usuario autenticad
        $user = $jwtAuth->checkToken($token, true);
        //  comprobamos si el resultado de la data de usuario es válida
        if ( is_object($user) && !empty($user) && isset($user->sub) ) {

            //  buscamos al usuario en base de datos
            $user_auth = User::find($user->sub);
            //  ademas comprobar si user_id figura en la tabla adquisiciones
            switch ($role) {
                case 1:
                    $user_role = User::where('id', $user_auth->id)->where('role_id', $role)->first();
                    break;
                case 2:
                    $user_role = Acquisition::where('user_id', $user_auth->id)->first();
                    break;
                case 3:
                    $user_role = Dispatcher::where('user_id', $user_auth->id)->first();
                    break;
                
                default:
                    $user_role = Dispatcher::where('user_id', $user_auth->id)->first();
                    break;
            }
            //  evaluamos si el resultado de la consulta es nulo
            if ( is_null($user_role) && empty( $user_role ) && !is_object( $user_role ) ) {
                //  estructuramos la respuesta de nuestro error
                $data = array(
                    'status'    =>  'error',
                    'code'      =>  401,
                    'message'   =>  'Acción no autorizada, el usuario no posee los privilegios necesarios'
                );

                //  retornamos la respuesta
                return response()->json($data, $data['code']);
            } else {
                //  evaluamos si tiene los privilegios necesarios
                if ( $user_auth->role_id !==  (int) $role ) {
        
                    $data = array(
                        'status'    =>  'error',
                        'code'      =>  401,
                        'message'   =>  'Acción no autorizada'
                    );
        
                    return response()->json($data, $data['code']);
        
                } else {

                    return $next($request);
                }
            }

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  401,
                'message'   =>  'Acción no autorizada'
            );

            return response()->json($data, $data['code']);
        }


    }
}
