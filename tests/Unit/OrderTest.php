<?php

namespace Tests\Unit;


use App\Models\Order;
use App\Models\User;
use App\Services\Repository\Contracts\OrderRepositoryInterface;
use App\Services\Repository\Elequonet\OrderRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $orderRepo;

    protected $order;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderRepo = app(OrderRepositoryInterface::class);
    }

    public function test_can_create_order()
    {
        $order = $this->orderRepo->createUsingFactory();

        $this->assertInstanceOf(Order::class, $order);

        $this->assertDatabaseHas('orders', ['id' => $order->id]);

        $this->assertContains($order->status, ['pending', 'paid', 'canceled']);
        $this->assertIsNumeric($order->quantity);
        $this->assertIsNumeric($order->price);

        $orderData = [
            'product_name' => $order->product_name,
            'quantity' => $order->quantity,
            'price' => $order->price,
            'status' => $order->status,
        ];

        $this->assertEquals($orderData['product_name'], $order->product_name);
        $this->assertEquals($orderData['quantity'], $order->quantity);
        $this->assertEquals($orderData['price'], $order->price);
        $this->assertEquals($orderData['status'], $order->status);
    }

    public function test_can_update_order_status()
    {
        $order = $this->orderRepo->createUsingFactory(['status' => 'pending']);
        $updatedOrder = $this->orderRepo->updateStatus($order->id, 'paid');
        $this->assertEquals('paid', $updatedOrder->status);
        $order->refresh();
        $this->assertEquals('paid', $order->status);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid'
        ]);
    }
}
