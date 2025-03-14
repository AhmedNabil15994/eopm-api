<?php
namespace App\Services;
use App\Services\PaymentGatewayService;

class CreditCardGateway implements PaymentGatewayInterface
{
    public function processPayment($order, $method = 'credit_card')
    {
        return $method;

        // Simulate credit card payment logic
//        $payment = Payment::create([
//            'order_id' => $order->id,
//            'payment_method' => 'credit_card',
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
