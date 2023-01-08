<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BankController extends Controller
{
    protected $userActivityLogUtil;
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
    }

    // Bank main page/index page
    public function index(Request $request)
    {
        if (!auth()->user()->can('ac_access')) {
            abort(403, 'Access Forbidden.');
        }
        if ($request->ajax()) {
            $bank = DB::table('banks')->orderBy('name', 'asc')->get();
            return DataTables::of($bank)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="' . route('accounting.banks.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                        $html .= '<a href="' . route('accounting.banks.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('accounting.banks.index');
    }

    public function edit($id)
    {
        $banks = Bank::find($id);
        return view('accounting.banks.ajax_view.edit', compact('banks'));
    }

    // Store bank
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'name' => 'required|unique:banks,name',
                'branch_name' => 'required',
            ]
        );

        $addBank = Bank::create([
            'name' => $request->name,
            'branch_name' => $request->branch_name,
            'address' => $request->address,
        ]);

        $this->userActivityLogUtil->addLog(
            action: 1,
            subject_type: 16,
            data_obj: $addBank
        );

        return response()->json('Bank created successfully');
    }

    // Update bank
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:banks,name,'.$id,
            'branch_name' => 'required',
        ]);
        $updateBank = Bank::where('id', $id)->update([
            'name' => $request->name,
            'branch_name' => $request->branch_name,
            'address' => $request->address,
        ]);

        $this->userActivityLogUtil->addLog(
            action: 2,
            subject_type: 16,
            data_obj: $updateBank
        );
        return response()->json('Bank updated successfully');
    }

    public function delete(Request $request, $id)
    {
        $deleteBank = Bank::find($id);
        if (!is_null($deleteBank)) {

            if(count($deleteBank->accounts) > 0) {

                return response()->json('Can not be deleted, This bank has one or more account.');
            }

            $this->userActivityLogUtil->addLog(
                action: 3,
                subject_type: 16,
                data_obj: $deleteBank
            );
            $deleteBank->delete();
        }
        return response()->json('Bank deleted successfully');
    }
}
