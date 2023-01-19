<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Modules\Communication\Entities\ContactGroup;
use Modules\Communication\Entities\Contacts;
use Modules\Communication\Entities\WhatsappMessage;
use Modules\Communication\Http\Controllers\Controller;
use Modules\Communication\Interface\SmsServiceInterface;

class WhatsappMessageController extends Controller
{
    private $whatsappService;
    public function __construct(SmsServiceInterface $whatsappService)
    {
        $this->smsService = $whatsappService;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $whatsapp = WhatsappMessage::all();

            return DataTables::of($whatsapp)
                ->addColumn('check', function ($row) {

                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="whatsapp_id[]" value="' . $row->id . '" id="check1" class="mt-2 check1">
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
                        $html .= '<div class="text-center"><a class="" href="' . route('communication.whatsapp.important', [$row->id, 1]) . '" id="status"><i class="fa-solid fa-star fa-lg"></i></a></div>';
                    } else {
                        $html .= '<div class="text-center"><a class="" href="' . route('communication.whatsapp.important', [$row->id, 2]) . '" id="status"><i class="fa-thin fa-star fa-lg"></i></a></div>';
                    }
                    return $html;
                })
                ->addColumn('delete', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="' . route('communication.whatsapp.delete', $row->id) . '" id="delete"><i class="fa-solid fa-trash-can"></i></a></div>';
                    return $html;
                })
                ->addColumn('time', function ($row) {

                    return  $row['created_at']->diffForHumans();
                })
                ->rawColumns(['check', 'status', 'message', 'delete', 'time'])
                ->make(true);
        }

        $whatsapp = WhatsappMessage::all();

        $groupIds = ContactGroup::pluck('id');
        $filtered_contact_whatsapp = Contacts::whereNotNull('whatsapp_number')->whereIn('group_id', $groupIds)->get();

        return view('communication::whatsapp.index', [
            'whatsapp' => $whatsapp,
            'filtered_contact_whatsapp' => $filtered_contact_whatsapp,
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

        if(!empty($request->group_id)){

            foreach($request->group_id as $id){
                $numbers = Contacts::where('group_id', $id)->get();
                foreach($numbers as $number){
                    array_push($numbersArray, $number->phone_number);
                }
            }
        }

        $numbersArray = array_merge($request->to, $numbersArray);

        $trimmedNumbers = array_map(fn ($item) => trim($item), $numbersArray);

        $message = trim(\html_entity_decode(\strip_tags($request->message)));

        $whatsapp = new WhatsappMessage();
        $whatsapp->to = implode(',', $trimmedNumbers);
        $whatsapp->message = $message;
        $whatsapp->save();

        return response()->json('Message sent successfully');
    }

    public function important(Request $request, $id, $flag)
    {
        if ($flag == 1) {
            $whatsapp = WhatsappMessage::find($id);

            $whatsapp->status = FALSE;
            $whatsapp->save();

            return response()->json('Message marked as unimportant');
        } else {
            $whatsapp = WhatsappMessage::find($id);

            $whatsapp->status = true;
            $whatsapp->save();

            return response()->json('Message marked as important');
        }
    }

    public function delete(Request $request, $id)
    {
        $whatsapp = WhatsappMessage::find($id);
        $whatsapp->delete();
        return response()->json(['errorMsg' => 'Message deleted successfully']);
    }

    public function delete_all(Request $request)
    {
        if (!isset($request->whatsapp_id)) {
            return response()->json(['errorMsg' => 'Select messages first']);
        }
        foreach ($request->whatsapp_id as $key => $items) {
            $whatsapp = WhatsappMessage::find($items);
            $whatsapp->delete();
        }
        return response()->json(['errorMsg' => 'Message deleted successfully']);
    }
}

