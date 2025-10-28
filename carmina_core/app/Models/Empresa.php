<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Empresa extends Model
{
    use HasFactory;

    public $table = 'empresa';
    public $timestamps = false;
    protected $fillable = ['name'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_empresa', 'id_empresa', 'id_user')->withPivot('admin');
    }
}
