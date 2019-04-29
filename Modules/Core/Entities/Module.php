<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

class Module extends Model
{
    //protected $primaryKey = 'name';

    public function paginate()
    {
        $per_page = 10;

        $modules = static::hydrate(module());

        $paginator = new LengthAwarePaginator($modules, count($modules), $per_page);

        $paginator->setPath(url()->current());


        return $paginator;
    }

    public static function with($relations)
    {
        return new static;
    }
}
