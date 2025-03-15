<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Transaction\Entities\Transaction;
use Modules\Order\Entities\Order;
use Modules\User\Entities\User;


class PaymentFeatureTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;
    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->order = Order::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user, 'api');
    }

    public function test_can_list_transactions()
    {
        Transaction::factory()->count(3)->create(['transaction_id' => $this->order->id]);

        $response = $this->getJson('/api/payments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'method', 'payment_id', 'tran_id', 'result', 'order_id', 'user_id'],
                ]
            ]);
    }

    public function test_can_get_transaction_details()
    {
        $transaction = Transaction::factory()->create(['transaction_id' => $this->order->id]);

        $response = $this->getJson('/api/payments/'. $transaction->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $transaction->id,
                    'order_id' => $this->order->id,
                ]
            ]);
    }

    public function test_cannot_get_nonexistent_transaction()
    {
        $response = $this->getJson('/api/payments/0');

        $response->assertStatus(404)
            ->assertJson([
                'message' => "Invalid data.",
            ]);
    }
}
