<?php

namespace App\Services\Payment;

use Omnipay\Omnipay;
use App\Models\Order;
use App\Services\Repository\Contracts\OrderRepositoryInterface;
use App\Services\Repository\Contracts\PaymentRepositoryInterface;

class PaymentService
{
    protected $gateway;


    public function __construct()
    {
        $this->gateway = Omnipay::create(config('services.paypal.gateway'));
        $this->gateway->setClientId(config('services.paypal.client_id'));
        $this->gateway->setSecret(config('services.paypal.secret'));
        $this->gateway->setTestMode(config('services.paypal.sadbox'));
    }

    /**
     * @param Order $order
     * @param string $currency
     *
     * @return Omnipay
     */
    public function purchase(Order $order, string $currency, $paymentRepo)
    {
        $parameters = [
            'amount' => $order->price,
            'currency' => $currency,
            'returnUrl' => route('payment.success'),
            'cancelUrl' => route('payment.cancel'),
            'custom' => $order->id,

        ];

        $response =  $this->gateway->purchase($parameters)->send();
        $data = $response->getData();
        $approvalUrl = $this->getApprovalUrl($data['links']);
        $token = $this->extractTokenFromUrl($approvalUrl);

        if ($response->isRedirect()) {
            $paymentRepo->create([
                'order_id' => $order->id,
                'transaction_id' => $data['id'],
                'amount' => $order->price,
                'paypal_token' => $token,
            ]);
        }

        return $response;
    }


    /**
     * completePurchase
     * @param array $parameters
     * @param OrderRepository $orderRepo
     *
     * @return [type]
     */
    public function completePurchase(array $parameters, PaymentRepositoryInterface $paymentRepo)
    {
        $parameters = [
            'transactionReference' => $parameters['paymentId'],
            'payerId' => $parameters['PayerID'],
        ];
        $response =  $this->gateway->completePurchase($parameters)->send();
        if ($response->isSuccessful()) {
            $data = $response->getData();

            //update the statis to Success in the payments table
            $paymentRepo->updateStatus($data['id'], 'success');
            // Update the status to paid in order table
            $paymentRepo->getPaymentByTrxId($data['id'])->order->update([
                'status' => 'paid'
            ]);

            return $data;
        }
        return $response;
    }

    /**
     * Handle payment cancellation
     * @param string $orderId
     * @param PaymentRepositoryInterface $paymentRepo
     * @param OrderRepositoryInterface $orderRepo
     *
     * @return array
     */
    public function handleCancellation(int $orderId, PaymentRepositoryInterface $paymentRepo, OrderRepositoryInterface $orderRepo)
    {
        $order = $orderRepo->findById($orderId);

        if (!$order) {
            throw new \Exception('Order not found');
        }

        // Find the associated payment
        $payment = $paymentRepo->findByOrderId($orderId);

        if (!$payment) {
            throw new \Exception('Payment not found for this order');
        }

        // Update payment status
        $paymentRepo->updateStatus($payment->transaction_id, 'failed');

        // Update order status
        $orderRepo->updateStatus($order->id, 'canceled');

        return [
            'status' => 'cancelled',
            'message' => 'Payment has been cancelled successfully',
            'order_id' => $order->id,
            'payment_id' => $payment->id,
        ];
    }


    private function getApprovalUrl($links)
    {
        foreach ($links as $link) {
            if ($link['rel'] === 'approval_url') {
                return $link['href'];
            }
        }
        return null;
    }

    private function extractTokenFromUrl($url)
    {
        $parsedUrl = parse_url($url);
        parse_str($parsedUrl['query'], $queryParams);
        return $queryParams['token'] ?? null;
    }
}
