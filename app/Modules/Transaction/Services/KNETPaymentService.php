<?php

namespace Modules\Transaction\Services;

use Modules\Order\Entities\Order;

class KNETPaymentService
{

    public function __construct()
    {
        $this->TranportalId = setting('payment_gateway','knet.test_mode.TRANPORTAL_ID');
        $this->ReqTranportalPassword = "password=".setting('payment_gateway','knet.test_mode.TRANPORTAL_PASSWORD');
        $this->resourceKey  = setting('payment_gateway','knet.test_mode.RESOURCE_KEY');
        $this->paymentUrl = "https://kpaytest.com.kw/kpg/PaymentHTTP.htm?param=paymentInit&trandata=";

        if (setting('payment_gateway','knet.payment_mode') == 'live_mode') {
            $this->paymentUrl = "https://kpay.com.kw/kpg/PaymentHTTP.htm?param=paymentInit&trandata=";
            $this->TranportalId = setting('payment_gateway','knet.live_mode.TRANPORTAL_ID');
            $this->ReqTranportalPassword = "password=".setting('payment_gateway','knet.live_mode.TRANPORTAL_PASSWORD');
            $this->resourceKey  = setting('payment_gateway','knet.live_mode.RESOURCE_KEY');
        }

        $this->TranTrackid = mt_rand();
        $this->ReqTranportalId="id=".$this->TranportalId;
    }

    public function send($order, $payment, $userToken = '', $type = 'api',$total=null)
    {
        $ReqAction= "action=1";
        $ReqLangid= "langid=AR";
        $ReqCurrency= "currencycode=414";
        $ReqTrackId= "trackid=".$this->TranTrackid;
        $ReqUdf1= "udf1=Test1";
        $ReqUdf2= "udf2=Test2";
        $ReqUdf3= "udf3=Test3";
        $ReqUdf4= "udf4=Test4";
        $ReqUdf5= "udf5=Test5";
        $orderTotal = str_replace(',','',$total ?? $order->total);
        $price = "amt=".$orderTotal;
        $urls = $this->paymentUrls('api-order');

        $ReqResponseUrl = "responseURL=".$urls['success'];
        $ReqErrorUrl = "errorURL=".$urls['failed'];

        $param=$this->ReqTranportalId."&".$this->ReqTranportalPassword."&".$ReqAction."&".$ReqLangid."&".$ReqCurrency."&".$price."&".
            $ReqResponseUrl."&".$ReqErrorUrl."&".$ReqTrackId."&".$ReqUdf1."&".$ReqUdf2."&".$ReqUdf3."&".$ReqUdf4."&".$ReqUdf5;

        $termResourceKey= $this->resourceKey;
        $param= $this->encryptAES($param,$termResourceKey)."&tranportalId=".$this->TranportalId."&responseURL=".$urls['success']."&errorURL=".$urls['failed'];
        $fullURL = $this->paymentUrl . $param;
        if(!$param){
            return ['status' => false];
        }

        return ['status' => true, 'url' => $fullURL];
    }

    public function encryptAES($str,$key) {
        $str = $this->pkcs5_pad($str);
        $encrypted = openssl_encrypt($str, 'AES-128-CBC', $key, OPENSSL_ZERO_PADDING, $key);
        $encrypted = base64_decode($encrypted);
        $encrypted=unpack('C*', ($encrypted));
        $encrypted= $this->byteArray2Hex($encrypted);
        $encrypted = urlencode($encrypted);
        return $encrypted;
    }

    public function pkcs5_pad ($text) {
        $blocksize = 16;
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    public function byteArray2Hex($byteArray) {
        $chars = array_map("chr", $byteArray);
        $bin = join($chars);
        return bin2hex($bin);
    }

    public function paymentUrls($orderType)
    {
        if ($orderType == 'api') {
            $url['success'] = url(route('api.orders.success.knet'));
            $url['failed'] = url(route('api.orders.failed.knet'));
        } else {
            $url['success'] = url(route('frontend.orders.success'));
            $url['failed'] = url(route('frontend.orders.failed'));
        }
        return $url;
    }
}
