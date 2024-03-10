<?php

namespace App\Services\Communication\Sms;

use App\Models\Communication\Sms\SendSms;
use App\Models\Communication\Sms\SmsServer;
use App\Models\Contacts\Contact;
use App\Models\User;

class SmsSendService
{
    public function index($request)
    {

        $data = SendSms::query()->orderBy('id', 'desc');

        if ($request->ajax()) {
            if ($request->status == 4) {
                $data = $data->withTrashed()->whereNotNull('deleted_at');
            } else {
                $data = $data->where('status', $request->status);
            }

            $data = $data->get();

            return response()->json(['data' => $data]);
        }

        return view('communication.sms.send.index', compact('data'));

    }

    public function store($request)
    {
        try {

            $formSmss = $request->input('phone');
            $group = $request->input('group_id');
            $message = mb_convert_encoding($request->message, 'UTF-8', 'auto');
            $SmsRecipients = [];
            $SmssSent = false;

            $sever = SmsServer::where('status', 1)->first();

            if (!isset($sever)) {
                return ['status' => 'error', 'message' => 'Sms Server not active'];
            }

            if ($group == 'all') {
                $userRecipients = User::whereNotNull('phone')->pluck('phone')->toArray();
                $contactRecipients = Contact::whereNotNull('phone')->pluck('phone')->toArray();
                $SmsRecipients = array_merge($userRecipients, $contactRecipients);
            } elseif ($group == 'customer') {
                $SmsRecipients = Contact::whereNotNull('phone')->where('type', 1)->pluck('phone')->toArray();
            } elseif ($group == 'supplier') {
                $SmsRecipients = Contact::whereNotNull('phone')->where('type', 2)->pluck('phone')->toArray();
            } elseif ($group == 'user') {
                $SmsRecipients = User::whereNotNull('phone')->pluck('phone')->toArray();
            }

            if (!empty($formSmss)) {
                foreach ($formSmss as $phone) {
                    SendSms::create([
                        'phone' => $phone,
                        'message' => $message,
                        'status' => 1,
                    ]);
                    //SendSmsJob::dispatch($Sms, $message);
                    $response = $this->send_sms($phone, $message);
                    $SmssSent = true;
                }
            }

            if (!empty($SmsRecipients)) {
                foreach ($SmsRecipients as $phone) {
                    SendSms::create([
                        'phone' => $phone,
                        'message' => $message,
                        'status' => 1,
                    ]);
                    //SendSmsJob::dispatch($Sms, $message);
                    $response = $this->send_sms($phone, $message);
                    $SmssSent = true;
                }
            }

            if ($SmssSent) {
                return ['status' => 'success', 'message' => 'Sms(s) sent successfully'];
            } else {
                return ['status' => 'error', 'message' => 'No recipients provided'];
            }

        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage(), 'On line' => $e->getLine()];
        }

    }

    public function edit($id)
    {
        $data = SmsBody::findOrFail($id);
        return $data;
    }

    public function restore($id)
    {
        $SmsBody = SendSms::withTrashed()->findOrFail($id);

        $restored = $SmsBody->restore();

        if ($restored) {
            return ['status' => 'success', 'message' => 'Sms has been restored successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to restore Sms'];
        }
    }

    public function destroy($id)
    {
        $SmsBody = SendSms::findOrFail($id);

        $deleted = $SmsBody->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Sms deleted successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to delete Sms'];
        }
    }

    public function deleteSmsMultiple($request)
    {

        $deleted = SendSms::whereIn('id', $request->ids)->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Sms deleted successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to delete Sms'];
        }
    }

    public function deleteSmsPermanent($request)
    {
        $deleted = SendSms::whereIn('id', $request->ids)->forceDelete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Sms deleted successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to delete Sms'];
        }
    }

    function send_sms($mobile, $message)
    {
        $sever = SmsServer::where('status', 1)->first();
        if (!isset($sever)) {
            return ['status' => 'error', 'message' => 'Sms Server not active'];
        }
        // $url = "https://msg.elitbuzz-bd.com/smsapi";
        $url = $sever->host;
        // $data = [
        //     "api_key" => "R6001497636b5b900483f9.42267793",
        //     "type" => "{content type}",
        //     "contacts" => "88" . $mobile,
        //     "senderid" => "SpeedDigit",
        //     "msg" => $message,
        // ];
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
