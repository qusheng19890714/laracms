<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Topic\Entities\Topic;


class User extends Model
{
    protected $fillable = ['avatar',  'name', 'status'];

    protected $table = 'users';


    public function authorization()
    {
        return $this->hasOne(Authorization::class);
    }


    public function topic()
    {
        return $this->hasMany(Topic::class);
    }
}
