<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivoBaja extends Model
{
    static $rules = [
        'baja' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['baja', 'active'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function personalBajas()
    {
        return $this->hasMany('App\Models\PersonalBaja', 'motivo_baja_id', 'id');
    }
}
