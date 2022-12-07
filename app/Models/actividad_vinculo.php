<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class actividad_vinculo extends Model
{
    use HasFactory;
    protected $table = 'actividad_vinculo';
    protected $fillable = ['vinculo_laboral_id', 'documento_id', 'referencia', 'status'];
}
