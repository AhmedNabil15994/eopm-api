<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\User\Entities\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartFeatureTest extends TestCase
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

    public function test_guest_can_fetch_cart()
    {
        $response = $this->getJson('/api/cart?user_token=1');
        $response->assertStatus(200);
    }

    public function test_auth_user_can_fetch_cart()
    {
        $response = $this->withHeaders($this->headers)->getJson('/api/cart');
        $response->assertStatus(200);
    }

    public function test_user_can_add_product_to_cart()
    {
        $response = $this->withHeaders($this->headers)->postJson('/api/cart/add/1', [
            'qty' => 3
        ]);
        $response->assertStatus(200);
    }

    public function test_user_can_update_cart_quantity()
    {
        $this->withHeaders($this->headers)->postJson('/api/cart/add/1', ['qty' => 3]);
        $response = $this->withHeaders($this->headers)->postJson('/api/cart/add/1', ['qty' => 5]);
        $response->assertStatus(200);
    }

    public function test_user_can_remove_product_from_cart()
    {
        $this->withHeaders($this->headers)->postJson('/api/cart/add/1', ['qty' => 3]);
        $response = $this->withHeaders($this->headers)->deleteJson('/api/cart/remove/1');
        $response->assertStatus(200);
    }

    public function test_user_can_clear_cart()
    {
        $this->withHeaders($this->headers)->postJson('/api/cart/add/1', ['qty' => 3]);
        $response = $this->withHeaders($this->headers)->postJson('/api/cart/clear');
        $response->assertStatus(200);
    }
}
