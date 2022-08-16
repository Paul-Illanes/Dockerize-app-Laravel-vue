<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PersonalInformesCese extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    // protected $attributes = [
    //     'forma_registro' => 'CRUD',
    // ];

    static $rules = [
        'dni' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'dni', 'fecha_ingreso', 'fecha_cese', 'motivo_cese', 'faltas', 'tardanzas', 'licencias', 'permisos_particulares', 'sancion_disciplinaria', 'huelga', 'licencia_covid', 'vacaciones_no_gozadas', 'bono_pago', 'descuento_bono_pago', 'guardias', 'horas_extras', 'horas_rpct', 'pct', 'notas_adicionales', 'zona_menor_desarrollo', 'nit', 'numero_informe', 'fecha_informe', 'path_informe', 'forma_registro', 'created_by', 'updated_by'];


    public function persona()
    {
        return $this->hasOne('App\Models\Persona', 'dni', 'dni');
    }

    public function user()
    {
        //return $this->belongsTo(user::class);
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function userUpdate()
    {
        //return $this->belongsTo(user::class);
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }
}
