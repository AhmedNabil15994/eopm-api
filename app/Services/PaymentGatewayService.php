<?php
namespace App\Services;

use Modules\Order\Entities\Order;
use App\Services\PaypalGateway;
use App\Services\CreditCardGateway;
use App\Services\CashGateway;
use App\Services\WalletGateway;
use App\Services\KnetGateway;
use App\Services\UpaymentGateway;

class PaymentGatewayService
{
    protected $gateways = [];

    public function __construct()
    {
        $this->gateways = [
            'credit_card' => new CreditCardGateway(),
            'paypal' => new PayPalGateway(),
            'cash' => new CashGateway(),
            'wallet' => new WalletGateway(),
            'knet'  => new KnetGateway(),
            'upayment' => new UpaymentGateway(),
        ];
    }

    public function processPayment(Order $order, $payment_method)
    {
        if (!isset($this->gateways[$payment_method])) {
            throw new \Exception("Payment method not supported");
        }

        $gateway = $this->gateways[$payment_method];
        return $gateway->processPayment($order, $payment_method);
    }

    public function createTransaction(Order $order, $request,$payment_method,$status)
    {
        if (!isset($this->gateways[$payment_method])) {
            throw new \Exception("Payment method not supported");
        }

        $gateway = $this->gateways[$payment_method];
        return $gateway->createTransaction($order,$request,$status);
    }
}
