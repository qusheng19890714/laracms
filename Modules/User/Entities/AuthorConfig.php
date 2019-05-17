<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class AuthorConfig extends Model
{
    protected $table = 'authorizations_config';
    protected $fillable = ['name', 'status'];
}
