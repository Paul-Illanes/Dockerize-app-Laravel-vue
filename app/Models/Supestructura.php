<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supestructura extends Model
{

    // use \OwenIt\Auditing\Auditable;

    protected $table = 'supestructura';

    protected $primaryKey  = 'id';
    public $incrementing = false;
    protected $keyType = 'string';



    public function users()
    {
        return $this->belongsToMany('App\Models\Supestructura', 'user_has_supestructura', 'user_id', 'supestructura_id');
    }
}
