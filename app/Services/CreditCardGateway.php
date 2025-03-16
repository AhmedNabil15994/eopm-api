<?php
namespace App\Services;
use App\Services\PaymentGatewayService;

class CreditCardGateway implements PaymentGatewayInterface
{
    protected $method = 'credit_card';

    public function processPayment($order)
    {
        // Simulate credit card payment logic
        return $this->method;
    }

    public function createTransaction($order, $request,$result='pending'){

    }
}
