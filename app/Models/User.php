<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Pin;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use \OwenIt\Auditing\Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'lastname',
        'mother_lastname',
        'email',
        'username',
        'active',
        'verified',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function pins()
    {
        return $this->hasMany(Pin::class);
    }


    public function profile()
    {
        return $this->hasOne('App\Profile', 'id', 'profile_id');
    }

    public function supestructuras()
    {
        return $this->belongsToMany('App\Models\Supestructura', 'user_has_supestructura', 'user_id', 'supestructura_id');
    }
    public function changeStatus($user, bool $status)
    {
        // $user_id = auth()->user() ? auth()->user()->id : null;
        $this::update([
            'active' => $status,
            'updated_by' => $user,
        ]);
    }
}
