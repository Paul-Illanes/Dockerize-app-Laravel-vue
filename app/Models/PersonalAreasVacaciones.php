<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PersonalAreasVacaciones extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['personal_area_id', 'periodo_vacaciones', 'status'];
    public function PersonalArea()
    {
        return $this->hasOne('App\Models\PersonalArea', 'id', 'personal_area_id');
    }
}
