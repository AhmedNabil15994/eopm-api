<?php
namespace App\Services;
use App\Services\PaymentGatewayService;

class KnetGateway implements PaymentGatewayInterface
{
    protected $method = 'knet';
    public function processPayment($order)
    {
        return $this->method;

        // Simulate knet payment logic
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
