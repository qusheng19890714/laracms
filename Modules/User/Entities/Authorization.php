<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Authorization extends Authenticatable implements JWTSubject
{

    protected $table = 'authorizations';

    protected $fillable = ['user_id', 'type', 'identifier', 'credential', 'verified', 'ip'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }


}
