<?php

namespace App\Http\Controllers;

use App\Models\CashCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class CashCounterController extends Controller
{
    // Cash Counter main page/index page
    public function index(Request $request)
    {
        if (!auth()->user()->can('cash_counters')) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $generalSettings = config('generalSettings');
            $cashCounters = '';
            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $cashCounters = DB::table('cash_counters')->orderBy('id', 'DESC')
                    ->leftJoin('branches', 'cash_counters.branch_id', 'branches.id')
                    ->select(
                        'branches.name as br_name',
                        'branches.branch_code as br_code',
                        'cash_counters.id',
                        'cash_counters.counter_name',
                        'cash_counters.short_name'
                    )->get();
            } else {
                
                $cashCounters = DB::table('cash_counters')->orderBy('id', 'DESC')
                    ->leftJoin('branches', 'cash_counters.branch_id', 'branches.id')
                    ->select(
                        'branches.name as br_name',
                        'branches.branch_code as br_code',
                        'cash_counters.id',
                        'cash_counters.counter_name',
                        'cash_counters.short_name'
                    )->where('branch_id', auth()->user()->branch_id)->get();
            }

            return DataTables::of($cashCounters)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';

                    $html .= '<a href="' . route('settings.cash.counter.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('settings.cash.counter.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('branch', function ($row) use ($generalSettings) {
                    if ($row->br_name) {
                        return $row->br_name . '/' . $row->br_code . '(<b>BR</b>)';
                    } else {
                        return $generalSettings['business__shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->rawColumns(['branch', 'action'])
                ->make(true);
        }
        return view('settings.cash_counter.index');
    }

    public function store(Request $request)
    {
        $generalSettings = config('generalSettings');
        $cash_counter_limit = $generalSettings['addons__cash_counter_limit'];

        $cash_counters = DB::table('cash_counters')
            ->where('branch_id', auth()->user()->branch_id)
            ->count();

        if ($cash_counter_limit <= $cash_counters) {

            return response()->json(["errorMsg" => "Cash counter limit is ${cash_counter_limit}"]);
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
