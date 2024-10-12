<?php
namespace App\Services\Repository\Contracts;

use App\Models\User;
use App\Services\Repository\BaseContract;

interface UserRepositoryInterface extends BaseContract{
    public function create(array $data): User;
}
