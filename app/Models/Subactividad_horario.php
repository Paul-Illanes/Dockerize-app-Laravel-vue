<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subactividad_horario extends Model
{
    use HasFactory;
    protected $table = 'subactividad_horario';
    protected $fillable = ['actividad_id', 'horario_id'];
}
