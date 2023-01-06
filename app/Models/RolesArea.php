<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesArea extends Model
{
    use HasFactory;
    protected $table = 'roles_area';
    protected $fillable = ['area_id', 'anio', 'mes', 'fecha_cierre'];
}
