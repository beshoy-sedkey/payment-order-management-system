<?php

namespace App\Services\Repository\Elequonet;

use App\Models\User;
use App\Models\Order;
use App\Services\Filters\Filters;
use App\Providers\APIServiceProvider;
use App\Services\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Filters\Elements\OrderFilters;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Services\Repository\Contracts\UserRepositoryInterface;
use App\Services\Repository\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class OrderRepository implements OrderRepositoryInterface
{

    /**
     * @var Order
     */
    protected $model;
    /**
     * @var OrderFilters
     */
    protected $filters;

    /**
     * @var [type]
     */
    protected $user;
    /**
     * @param Order $model
     */
    public function __construct(Order $model, OrderFilters $filters)
    {
        $this->model = $model;
        $this->filters  = $filters;
        $this->user  = Auth::user();
    }

    /**
     * create
     * @param array $data
     *
     * @return Order
     */
    public function create(array $data): Order
    {
        $data = array_merge(['user_id' => auth()->id()], $data);
        return $this->model->create($data);
    }
    /**
     * list
     * @param array|null $filters
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        return $this->user->orders()->filter($this->filters)->paginate(APIServiceProvider::ItemsPerPage);
    }
    /**
     * update
     * @param int $id
     * @param array $data
     *
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->user->orders()->where('id', $id)->update($data);
    }

    public function createUsingFactory(?array  $attr = [] ,  $count = null)
    {
        return $this->model->factory($count)->create($attr);
    }

    public function updateStatus(int $id, string $status): Order
    {
        $order = $this->model->findOrFail($id);
        $order->status = $status;
        $order->save();

        return $order;
    }
}
