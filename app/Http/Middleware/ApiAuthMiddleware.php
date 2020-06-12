<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //  comprobamos si el usuario está identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();

        $checkToken = $jwtAuth->checkToken($token);

        //  comprobamos si está autehticado
        if ( $checkToken) {

            return $next($request);
        } else {
            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'message'   =>  'El usuario no está identificado'
            );
            return response()->json($data, $data['code']);
        }
    }
}
