<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Acquisition;
use App\Helpers\JwtAuth;
use App\Material;
use App\MaterialInvoice;
use App\MaterialOrder;
use App\Order;
use App\OrderOffice;
use App\Provider;
use App\User;
use Illuminate\Support\Str;

class AcquisitionController extends Controller
{

    public function __construct() {

        $this->middleware('cors');
        $this->middleware('api.auth')->except(['getOrderDetail']);
        $this->middleware( sprintf('role:%s', \App\Role::ACQUISITION) )->except(['getOrderDetail']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }
    
    
    public function getOrderDetail( $id ) {
        //  obtener todos los materiales pertenecientes a una orden
        $detailOrder = MaterialOrder::with(['material'])->where('order_id', $id)->get();

        if ( !empty( $detailOrder ) && $detailOrder->count() !== 0 ) {

            $data = array(
                'status'        =>  'success',
                'code'          =>  200,
                'detailOrder'   =>  $detailOrder
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'No hay resultados para tu busqueda'
            );
        }

        return response()->json($data, $data['code']);
        
    }


    public function getOutOfStock() {

        $materials = Material::with(['acquisition.user'])->where('stock', 0)->get();

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //  obtenemos la data desde el form
        $json = $request->input('json', null);
        //  obtenemos el token
        $token = $request->header('Authorization');
        //  decodificamos el json con la data
        $params = json_decode($json);
        //  decodificamos en un arreglo
        $params_array = json_decode($json, true);
        //  validamos los datos
        if ( !empty( $params ) && !empty( $params_array ) ) {


            $jwtAuth = new JwtAuth();
            $userDecoded = $jwtAuth->checkToken( $token, true );

            $acquisitionUser = Acquisition::where('user_id', $userDecoded->sub)->first();
            
            
            
            foreach ($params->materials as $item) {
                
                $materialExists = Material::where('bar_code', $item->material)->first();

                if ( !empty( $materialExists ) && isset( $materialExists->id ) ) {
        
                    //  si existe el material, entonces actualizamos su stock
                    $materialExists->stock = $materialExists->stock + $item->quantity;
                    $materialExists->save();
                    //  y grabamos el detalle de la factura
                    MaterialInvoice::create([
    
                        'material_id'   =>  $materialExists->id,
                        'invoice_id'    =>  $params->invoice->id,
                        'quantity'      =>  $item->quantity,
                        'unity_cost'    =>  $item->unity_cost,
                        'iva'           =>  $item->iva,
                        'total_cost'    =>  $item->total_cost
                    ]);
    
                } else {
    
                    //  si no existe, creamos el material
                    $materialCreate = new Material();
    
                    $materialCreate->bar_code = $item->material;
                    $materialCreate->acquisition_id = $acquisitionUser->id;
                    $materialCreate->name = $item->nombre;
                    $materialCreate->slug = Str::slug($item->nombre);
                    $materialCreate->unity_type = $item->unity_type;
                    $materialCreate->stock = $item->quantity;
                    $materialCreate->picture = null;
    
                    //  guardamos los datos del usuario
                    $materialCreate->save();
    
                    //  y grabamos el detalle de la factura
                    MaterialInvoice::create([
    
                        'material_id'   =>  $materialCreate->id,
                        'invoice_id'    =>  $params->invoice->id,
                        'quantity'      =>  $item->quantity,
                        'unity_cost'    =>  $item->unity_cost,
                        'iva'           =>  $item->iva,
                        'total_cost'    =>  $item->total_cost
                    ]);
    
    
                }

            }


            $data = array(
                'status'        =>  'success',
                'code'          =>  200,
                'message'       =>  'Detalle factura creado con éxito',
                'materiales'    =>  $params->materials,
                'factura'       =>  $params->invoice,
                'materialExists'    =>  $materialExists
            );

        } else {
            $data = array(
                'status'    =>  'success',
                'code'      =>  400,
                'message'   =>  'No hemos recibido información desde el formulario'
            );
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show ( $id ) {

        $acquisition = Acquisition::where('user_id', $id)->first();

        if ( !empty( $acquisition ) && isset( $acquisition->id ) ) {

            $data = array(
                'status'        =>  'success',
                'code'          =>  200,
                'acquisition'   =>  $acquisition
            );

        } else {

            $data = array(
                'status'        =>  'error',
                'code'          =>  404,
                'message'       =>  'usuario adquisiciones no encontrado'
            );
        }

        return response()->json($data, $data['code']);
    }


    public function getInvoicesProvider( $id ) {

        $provider = Provider::with(['invoices.materialsInvoices.material'])->withCount('invoices')->where('id', $id)->first();

        if ( is_object( $provider ) && !empty( $provider ) ) {

            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'provider'  =>  $provider
            );

        } else {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'No se encontró el proveedor'
            );
        }

        return response()->json($data, $data['code']);
    }


    public function updateStatusOrder( Request $request ) {
        //  obtenemos la data desde el form
        $json = $request->input('json', null);
        //  decodificamos el json con la data
        $params = json_decode($json);
        //  decodificamos en un arreglo
        $params_array = json_decode($json, true);
        //  validamos los datos
        if ( !empty( $params ) && !empty( $params_array ) ) {


            if ( (int) $params->status === Order::CANCELED ) {

                //  si viene el id de la orden, buscamos la orden y agregamos su detalle
                $order = Order::with(['materialsOrders'])
                    ->withCount('materialsOrders')
                    ->where('id', $params->id )->first();

                    
                foreach ( $params->materials_orders as $item) {
    
                    $material = Material::find( $item->material_id );
                    $orderDetail = MaterialOrder::find( $item->id );
                    
                    // if ( !empty( $material ) ) {
                        //  reponemos la cantidad en el stock de materiales
                        $material->stock = $material->stock + $item->quantity;
                        //  grabamos los cambios
                        $material->save();
                        //  eliminamos detalle de la orden
                        $orderDetail->delete(); //  softdelete
                        
                    // }
                }
                
                //  buscamos la relacion orderOffice
                $orderOffice = OrderOffice::where('order_id', $params->id )->first();
                $orderOffice->delete(); //  softdelete
                //  actualizamos el stado
                $order->status = $params->status;
                //  grabamos en base de datos
                $order->save();

            }
            
            if ( (int) $params->status === Order::FINISHED ) {

                //  buscar orden
                $order = Order::find($params->id );
                //  actualizar su estado
                $order->status = $params->status;
                //  grabar cambios
                $order->save();

            }


            //  actualizamos la orden
            //  si la orden es igual a status 4
            //  debemos

            //  agregar detalle orden
            // $orderDetail = MaterialOrder::create([
            //     'material_id'   =>  $params->idProducto,
            //     'order_id'      =>  $order->id,
            //     'quantity'      =>  $params->qty
            // ]);

            $data = array(
                'status'        =>  'success',
                'code'          =>  200,
                'message'       =>  'Orden actualizada con éxito',
                // 'order'         =>  $order
            );

        } else {
            $data = array(
                'status'    =>  'success',
                'code'      =>  400,
                'message'   =>  'No hemos recibido información desde el formulario'
            );
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
