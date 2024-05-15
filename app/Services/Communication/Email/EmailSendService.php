<?php

namespace App\Services\Communication\Email;

use App\Jobs\Communication\Email\SendEmailJob;
use App\Models\Communication\Email\SendEmail;
use App\Models\Communication\Email\EmailServer;
use App\Models\Contacts\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class EmailSendService
{
    public function index($request)
    {
        $data = SendEmail::query()->orderBy('id', 'desc');
        if ($request->ajax()) {

            if ($request->status == 4) {

                $data = $data->withTrashed()->whereNotNull('deleted_at');
            } else {

                $data = $data->where('status', $request->status);
            }

            $data = $data->get();

            return response()->json(['data' => $data]);
        }

        return view('communication.email.send.index', compact('data'));
    }

    public function store($request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'mail.*' => 'required|email',
                'subject' => 'required',
                'message' => 'required',
                'attachment.*' => 'nullable|max:1024',
            ], [
                'mail.*.required' => 'The email field is required.',
                'mail.*.email' => 'The email must be a valid email address.',
                'subject.required' => 'The subject field is required.',
                'message.required' => 'The message field is required.',
                'attachment.*.max' => 'The attachment may not be greater than 1024 kilobytes.',
            ]);

            // Check if validation fails
            if ($validator->fails()) {

                return ['status' => 'error', 'message' => $validator->errors()->first()];
            }

            $subject = $request->input('subject');
            $message = $request->input('message');
            $formEmails = $request->input('mail');
            $group = $request->input('group_id');
            $emailRecipients = [];
            $emailsSent = false;
            $attachments = [];
            $attachmentNames = [];

            if ($request->hasFile('attachment')) {

                foreach ($request->attachment as $images) {

                    $attach_name = $images->getClientOriginalName();
                    $attachmentNames[] = $attach_name;
                    $fileName = uniqid() . '.' . $images->getClientOriginalExtension();
                    $tenantFolder = 'uploads/communication/' . 'email/attachment/';
                    $images->move(public_path($tenantFolder), $fileName);
                    $attachmentUrl = public_path($tenantFolder . $fileName);
                    $attachments[] = $attachmentUrl;
                }
            }

            if ($group == 'all') {

                $userRecipients = User::whereNotNull('email')->pluck('email')->toArray();
                $contactRecipients = Contact::whereNotNull('email')->pluck('email')->toArray();
                $emailRecipients = array_merge($userRecipients, $contactRecipients);
            } elseif ($group == 'customer') {

                $emailRecipients = Contact::whereNotNull('email')->where('type', 1)->pluck('email')->toArray();
            } elseif ($group == 'supplier') {

                $emailRecipients = Contact::whereNotNull('email')->where('type', 2)->pluck('email')->toArray();
            } elseif ($group == 'user') {

                $emailRecipients = User::whereNotNull('email')->pluck('email')->toArray();
            }


            $sever = EmailServer::where('status', 1)->first();
            if (!isset($sever)) {

                return ['status' => 'error', 'message' => 'Email Server not active'];
            }

            $userRecipients = '';
            $contactRecipients = '';

            if (!empty($formEmails)) {

                $user_email = json_encode($formEmails);
                $userRecipients = implode(',', json_decode($user_email, true));
            }

            if (!empty($emailRecipients)) {

                $contact_email = json_encode($emailRecipients);
                $contactRecipients = implode(',', json_decode($contact_email, true));
            }

            $allRecipients = $userRecipients . ($userRecipients && $contactRecipients ? ',' : '') . $contactRecipients;

            $attachmentsJson = json_encode($attachmentNames);

            SendEmail::create([
                'mail' => $allRecipients,
                'group_name' => $group,
                'subject' => $subject,
                'message' => $message,
                'attachment' =>  $attachmentsJson,
                'status' => 1,
            ]);

            if (!empty($formEmails)) {

                foreach ($formEmails as $email) {

                    SendEmailJob::dispatch($email, $subject, $message, $attachments);
                    $emailsSent = true;
                }
            }

            if (!empty($emailRecipients)) {

                foreach ($emailRecipients as $email) {

                    SendEmailJob::dispatch($email, $subject, $message, $attachments);
                    $emailsSent = true;
                }
            }

            if ($emailsSent) {

                return ['status' => 'success', 'message' => __('Email(s) sent successfully')];
            } else {

                return ['status' => 'error', 'message' => __('No recipients provided')];
            }
        } catch (\Exception $e) {

            return ['status' => 'error', 'message' => $e->getMessage(), 'On line' => $e->getLine()];
        }
    }

    public function edit($id)
    {
        // $data = EmailBody::findOrFail($id);
        // return $data;
    }

    public function restore($id)
    {
        $emailBody = SendEmail::withTrashed()->findOrFail($id);

        $restored = $emailBody->restore();

        if ($restored) {
            return ['status' => 'success', 'message' => 'Email has been restored successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to restore email'];
        }
    }

    public function destroy($id)
    {
        $emailBody = SendEmail::findOrFail($id);

        $deleted = $emailBody->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Email deleted successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to delete email'];
        }
    }

    public function deleteEmailMultiple($request)
    {
        $deleted = SendEmail::whereIn('id', $request->ids)->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Email deleted successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to delete email'];
        }
    }

    public function deleteEmailPermanent($request)
    {
        $deleted = SendEmail::whereIn('id', $request->ids)->forceDelete();
        if ($deleted) {
            return ['status' => 'success', 'message' => 'Email deleted successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to delete email'];
        }
    }
}
