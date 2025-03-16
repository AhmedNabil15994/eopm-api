<?php
namespace App\Services;

interface PaymentGatewayInterface
{
    public function processPayment($order);

    public function createTransaction($order, $request,$result='pending');
}
