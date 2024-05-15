<?php

namespace App\Http\Traits\Communication\Sms;

use App\Models\Communication\Sms\SmsServer;

trait SmsConfiguration
{
    function sendSms($mobile, $message)
    {
        $sever = SmsServer::where('status', 1)->first();

        if (!isset($sever)) {
            $SmssSent = false;
            return ['status' => 'error', 'message' => 'Sms Server not active'];
        }

        // $url = "https://msg.elitbuzz-bd.com/smsapi";
        // $data = [
        //     "api_key" => "R6001497636b5b900483f9.42267793",
        //     "type" => "{content type}",
        //     "contacts" => "88" . $mobile,
        //     "senderid" => "SpeedDigit",
        //     "msg" => $message,
        // ];
        $url = $sever->host;
        $data = [
            "api_key" => $sever->api_key,
            "type" => "{content type}",
            "contacts" => "88" . $mobile,
            "senderid" => $sever->sender_id,
            "msg" => $message,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    function sendSmsManual($id, $mobile, $message)
    {
        $sever = SmsServer::where('id', $id)->first();

        if (!isset($sever)) {
            $SmssSent = false;
            return ['status' => 'error', 'message' => 'Sms Server not active'];
        }

        // $url = "https://msg.elitbuzz-bd.com/smsapi";
        // $data = [
        //     "api_key" => "R6001497636b5b900483f9.42267793",
        //     "type" => "{content type}",
        //     "contacts" => "88" . $mobile,
        //     "senderid" => "SpeedDigit",
        //     "msg" => $message,
        // ];
        $url = $sever->host;
        $data = [
            "api_key" => $sever->api_key,
            "type" => "{content type}",
            "contacts" => "88" . $mobile,
            "senderid" => $sever->sender_id,
            "msg" => $message,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
