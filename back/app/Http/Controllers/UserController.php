<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;
class UserController extends Controller
{
    //
    public function index()
    {
        //
        return User::where('id','<>',1)->get();
    }

    public function listUser($id)
    {
        //
        return User::select('id','nombre')->where('state','ACTIVO')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'nombre' => 'required',
            'name' => 'required|unique:users',
            'email' => 'required|email',
            'password' => 'required',
            'fechalimite' => 'required'
        ]);
        
        $validated['password']=Hash::make($validated['password']);
        $user = User::create($validated);

        return($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validated = $request->validate([
            'name' => 'required',
            'nombre' => 'required',
            'email' => 'required|email',
            'fechalimite' => 'required',
        ]);
        
        $user = User::find($request->id);
        $user->update($validated);
        return response(['user' => $user]);
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:6',
        ]);
        $request['password']=Hash::make($request['password']);
        $user->update($request->all());
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user=User::find($id);
        $user->delete();
        return response(['message' => 'Usuario eliminado']);
    }

    public function login(Request $request)
    {

        $user = User::where('name',$request->name)->whereDate('fechalimite','>=',date('Y-m-d'))->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $user->tokens()->delete();
                $user = User::where('name',$request->name)->first();
                $token = $user->createToken('authToken')->plainTextToken;
                return response(['user' => $user, 'token' => $token]);
            } else {
                return response(['message' => 'Contraseña incorrecta'],500);
            }
        } else {
            return response(['message' => 'Usuario no encontrado'],500);
        }
    }

    public function me($tokenString)
    {
        $token = PersonalAccessToken::findToken($tokenString);
            //return $token->tokenable->id;
            //if($token && $token->estado=='ACTIVO' && $token->fechalimite >= date('Y-m-d'))
            if ($token && $this->isTokenExpired($token)) {
                return false;
            }
        $user=User::where('id',$token->tokenable->id)
                    ->where('estado','ACTIVO')
                    ->whereDate('fechalimite','>=',date('Y-m-d'))
                    ->firstOrFail();
                return $user;
    }

    protected function isTokenExpired($token)
    {
        // Verificar la expiración basado en la configuración de Sanctum
        $expiration = $token->created_at->addMinutes(config('sanctum.expiration'));

        // Devolver true si la fecha actual es mayor que la fecha de expiración
        return Carbon::now()->greaterThan($expiration);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response(['message' => 'Sesión cerrada']);
    }

    public function cambioEstado($id){
        $user=User::find($id);
        if($user->state=='ACTIVO')
            $user->state='INACTIVO';
        else
            $user->state='ACTIVO';
        $user->save();
    }

}
