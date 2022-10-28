<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class VacacionesProgramacion extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $table = 'vacaciones_programaciones';
    protected $fillable = [
        'dni',
        'cod_planilla',
        'nombres',
        'fecha_ingreso',
        'regimen_laboral',
        'periodo_vacacional',
        'mes_programacion_reportado',
        'anio_programacion_reportado',
        'mes_programacion_solicitado',
        'anio_programacion_solicitado',
        'mes_programacion_pago',
        'anio_programacion_pago',
        'mes_reprogramacion',
        'anio_reprogramacion',
        'estado_presentacion',
        'obs_1',
        'obs_2',
        'obs_3',
        'nit',
        'numero_documento',
        'fecha_documento',
        'path_documento',
        'documento',
        'folio'
    ];
    public function grupo()
    {
        return $this->hasMany('App\Models\PersonalGrupo', 'dni', 'dni');
    }
}
