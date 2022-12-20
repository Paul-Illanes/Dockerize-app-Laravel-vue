<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subactividade extends Model
{
    static $rules = [
        'actividad' => 'required',
    ];

    protected $table = 'essi_subactividades';
    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cod', 'actividad', 'tipo_prog', 'abreviacion', 'tipo_actividad_id', 'active'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function personalRolDetalles()
    {
        return $this->hasMany('App\Models\PersonalRolDetalle', 'actividad_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subactividadHorario()
    {
        return $this->hasOne('App\Models\SubactividadHorario', 'actividad_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tipoActividade()
    {
        return $this->hasOne('App\Models\TipoActividade', 'id', 'tipo_actividad_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BenlognsToMany
     */
    public function horarios()
    {
        return $this->belongsToMany('App\Models\Horario', 'actividad_horario', 'actividad_id', 'horario_id');
    }
}
