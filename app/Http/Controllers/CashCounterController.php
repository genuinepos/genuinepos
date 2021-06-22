<?php

namespace App\Http\Controllers;

use App\Models\CashCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CashCounterController extends Controller
{
     // Cash Counter main page/index page
     public function index(Request $request)
     {
         if ($request->ajax()) {
             $cashCounters = DB::table('cash_counters')->orderBy('id', 'DESC')->get(['id', 'counter_name', 'short_name']);
            return DataTables::of($cashCounters)
             ->addIndexColumn()
             ->addColumn('action', function($row) {
                 // return $action_btn;
                 $html = '<div class="dropdown table-dropdown">';
                 
                $html .= '<a href="' . route('settings.cash.counter.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                $html .= '<a href="' . route('settings.cash.counter.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                 $html .= '</div>';
                 return $html;
             })
             ->rawColumns(['action'])
             ->make(true);
         }
         return view('settings.cash_counter.index');
     }

     public function store(Request $request)
     {
        $this->validate($request, [
            'counter_name' => 'required|unique:cash_counters,counter_name',
            'short_name' => 'required|unique:cash_counters,short_name',
        ]);

        CashCounter::insert([
            'counter_name' => $request->counter_name,
            'short_name' => $request->short_name,
        ]);

        return response()->json('Cash counter created Successfully.');
     }

     public function edit($id)
     {
        $cc = DB::table('cash_counters')->where('id', $id)->orderBy('id', 'DESC')->first(['id', 'counter_name', 'short_name']);
        return view('settings.cash_counter.ajax_view.edit_cash_counter', compact('cc'));
     }

     public function update(Request $request, $id)
     {
        
        $this->validate($request, [
            'counter_name' => 'required|unique:cash_counters,counter_name,'.$id,
            'short_name' => 'required|unique:cash_counters,short_name,'.$id,
        ]);
       
        $updateCC = CashCounter::where('id', $id)->first();
        $updateCC->update([
            'counter_name' => $request->counter_name,
            'short_name' => $request->short_name,
        ]);

        return response()->json('Cash counter updated Successfully.');
     }

    public function delete(Request $request, $id)
    {
        $delete = CashCounter::find($id);
        if (!is_null($delete)) {
            $delete->delete();
        }

        return response()->json('Cash counter deleted Successfully.');
    }
}
