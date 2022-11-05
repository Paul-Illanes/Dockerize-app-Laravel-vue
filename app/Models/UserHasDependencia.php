<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasDependencia extends Model
{
    use HasFactory;
    protected $table = 'user_has_dependencia';
    protected $fillable = ['user_id', 'dependencia_id', 'created_by', 'updated_by'];
}
