<?php

namespace App\Services\Traits;

use App\Services\Filters\Filters;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{

    /**
     * @param Builder $query
     * @param Filters $filters
     * @param array $excepts
     * @return  mixed
     */
    public function scopeFilter(Builder $query, Filters $filters, array $excepts = [])
    {
        return $filters->prepareFilters($excepts)->apply($query);
    }
}
