<?php
namespace App\Services;

interface PaymentGatewayInterface
{
    public function processPayment($order, $method);

    public function createTransaction($order, $request,$method,$result='pending');
}
