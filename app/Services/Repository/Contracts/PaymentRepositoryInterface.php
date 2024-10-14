<?php

namespace App\Services\Repository\Contracts;

use App\Models\Payment;
use App\Services\Repository\BaseContract;

interface PaymentRepositoryInterface extends BaseContract
{
    public function create(array $data): Payment;
    public function getPaymentByTrxId(string $trxId) : Payment;
    public function updateStatus(string $trxId , string $status) : bool;
    public function findByToken(string $token): Payment;
    public function findByOrderId(int $orderId): Payment;
}
