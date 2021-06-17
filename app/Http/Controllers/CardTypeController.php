<?php

namespace App\Http\Controllers;

use App\Models\CardType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CardTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $cardTypes = DB::table('card_types')
            ->leftJoin('accounts', 'card_types.account_id', 'accounts.id')
            ->select('card_types.*', 'accounts.name as ac_name', 'accounts.account_number')
            ->orderBy('id', 'desc')
            ->get();
            return DataTables::of($cardTypes)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('settings.payment.card.types.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('settings.payment.card.types.delete', [$row->id]) . '" class="action-btn c-delete" id="delete_card_type" title="Delete"><span class="fas fa-trash"></span></a>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('account', function($row) {
                return $row->ac_name.' (A/C'.$row->account_number.')';
            })
            ->rawColumns(['action', 'account'])
            ->make(true);
        }
       $accounts = DB::table('accounts')->get(['id', 'name', 'account_number']);
       return view('settings.payment_settings.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'card_type_name' => 'required|unique:card_types,card_type_name',
        ]);

        CardType::insert([
            'card_type_name' => $request->card_type_name,
            'account_id' => $request->account_id,
        ]);

        return response()->json('Card type created successfully.');
    }

    public function edit($cardTypeId)
    {
        $cardType = DB::table('card_types')->where('id', $cardTypeId)->first();
        $accounts = DB::table('accounts')->get(['id', 'name', 'account_number']);
        return view('settings.payment_settings.ajax_view.edit_card_type', compact('cardType', 'accounts'));
    }

    public function update(Request $request, $cardTypeId)
    {
        $this->validate($request, [
            'card_type_name' => 'required|unique:card_types,card_type_name,'.$cardTypeId,
        ]);

        $updateCardType = CardType::where('id', $cardTypeId)->first();
        $updateCardType->update([
            'card_type_name' => $request->card_type_name,
            'account_id' => $request->account_id,
        ]);

        return response()->json('Card type update successfully.');
    }

    public function delete(Request $request, $cardTypeId)
    {
        $deleteCardType = CardType::where('id', $cardTypeId)->first();
        if (!is_null($deleteCardType)) {
            $deleteCardType->delete();  
        }
        return response()->json('Card type deleted successfully.');
    }
}
