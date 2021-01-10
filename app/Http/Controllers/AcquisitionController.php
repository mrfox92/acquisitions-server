<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Acquisition;
use App\MaterialOrder;

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
        //
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
