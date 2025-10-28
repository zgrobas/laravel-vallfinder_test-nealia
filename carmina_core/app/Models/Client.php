<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Empresa;
use DB;

class Client extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cliente';
    public $table = 'clientes';
    public $timestamps = false;
    protected $fillable = ['id_cliente','nombre', 'direccion', 'tipocliente', 'coordenadas', 'latitud', 'longitud','id_empresa'];

    public function products($id_empresa): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_clientes', 'id_cliente', 'id_product')->wherePivot('id_empresa', $id_empresa);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id');
    }

    protected $casts = [
        'id_cliente' => 'string',
        'nombre' => 'string',
        'direccion' => 'string',
        'tipocliente' => 'string',
        'id_pais' => 'string',
        'coordenadas' => 'string',
        'latitud' => 'float',
        'longitud' => 'float',
        'zipcode' => 'string',
        'ciudad' => 'string',
        'id_empresa' => 'integer',
    ];

    public static function clientes_usuario($input = null){
        $query = DB::table("user_empresa")
                ->join("clientes", function($join){
                    $join->on("clientes.id_empresa", "=", "user_empresa.id_empresa");
                })
                ->join("empresa", function($join){
                    $join->on("empresa.id", "=", "clientes.id_empresa");
                })
                ->selectRaw("clientes.id_cliente, TRIM(clientes.direccion) as direccion, TRIM(clientes.nombre) as nombre, clientes.latitud, clientes.longitud,
                            TRIM(clientes.tipocliente) as tipocliente, TRIM(clientes.id_pais) as id_pais, TRIM(clientes.zipcode) as zipcode,
                            TRIM(clientes.ciudad) as ciudad, clientes.id_empresa, TRIM(empresa.name)  as Empresa")
                ->where("id_user", "=", auth()->user()->id);
        if(!is_null($input)){
            $filter = "1=1";
            if (array_key_exists("company",$input)){
                $filter.= ' AND clientes.id_empresa IN(';
                foreach($input['company'] as $data){
                    $filter.= "'".$data. "',";
                }
                $filter = substr_replace($filter ,"", -1);
                $filter.= ')';
            }
            if (array_key_exists("category",$input)){
                $filter.= ' AND clientes.tipocliente IN(';
                foreach($input['category'] as $data){
                    $filter.= "'".$data. "',";
                }
                $filter = substr_replace($filter ,"", -1);
                $filter.= ')';
            }
            $query->whereRaw($filter);
            if (array_key_exists("familias",$input)){
                $arr_famlias = explode(',', $input['familias']);
                $query->whereIn("user_empresa.id_empresa", function($query) use($arr_famlias){
                    $query->from("product_clientes")
                    ->select("id_empresa")
                    ->whereIn("familia", $arr_famlias);
                });
                $query->whereIn("clientes.id_cliente", function($query) use($arr_famlias){
                    $query->from("product_clientes")
                    ->select("id_cliente")
                    ->whereIn("familia", $arr_famlias);
                });
            }
        }

        return  $query->get();
    }

    public static function clientes_productos($input){
        return DB::table("clientes")
        ->join("product_clientes", function($join) use($input){
            $join->on("product_clientes.id_cliente", "=", "clientes.id_cliente")
            ->where("product_clientes.id_empresa", "=", $input['id_empresa']);
        })
        ->join("products", function($join) use($input){
            $join->on("products.id_product", "=", "product_clientes.id_product")
            ->where("products.id_empresa", "=", $input['id_empresa']);
        })
        ->select("products.name_cons","product_clientes.familia")
        ->where("clientes.id_empresa", "=", $input['id_empresa'])
        ->where("clientes.id_cliente", "=", $input['id_cliente'])
        ->distinct()
        ->get();
    }

    public static function tiposClientes(){
        return DB::table("clientes")->select('tipocliente')->distinct()->orderBy('tipocliente','ASC')->get();
    }
}
