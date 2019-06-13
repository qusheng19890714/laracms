<?php

namespace Modules\Topic\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class Topic extends Model
{
    protected $table = 'topics';

    protected $fillable = ['user_id', 'category_id', 'title', 'excerpt', 'body', 'order', 'tags', 'status'];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

}
