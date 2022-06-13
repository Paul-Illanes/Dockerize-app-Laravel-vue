<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Papeleta extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha',
        'anio',
        'numero_correlativo',
        'nro_papeleta',
        'fecha',
        'cod_planilla',
        'dni',
        'nombres',
        'tipo_permiso_id',
        'fecha_salida',
        'fecha_retorno',
        'hora_salida',
        'hora_retorno',
        'tdm',
        'tdd',
        'doc',
        'nit',
        'observacion',
        'presento',
        'chk2',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}
