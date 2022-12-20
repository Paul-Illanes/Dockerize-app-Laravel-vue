<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalRolDetalle extends Model
{
    static $rules = [];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['persona_dni', 'actividad_id', 'personal_rol_id', 'fecha_turno', 'anio', 'mes', 'dia', 'hora', 'minuto', 'estado_trabajo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function actividad()
    {
        return $this->hasOne('App\Models\Subactividade', 'id', 'actividad_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function personalRole()
    {
        return $this->hasOne('App\Models\PersonalRole', 'id', 'personal_rol_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function persona()
    {
        // return $this->hasOne('App\Models\Persona', 'dni', 'persona_dni');
        return $this->belongsTo('App\Persona', 'dni', 'persona_dni');
    }
}
