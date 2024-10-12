<?php
namespace App\Services\Repository\Elequonet;

use App\Models\User;
use App\Services\Repository\BaseRepository;
use App\Services\Repository\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface{

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }
}
