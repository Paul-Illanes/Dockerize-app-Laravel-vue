<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PersonalGrupo extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'personal_grupos';
    protected $fillable = [
        'personal_area_id',
        'dni',
        'area_servicio',
    ];

    public function vacaciones()
    {
        return $this->hasMany('App\Models\VacacionesProgramacion', 'dni', 'dni');
    }
}
