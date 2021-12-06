<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\MaterialInvoice;
use App\MaterialOrder;
use App\OrderOffice;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('cors');
        $this->middleware('api.auth');
        $this->middleware( sprintf('role:%s', \App\Role::ADMIN) );
    }
    

    public function getOffices( Request $request ) {

        // $token = $request->header('Authorization');

        $json = $request->input('json', null);
        //  decodificamos el json en un objeto
        $params = json_decode($json);
        //  decodificamos el json en un array para la validacion
        $params_array = json_decode($json, true);

        if ( !empty( $params ) && !empty( $params_array ) ) {

            //  total ordenes por oficina y por mes
            $offices = OrderOffice::with(['office.department'])
                ->whereMonth('created_at', $params->mes)
                ->whereYear('created_at', $params->anio)
                ->groupBy('office_id')->selectRaw('count(*) as total, office_id')->get();

            // Carbon::setLocale('es');
            // $monthNum  = 03;
            setlocale(LC_ALL,"es_ES"); 
            $date = Carbon::create(0, $params->mes);
            $date->format('F'); // March
            $mes = $date->formatLocalized('%B');
            // $mes = $monthName->formatLocalized('%B');
    
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'offices'   =>  $offices,
                'params'    =>  $params,
                'mes'       =>  $mes
            );
    
        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'offices'   =>  []
            );
        }
        
        
        return response()->json($data, $data['code']);

    }


    public function getOfficesByYear( Request $request ) {

        $json = $request->input('json', null);
        //  decodificamos el json en un objeto
        $params = json_decode($json);

        if ( !empty( $params ) ) {

            //  total ordenes anuales por oficina
            $offices = OrderOffice::with(['office.department'])
                ->whereYear('created_at', $params)
                ->groupBy('office_id')->selectRaw('count(*) as total, office_id')->get();

            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'offices'   =>  $offices
            );
    
        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'offices'   =>  []
            );
        }
        
        
        return response()->json($data, $data['code']);
    }


    public function compareByYear( Request $request ) {

        $json = $request->input('json', null);
        //  decodificamos el json en un objeto
        $params = json_decode($json);

        if ( !empty( $params ) ) {

            //  total ordenes anuales por oficina
            $fromYear = OrderOffice::with(['office.department'])
                ->whereYear('created_at', $params->from)
                ->groupBy('office_id')->selectRaw('count(*) as total, office_id')->get();
            
            $toYear = OrderOffice::with(['office.department'])
                ->whereYear('created_at', $params->to)
                ->groupBy('office_id')->selectRaw('count(*) as total, office_id')->get();
    
    
            $offices = array(
                $fromYear, $toYear
            );
    
            $data = array(
                'status'        =>  'success',
                'code'          =>  200,
                'offices'       =>  $offices
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'offices'   =>  []
            );
        }

        return response()->json($data, $data['code']);
    }


    public function isAdmin(Request $request) {

        //  obtenemos el token
        $token = $request->header('Authorization');

        $jwtAuth = new JwtAuth();
        $userDecoded = $jwtAuth->checkToken( $token, true );

        $user = User::find( $userDecoded->sub );

        if ( !empty( $user ) && isset( $user->id ) ) {

            if ( $user->role_id === Role::ADMIN ) {

                $data = array(
                    'status'        =>  'success',
                    'code'          =>  200,
                    'isAdmin'       =>  true
                );

            } else {

                $data = array(
                    'status'        =>  'error',
                    'code'          =>  401,
                    'isAdmin'       =>  false
                );

            }


        } else {

            $data = array(
                'status'        =>  'error',
                'code'          =>  404,
                'message'       =>  'usuario no encontrado',
                'isAdmin'       =>  false
            );
        }

        return response()->json($data, $data['code']);
    }


    public function getMaterials() {

        //  cantidad de material gastada por año
        $materials = MaterialOrder::with(['material'])
                ->whereYear('created_at', '2021')
                ->where('material_id', '=', 1)
                ->groupBy('material_id')->selectRaw('SUM(quantity) as total, material_id')->get();

        $data = array(
            'status'        =>  'success',
            'code'          =>  200,
            'materials'     =>  $materials,
        );

        return response()->json($data, $data['code']);
    }


    public function getInvoices( Request $request ) {

        $json = $request->input('json', null);
        //  decodificamos el json en un objeto
        $params = json_decode($json);

        if ( !empty( $params ) ) {

            $invoices = MaterialInvoice::with(['invoice'])
                    ->whereYear('created_at', $params)
                    ->groupBy('invoice_id')->selectRaw('SUM(total_cost) as total, invoice_id')->get();
    
            $data = array(
                'status'        =>  'success',
                'code'          =>  200,
                'invoices'      =>  $invoices,
                'params'        =>  $params
            );

        } else {

            $data = array(
                'status'        =>  'error',
                'code'          =>  400,
                'invoices'     =>  []
            );
        }

        return response()->json($data, $data['code']);
    }

    
}
