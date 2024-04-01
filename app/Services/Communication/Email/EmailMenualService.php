<?php

namespace App\Services\Communication\Email;

use App\Models\Communication\Email\EmailBody;
use App\Models\Communication\Email\EmailServer;
use App\Jobs\Communication\Email\SendManualEmailJob;
use App\Http\Traits\Communication\Email\MenualEmailConfiguration;
use App\Models\Contacts\Contact;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class EmailMenualService
{

    use MenualEmailConfiguration;

    public function index()
    {
        $body = EmailBody::where('is_important', 1)->orderBy('id', 'DESC')->get();
        $sender = EmailServer::select('id', 'host', 'name')->get();
        return view('communication.email.menual.index', compact('body', 'sender'));
    }

    public function store($request)
    {
        $request->validate([
            'sender_id' => 'required',
            'mail.*' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'cc' => 'required',
            'bcc' => 'required',
        ]);

        $this->menualConfiguration($request->sender_id);

        $emailsSent = false;

        if (!empty($request->mail)) {
            foreach ($request->mail as $email) {
                SendManualEmailJob::dispatch($email, $request->subject, $request->message, $request->cc, $request->bcc);
                $emailsSent = true;
            }
        }

        if ($emailsSent) {
            return ['status' => 'success', 'message' => 'Email(s) sent successfully'];
        } else {
            return ['status' => 'error', 'message' => 'No recipients provided'];
        }
    }

    public function show($id)
    {
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
