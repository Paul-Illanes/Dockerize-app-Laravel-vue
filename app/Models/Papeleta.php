<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\actividad_vinculo;

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
    public function actividad_vinculo($id_vinculo)
    {
        $vinculo = new actividad_vinculo();
        $vinculo->vinculo_laboral_id = $id_vinculo;
        $vinculo->documento_id = $this->id;
        $vinculo->status = 1;
        $vinculo->referencia = 'papeleta';
        $vinculo->save();
    }
    public function scopeVinculo($query, $id)
    {
        if ($id)
            return $query->join('actividad_vinculo', 'actividad_vinculo.documento_id', '=', $id)
                ->join('vinculo_laboral', 'vinculo_laboral.id', 'actividad_vinculo.vinculo_laboral_id')
                ->where('vinculo_laboral.status', '=', '1');
    }
}
