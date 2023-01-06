<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class actividad_area extends Model
{
    use HasFactory;
    protected $table = 'actividad_area';
    protected $fillable = ['id', 'area_id', 'actividad_id'];
}
