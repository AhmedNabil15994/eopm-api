<?php
namespace App\Services;
use App\Services\PaymentGatewayService;

class KnetGateway implements PaymentGatewayInterface
{
    public function processPayment($order, $method='knet')
    {
        return $method;

        // Simulate knet payment logic
//        $payment = Payment::create([
//            'order_id' => $order->id,
//            'payment_method' => 'knet',
//            'status' => 'successful',
//            'amount' => $data['amount'],
//            'payment_id' => 'cc_' . uniqid(),
//        ]);
//
//        return $payment;
    }

    public function createTransaction($order, $request,$method,$result='pending'){

    }
}
