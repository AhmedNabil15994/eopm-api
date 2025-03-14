<?php
namespace App\Services;
use App\Services\PaymentGatewayService;

class WalletGateway implements PaymentGatewayInterface
{
    public function processPayment($order, $method = 'wallet')
    {
        return $method;
        // Simulate wallet payment logic
//        $payment = Payment::create([
//            'order_id' => $order->id,
//            'payment_method' => 'wallet',
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
