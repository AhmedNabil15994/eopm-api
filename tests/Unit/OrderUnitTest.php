<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Cart\Entities\DatabaseStorageModel;
use Modules\Cart\Repositories\CartRepository;
use Tests\TestCase;
use Modules\Order\Repositories\Api\OrderRepository;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderUnitTest extends TestCase
{
    use DatabaseTransactions;

    protected $orderRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderRepository = new OrderRepository(
            new Order(),
            new \Modules\Order\Entities\OrderStatus(),
            new \Modules\User\Entities\User(),
            new \Modules\Cart\Entities\DatabaseStorageModel(),
            new Product()
        );

        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
        $this->headers = [
            'Authorization' => 'Bearer ' . JWTAuth::fromUser($this->user),
            'Accept' => 'application/json'
        ];

        $cartModel = new DatabaseStorageModel();
        $this->cartRepository = new CartRepository($cartModel);
        $this->cartRepository->identifier = $this->user->id;
    }

    public function test_can_fetch_all_orders_for_user()
    {
        $user = $this->user;
        Order::factory()->create(['user_id' => $user->id]);

        $orders = $this->orderRepository->getAll((object) ['user_id' => $user->id]);

        $this->assertNotEmpty($orders);
    }

    public function test_can_find_order_by_id()
    {
        $order = Order::factory()->create();

        $foundOrder = $this->orderRepository->findById($order->id);
        $this->assertEquals($order->id, $foundOrder->id);
    }

    public function test_can_create_order()
    {

        $product = Product::factory()->create();
        $result = $this->cartRepository->addToCart(['id' => $product->id, 'name' => $product->name, 'price' => $product->price], 'product', 2);

        $orderData = [
            'user_token' => $this->user->id,
            'subtotal' => '100.00',
            'discount' => '0.00',
            'tax' => '5.00',
            'total' => '105.00',
            'order_status_id' => 1
        ];

        $order = $this->orderRepository->create($orderData);

        $this->assertNotNull($order);
    }

    public function test_can_delete_order()
    {
        $order = Order::factory()->create();

        $this->orderRepository->deleteOrder($order);

        $this->assertNull(Order::find($order->id));
    }

    public function test_can_cancel_order()
    {
        $order = Order::factory()->create();

        $result = $this->orderRepository->deleteOrder($order, false);

        $this->assertTrue($result);
    }

    public function test_can_update_order_status()
    {
        $order = Order::factory()->create(['order_status_id' => 1]);

        $this->orderRepository->update($order->id, 2);

        $updatedOrder = Order::find($order->id);

        $this->assertEquals(2, $updatedOrder->order_status_id);
    }
}
