<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVentaRequest $request)
    {
        //
        $salida=[];
        $mes=date('m');
        $anio=date('Y');
        $datos=Venta::where('servicio_id',$request->id)->whereMonth('fecha',$mes)->whereYear('fecha',$anio)->count();
        if($datos==0)
            $numero=1;
        else
        {
            $datos=Venta::where('servicio_id',$request->id)->whereMonth('fecha',$mes)->whereYear('fecha',$anio)->orderBy('id','desc')->first();
            $numero=$datos->numero + 1 ;
        }

        for ($i=0; $i < $request->cantidad; $i++) { 
            # code...
            $venta=new Venta();
            $venta->fecha=date('Y-m-d');
            $venta->numero=$numero;
            $venta->servicio_id=$request->id;
            $venta->user_id=$request->user()->id;
            $venta->save();
            $ven=Venta::with('servicio')->where('id',$venta->id)->first();
            $numero++;
            array_push($salida,$ven);
        }

        return $salida;

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        return Venta::with('servicio')->where('id',$id)->first();

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVentaRequest $request, Venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $venta=Venta::find($id);
        $venta->estado='INACTIVO';
        $venta->save();
    }
}
