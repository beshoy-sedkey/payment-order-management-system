<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Repository\Contracts\OrderRepositoryInterface as ContractsOrderRepositoryInterface;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $orderRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderRepo = app(ContractsOrderRepositoryInterface::class);
    }

    public function test_unauthenticated_user_cannot_create_order()
    {
        $orderData = $this->orderRepo->createUsingFactory();

        $response = $this->postJson('/api/orders', collect($orderData)->toArray());

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_order()
    {
        $user = User::factory()->create();
        $orderData = collect($this->orderRepo->createUsingFactory())->toArray();

        unset($orderData['id'], $orderData['created_at'], $orderData['updated_at']);

        $response = $this->actingAs($user)
            ->postJson('/api/orders', $orderData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'product_name',
                    'quantity',
                    'price',
                    'created_at',
                    'updated_at'
                ],
                'message',
                'status_code'
            ])
            ->assertJsonFragment([
                'product_name' => $orderData['product_name'],
                'quantity' => $orderData['quantity'],
                'price' => $orderData['price'],
                'user_id' => $user->id
            ]);

        $this->assertDatabaseHas('orders', [
            'product_name' => $orderData['product_name'],
            'quantity' => $orderData['quantity'],
            'price' => $orderData['price'],
            'user_id' => $user->id
        ]);
    }

    public function test_unauthenticated_user_cannot_update_order_status()
    {
        $order = $this->orderRepo->createUsingFactory(['status' => 'pending']);

        $response = $this->putJson("/api/orders/{$order->id}/status", [
            'status' => 'paid'
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_update_order_status()
    {
        $user = User::factory()->create();
        $order = $this->orderRepo->createUsingFactory(['status' => 'pending']);

        $response = $this->actingAs($user)
            ->putJson("/api/orders/{$order->id}/status", [
                'status' => 'paid'
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => "Order Status Updated Successfully!"]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid'
        ]);
    }
}
