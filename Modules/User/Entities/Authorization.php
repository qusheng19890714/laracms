<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class Authorization extends Model
{

    protected $table = 'authorizations';
    protected $fillable = ['open_id', 'union_id', 'type'];


    public function user()
    {
        return $this->hasOne(User::class);
    }
}
