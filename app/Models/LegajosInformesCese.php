<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LegajosInformesCese extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    static $rules = [
        'dni' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['baja_id', 'nombre', 'dni', 'codigo_planilla', 'fecha_nacimiento', 'fecha_ingreso', 'ultimo_dia_labor', 'fecha_cese', 'regimen_laboral', 'grupo_ocupacional', 'numero_documento_cese', 'motivo_cese', 'regimen_pensionario', 'linea_carrera', 'condicion_laboral', 'modalidad_contrato', 'dependencia', 'licencia_sg_haber', 'sancion_disciplinaria', 'licencia_covid', 'permisos_particulares', 'acuenta_vacaciones', 'tiempo_servicio', 'total_tpo_deducible', 'total_tpo_essalud', 'nit', 'numero_informe', 'fecha_informe', 'path_informe', 'status'];

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
