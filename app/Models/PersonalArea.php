<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PersonalArea extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'personal_area';
    protected $fillable = [
        'dependencia_id',
        'supestructura_id',
        'area',
    ];
    // public function servicio()
    // {
    //     return $this->hasOne('App\Models\Servicio', 'id', 'servicio_id');
    // }
    public function scopeServicioFilter($query, $servicio)
    {
        if ($servicio)
            return $query
                ->where('servicio_id', $servicio);
    }
    // public function servicio()
    // {
    //     return $this->hasOne('App\Models\CmsParameter', 'id', 'servicio_id');
    // }
    // public function dependencia()
    // {
    //     // model | model_id | fk -id
    //     return $this->hasOne('App\Models\CmsParameter', 'id', 'dependencia_id');
    // }
    public function scopeSupestructura($query, $id, $supestructura_id)
    {
        if ($id)
            return $query->join('cms_parameters as supestructura', 'supestructura.id', '=', $id)->where('supestructura.id', '=', $supestructura_id);
    }
    public function scopeDependencia($query, $id, $dependencia_id)
    {
        if ($id)
            return $query->join('cms_parameters as dependencia', 'dependencia.id', '=', $id)->where('dependencia.id', '=', $dependencia_id);
    }
}
