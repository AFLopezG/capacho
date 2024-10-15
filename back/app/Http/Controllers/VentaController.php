<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;
use Illuminate\Support\Facades\DB;

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
        if($request->cantidad<1){
            return false;
        }
        
        $salida=[];
        $mes=date('m');
        $anio=date('Y');
        $datos=Venta::where('servicio_id',$request->id)->whereYear('fecha',$anio)->count();
        if($datos==0)
            $numero=1;
        else
        {
            $datos=Venta::where('servicio_id',$request->id)->whereYear('fecha',$anio)->orderBy('id','desc')->first();
            $numero=$datos->numero + 1 ;
        }

        for ($i=0; $i < $request->cantidad; $i++) { 
            # code...
            $venta=new Venta();
            $venta->fecha=date('Y-m-d');
            $venta->hora=date('H:i:s');
            $venta->numero=$numero;
            $venta->monto=$request->monto;
            $venta->servicio_id=$request->id;
            $venta->user_id=$request->user()->id;
            $venta->save();
            $ven=Venta::with('servicio')->where('id',$venta->id)->first();
            $numero++;
            array_push($salida,$ven);
        }

        return $salida;

    }

    public function reporte($fecha){
        return DB::SELECT("select s.nombre,count(*) cantidad,sum(v.monto) subtotal,min(v.numero) primero,max(v.numero) ultimo 
        from ventas v inner join servicios s on v.servicio_id=s.id  where date(v.fecha)='$fecha' and v.monto > 0 group by s.nombre");
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
