<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CambioTurno extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $attributes = [
        'status' => 0,
        'registro_essi' => false,
    ];
    //
    protected $fillable = [
        'fecha',
        'anio',
        'nro_papeleta',
        'nombre_jefe',
        'nombre_servicio',
        'cod_planilla',
        'dni',
        'solicitante_id',
        'aceptante_id',
        'turno_origen',
        'turno_cambio',
        'origen_fecha',
        'origen_ingreso',
        'origen_salida',
        'cambio_fecha',
        'cambio_ingreso',
        'cambio_salida',
        'motivo',
        'status',
        'registro_essi',
        'observacion_essi_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'vinculo_laboral'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
    public function userUpdated()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }
    public function solicitante()
    {
        return $this->hasOne('App\Models\Persona', 'dni', 'solicitante_id');
    }
    public function aceptante()
    {
        // model | model_id | fk -id
        return $this->hasOne('App\Models\Persona', 'dni', 'aceptante_id');
    }
    public function observacionEssi()
    {
        return $this->hasOne('App\Models\EssiObservacione', 'id', 'observacion_essi_id');
    }

    /**
     * count CT as solicitante for current month
     */
    static function countSolicitado($dni) //,$fecha_inicio,$fecha_fin
    {
        $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
        $last_day_this_month  = date('Y-m-t');

        return self::where('solicitante_id', $dni)
            ->where('status', '!=', 3)
            ->whereBetween('origen_fecha', [$first_day_this_month, $last_day_this_month])
            ->count();
    }

    /**
     * count CT as aceptante for current month
     */
    static function countAceptado($dni) //,$fecha_inicio,$fecha_fin
    {
        $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
        $last_day_this_month  = date('Y-m-t');

        return self::where('aceptante_id', $dni)
            ->where('status', '!=', 3)
            ->whereBetween('cambio_fecha', [$first_day_this_month, $last_day_this_month])
            ->count();
    }

    static function countPendientes()
    {
        $user_id = auth()->user()->id;
        if ($user_id)
            return self::join('personas', 'personas.dni', '=', 'cambio_turnos.solicitante_id')
                ->join('personas as p2', 'p2.dni', '=', 'cambio_turnos.aceptante_id')
                ->where(function ($query) use ($user_id) {
                    $query->whereIn(
                        'personas.sub_estructura',
                        \App\Models\User::find($user_id)
                            ->supestructuras()
                            ->select('id')
                    );
                    $query->orWhereIn(
                        'p2.sub_estructura',
                        \App\Models\User::find($user_id)
                            ->supestructuras()
                            ->select('id')
                    );
                })
                ->where('cambio_turnos.status', 0)
                ->count();
    }

    static function countRespondidas()
    {
        $user_id = auth()->user()->id;
        if ($user_id)
            return self::join('personas', 'personas.dni', '=', 'cambio_turnos.solicitante_id')
                ->join('personas as p2', 'p2.dni', '=', 'cambio_turnos.aceptante_id')
                ->where(function ($query) use ($user_id) {
                    $query->whereIn(
                        'personas.sub_estructura',
                        \App\Models\User::find($user_id)
                            ->supestructuras()
                            ->select('id')
                    );
                    $query->orWhereIn(
                        'p2.sub_estructura',
                        \App\Models\User::find($user_id)
                            ->supestructuras()
                            ->select('id')
                    );
                })
                ->where('cambio_turnos.status', '>', 0)
                ->count();
    }

    static function countStatus($status)
    {
        $user_id = auth()->user()->id;
        if ($status)
            return self::join('personas', 'personas.dni', '=', 'cambio_turnos.solicitante_id')
                ->join('personas as p2', 'p2.dni', '=', 'cambio_turnos.aceptante_id')
                ->where(function ($query) use ($user_id) {
                    $query->whereIn(
                        'personas.sub_estructura',
                        \App\Models\User::find($user_id)
                            ->supestructuras()
                            ->select('id')
                    );
                    $query->orWhereIn(
                        'p2.sub_estructura',
                        \App\Models\User::find($user_id)
                            ->supestructuras()
                            ->select('id')
                    );
                })
                ->where('cambio_turnos.status', $status)
                ->count();
    }

    // QUERY SCOPE

    public function scopeSupestructura($query, $user_id)
    {
        if ($user_id)
            return $query->join('personas', 'personas.dni', '=', 'cambio_turnos.solicitante_id')
                ->join('personas as p2', 'p2.dni', '=', 'cambio_turnos.aceptante_id')
                ->whereIn(
                    'personas.sub_estructura',
                    \App\Models\User::find($user_id)
                        ->supestructuras()
                        ->select('id')
                )
                ->orWhereIn(
                    'p2.sub_estructura',
                    \App\Models\User::find($user_id)
                        ->supestructuras()
                        ->select('id')
                );
    }


    public function scopeDni($query, $dni)
    {
        if ($dni)
            return $query
                ->where('cambio_turnos.solicitante_id', "%$dni%")
                ->orwhere('cambio_turnos.aceptante_id', "%$dni%");
    }



    public function scopeName($query, $name)
    {
        if ($name)
            return $query
                ->where('personas.nombres', 'LIKE', "%$name%")
                ->orwhere('personas.apellido_paterno', 'LIKE', "%$name%")
                ->orwhere('personas.apellido_materno', 'LIKE', "%$name%")
                ->orwhere('p2.nombres', 'LIKE', "%$name%")
                ->orwhere('p2.apellido_paterno', 'LIKE', "%$name%")
                ->orwhere('p2.apellido_materno', 'LIKE', "%$name%");
    }

    public function scopeNumero($query, $numero)
    {
        if ($numero)
            return $query
                ->orwhere('cambio_turnos.nro_papeleta', 'LIKE', "%$numero%");
    }

    public function scopeCerrado($query, $close)
    {

        if ($close === 0)
            return $query->where('cambio_turnos.status', 0);
        if ($close)
            return $query->where('cambio_turnos.status', '>', 0);
        if (!$close)
            return $query->where('cambio_turnos.status', 0);
    }

    public function scopeEssi($query, $essi)
    {
        if ($essi)
            return $query
                // ->where('cambio_turnos.registro_essi',$essi);
                ->where('cambio_turnos.registro_essi', $essi);
    }
    public function scopeAceptante($query, $dni)
    {
        if ($dni)
            return $query->join('personas as aceptante', 'aceptante.dni', '=', $dni);
    }
    public function scopeSolicitante($query, $dni)
    {
        if ($dni)
            return $query->join('personas as solicitante', 'solicitante.dni', '=', $dni);
    }
}
