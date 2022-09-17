<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Papeleta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
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
        'vinculo_laboral',
    ];
    public function scopeSupestructura($query, $user_id)
    {
        if ($user_id)
            return $query->join('personas', 'personas.dni', '=', 'papeletas.dni')
                ->whereIn(
                    'personas.sub_estructura',
                    \App\Models\User::find($user_id)
                        ->supestructuras()
                        ->select('id')
                );
    }
    public function user()
    {
        //return $this->belongsTo(user::class);
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
    public function userUpdated()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }
}
