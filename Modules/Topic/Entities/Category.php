<?php

namespace Modules\Topic\Entities;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'topic_category';

    protected $fillable = [];

    use ModelTree, AdminBuilder;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTitleColumn('name');
        $this->setOrderColumn('sort');
        $this->setParentColumn('pid');

    }


    public function topic()
    {
        return $this->hasMany(Topic::class);
    }
}
