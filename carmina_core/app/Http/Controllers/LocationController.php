<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Empresa;
use App\Models\Product;
use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function __construct()
    {

    }

    public function index(){
        return view('location')->with(['companies'=>null, 'categories' => null, 'paises' => null, 'ciudades' => null, 'zipcodes' => null, 'familias' => null]);
    }

    public function getClients(Request $request){
        if($request->ajax()) {
            try {

                $input = $request->all();
                $id = hashid('hashids_integer')->decode($input['location']);
                $location = Location::where('id', $id)->firstOrFail();
                $radius = $location->radius;
                $clients = \DB::select('EXEC sp_getClientsByDistance ?, ?, ?', [
                    $location->latitude,
                    $location->longitude,
                    $location->radius
                ]);

                $clients = collect($clients);
                $categories = $clients->pluck('tipocliente')->map(fn($v) => trim($v))->unique()->values();
                $paises = $clients->pluck('id_pais')->map(fn($v) => trim($v))->unique()->values();
                $ciudades = $clients->pluck('ciudad')->map(fn($v) => trim($v))->filter()->unique()->values();
                $zipcodes = $clients->pluck('zipcode')->map(fn($v) => trim($v))->unique()->values();
                $empresas = $clients->pluck('id_empresa')->unique()->values();
                $companies = Empresa::whereIn('id', $empresas)->get();
                $familias = $clients
                                ->pluck('familia')
                                ->filter()
                                ->flatMap(function($item) {
                                    return array_map('trim', explode(',', $item));
                                })
                                ->filter()
                                ->unique()
                                ->sort()
                                ->values();

                $data = [
                    'clients' => $clients,
                    'companies' => $companies,
                    'categories' => $categories,
                    'paises' => $paises,
                    'ciudades' => $ciudades,
                    'zipcodes' => $zipcodes,
                    'familias' => $familias,
                    'time' => $location->time,
                    'compLatitude' => $location->latitude,
                    'compLongitude' => $location->longitude,
                    'compName' => $location->name,
                ];
                return response()->json($data, 200);
            } catch (\Throwable $th) {
                Log::error($th);
                return response()->json('Server Error', 500);
            }

        } else {
            return response()->json('Access denied', 403);
        }
    }

    public function getProducts(Request $request){
        if($request->ajax()) {
            $input = $request->all();
            $query = Client::clientes_productos($input);
            return response()->json($query, 200);
        } else {
            return response()->json('Access denied', 403);
        }
    }
}
