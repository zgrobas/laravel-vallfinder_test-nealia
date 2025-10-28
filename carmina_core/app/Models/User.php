<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'azure_id',
        'super_admin',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'super_admin' => 'boolean',
        'rol' => 'integer',
    ];

    public function empresas(): BelongsToMany
    {
        return $this->belongsToMany(Empresa::class, 'user_empresa', 'id_user', 'id_empresa')->withPivot('admin');
    }

    // public static function actualizarPermisos($id_user, $empresas){
    public static function actualizarPermisos($id_user, $tipos_cliente){
        try {
            // DB::delete('delete from user_empresa where id_user = '.$id_user.';');
            DB::delete('delete from user_tipocliente where id_user = '.$id_user.';');
            foreach ($tipos_cliente as $tipocliente) {
                // DB::insert('insert into user_empresa (id_user, id_empresa, admin) values (?, ?, ?)', [$id_user, $empresa, 0]);
                DB::insert('insert into user_tipocliente (id_user, tipocliente) values (?, ?)', [$id_user, $tipocliente]);
            }
            return 1;
        } catch (\PDOException $e) {
            return 0;
        }
    }

    public static function acceso_tipo_clientes($id_user){
        return DB::table("user_tipocliente")->select('tipocliente')->where('id_user', $id_user)->distinct()->orderBy('tipocliente','ASC')->get();
    }

}
