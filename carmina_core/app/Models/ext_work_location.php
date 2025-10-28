<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ext_work_location extends Model
{
    use HasFactory;
    public $table = 'ext_work_location';
    public $timestamps = false;
    protected $fillable = [ 'ID',
                            'STD_ID_LEG_ENT',
                            'STD_ID_WORK_LOCAT',
                            'STD_WORK_LOCESP',
                            ];
}
