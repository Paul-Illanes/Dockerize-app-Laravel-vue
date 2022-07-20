<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CmsParameter;

class IncorporacionesValidate extends Model
{
    protected $table = 'incorporacion_validacion';
    protected $fillable = [
        'incorporacion_id',
        'parameter_id',
        'status'
    ];
    public function parameter()
    {
        return $this->hasMany('App\Models\CmsParameter', 'id', 'parameters_id');
    }
    public function incorporation()
    {
        return $this->belongsTo('App\Models\Incorporacione', 'incorporacion_id', 'id');
    }
}
