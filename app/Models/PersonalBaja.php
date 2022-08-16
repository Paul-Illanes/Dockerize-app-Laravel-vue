<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\TipoDocumentoBaja;
use App\Models\DependenciaDocumento;

class PersonalBaja extends Model implements Auditable
{
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
    protected $fillable = [
        'dni',
        'regimen_laboral',
        'cargo',
        'nivel',
        'grupo',
        'dependencia',
        'fecha_ingreso',
        'fecha_cese',
        'fecha_ultimo_dia',
        'motivo_baja_id',
        'fecha_registro',
        'posicion',
        'plaza',
        'nit',
        'observacion',
        'origen_dependencia_id',
        'tipo_documento_id',
        'numero_documento',
        'documento_sustento',
        'path_documeto',
        'fecha_documento_sustento',
        'periodo',
        'status',
        'created_by',
        'updated_by'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function motivoBaja()
    {
        return $this->hasOne('App\Models\MotivoBaja', 'id', 'motivo_baja_id');
    }
    public function dependenciaBaja()
    {
        return $this->hasOne('App\Models\DependenciaDocumento', 'id', 'origen_dependencia_id');
    }
    public function tipoDocumentoBaja()
    {
        return $this->hasOne('App\Models\TipoDocumentoBaja', 'id', 'tipo_documento_id');
    }

    public function persona()
    {
        return $this->hasOne('App\Models\Persona', 'dni', 'dni');
    }

    public function user()
    {
        //return $this->belongsTo(user::class);
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    public function userUpdate()
    {
        //return $this->belongsTo(user::class);
        return $this->hasOne('App\User', 'id', 'updated_by');
    }


    // functions

    public function getGetFileAttribute()
    {
        if ($this->path_documento)
            return url("storage/$this->path_documento");
    }

    /**
     * function to contact data to set documento sustento
     * tipo-#-origenDep-fecha
     */
    public function DocumentoSustento($request)
    {
        $fecha_doc = '';
        $numero_doc = '';
        if ($request->tipo_documento_id)
            $tipo_documento = strtoupper(TipoDocumentoBaja::find($request->tipo_documento_id)->tipo_documento);
        if ($request->origen_dependencia_id)
            $origen_doc = '-' . strtoupper(DependenciaDocumento::find($request->origen_dependencia_id)->siglas);
        if ($request->fecha_documento_sustento)
            $fecha_doc = '-' . date('d-m-Y', strtotime($request->fecha_documento_sustento));
        if ($request->numero_documento)
            $numero_doc = '-' . $request->numero_documento;

        return $documento_sustento = $tipo_documento . $numero_doc . $origen_doc . $fecha_doc;
    }

    /**
     * Cancel a baja and restart data for persona and user.
     */
    public function AnularBaja()
    {
        $this->persona->deshacerBaja();
        $user = $this->persona->user;
        if ($user)
            $user->changeStatus(true);
    }

    //Query Scope

    public function scopeSupestructura($query, $user_id)
    {
        if ($user_id)
            return $query->join('personas', 'personas.dni', '=', 'personal_bajas.dni')
                ->whereIn(
                    'personas.sub_estructura',
                    \App\Models\User::find($user_id)
                        ->supestructuras()
                        ->select('id')
                );
    }
    public function scopeMotivoBaja($query, $motivo_id)
    {
        if ($motivo_id)
            return $query->join('motivo_bajas', 'motivo_bajas.id', '=', 'personal_bajas.motivo_baja_id');
    }
    public function scopeName($query, $name)
    {
        if ($name)
            return $query->where('personas.nombres', 'LIKE', "%$name%")
                ->orwhere('personas.apellido_paterno', 'LIKE', "%$name%")
                ->orwhere('personas.apellido_materno', 'LIKE', "%$name%");
    }

    public function scopeDni($query, $dni)
    {
        if ($dni)
            return $query->orwhere('personal_bajas.dni', 'LIKE', "%$dni%");
    }

    public function scopeCerrado($query, $close)
    {

        if ($close === 0)
            return $query->where('personal_bajas.status', 0);
        if ($close)
            return $query->where('personal_bajas.status', '>', 0);
        if (!$close)
            return $query->where('personal_bajas.status', 0);
    }
}
