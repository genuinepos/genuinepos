<?php

namespace App\Services\Communication\Sms;

use App\Models\Communication\Sms\SendSms;
use App\Models\Communication\Sms\SmsServer;
use App\Http\Traits\Communication\Sms\SmsConfiguration;
use App\Jobs\Communication\Sms\SendSmsJob;
use App\Models\Contacts\Contact;
use App\Models\User;

class SmsSendService
{
    use SmsConfiguration;

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
            $formSms = $request->input('phone');
            $group = $request->input('group_id');
            $message = $request->input('message');
            $smsRecipients = [];
            $smsSent = false;
            if ($group == 'all') {
                $userRecipients = User::whereNotNull('phone')->pluck('phone')->toArray();
                $contactRecipients = Contact::whereNotNull('phone')->pluck('phone')->toArray();
                $smsRecipients = array_merge($userRecipients, $contactRecipients);
            } elseif ($group == 'customer') {
                $smsRecipients = Contact::whereNotNull('phone')->where('type', 1)->pluck('phone')->toArray();
            } elseif ($group == 'supplier') {
                $smsRecipients = Contact::whereNotNull('phone')->where('type', 2)->pluck('phone')->toArray();
            } elseif ($group == 'user') {
                $smsRecipients = User::whereNotNull('phone')->pluck('phone')->toArray();
            }

            if (!preg_match('/\p{Bengali}/u', $message)) {
                $smsSent = false;
                return ['status' => 'error', 'message' => 'Content must be Bangla'];
            }

            $sever = SmsServer::where('status', 1)->first();
            if (!isset($sever)) {
                $smsSent = false;
                return ['status' => 'error', 'message' => 'Sms Server not active'];
            }

            if (!empty($formSms)) {
                $smsSent = true;
                foreach ($formSms as $phone) {
                    SendSms::create([
                        'phone' => $phone,
                        'message' => $message,
                        'status' => 1,
                    ]);
                    SendSmsJob::dispatch($phone, $message);
                }
            }

            if (!empty($smsRecipients)) {
                $smsSent = true;
                foreach ($smsRecipients as $phone) {
                    SendSms::create([
                        'phone' => $phone,
                        'message' => $message,
                        'status' => 1,
                    ]);
                    SendSmsJob::dispatch($phone, $message);
                }
            }

            if ($smsSent) {
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

}
