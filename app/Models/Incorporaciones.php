<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incorporaciones extends Model
{
    protected $table = 'incorporaciones';
    protected $primaryKey = 'id';
    // protected $primaryKey = 'dni';
    // public $incrementing = false;
    // protected $keyType = 'string';

    static $rules = [
        'dni' => 'required',

    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organo',
        'centro',
        'area',
        'cod_area',
        'plaza',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'sexo',
        'estado_civil',
        'domicilio',
        'dni',
        'fecha_nacimiento',
        'nivel_educativo',
        'sistema_pension',
        'nombre_afp',
        'cod_afp',
        'afp_cuz',
        'fecha_afp',
        'cod_cargo',
        'cargo',
        'especialidad',
        'nivel',
        'fecha_inicio',
        'fecha_termino',
        'bonificacion',
        'remuneracion',
        'ingreso_mensual',
        'banco',
        'numero_cuenta_banco',
        'motivo_contrato',
        'modalidad_contrato',
        'beneficiario_ley30555',
        'ruc',
        'numero_proceso_seleccion',
        'fecha_proceso',
        'situacion_proceso',
        'personal_deja_cargo',
        'tipo_ausencia',
        'fecha_inicio_ausencia',
        'fecha_fin_ausencia',
        'alta_baja',
        'mes_alta_planilla',
        'suspension_cuarta',
        'celular',
        'correo',
        'status',
        'cerrado',
        'periodo'
    ];

    public function getFullNameAttribute()
    {
        return "{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}";
    }

    //Query scope
    public function scopeName($query, $name)
    {
        if ($name)
            return $query->where('nombres', 'LIKE', "%$name%")
                ->orwhere('apellido_paterno', 'LIKE', "%$name%")
                ->orwhere('apellido_materno', 'LIKE', "%$name%");
    }

    public function scopeDni($query, $dni)
    {
        if ($dni)
            return $query->orwhere('dni', 'LIKE', "%$dni%");
    }

    public function scopeStatus($query, $status)
    {
        if ($status === '0')
            return $query->where('status', 0);
        if ($status) {
            return $query->where('status', '=', $status);
        }
    }
    public function validation()
    {
        return $this->hasMany('App\Models\IncorporacionesValidate', 'incorporacion_id', 'id');
    }
    // public function parameter()
    // {
    //     return $this->belongsTo('App\Models\CmsParameter', 'parameters_id', 'id');
    // }
}
