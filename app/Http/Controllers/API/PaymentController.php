<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Http\Responses\ResponsesInterface;
use App\Models\Order;
use App\Services\Payment\OmnipayService;
use App\Services\Payment\PaymentService;
use App\Services\Repository\Contracts\OrderRepositoryInterface;
use App\Services\Repository\Contracts\PaymentRepositoryInterface;

class PaymentController extends Controller
{
    protected $payment;

    protected $responder;

    protected $orderRepo;

    protected $paymentRepo;


    public function __construct(PaymentService $payment,
    ResponsesInterface $responder,
    PaymentRepositoryInterface $paymentRepo,
    OrderRepositoryInterface $orderRepo
    )
    {
        $this->payment = $payment;
        $this->middleware('auth:api')->only('purchase');
        $this->responder = $responder;
        $this->paymentRepo = $paymentRepo;
        $this->orderRepo = $orderRepo;
    }

    public function purchase(Order $order, PurchaseRequest $request)
    {
        try {
            $response = $this->payment->purchase($order, $request->currency, $this->paymentRepo);
        } catch (\Throwable $th) {
            throw $th;
        }
        return $this->responder->respondCreated('You Have Create New Payment and The next step confirm payment!', ['redirect_url' => $response->getRedirectUrl()]);
    }

    public function success(Request $request)
    {
        try {
            $response = $this->payment->completePurchase($request->all(), $this->paymentRepo);
        } catch (\Throwable $th) {
            return $this->responder->respondWithError($th->getMessage());
        }

        return $this->responder->respond(['status' => 'success',  'data' => $response]);
    }

    public function cancel(Request $request)
    {
        try {
            $token = $request->input('token');

            if (!$token) {
                throw new \Exception('PayPal token not provided');
            }
            $payment = $this->paymentRepo->findByToken($token);


            if (!$payment) {
                throw new \Exception('Payment not found for this token');
            }
            $result = $this->payment->handleCancellation($payment->order_id,  $this->paymentRepo, $this->orderRepo);
        } catch (\Throwable $th) {
            return $this->responder->respondWithError($th->getMessage());
        }

        return $this->responder->respond($result);
    }
}
