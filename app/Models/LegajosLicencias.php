<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LegajosLicencias extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'legajos_licencias';
    protected $fillable = [
        'legajos_informe_cese_id',
        'parameter_id',
        'fecha_inicio',
        'fecha_termino',
        'status'
    ];
}
