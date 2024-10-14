<?php

namespace App\Services\Filters\Elements;

use App\Services\Filters\Filters;
use Illuminate\Contracts\Database\Eloquent\Builder;

class OrderFilters extends Filters
{

    /**
     * filter by product_name
     * @param mixed $value
     *
     * @return Builder
     */
    public function product_name($value): Builder
    {
        return $this->builder->where('product_name', 'LIKE', '%' . $value . '%');
    }


    /**
     * filter by status
     * @param mixed $value
     *
     * @return Builder
     */
    public function status($value): Builder
    {
        return $this->builder->where('status', $value);
    }
}
