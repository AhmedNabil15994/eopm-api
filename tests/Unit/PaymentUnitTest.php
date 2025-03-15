<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Order\Entities\Order;
use Modules\Transaction\Repositories\Api\TransactionRepository;
use Tests\TestCase;
use Modules\User\Entities\User;
use Modules\Transaction\Entities\Transaction;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentUnitTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->order = Order::factory()->create(['user_id' => $this->user->id]);

        $this->transactionRepository = new TransactionRepository(new Transaction());
        // Authenticate the user
        $this->actingAs($this->user, 'api');

        $this->headers = [
            'Authorization' => 'Bearer ' . JWTAuth::fromUser($this->user),
            'Accept' => 'application/json'
        ];
    }

    public function test_can_get_all_transactions()
    {
        Transaction::factory()->count(3)->create(['transaction_id' => $this->order->id]);

        $transactions = $this->transactionRepository->getAll([]);

        $this->assertNotEmpty($transactions);
        $this->assertCount(3, $transactions);
    }

    public function test_can_get_transaction_by_id()
    {
        $transaction = Transaction::factory()->create(['transaction_id' => $this->order->id]);
        $foundTransaction = $this->transactionRepository->getById($transaction->id);

        $this->assertNotNull($foundTransaction);
        $this->assertEquals($transaction->id, $foundTransaction->id);
    }
}
