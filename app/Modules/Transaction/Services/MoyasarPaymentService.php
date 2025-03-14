<?php
namespace Modules\Transaction\Services;

class MoyasarPaymentService
{
    protected $publishKey;
    protected $secretKey;
    protected $paymentMode;
    protected $paymentUrl = "https://api.moyasar.com/v1";

    public function __construct()
    {
        $this->publishKey =  setting('payment_gateway','moyasar.test_mode.PUBLISH_KEY');
        $this->secretKey =  setting('payment_gateway','moyasar.test_mode.SECRET_KEY');
        if (setting('payment_gateway','moyasar.payment_mode') == 'live_mode') {
            $this->paymentMode = 'live';
            $this->publishKey = setting('payment_gateway','moyasar.live_mode.PUBLISH_KEY');
            $this->secretKey = setting('payment_gateway','moyasar.live_mode.SECRET_KEY');
        }
    }

    public function send($order, $payment, $userToken = '', $type = 'frontend',$total=null)
    {
        if (auth()->check()) {
            $user = [
                'name' => auth()->user()->name ?? '',
                'email' => auth()->user()->email ?? '',
                'mobile' => auth()->user()->calling_code ?? '' . auth()->user()->mobile ?? '',
            ];
        } else {
            $user = [
                'name' => 'Guest User',
                'email' => 'test@test.com',
                'mobile' => '12345678',
            ];
        }

        $url = $this->paymentUrls($type);
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $this->paymentUrl.'/invoices',[
            'auth' => [$this->secretKey, ''],
            'form_params'  => [
                'amount' => ($total ?? $order['total']) * 100,
                'currency'  => 'SAR',
                'description'   => 'Order #'. $order['id'],
                'back_url'  => $url['failed'].'?transaction='.encryptOrderId($order['id']).'&total='.($total ?? $order['total']),
                'success_url'  => $url['success'].'?transaction='.encryptOrderId($order['id']).'&total='.($total ?? $order['total']),
                'metadata'  => [
                    'user'  => $user,
                    'order_id'  => $order['id']
                ]
            ],
        ]);

        $payload = json_decode($response->getBody()->getContents());
        if($payload?->id){
            return ['status' => true, 'url' => $payload->url];
        }

        return ['status' => false];
    }

    public function paymentUrls($type)
    {
        switch($type){
            case 'api':
                $url['success'] = route('api.orders.success.moyasar');
                $url['failed'] = route('api.orders.failed.moyasar');
                break;

            case 'wallet':
                $url['success'] = route('api.wallets.success.moyasar');
                $url['failed'] = route('api.wallets.failed.moyasar');
                break;
        }
        return $url;
    }

    public function getPaymentDetails($paymentId)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->paymentUrl.'/payments/'.$paymentId,[
            'auth' => [$this->secretKey, ''],
        ]);

        $payload = json_decode($response->getBody()->getContents());
        if($payload){
            return $payload;
        }

        return false;
    }
}
