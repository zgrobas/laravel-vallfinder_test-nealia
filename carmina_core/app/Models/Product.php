<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use DB;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_product';
    public $table = 'products';
    public $timestamps = false;
    protected $fillable = ['name_cons', 'id_empresa'];

    public function clientes($id_empresa): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'product_clientes', 'id_product', 'id_cliente')->wherePivot('id_empresa', $id_empresa);
    }

    protected $casts = [
        'id_product' => 'string',
        'name_cons' => 'string',
        'id_empresa' => 'integer',
    ];

    public static function productos_familiaXempresa($empresas = null, $empArray = []){
        $list_emp = "";
        if(is_null($empresas)){
            $list_emp = implode(',', $empArray);
        }else{

            foreach ($empresas as $empresa) {
                $list_emp .= $empresa->id.',';
            }
            $list_emp = substr($list_emp, 0, -1);
        }

        return DB::table("product_clientes")
        ->whereRaw("product_clientes.id_empresa in (".$list_emp.")")
        ->select(["familia"])
        ->distinct()
        ->get();
    }
    public static function productos_tipoClienteXuser($id_user){
        return DB::table("clientes")
            ->join("product_clientes", function($join){
                $join->on("clientes.id_cliente", "=", "product_clientes.id_cliente");
            })
            ->join("user_tipocliente", function($join) use($id_user){
                $join->on("clientes.tipocliente", "=", "user_tipocliente.tipocliente")
                ->where("user_tipocliente.id_user", "=", $id_user);
            })
            ->whereRaw('product_clientes.familia is not null')
            ->select("product_clientes.familia")
            ->distinct()
            ->orderBy("product_clientes.familia","asc")
            ->get();
    }

}
