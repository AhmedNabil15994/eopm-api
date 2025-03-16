<?php
namespace App\Services;
use App\Services\PaymentGatewayService;

class KnetGateway implements PaymentGatewayInterface
{
    protected $method = 'knet';
    public function processPayment($order)
    {
        // Simulate knet payment logic
        return $this->method;
    }

    public function createTransaction($order, $request,$result='pending'){

    }
}
