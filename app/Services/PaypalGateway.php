<?php
namespace App\Services;
use App\Services\PaymentGatewayService;

class PaypalGateway implements PaymentGatewayInterface
{
    protected $method= 'paypal';
    public function processPayment($order)
    {
        return $this->method;

        // Simulate paypal payment logic
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
