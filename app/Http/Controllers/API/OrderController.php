<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Responses\ResponsesInterface;
use App\Models\Order;
use App\Services\Repository\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @var ResponsesInterface
     */
    protected $responder;

    /**
     * @var [type]
     */
    protected $orderRepo;
    /**
     * @param ResponsesInterface $responder
     */
    public function __construct(ResponsesInterface $responder, OrderRepositoryInterface $orderRepo)
    {
        $this->responder = $responder;
        $this->orderRepo = $orderRepo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allOrders = $this->orderRepo->list($request->all());
        return $this->responder->respond(['orders' => $allOrders]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $newOrder = $this->orderRepo->create($request->validated());
        } catch (\Throwable $th) {
            throw $th;
        }
        return $this->responder->respondCreated('Order Created Successfully!', $newOrder);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(UpdateOrderRequest $request, Order $order)
    {
        try {
            $this->orderRepo->updateStatus($order->id, $request->status);
        } catch (\Throwable $th) {
            throw $th;
        }
        return $this->responder->respond(['message' => 'Order Status Updated Successfully!']);
    }
}
