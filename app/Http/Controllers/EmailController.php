<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Artisan;

class EmailController extends Controller
{
    public function __construct()
    {
        
    }

    public function emailSettings(Request $request)
    {
        $data = GeneralSetting::email();
        $emailSetting = [];
        $emailSetting['MAIL_MAILER'] =  $data['MAIL_MAILER'] ?? '';
        $emailSetting['MAIL_HOST'] = $data['MAIL_HOST'] ?? '';
        $emailSetting['MAIL_PORT'] = $data['MAIL_PORT'] ?? '';
        $emailSetting['MAIL_USERNAME'] = $data['MAIL_USERNAME'] ?? '';
        $emailSetting['MAIL_PASSWORD'] =  $data['MAIL_PASSWORD'] ?? '';
        $emailSetting['MAIL_ENCRYPTION'] =  $data['MAIL_ENCRYPTION'] ?? '';
        $emailSetting['MAIL_FROM_ADDRESS'] = $data['MAIL_FROM_ADDRESS'] ?? '';
        $emailSetting['MAIL_FROM_NAME'] =  $data['MAIL_FROM_NAME'] ?? '';
        $emailSetting['MAIL_ACTIVE'] = $data['MAIL_ACTIVE'] ?? '';

        return view('communication.email.settings.index', compact('emailSetting'));
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
