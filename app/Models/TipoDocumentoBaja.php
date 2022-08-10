<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumentoBaja extends Model
{
    use HasFactory;
    static $rules = [];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['codigo', 'tipo_documento', 'is_documento', 'active'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function personalBajas()
    {
        return $this->hasMany('App\Models\PersonalBaja', 'tipo_documento_id', 'id');
    }
}
