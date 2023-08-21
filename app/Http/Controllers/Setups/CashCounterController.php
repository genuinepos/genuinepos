<?php

namespace App\Http\Controllers\Setups;

use App\Models\CashCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class CashCounterController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('cash_counters')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            
        }

        return view('setups.cash_counter.index');
    }

    public function store(Request $request)
    {
        $generalSettings = config('generalSettings');
        $cash_counter_limit = $generalSettings['addons__cash_counter_limit'];

        $cash_counters = DB::table('cash_counters')
            ->where('branch_id', auth()->user()->branch_id)
            ->count();

        if ($cash_counter_limit <= $cash_counters) {

            return response()->json(['errorMsg' => "Cash counter limit is ${cash_counter_limit}"]);
        }

        $this->validate($request, [
            // 'counter_name' => 'required|unique:cash_counters,counter_name',
            'counter_name' => ['required', Rule::unique('cash_counters')->where(function ($query) {
                return $query->where('branch_id', auth()->user()->branch_id);
            })],
            // 'short_name' => 'required|unique:cash_counters,short_name',
            'short_name' => ['required', Rule::unique('cash_counters')->where(function ($query) {
                return $query->where('branch_id', auth()->user()->branch_id);
            })],
        ]);

        CashCounter::insert([
            'branch_id' => auth()->user()->branch_id,
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
            'counter_name' => 'required|unique:cash_counters,counter_name,' . $id,
            'short_name' => 'required|unique:cash_counters,short_name,' . $id,
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
