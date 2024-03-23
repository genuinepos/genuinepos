<?php

namespace App\Services\Communication\Email;
use App\Models\Communication\Email\EmailBody;
use App\Models\Communication\Email\EmailServer;
use App\Jobs\SendManualEmailJob;
use App\Models\Contacts\Contact;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class EmailMenualService
{
    public function index()
    {
        $body = EmailBody::where('is_important',1)->orderBy('id','DESC')->get();
        $sender = EmailServer::select('id','name')->get();
        return view('communication.email.menual.index',compact('body','sender'));
    }

    public function store($data)
    {

        $emailsSent = false;

        if (!empty($data->mail)) {
                foreach ($data->mail as $email) {
                    SendManualEmailJob::dispatch($email, $data->subject, $data->message, $data->cc, $data->bcc);
                    $emailsSent = true;
                }
        }

        if ($emailsSent) {
                return ['status' => 'success', 'message' => 'Email(s) sent successfully'];
            } else {
                return ['status' => 'error', 'message' => 'No recipients provided'];
        }
    
    }

    public function show($id){
      $data = EmailBody::findOrFail($id);
      return $data;
    } 

    public function edit($id)
    {
          $emailRecipients = [];
          if ($id == 1) {
                $userRecipients = User::whereNotNull('email')->select('email')->get()->toArray();
                $contactRecipients = Contact::whereNotNull('email')->select('email')->get()->toArray();
                $emailRecipients = array_merge($userRecipients, $contactRecipients);
            } elseif ($id == 2) {
                $emailRecipients = Contact::whereNotNull('email')->where('type', 1)->select('email')->get();
            } elseif ($id == 3) {
                $emailRecipients = Contact::whereNotNull('email')->where('type', 2)->select('email')->get();
            } elseif ($id == 4) {
                $emailRecipients = User::whereNotNull('email')->select('email')->get();
            }
         return $emailRecipients;
    }

}
