<?php

namespace App\Services\Repository\Elequonet;

use App\Models\Payment;
use App\Services\Repository\BaseRepository;
use App\Services\Repository\Contracts\PaymentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    /**
     * @param Payment $model
     */
    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Payment
    {
        return $this->model->create($data);
    }

    public function getPaymentByTrxId(string $trxId): Payment
    {
        return $this->model->where('transaction_id', $trxId)->first();
    }

    public function updateStatus(string $trxId, string $status): bool
    {
        return $this->model->where('transaction_id', $trxId)->update([
            'status' => $status
        ]);
    }

    public function findByToken($token): Payment
    {
      return $this->model->where('paypal_token' , $token)->firstOrFail();
    }

    public function findByOrderId($orderId): Payment
    {
        return $this->model->where('order_id'  , $orderId)->firstOrFail();
    }
}
