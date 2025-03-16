<?php
namespace App\Services;
use App\Services\PaymentGatewayService;

class UpaymentGateway implements PaymentGatewayInterface
{
    protected $method='upayment';
    public function processPayment($order)
    {
        $payment_mode = strtoupper(env('UPAYMENT.MODE'));
        $white_labeled   = $payment_mode == 'TEST' ? true : false;
        $payment_url   = env('UPAYMENT.'.$payment_mode.'.PAYMENT_URL');
        $api_key   = env('UPAYMENT.'.$payment_mode.'.API_KEY');
        $merchant_id   = env('UPAYMENT.'.$payment_mode.'.MERCHANT_ID');
        $username   = env('UPAYMENT.'.$payment_mode.'.USERNAME');
        $password   = env('UPAYMENT.'.$payment_mode.'.PASSWORD');
        $charges   = env('UPAYMENT.'.$payment_mode.'.CHARGES');
        $cc_charges   = env('UPAYMENT.'.$payment_mode.'.CC_CHARGES');
        $ibans   = env('UPAYMENT.'.$payment_mode.'.IBANS');

        $user = [
            'name' => $order->user->name ?? '',
            'email' => $order->user->email ?? '',
            'mobile' => $order->user->calling_code ?? '' . $order->user->mobile ?? '',
        ];

        $extraMerchantsData = array();
        $extraMerchantsData['amounts'][0] = $order->total;
        $extraMerchantsData['charges'][0] = $charges;
        $extraMerchantsData['chargeType'][0] = 'fixed'; // or 'percentage'
        $extraMerchantsData['cc_charges'][0] = $cc_charges; // or 'percentage'
        $extraMerchantsData['cc_chargeType'][0] = 'percentage'; // or 'percentage'
        $extraMerchantsData['ibans'][0] = $ibans;

        $url = $this->paymentUrls();

        $fields = [
            'api_key' => $api_key,
            'merchant_id' => $merchant_id,
            'username' => $username,
            'password' => stripslashes( $password),
            'order_id' => $order->id,
            'CurrencyCode' => 'KWD',
            'CstFName' => $user['name'],
            'CstEmail' => $user['email'],
            'CstMobile' => $user['mobile'],
            'success_url' => $url['success'],
            'error_url' => $url['failed'],
            'ExtraMerchantsData' => json_encode($extraMerchantsData),
            'test_mode' => $white_labeled, // 1 == test mode enabled
            'whitelabled' => $white_labeled, // false == in live mode
            'payment_gateway' => 'cc',
            'reference' => $order->id,
            'notifyURL' => $url['failed'],
            'total_price' => $order->total,
        ];

        $fields_string = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $payment_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $server_output = json_decode($server_output, true);

        if (isset($server_output['status']) && $server_output['status'] == 'errors') {
            return ['status' => false];
        }

        if(isset($server_output['paymentURL']) && !empty($server_output['paymentURL'])){
            $this->createTransaction($order,request(),'pending');
        }

        return ['status' => true, 'url' => $server_output['paymentURL']];
    }

    public function paymentUrls()
    {
        $url['success'] = \URL::to('/api/orders/success/upayment');
        $url['failed'] = \URL::to('/api/orders/failed/upayment');
        return $url;
    }

    public function createTransaction($order, $request,$result='pending'){
        $dataArr = [
            'transaction_id' => $order->id,
            'method'    => $this->method,
            'result' => $result,
            'auth' => $order->user_id,
            'post_date' => now(),
        ];

        if($result && $result !== 'pending'){
            $dataArr['tran_id'] = $request->TranID ?? '';
            $dataArr['ref'] = $request->Ref ?? '';
            $dataArr['track_id'] =  $request->TrackID ?? '';
            $dataArr['payment_id'] =  $request->PaymentID ?? '';
        }

        return $order->transactions()->create($dataArr);
    }
}
