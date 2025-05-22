<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'npp',
        'npp_supervisor'
    ];

    // Get the identifier for the JWT.
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Get custom claims for the JWT.
    public function getJWTCustomClaims()
    {
        return [];
    }
}
