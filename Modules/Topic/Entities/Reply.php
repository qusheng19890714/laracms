<?php

namespace Modules\Topic\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class Reply extends Model
{
    protected $table = 'reply';

    protected $fillable = ['content'];


    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
