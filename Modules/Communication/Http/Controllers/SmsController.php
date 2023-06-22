<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Modules\Communication\Entities\Sms;
use Modules\Communication\Entities\ContactGroup;
use Modules\Communication\Entities\Contact;
use Modules\Communication\Http\Controllers\Controller;
use Modules\Communication\Interface\SmsServiceInterface;

class SmsController extends Controller
{
    private $smsService;
    public function __construct(SmsServiceInterface $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sms = Sms::all();
            return DataTables::of($sms)
                ->addColumn('check', function ($row) {

                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="sms_id[]" value="' . $row->id . '" id="check1" class="mt-2 check1">
                                    <label for="check1"></label>
                                </div>';
                    return $html;
                })
                ->editColumn('message', function ($row) {
                    $html = '';
                    $html .= $row['message'];
                    return $html;
                })
                ->addColumn('status', function ($row) {
                    $html = '';
                    if ($row['status'] == 1) {
                        $html .= '<div class="text-center"><a class="" href="' . route('communication.sms.important', [$row->id, 1]) . '" id="status"><i class="fa-solid fa-star fa-lg"></i></a></div>';
                    } else {
                        $html .= '<div class="text-center"><a class="" href="' . route('communication.sms.important', [$row->id, 2]) . '" id="status"><i class="fa-thin fa-star fa-lg"></i></a></div>';
                    }
                    return $html;
                })
                ->addColumn('delete', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="' . route('communication.sms.delete', $row->id) . '" id="delete"><i class="fa-solid fa-trash-can"></i></a></div>';
                    return $html;
                })
                ->addColumn('time', function ($row) {

                    return  $row['created_at']->diffForHumans();
                })
                ->rawColumns(['check', 'status', 'message', 'delete', 'time'])
                ->make(true);
        }

        $sms = Sms::all();
        $groupIds = ContactGroup::pluck('id');
        $filtered_contact_numbers = Contact::whereNotNull('phone_number')->whereIn('group_id', $groupIds)->get();
        return view('communication::sms.index', [
            'sms' => $sms,
            'filtered_contact_numbers' => $filtered_contact_numbers,
        ]);
    }

    public function send(Request $request)
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
            'message' => 'required',
        ]);

        $numbersArray = array();

        if (!empty($request->group_id)) {

            foreach ($request->group_id as $ids) {

                $numbers = Contact::where('contact_group_id', $ids)->get();
                foreach ($numbers as $number) {
                    array_push($numbersArray, $number->phone_number);
                }
            }
        }

        $numbersArray = array_merge($request->to, $numbersArray);
        $trimmedNumbers = array_map(fn ($item) => trim($item), $numbersArray);
        $message = trim(\html_entity_decode(\strip_tags($request->message)));
        $sms = new Sms;
        $sms->to = implode(',', $trimmedNumbers);
        $sms->message = $message;
        $sms->save();

        return response()->json('Message sent successfully');
    }

    public function important(Request $request, $id, $flag)
    {
        if ($flag == 1) {
            $sms = Sms::find($id);

            $sms->status = FALSE;
            $sms->save();

            return response()->json('Message marked as unimportant');
        } else {
            $sms = Sms::find($id);

            $sms->status = true;
            $sms->save();

            return response()->json('Message marked as important');
        }
    }

    public function delete(Request $request, $id)
    {
        $sms = Sms::find($id);
        $sms->delete();
        return response()->json(['errorMsg' => 'Message deleted successfully']);
    }

    public function delete_all(Request $request)
    {
        if (!isset($request->sms_id)) {
            return response()->json(['errorMsg' => 'Select messages first']);
        }
        foreach ($request->sms_id as $key => $items) {
            $sms = Sms::find($items);
            $sms->delete();
        }
        return response()->json(['errorMsg' => 'Message deleted successfully']);
    }
}
