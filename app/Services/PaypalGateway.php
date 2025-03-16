<?php
namespace App\Services;
use App\Services\PaymentGatewayService;

class PaypalGateway implements PaymentGatewayInterface
{
    protected $method= 'paypal';
    public function processPayment($order)
    {
        // Simulate paypal payment logic
        return $this->method;
    }

    public function createTransaction($order, $request,$result='pending'){

    }
}
