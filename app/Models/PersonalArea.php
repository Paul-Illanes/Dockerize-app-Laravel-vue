<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalArea extends Model
{
    use HasFactory;
    protected $table = 'personal_area';
    protected $fillable = [
        'persona_dni',
        'servicio_id',
        'dependencia_id',
        'superstructura_id',
        'area_principal',
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
    public function scopeServicio($query, $id, $servicio_id)
    {
        if ($id)
            return $query->join('cms_parameters as servicio', 'servicio.id', '=', $id)->where('servicio.id', '=', $servicio_id);
    }
    public function scopeDependencia($query, $id, $dependencia_id)
    {
        if ($id)
            return $query->join('cms_parameters as dependencia', 'dependencia.id', '=', $id)->where('dependencia.id', '=', $dependencia_id);
    }
}
