<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorioReclamo extends Model
{
    use HasFactory;
    protected $table = 'directorio_reclamos';
    protected $fillable = [
        'user_id',
        'area',
        'email',
        'telefono',
        'active',
        'show_ayuda',
        'send_emails',
    ];
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
