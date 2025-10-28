<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Empresa;
use App\Models\Product;
use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * The URL for the initial JSON data.
     *
     * @var string
     */
    private $jsonUrlInicial;
    private $jsonUrlInicialAdmin;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        if(env('APP_ENV') == 'local'){
            $this->jsonUrlInicial = 'https://stgbbtemployeesproduct01.blob.core.windows.net/gvc-employees-product-99/gvc-carga-inicial-filtrado-blob-99?sv=2024-11-04&si=gvc-employees-product-99-web-00&sr=c&sig=l%2FbRNJYA9JRU%2FD9hXVDidXRZ5PABx%2BW0XFsZhvsQ7zc%3D';
            $this->jsonUrlInicialAdmin = 'https://stgbbtemployeesproduct01.blob.core.windows.net/gvc-employees-product-99/gvc-carga-inicial-blob-99?sv=2024-11-04&si=gvc-employees-product-99-web-00&sr=c&sig=l%2FbRNJYA9JRU%2FD9hXVDidXRZ5PABx%2BW0XFsZhvsQ7zc%3D';
        }else{
            $this->jsonUrlInicial = 'https://stgbbtemployeesproduct01.blob.core.windows.net/gvc-employees-product-01/gvc-carga-inicial-filtrado-blob-01?sv=2023-01-03&si=gvc-employees-product-01-web-00&sr=c&sig=YD%2Fw4DxzxZJEOAxcFnSIlgbEaa3OHaKFi2SBb0%2B2vBU%3D';
            $this->jsonUrlInicialAdmin = 'https://stgbbtemployeesproduct01.blob.core.windows.net/gvc-employees-product-01/gvc-carga-inicial-blob-01?sv=2023-01-03&si=gvc-employees-product-01-web-00&sr=c&sig=YD%2Fw4DxzxZJEOAxcFnSIlgbEaa3OHaKFi2SBb0%2B2vBU%3D';
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $companies = auth()->user()->empresas()->get();
        $clients = Client::clientes_usuario();
        $categories = $clients->unique('tipocliente')->sortBy('tipocliente')->pluck('tipocliente');
        $paises = $clients->unique('id_pais')->sortBy('id_pais')->pluck('id_pais');
        $ciudades = $clients->unique('ciudad')->sortBy('ciudad')->pluck('ciudad');
        $zipcodes = $clients->unique('zipcode')->sortBy('zipcode')->pluck('zipcode');
        $familias = Product::productos_familiaXempresa($companies);
        return view('homeGoogle')->with(['companies'=>$companies, 'categories' => $categories, 'paises' => $paises, 'ciudades' => $ciudades, 'zipcodes' => $zipcodes, 'familias' => $familias]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index2()
    {

        $companies = auth()->user()->empresas()->get();
        $clients = Client::clientes_usuario();
        $categories = $clients->unique('tipocliente')->sortBy('tipocliente')->pluck('tipocliente');
        $paises = $clients->unique('id_pais')->sortBy('id_pais')->pluck('id_pais');
        $ciudades = $clients->unique('ciudad')->sortBy('ciudad')->pluck('ciudad');
        $zipcodes = $clients->unique('zipcode')->sortBy('zipcode')->pluck('zipcode');
        $familias = Product::productos_familiaXempresa($companies);
        return view('home')->with(['companies'=>$companies, 'categories' => $categories, 'paises' => $paises, 'ciudades' => $ciudades, 'zipcodes' => $zipcodes, 'familias' => $familias]);
    }

    public function indexJson(){
        return view('homeGoogle')->with(['companies'=>null, 'categories' => null, 'paises' => null, 'ciudades' => null, 'zipcodes' => null, 'familias' => null]);
    }

    public function getClients(Request $request){
        if($request->ajax()) {
            try {
                $input = $request->all();
                $url = $this->jsonUrlInicial;
                if(auth()->user()->rol == 2){
                    $url = $this->jsonUrlInicialAdmin;
                }
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);
                curl_close($ch);
                $data = [];
                $companies = Empresa::all();
                $tiposcliente = User::acceso_tipo_clientes(auth()->user()->id);
                foreach ($tiposcliente as $tipoCliente) {
                    $tipoCliente->tipocliente = str_replace('-',' ',$tipoCliente->tipocliente);
                }
                if(json_decode($response)){
                    $clients = json_decode($response);
                    //filtramos los datos segun el acceso a las empresas
                    $clients = array_filter($clients, function($client)use($tiposcliente){
                        $client->zipcode = trim($client->zipcode);
                        $client->accion = '<div class="w-100" style="cursor:pointer" data-id="'.$client->id_cliente.'" data-id_empresa="'.$client->id_empresa.'" data-emp="'.$client->Empresa.'" data-dir="'.$client->direccion.'" data-nombre="'.$client->nombre.'" onclick="ubicarCliente(this)" title="Ubicar en el mapa">'.$client->nombre.'</div>';
                        return $client->latitud != 0 && in_array($client->tipocliente, $tiposcliente->pluck('tipocliente')->toArray());
                    });
                }else{
                    $clients = Client::clientes_usuario($input);
                }

                $categories = array_values(array_unique(array_column($clients, 'tipocliente')));
                $paises = array_values(array_unique(array_column($clients, 'id_pais')));
                $ciudades = array_values(array_unique(array_column($clients, 'ciudad')));
                $zipcodes = array_values(array_unique(array_column($clients, 'zipcode')));
                // Filtrar los valores nulos
                $filteredArray = array_filter(array_column($clients, 'familia'), function($item) {
                    return !is_null($item);
                });
                $ciudades = array_filter($ciudades, function($item) {
                    return $item !== null;
                });
                // Convertir cada string en un array de elementos y combinarlos en un solo array
                $combinedArray = [];
                foreach ($filteredArray as $item) {
                    $elements = explode(',', $item);
                    $trimmedElements = array_map('trim', $elements); // Aplicar trim a cada elemento
                    $combinedArray = array_merge($combinedArray, $trimmedElements);
                }
                // Obtener los valores únicos
                $familias = array_values(array_unique($combinedArray));
                //ordenar el array
                sort($familias);

                $data['clients'] = array_values($clients);
                $data['companies'] = $companies;
                $data['categories'] = $categories;
                $data['paises'] = $paises;
                $data['ciudades'] = $ciudades;
                $data['zipcodes'] = $zipcodes;
                $data['familias'] = $familias;
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

    public function indexLocation(){
        return view('location.index');
    }

    public function getLocations(Request $request){
        if($request->ajax()) {
            try {
                $locations = Location::all();
                foreach ($locations as $element) {
                    $element->url = '<a href="'.route('location').'?loc='.hashid('hashids_integer')->encode($element->id).'" target="_blank">Ver</a>';
                    $element->actions = '
                        <div class="d-flex flex-column flex-md-row gap-2">
                            <a class="btn btn-warning" href="'.route('editLocation', $element->id).'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="20">
                                    <path d="M290.7 93.2l128 128-278 278-114.1 12.6C11.4 513.5-1.6 500.6 .1 485.3l12.7-114.2 277.9-277.9zm207.2-19.1l-60.1-60.1c-18.8-18.8-49.2-18.8-67.9 0l-56.6 56.6 128 128 56.6-56.6c18.8-18.8 18.8-49.2 0-67.9z"/>
                                </svg>
                            </a>
                            <button class="btn btn-danger" onclick="deleteLocation('.$element->id.', \''.$element->name.'\')">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" height="20">
                                    <path fill="white" d="M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.7 23.7 0 0 0 -21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0 -16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z"/>
                                </svg>
                            </button>
                        </div>';
                }
                return response()->json($locations, 200);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }else {
            return response()->json('Access denied', 403);
        }
    }

    public function createLocation(Request $request){
        return view('location.create')->with(['edit'=> false]);
    }

    public function storeLocation(Request $request){
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'address' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'radius' => 'required',
                'time' => 'required',
            ]);
            // $validatedData['idExt']= strtolower(str_replace(' ','_', $validatedData['name']));

            $location = Location::insertGetId($validatedData);
            return redirect(route('indexLocation'))->with('success','Se han guardado los datos.');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->withInput($request->input())->withError('No se ha podido guardar los datos');
        }
    }

    public function editLocation(Request $request, $id){
        $location = Location::where('id', $id)->firstOrFail();
        return view('location.edit')->with(['location'=>$location, 'edit'=> true]);
    }

    public function updateLocation(Request $request, $id){
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                // 'address' => 'required',
                // 'latitude' => 'required',
                // 'longitude' => 'required',
                'radius' => 'required',
                'time' => 'required',
            ]);
            // $validatedData['idExt']= strtolower(str_replace(' ','_', $validatedData['name']));

            $udpated = Location::whereid($id)->update($validatedData);
            return redirect(route('indexLocation'))->with('success','Se han actualizado los datos.');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->withInput($request->input())->withError('No se ha podido actualizar los datos');
        }
    }

    public function deleteLocation(Request $request, $id){
        try {
            $location = Location::where('id', $id)->firstOrFail();
            $location->delete();
            return response()->json('good', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json('bad', 400);
        }
    }
}
