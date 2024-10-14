<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * Filters constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Applies filters to the Query Builder
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters as $filter => $value) {
            if (method_exists($this, $filter) && ($value || $value === 0 || $value === "0"))
                call_user_func([$this, $filter], $value);
        }

        return $this->builder;
    }

    /**
     * Gets the filters from the request inputs.
     *
     * @return  Filters
     */
    public function prepareFilters($excepts = [])
    {
        $this->filters = $this->request->request->all() + $this->request->all();

        if (!empty($excepts)) {
            $this->excepts($excepts);
        }

        return $this;
    }

    /**
     * @param $value
     * @return Builder
     */
    public function date_from($value): Builder
    {
        return $this->builder->whereDate('created_at', '>=', $value);
    }

    /**
     * @param $value
     * @return Builder
     */
    public function date_to($value): Builder
    {
        return $this->builder->whereDate('created_at', '<=', $value);
    }

    /**
     * @param $value
     * @return Builder
     */
    public function sort($value): Builder
    {
        if (!in_array($value, ['asc', 'desc']))
            $value = 'desc';

        return $this->builder->orderBy('id', $value);
    }

    /**
     * @param array $excepts
     * @return void
     */
    private function excepts(array $excepts)
    {
        $this->filters = collect($this->filters)->filter(function ($value, $key) use ($excepts) {
            return !in_array($key, $excepts);
        })->toArray();
    }
}
