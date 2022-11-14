<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\NativeIn;
use App\Models\AlsoSpeaking;
use App\Models\Learning;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function NativeIn() {
        return $this->hasMany(NativeIn::class);
    }
    public function AlsoSpeaking() {
        return $this->hasMany(AlsoSpeaking::class);
    }
    public function Learning() {
        return $this->hasMany(Learning::class);
    }
}
