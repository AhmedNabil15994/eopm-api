<?php
namespace App\Services;
use App\Services\PaymentGatewayService;

class CreditCardGateway implements PaymentGatewayInterface
{
    protected $method = 'credit_card';

    public function processPayment($order)
    {
        return $this->method;

        // Simulate credit card payment logic
//        $payment = Payment::create([
//            'order_id' => $order->id,
//            'payment_method' => $this->method',
//            'status' => 'successful',
//            'amount' => $data['amount'],
//            'payment_id' => 'cc_' . uniqid(),
//        ]);
//
//        return $payment;
    }

    public function createTransaction($order, $request,$result='pending'){

    }
}
