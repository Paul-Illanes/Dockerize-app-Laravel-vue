<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $primaryKey = 'dni';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [

        'cod_planilla',
        'sub_estructura',
        'estructura',
        'dependencia',
        'condicion_laboral_id',
        'c_l',
        'nivel',
        'grupo',
        'cargo',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'cod_cargo',
        'dni',
        'fecha_ingreso',
        'fecha_nacimiento',
        'tipo_personal',
        'status', //0=nuevo, 1=activo, 2=baja
        'reingreso', // 0=no 1= si
        'forma_registro', //MIGRACION, XLS, CRUD
        'obs',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];


    public function getFullNameAttribute()
    {
        return "{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}";
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'username', 'dni');
    }

    public function personalRolDetalle()
    {
        return $this->hasMany('App\Models\PersonalRolDetalle', 'persona_dni', 'dni');
        // return $this->hasOne('App\Models\PersonalRolDetalle', 'personal_rol_id', 'id');
    }

    public function servicios()
    {
        return $this->belongsToMany('App\Models\Servicio', 'personal_servicio', 'persona_dni', 'servicio_id');
    }

    //Functions

    /**
     * change status
     */
    public function changeStatus(int $status)
    {
        $user_id = auth()->user() ? auth()->user()->id : null;
        $this::update([
            'status' => $status,
            'updated_by' => $user_id,
        ]);
    }


    /**
     * function to 
     */
    public function deshacerBaja()
    {
        $user_id = auth()->user() ? auth()->user()->id : null;
        // cod planilla = 0 - nuevo
        $this::where('cod_planilla', '0')->update([
            'status' => 0,
            'updated_by' => $user_id,
        ]);

        $this::where('cod_planilla', '!=', 0)->update([
            'status' => 1,
            'updated_by' => $user_id,
        ]);
    }

    //Query scope
    public function scopeSupestructura($query, $user_id)
    {
        if ($user_id)
            return $query->whereIn(
                'personas.sub_estructura',
                \App\Models\User::find($user_id)
                    ->supestructuras()
                    ->select('id')
            );
    }

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
}
