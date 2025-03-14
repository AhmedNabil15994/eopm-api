<?php
namespace App\Services;
use App\Services\PaymentGatewayService;
use Psy\Util\Str;

class CashGateway implements PaymentGatewayInterface
{
    public function processPayment($order, $method='cash')
    {
        // Simulate cash payment logic
        return $this->createTransaction($order,request(),$method,'success');
    }

    public function createTransaction($order, $request,$method,$result='pending'){
        $result = 'success';

        if($result == 'success'){
            $order->update(['order_status_id' => 2]); // change to success status
        }else{
            $order->update(['order_status_id' => 3]); // change to failed status
        }

        $order->transactions()->create(
            [
                'transaction_id' => $order->id,
                'method'    => $method,
                'auth' => $order->user_id,
                'tran_id' => rand(1,99999999).time(),
                'result' => $result,
                'post_date' => now(),
                'ref' => '',
                'track_id' => rand(1,99999999).time(),
                'payment_id' => \Illuminate\Support\Str::uuid(),
            ]
        );
    }
}
