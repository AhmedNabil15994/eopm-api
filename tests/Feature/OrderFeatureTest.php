<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Modules\User\Entities\User;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderFeatureTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->headers = [
            'Authorization' => 'Bearer ' . JWTAuth::fromUser($this->user),
            'Accept' => 'application/json'
        ];
    }

    public function test_user_can_list_orders()
    {
        Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->headers)->getJson('/api/orders');
        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta', 'links']);
    }

    public function test_user_can_view_order_details()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->headers)->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_user_can_checkout_order()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id, 'order_status_id' => 5]);

        $response = $this->withHeaders($this->headers)->postJson("/api/orders/{$order->id}/checkout", [
            'payment_method' => 'cash'
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_create_order()
    {
        $product = Product::factory()->create();

        $addToCartResponse = $this->withHeaders($this->headers)->postJson('/api/cart/add/'.$product->id, [
            'qty' => 3
        ]);

        $response = $this->withHeaders($this->headers)->postJson('/api/orders/create', [
            'items' => [['product_id' => $product->id, 'quantity' => 2]]
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Your order is being reviewed, please wait.']);
    }


    public function test_user_can_accept_order()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id, 'order_status_id' => 1]);

        $response = $this->withHeaders($this->headers)->postJson("/api/orders/{$order->id}/accept");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Pending']);
    }

    public function test_user_can_delete_order()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->headers)->deleteJson("/api/orders/{$order->id}/delete");

        $response->assertStatus(400);
    }

    public function test_user_can_cancel_order()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id, 'order_status_id' => 1]);

        $response = $this->withHeaders($this->headers)->postJson("/api/orders/{$order->id}/cancel");

        $response->assertStatus(200);
    }

    public function test_user_cannot_access_another_users_order()
    {
        $anotherUser = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $anotherUser->id]);

        $response = $this->withHeaders($this->headers)->getJson("/api/orders/{$order->id}");

        $response->assertStatus(400);
    }

    public function test_user_cannot_checkout_with_invalid_payment_method()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id, 'order_status_id' => 5]);

        $response = $this->withHeaders($this->headers)->postJson("/api/orders/{$order->id}/checkout", [
            'payment_method' => 'invalid_method'
        ]);

        $response->assertStatus(400);
    }
}
