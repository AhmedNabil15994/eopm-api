<?php

namespace Modules\Transaction\Services\SMS;

class SMS
{
    private function smsbox($to, $message)
    {
        $url = 'https://smsbox.com/SMSGateway/Services/Messaging.asmx/Http_SendSMS?';
        $to = is_array($to) ? $to : (array)$to;

        $addTwo = function ($number) {
            return '965' . $number;
        };

        $to = array_map($addTwo, $to);

        $push_payload = array(
            "username" => env("SMS_BOX_USERNAME"),
            "password" => env("SMS_BOX_PASWORD"),//01000000
            "customerid" => env("SMS_BOX_CUSTOMERID"),
            "senderText" => env("SMS_BOX_SENDER_TEXT"),
            "recipientNumbers" => implode(',',$to),
            "messageBody" => $message,
            "defdate" => '',
            "isBlink" => false,
            "isFlash" => false,
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.http_build_query($push_payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = (array)simplexml_load_string($result);
        
        if($result['Result'] == 'false'){
            
            return [
                'server_response' => 'error',
            ];
        }

        return $result;
    }

    public function send($to, $message, $provider = 'smsbox')
    {
        switch ($provider) {
            
            case'smsbox':
                return $this->smsbox($to, $message);
            default:
                return 'mismatch provider';
        }
    }
}
