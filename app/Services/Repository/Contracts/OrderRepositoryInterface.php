<?php

namespace App\Services\Repository\Contracts;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function create(array $data): Order;
    public function list(): LengthAwarePaginator;
    public function update(int $id, array $data): bool;
    public function createUsingFactory(?array $data ,  $count = null);
    public function updateStatus(int $id , string $status): Order;
}
