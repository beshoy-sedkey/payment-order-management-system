<?php
namespace App\Services\Webhook;


use App\Models\Payment;
use App\Services\Repository\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Facades\Log;

class PayPalWebhookService
{
    protected $paymentRepo;

    public function __construct(PaymentRepositoryInterface $paymentRepo)
    {
        $this->paymentRepo = $paymentRepo;

    }
    public function processWebhook(array $payload)
    {
        $eventType = $payload['event_type'] ?? '';
        $resourceId = $payload['resource']['id'] ?? '';

        switch ($eventType) {
            case 'PAYMENT.SALE.COMPLETED':
                $resourceId = $payload['resource']['parent_payment'];
                $this->handlePaymentCompleted($resourceId);
                break;
            case 'PAYMENT.SALE.DENIED':
                $this->handlePaymentDenied($resourceId);
                info($resourceId);
                break;
            case 'PAYMENT.SALE.PENDING':
                $this->handlePaymentPending($resourceId);
                break;
            case 'PAYMENT.AUTHORIZATION.VOIDED':
                $resourceId = $payload['resource']['amount']['parent_payment'];
                info($resourceId);
                $this->handlePaymentCanceled($resourceId);
                break;
            default:
                Log::info('Unhandled PayPal webhook event type: ' . $eventType);
        }
    }

    private function handlePaymentCompleted(string $saleId)
    {

        $payment = $this->paymentRepo->getPaymentByTrxId($saleId);
        if ($payment) {
            $payment->update(['status' => 'success']);
            $payment->order->update(['status' => 'paid']);
            Log::info("Payment completed for sale ID: {$saleId}");
        } else {
            Log::warning("Payment not found for sale ID: {$saleId}");
        }
    }

    private function handlePaymentDenied(string $saleId)
    {
        $payment = $this->paymentRepo->getPaymentByTrxId($saleId);
        if ($payment) {
            $payment->update(['status' => 'failed']);
            $payment->order->update(['status' => 'Canceled']);
            Log::info("Payment denied for sale ID: {$saleId}");
        } else {
            Log::warning("Payment not found for sale ID: {$saleId}");
        }
    }

    private function handlePaymentPending(string $saleId)
    {
        $payment = $this->paymentRepo->getPaymentByTrxId($saleId);
        if ($payment) {
            $payment->update(['status' => Null]);
            $payment->order->update(['status' => 'pending']);
            Log::info("Payment pending for sale ID: {$saleId}");
        } else {
            Log::warning("Payment not found for sale ID: {$saleId}");
        }
    }

    private function handlePaymentCanceled(string $authorizationId)
    {
        $payment = $this->paymentRepo->getPaymentByTrxId($authorizationId);
        if ($payment) {
            $payment->update(['status' => 'failed']);
            $payment->order->update(['status' => 'canceled']);
            Log::info("Payment canceled for authorization ID: {$authorizationId}");
        } else {
            Log::warning("Payment not found for authorization ID: {$authorizationId}");
        }
    }
}
