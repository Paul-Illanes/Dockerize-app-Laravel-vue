<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class VacacionesDocumento extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'tipo_documento_id',
        'persona_dni',
        'fecha_inicio',
        'fecha_final',
        'nro_dias',
        'observacion',
        'periodo',
        'folio',
        'documento',
        'nit',
        'tipo',
        'mes',
        'estado_valido',
        'estado_esi',
        'es_suspension',
        'papeleta_id',
        'actualizado_por',
        'fecha_recepcion',
        'id_usuarios',
        'created_by',
        'updated_by',
    ];
}
