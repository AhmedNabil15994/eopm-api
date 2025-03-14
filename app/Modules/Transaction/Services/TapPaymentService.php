<?php

namespace Modules\Transaction\Services;

use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client;

class TapPaymentService
{

    private $apiKey;

    public function __construct()
    {
        $this->apiKey = setting('payment_gateway', "tap." . setting('payment_gateway,"tap.payment_mode')  . '.API_KEY') ?? 'sk_test_eoIW8Dm6XyTuUdk0qsf71cj9';
    }
    /**
     * @param $order
     * @param $payment
     * @param  string  $userToken
     * @param $type
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($order, $payment, $userToken = '', $type = 'order',$total=null)
    {
        $fields = $this->getRequestFields($order, $type, $payment,$total);

        $client = new Client();

        try {

            $res = $client->post('https://api.tap.company/v2/charges/', [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ],
                RequestOptions::JSON    => $fields
            ]);

            return isset(json_decode($res->getBody(), true)['transaction']['url']) ?
                ['status' => true, 'url' => json_decode($res->getBody(), true)['transaction']['url']] :
                ['status' => false];
        } catch (\Exception $e) {

            return [
                'status' => false,
                'server_response' => 'error',
                'order_id'        => $order->id
            ];
        }
    }

    /**
     * @param $request
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTransactionDetails($request)
    {
        $client = new Client();

        try {

            $res = $client->get('https://api.tap.company/v2/charges/' . $request->tap_id, [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]
            ]);

            return json_decode($res->getBody(), true);
        } catch (\Exception $e) {

            return [
                'server_response' => 'error',
            ];
        }
    }

    /**
     * @param $order
     * @param $type
     * @param $payment
     * @return array
     */
    private function getRequestFields($order, $type, $payment,$total=null)
    {
        $url = $this->paymentUrls($type);

        if (auth()->check()) {
            $user = [
                'name' => auth()->user()->name ?? '',
                'email' => auth()->user()->email ?? '',
                'country_code' => auth()->user()->calling_code ?? '965',
                'mobile' => auth()->user()->calling_code ?? '' . auth()->user()->mobile ?? '',
            ];
        } else {
            $user = [
                'name' => 'Guest User',
                'email' => 'test@test.com',
                'country_code' => '965',
                'mobile' => '12345678',
            ];
        }

        return [
            'amount'               => $total ?? $order['total'],
            'currency'             => 'kwd',
            'threeDSecure'         => true,
            'save_card'            => false,
            'description'          => 'Order Fees',
            'statement_descriptor' => 'Sample',
            'receipt'              => [
                'email' => true,
                'sms'   => false
            ],
            'metadata' => [
                'udf4' => $payment,
                'udf5' => $order->id,
            ],
            'customer' => [
                'first_name' => $user['name'],
                'email'      => $user['email'],
                'phone'      => [
                    'country_code' => $user['country_code'],
                    'number'       => $user['mobile'],
                ]
            ],
            'source'               => ['id' => 'src_all'],
            'redirect'             => [
                'url' => $url,
            ],
            'post'             => [
                'url' => $url,
            ]
        ];
    }

    public function paymentUrls($type)
    {
        switch ($type) {
            case 'order':
                return url(route('frontend.orders.success.tap'));
        }

        return null;
    }
}
