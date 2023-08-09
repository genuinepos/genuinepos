<?php

namespace Modules\Communication\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Modules\Communication\Entities\EmailServer;
use Modules\Communication\Entities\EmailTemplate;
use Yajra\DataTables\Facades\DataTables;

class EmailSettingController extends Controller
{
    public function __construct()
    {
    }

    public function emailBodyStore(Request $request)
    {
        $request->validate([
            'format_name' => 'required',
            'mail_subject' => 'required',
            // 'format_name' =>'required|unique:email_templates,format_name',
        ]);

        $template = '';
        $template = EmailTemplate::where('format_name', $request->format_name)->first();

        if ($template) {
            $template->format_name = $request->format_name;
            $template->mail_subject = $request->mail_subject;
            $template->body_format = $request->body_format;
            $template->save();
        } else {
            $template = new EmailTemplate();
            $template->format_name = $request->format_name;
            $template->mail_subject = $request->mail_subject;
            $template->body_format = $request->body_format;
            $template->save();
        }

        return response()->json(['status' => 'success', 'template' => $template]);
        // return view('communication::email.email-body', compact('all'));

    }

    public function emailBody(Request $request)
    {
        // putenv ("CUSTOM_VARIABLE=hero");
        // return env('CUSTOM_VARIABLE');

        if ($request->ajax()) {

            $email = EmailTemplate::all();

            return DataTables::of($email)
                ->addColumn('format_name', function ($row) {
                    $html = '';
                    $body_format = $row['body_format'];
                    $body_format = substr($row['body_format'], 0, 60);
                    $html = '<p><strong>'.$row['format_name'].'</strong> - '.strip_tags($body_format).'...</p>';

                    return $html;
                })
                ->addColumn('mail_subject', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['mail_subject'].'</strong> </p>';

                    return $html;
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                <input type="checkbox" name="email_id[]" value="'.$row->id.'" id="check1" class="mt-2 check1">
                                <label for="check1"></label>
                            </div>';

                    return $html;
                })
                ->addColumn('status', function ($row) {
                    $html = '';
                    if ($row['status'] == 1) {
                        $html .= '<div class="text-center"><a class="" href="'.route('communication.email.body.important', [$row->id, 1]).'" id="status"><i class="fa-solid fa-star fa-lg"></i></a></div>';
                    } else {
                        $html .= '<div class="text-center"><a class="" href="'.route('communication.email.body.important', [$row->id, 2]).'" id="status"><i class="fa-thin fa-star fa-lg"></i></a></div>';
                    }

                    return $html;
                })
                ->addColumn('view', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="'.route('communication.email.body.view', $row->id).'" id="emailBodyView"><i class="fa-sharp fa-solid fa-eye"></i></a></div>';

                    return $html;
                })
                ->addColumn('delete', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="'.route('communication.email.body.delete', $row->id).'" id="delete"><i class="fa-solid fa-trash-can"></i></a></div>';

                    return $html;
                })
                ->rawColumns(['format_name', 'mail_subject', 'check', 'status', 'delete', 'view'])
                ->make(true);
        }

        $mails = EmailTemplate::all();

        return view('communication::email.email-body', [
            'mails' => $mails,
        ]);
    }

    public function view($id)
    {
        $template = EmailTemplate::find($id);

        return response()->json(['status' => 'view email body', 'template' => $template]);
    }

    public function importantBody(Request $request, $id, $flag)
    {
        if ($flag == 1) {
            $mails = EmailTemplate::find($id);

            $mails->status = false;
            $mails->save();

            return response()->json('This Mail Body is Marked as Unimportant');
        } else {
            $mails = EmailTemplate::find($id);

            $mails->status = true;
            $mails->save();

            return response()->json('This Mail Body is Marked as Important');
        }
    }

    public function deleteAllBody(Request $request)
    {
        if (! isset($request->email_id)) {
            return response()->json(['errorMsg' => 'Select mail first']);
        }
        foreach ($request->email_id as $key => $items) {
            $mails = EmailTemplate::find($items);
            $mails->delete();
        }

        return response()->json(['errorMsg' => 'Mail Deleted Successfully']);
    }

    public function deleteBody(Request $request, $id)
    {
        $mails = EmailTemplate::find($id);
        $mails->delete();

        return response()->json(['errorMsg' => 'Mail Body Deleted Successfully']);
    }

    public function emailPermission(Request $request)
    {

        return view('communication::email.email-permission');
    }

    public function emailManual(Request $request)
    {

        return view('communication::email.email-manual-service');
    }

    public function emailServerSetup(Request $request)
    {
        if ($request->ajax()) {

            $server = EmailServer::all();

            return DataTables::of($server)
                ->addColumn('status', function ($row) {
                    $html = '';
                    if ($row['status'] == 1) {
                        $html .= '<div class="text-center"><a class="" href="'.route('communication.email.server.active', [$row->id, 1]).'" id="status"><i class="fa-solid fa-check-to-slot"></i></a></div>';
                    } else {
                        $html .= '<div class="text-center"><a class="" href="'.route('communication.email.server.active', [$row->id, 2]).'" id="status"><i class="fa-regular fa-check-to-slot"></i></a></div>';
                    }

                    return $html;
                })
                ->addColumn('server_name', function ($row) {
                    $html = '';
                    $encryption = $row['encryption'];
                    $encryption = substr($row['encryption'], 0, 60);
                    $html = '<p><strong>'.$row['server_name'].'</strong> - '.strip_tags($encryption).'</p>';

                    return $html;
                })
                ->addColumn('host', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['host'].'</strong>'.'</p>';

                    return $html;
                })
                ->addColumn('port', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['port'].'</strong>'.'</p>';

                    return $html;
                })
                ->addColumn('user_name', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['user_name'].'</strong>'.'</p>';

                    return $html;
                })
                ->addColumn('password', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['password'].'</strong>'.'</p>';

                    return $html;
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                <input type="checkbox" name="server_id[]" value="'.$row->id.'" id="check1" class="mt-2 check1">
                                <label for="check1"></label>
                            </div>';

                    return $html;
                })
                ->addColumn('edit', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="'.route('communication.email.serve.edit', $row->id).'" id="emailServerEdit"><i class="fa-solid fa-pen-to-square"></i></a></div>';

                    return $html;
                })
                ->addColumn('delete', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="'.route('communication.email.serve.delete', $row->id).'" id="delete"><i class="fa-solid fa-trash-can"></i></a></div>';

                    return $html;
                })
                ->rawColumns(['server_name', 'status', 'host', 'port', 'user_name', 'password', 'encryption', 'address', 'name', 'check', 'delete', 'edit'])
                ->make(true);
        }

        $server = EmailServer::all();

        return view('communication::email.email-server-setup', [
            'mails' => $server,
        ]);

    }

    public function emailServerStore(Request $request)
    {
        $request->validate([
            'server_name' => 'required',
            'host' => 'required',
            'port' => 'required',
            'user_name' => 'required',
            'password' => 'required',
            'encryption' => 'required',
            // 'format_name' =>'required|unique:email_servers,server_name',
        ]);

        $serverCredential = '';
        $serverCredential = EmailServer::where('id', $request->mail_server_primary_id)->first();

        if ($serverCredential) {
            $serverCredential->server_name = $request->server_name;
            $serverCredential->host = $request->host;
            $serverCredential->port = $request->port;
            $serverCredential->user_name = $request->user_name;
            $serverCredential->password = $request->password;
            $serverCredential->encryption = $request->encryption;
            $serverCredential->address = $request->address;
            $serverCredential->name = $request->name;
            $serverCredential->save();
        } else {
            $serverCredential = new EmailServer();
            $serverCredential->server_name = $request->server_name;
            $serverCredential->host = $request->host;
            $serverCredential->port = $request->port;
            $serverCredential->user_name = $request->user_name;
            $serverCredential->password = $request->password;
            $serverCredential->encryption = $request->encryption;
            $serverCredential->address = $request->address;
            $serverCredential->name = $request->name;
            $serverCredential->save();
        }

        return response()->json(['status' => 'success', 'template' => $serverCredential]);
    }

    public function activeServer(Request $request, $id, $flag)
    {
        if ($flag == 1) {
            $mails = EmailServer::find($id);

            $mails->status = false;
            $mails->save();

            return response()->json('This Mail Server is De Active');
        } else {
            $mails = EmailServer::find($id);

            $mails->status = true;
            $mails->save();

            return response()->json('This Mail Server is Actived');
        }
    }

    public function editServer($id)
    {
        $serverCredential = EmailServer::find($id);

        return response()->json(['status' => 'view for edit email serve', 'serverCredentialVal' => $serverCredential]);
    }

    public function deleteAllserver(Request $request)
    {
        if (! isset($request->server_id)) {
            return response()->json(['errorMsg' => 'Select mail first']);
        }
        foreach ($request->server_id as $key => $items) {
            $mails = EmailServer::find($items);
            $mails->delete();
        }

        return response()->json(['errorMsg' => 'Mail Server Deleted Successfully']);
    }

    public function deleteServe(Request $request, $id)
    {
        $mails = EmailServer::find($id);
        $mails->delete();

        return response()->json(['errorMsg' => 'Mail Server Deleted Successfully']);
    }

    public function emailSettingsUI(Request $request)
    {

        return view('communication::email.email-settings-ui');
    }

    public function emailSettings(Request $request)
    {
        if (! auth()->user()->can('email_settings')) {
            abort(403, 'Access Forbidden.');
        }

        $data = GeneralSetting::email();

        $emailSetting = [];
        $emailSetting['MAIL_MAILER'] = $data['MAIL_MAILER'] ?? '';
        $emailSetting['MAIL_HOST'] = $data['MAIL_HOST'] ?? '';
        $emailSetting['MAIL_PORT'] = $data['MAIL_PORT'] ?? '';
        $emailSetting['MAIL_USERNAME'] = $data['MAIL_USERNAME'] ?? '';
        $emailSetting['MAIL_PASSWORD'] = $data['MAIL_PASSWORD'] ?? '';
        $emailSetting['MAIL_ENCRYPTION'] = $data['MAIL_ENCRYPTION'] ?? '';
        $emailSetting['MAIL_FROM_ADDRESS'] = $data['MAIL_FROM_ADDRESS'] ?? '';
        $emailSetting['MAIL_FROM_NAME'] = $data['MAIL_FROM_NAME'] ?? '';
        $emailSetting['MAIL_ACTIVE'] = $data['MAIL_ACTIVE'] ?? '';

        return view('communication::email.email-settings', compact('emailSetting'));
    }

    public function emailSettingsStore(Request $request)
    {
        $data = [];
        $data['MAIL_MAILER'] = $request->get('MAIL_MAILER');
        $data['MAIL_HOST'] = $request->get('MAIL_HOST');
        $data['MAIL_PORT'] = $request->get('MAIL_PORT');
        $data['MAIL_USERNAME'] = $request->get('MAIL_USERNAME');
        $data['MAIL_PASSWORD'] = $request->get('MAIL_PASSWORD');
        $data['MAIL_ENCRYPTION'] = $request->get('MAIL_ENCRYPTION');
        $data['MAIL_FROM_ADDRESS'] = $request->get('MAIL_FROM_ADDRESS');
        $data['MAIL_FROM_NAME'] = $request->get('MAIL_FROM_NAME');
        $data['MAIL_ACTIVE'] = $request->MAIL_ACTIVE == 'on' ? true : false;

        $generalSetting = GeneralSetting::first();
        $generalSetting->email_setting = $data;
        $generalSetting->save();

        return response()->json('Email settings updated successfully');
    }
}
