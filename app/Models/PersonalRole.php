<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalRole extends Model
{
    static $rules = [];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['servicio_id', 'anio', 'mes', 'fecha_cierre'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function personalRolDetalle()
    {
        return $this->hasOne('App\Models\PersonalRolDetalle', 'personal_rol_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function servicio()
    {
        return $this->hasOne('App\Models\Servicio', 'id', 'servicio_id');
    }
}
