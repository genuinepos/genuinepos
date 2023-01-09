<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Artisan;
use App\Services\GeneralSettingServiceInterface;

class EmailController extends Controller
{
    public function __construct(
        private GeneralSettingServiceInterface $generalSettingService
    ) {
    }

    public function emailServerSetupDesignPages()
    {
        return view('communication.email.design_pages');
    }

    public function emailSettings(Request $request)
    {
        $generalSettings = config('generalSettings');
        $emailSetting = [];
        $emailSetting['MAIL_MAILER'] =  $generalSettings['email_setting__MAIL_MAILER'] ?? '';
        $emailSetting['MAIL_HOST'] = $generalSettings['email_setting__MAIL_HOST'] ?? '';
        $emailSetting['MAIL_PORT'] = $generalSettings['email_setting__MAIL_PORT'] ?? '';
        $emailSetting['MAIL_USERNAME'] = $generalSettings['email_setting__MAIL_USERNAME'] ?? '';
        $emailSetting['MAIL_PASSWORD'] =  $generalSettings['email_setting__MAIL_PASSWORD'] ?? '';
        $emailSetting['MAIL_ENCRYPTION'] =  $generalSettings['email_setting__MAIL_ENCRYPTION'] ?? '';
        $emailSetting['MAIL_FROM_ADDRESS'] = $generalSettings['email_setting__MAIL_FROM_ADDRESS'] ?? '';
        $emailSetting['MAIL_FROM_NAME'] =  $generalSettings['email_setting__MAIL_FROM_NAME'] ?? '';
        $emailSetting['MAIL_ACTIVE'] = $generalSettings['email_setting__MAIL_ACTIVE'] ?? '';

        return view('communication.email.settings.index', compact('emailSetting'));
    }

    public function emailSettingsStore(Request $request)
    {
        $settings = [];
        $settings['email_setting__MAIL_MAILER'] = $request->get('MAIL_MAILER');
        $settings['email_setting__MAIL_HOST'] = $request->get('MAIL_HOST');
        $settings['email_setting__MAIL_PORT'] = $request->get('MAIL_PORT');
        $settings['email_setting__MAIL_USERNAME'] = $request->get('MAIL_USERNAME');
        $settings['email_setting__MAIL_PASSWORD'] = $request->get('MAIL_PASSWORD');
        $settings['email_setting__MAIL_ENCRYPTION'] = $request->get('MAIL_ENCRYPTION');
        $settings['email_setting__MAIL_FROM_ADDRESS'] = $request->get('MAIL_FROM_ADDRESS');
        $settings['email_setting__MAIL_FROM_NAME'] = $request->get('MAIL_FROM_NAME');
        $settings['email_setting__MAIL_ACTIVE'] = $request->MAIL_ACTIVE == 'on' ? true : false;
        $isSucceed = $this->generalSettingService->updateAndSync($settings);
        if($isSucceed) {
            return response()->json('Email settings updated successfully');
        }
    }
}
