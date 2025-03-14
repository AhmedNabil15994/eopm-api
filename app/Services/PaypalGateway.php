<?php
namespace App\Services;
use App\Services\PaymentGatewayService;

class PaypalGateway implements PaymentGatewayInterface
{
    public function processPayment($order, $method='paypal')
    {
        return $method;

        // Simulate paypal payment logic
//        $payment = Payment::create([
//            'order_id' => $order->id,
//            'payment_method' => 'paypal',
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
