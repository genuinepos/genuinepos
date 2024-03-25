<?php

namespace App\Services\Communication\Sms;
use App\Models\Communication\Sms\SmsBody;
use App\Models\Communication\Sms\SmsServer;
use App\Jobs\Communication\Sms\SendManualSmsJob;
use App\Models\Contacts\Contact;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class SmsMenualService
{

    public function index()
    {
        $body = SmsBody::where('is_important',1)->orderBy('id','DESC')->get();
        $sender = SmsServer::select('id','host','server_name as name')->get();
        return view('communication.sms.menual.index',compact('body','sender'));
    }

    public function store($request)
    {
         $request->validate([
            'sender_id' => 'required',
            'mobile.*' => 'required', 
            'message' => 'required',
        ]);

        $smsSent = false;

        if (!preg_match('/\p{Bengali}/u', $request->message)) {
                $smsSent = false;
                return ['status' => 'error', 'message' => 'Content must be Bangla'];
        }

        if (!empty($request->mobile)) {
                foreach ($request->mobile as $mobile) {
                    SendManualSmsJob::dispatch($request->sender_id, $mobile, $request->message);
                    $smsSent = true;
                }
        }

        if ($smsSent) {
                return ['status' => 'success', 'message' => 'Sms(s) sent successfully'];
            } else {
                return ['status' => 'error', 'message' => 'No recipients provided'];
        }
    
    }

    public function show($id){
      $data = SmsBody::findOrFail($id);
      return $data;
    } 

    public function edit($id)
    {
          $smsRecipients = [];

          if ($id == 1) {
                $userRecipients = User::whereNotNull('phone')->select('phone')->get()->toArray();
                $contactRecipients = Contact::whereNotNull('phone')->select('phone')->get()->toArray();
                $smsRecipients = array_merge($userRecipients, $contactRecipients);
            } elseif ($id == 2) {
                $smsRecipients = Contact::whereNotNull('phone')->where('type', 1)->select('phone')->get();
            } elseif ($id == 3) {
                $smsRecipients = Contact::whereNotNull('phone')->where('type', 2)->select('phone')->get();
            } elseif ($id == 4) {
                $smsRecipients = User::whereNotNull('phone')->select('phone')->get();
            }
         return $smsRecipients;
    }

}
