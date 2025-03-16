<?php
namespace App\Services;
use App\Services\PaymentGatewayService;

class WalletGateway implements PaymentGatewayInterface
{
    protected $method='wallet';
    public function processPayment($order)
    {
        // Simulate wallet payment logic
        return $this->method;
    }

    public function createTransaction($order, $request,$result='pending'){

    }
}
