<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssiSubactividades extends Model
{
    use HasFactory;
    protected $table = 'essi_subactividades';
    protected $fillable = ['cod', 'actividad', 'tipo_prog', 'abreviacion', 'tipo_actividad_id', 'active'];
}
