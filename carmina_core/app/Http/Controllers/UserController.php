<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        // $empresas = Empresa::all();
        $tiposClientes = Client::tiposClientes();
        return view('auth.register')->with(['tiposClientes' => $tiposClientes]);
    }

    public function register(Request $request){
        try {
            DB::beginTransaction();
            $data = $request->all();
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                // 'password' => ['required', 'string', 'min:8'],
                'rol'=> '',
            ]);
            $validatedData['password'] = Hash::make('123456789');
            $user = User::insertGetId($validatedData);
            //asignamos las empresas al usuario recien creado
            $empresas = (array_key_exists("empresa",$data))? $data['empresa'] : [];
            User::actualizarPermisos($user, $empresas);

            session()->flash('success', 'Usuario registrado');
            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) {
            session()->flash('error', 'Ha ocurrido un error, por favor contacte al administrador');
            DB::rollback();
            Log::error($th);
            return redirect()->back()->withInput($request->input());
        }
    }

    public function update(Request $request){
        $tiposClientes = Client::tiposClientes();
        if(auth()->user()->super_admin == 1){
            $usuarios = User::all();
        }elseif(auth()->user()->rol == 2){
            $usuarios = User::where('id', '=', auth()->user()->id)->get();
        }
        return view('auth.update')->with(['tiposClientes' => $tiposClientes, 'usuarios' => $usuarios]);
    }

    public function upgrade(Request $request){
        try {
            DB::beginTransaction();
            $data = $request->all();
            //actualizamos el rol del usuario si es super admin
            $user = User::find($data['usuario']);
            if(auth()->user()->super_admin){
                $user->rol = $data['rol'];
                $user->save();
            }
            //asignamos las empresas al usuario recien creado
            $empresas = (array_key_exists("empresa",$data))? $data['empresa'] : [];
            User::actualizarPermisos($user->id, $empresas);

            session()->flash('success', 'Usuario actualizado');
            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) {
            session()->flash('error', 'Ha ocurrido un error, por favor contacte al administrador');
            DB::rollback();
            Log::error($th);
            return redirect()->back()->withInput($request->input());
        }
    }

    public function getUsuarioInfo(Request $request){
        if($request->ajax()) {
            $input = $request->all();
            $user = User::whereId($input['user'])->first();
            $query = User::acceso_tipo_clientes($user->id);
            return response()->json([$user->rol,$query], 200);
        } else {
            return response()->json('Access denied', 403);
        }
    }
}
