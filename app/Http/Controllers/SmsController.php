<?php

namespace App\Http\Controllers;

use App\Services\GeneralSettingServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SmsController extends Controller
{
    public function __construct()
    {

    }

    public function smsSettings(Request $request)
    {
        $generalSettings = config('generalSettings');
        return view('communication.sms.settings.index', compact('generalSettings'));
    }

    public function smsSettingsStore(Request $request, GeneralSettingServiceInterface $generalSettingService)
    {
        $settings  = [
           'sms__SMS_URL' => str_replace('"', '', $request->get('SMS_URL')),
           'sms__API_KEY' => str_replace('"', '', $request->get('API_KEY')),
           'sms__SENDER_ID' => str_replace('"', '', $request->get('SENDER_ID')),
           'sms__SMS_ACTIVE' => isset($request->SMS_ACTIVE) ? 'true' : 'false',
        ];
        $isSucceded = $generalSettingService->updateAndSync($settings);
        if($isSucceded) {
            return response()->json('SMS settings updated successfully');
        }
        return response()->json('SMS settings update failed.');
    }

    public function smsServerSetupDesignPages()
    {
        return view('communication.sms.design_pages');
    }
}
