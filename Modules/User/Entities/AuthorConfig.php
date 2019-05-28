<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class AuthorConfig extends Model
{
    protected $table = 'third_party';
    protected $fillable = ['name', 'res_name', 'data', 'status'];

    protected $casts = ['data'=>'json'];
}
