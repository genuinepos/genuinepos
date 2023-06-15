<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use Modules\Communication\Entities\Email;
use Modules\Communication\Entities\Contact;
use Modules\Communication\Mail\WelcomeEmail;
use App\Interface\FileUploaderServiceInterface;
use Modules\Communication\Entities\ContactGroup;
use Modules\Communication\Emails\SendWeeklyPostsEmail;
use Modules\Communication\Http\Controllers\Controller;
use Modules\Communication\Interface\EmailServiceInterface;

class CustomEmailController extends Controller
{
    private $emailService;
    public function __construct(EmailServiceInterface $emailService)
    {
        $this->emailService = $emailService;
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $email = Email::all();
            return DataTables::of($email)
                ->addColumn('check', function ($row) {

                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="email_id[]" value="' . $row->id . '" id="check1" class="mt-2 check1">
                                    <label for="check1"></label>
                                </div>';
                    return $html;
                })
                ->editColumn('subject', function ($row) {
                    $html = '';
                    $message = $row['message'];
                    $message = substr($row['message'], 0, 60);
                    $html = '<p><strong>' . $row['subject'] . '</strong> - ' . strip_tags($message) . '...</p>';
                    return $html;
                })
                ->addColumn('status', function ($row) {
                    $html = '';
                    if ($row['status'] == 1) {
                        $html .= '<div class="text-center"><a class="" href="' . route('communication.email.important', [$row->id, 1]) . '" id="status"><i class="fa-solid fa-star fa-lg"></i></a></div>';
                    } else {
                        $html .= '<div class="text-center"><a class="" href="' . route('communication.email.important', [$row->id, 2]) . '" id="status"><i class="fa-thin fa-star fa-lg"></i></a></div>';
                    }
                    return $html;
                })
                ->addColumn('delete', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="' . route('communication.email.delete', $row->id) . '" id="delete"><i class="fa-solid fa-trash-can"></i></a></div>';
                    return $html;
                })
                ->editColumn('attachment', function ($row) {
                    $html = '';
                    if ($row['attachment'] != NULL) {
                        $html .= '<div class="text-center"><i class="fa-solid fa-paperclip"></i></div>';
                    } else {
                        $html .= '&nbsp;&nbsp;&nbsp; &nbsp;';
                    }
                    return $html;
                })
                ->addColumn('time', function ($row) {

                    return  $row['created_at']->diffForHumans();
                })
                ->rawColumns(['check', 'status', 'subject', 'delete', 'time', 'attachment'])
                ->make(true);
        }

        $mails = Email::all();
        $groupIds = ContactGroup::pluck('id');
        $filtered_contact_email = Contact::whereNotNull('email')->whereIn('group_id', $groupIds)->get();
        return view('communication::email.index', [
            'mails' => $mails,
            'filtered_contact_email' => $filtered_contact_email,
        ]);
    }

    public function send(Request $request, FileUploaderServiceInterface $fileUploaderService)
    {

        if (isset($request->group_id) && (count($request->group_id) > 0)) {
            $to = $request->to;
            if (isset($to) && (count($to) == 0)) {
                $request->validate([
                    'to.*' => 'required',
                ]);
            }
        }

        $request->validate([
            'subject' => 'required',
        ]);

        $emailArray = array();

        if (!empty($request->group_id)) {

            foreach ($request->group_id as $ids) {

                $email = Contact::where('group_id', $ids)->get();
                foreach ($email as $email) {
                    array_push($emailArray, $email->email);
                }
            }
        }


        $emailArray = array_merge($request->to, $emailArray);

        $subject = $request->subject;
        $body = $request->description;
        $files = $request->file;

        $trimmedEmails = array_map(fn ($item) => trim($item), $emailArray);

        $mailData = [
            'subject' => $subject,
            'body' => $body,
            'files' => $files,
        ];
        $emailsAsString = implode(',', $trimmedEmails);
        $mails = new Email;
        $mails->mail = $emailsAsString;
        $mails->subject = $subject;
        $mails->message = $body;
        $mails->attachment = $files;
        $mails->save();

        // $this->emailService->sendMultiple($trimmedEmails, new SendWeeklyPostsEmail($mailData));
        $testMailData = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp.'
        ];

        Mail::to('your_email@gmail.com')->send(new SendWeeklyPostsEmail($testMailData));

        dd("Email is sent successfully.");
        return response()->json('Mail sent successfully');
    }

    public function important(Request $request, $id, $flag)
    {
        if ($flag == 1) {
            $mails = Email::find($id);

            $mails->status = FALSE;
            $mails->save();

            return response()->json('Mail Marked as Unimportant');
        } else {
            $mails = Email::find($id);

            $mails->status = true;
            $mails->save();

            return response()->json('Mail Marked as Important');
        }
    }


    public function delete(Request $request, $id)
    {
        $mails = Email::find($id);
        $mails->delete();
        return response()->json(['errorMsg' => 'Mail Deleted Successfully']);
    }

    public function delete_all(Request $request)
    {
        if (!isset($request->email_id)) {
            return response()->json(['errorMsg' => 'Select mail first']);
        }
        foreach ($request->email_id as $key => $items) {
            $mails = Email::find($items);
            $mails->delete();
        }
        return response()->json(['errorMsg' => 'Mail Deleted Successfully']);
    }
}
