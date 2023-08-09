<?php

namespace Modules\Communication\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class SmsSettingController extends Controller
{
    public function smsSettings(Request $request)
    {
        if (! auth()->user()->can('sms_settings')) {
            abort(403, 'Access Forbidden.');
        }

        $generalSetting = GeneralSetting::first();
        $retrievedSmsSetting = json_decode($generalSetting->sms_setting, true);

        $smsSetting = [];
        $smsSetting['url'] = $retrievedSmsSetting['url'] ?? '';
        $smsSetting['type'] = $retrievedSmsSetting['type'] ?? '';
        $smsSetting['status'] = $retrievedSmsSetting['status'] ?? '';
        $smsSetting['config'] = $retrievedSmsSetting['config'] ?? [];
        $smsSetting['final_url'] = $retrievedSmsSetting['final_url'] ?? '';

        return view('communication::sms.sms-settings', compact('smsSetting'));
    }

    public function smsSettingsStore(Request $request)
    {
        $data = [];
        $pattern = [];

        $data['url'] = $request->url;
        $data['type'] = $request->type;
        $data['status'] = $request->status == 'on' ? 1 : 0;

        foreach ($request->key as $index => $key) {
            $data['config']["$key"] = $request->value[$index];
            $pattern[] = "/$key/";
        }
        $final_url = preg_replace($pattern, $request->value, $request->url);
        $data['final_url'] = $final_url;

        $generalSetting = GeneralSetting::first();
        $generalSetting->sms_setting = $data;
        $generalSetting->save();

        return response()->json('SMS settings updated successfully!');
    }
}
