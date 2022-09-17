<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Contratos extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $fillable = ['tipo_contrato', 'regimen_laboral', 'empleado_dni', 'empleado_ruc', 'empleado_direccion', 'cargo', 'lugar_prestacion_servicio', 'remuneracion', 'fecha_inicio', 'fecha_termino', 'fecha_firma_contrato', 'status_firma', 'status_contrato', 'created_by', 'updated_by'];
}
