<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Servicio::with('unit')->get();
    }

    public function listServicio(Request $request){
        return Servicio::with('unit')->where('unit_id',$request->user()->unit_id)->where('estado','ACTIVO')->get();
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
    public function store(StoreServicioRequest $request)
    {
        //
        $servicio=new Servicio();
        $servicio->item=$request->item;
        $servicio->subitem=$request->subitem;
        $servicio->nombre=strtoupper($request->nombre);
        $servicio->monto=$request->monto;
        $servicio->unit_id=$request->unit_id;
        $servicio->save();

    }

    /**
     * Display the specified resource.
     */
    public function show(Servicio $servicio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Servicio $servicio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServicioRequest $request, Servicio $servicio)
    {
        //
        $servicio=Servicio::find($request->id);
        $servicio->item=$request->item;
        $servicio->subitem=$request->subitem;
        $servicio->nombre=strtoupper($request->nombre);
        $servicio->monto=$request->monto;
        $servicio->unit_id=$request->unit_id;
        $servicio->save();
    }

    public function servEstado(Request $request){
        $servicio=Servicio::find($request->id);
        if($servicio->estado=='ACTIVO')
            $servicio->estado='INACTIVO';
        else
            $servicio->estado='ACTIVO';
        $servicio->save();

    }

    public function uploadImg(Request $request){
        $this->validate($request, [
            'file'=>'required',
            'id'=>'required'
        ]);
            if ($request->hasFile('file')){
                $file    = $request->file('file');
                $nombre  = $request->id.".".$file->getClientOriginalExtension();
                $image = ImageManager::imagick()->read($file->getRealPath());
                $image->scale(600, 600);
                $image->save(public_path('/imagen/'.$nombre));
            }
            //return $nombre;

            $servicio=Servicio::find($request->id);
            $servicio->imagen=$nombre;
            $servicio->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servicio $servicio)
    {
        //
    }
}
