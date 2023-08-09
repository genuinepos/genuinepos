<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerGroupController extends Controller
{
    public function __construct()
    {

    }

    // Customer main page/index page
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customerGroup = DB::table('customer_groups')->orderBy('group_name', 'asc')->get();

            return DataTables::of($customerGroup)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="'.route('contacts.customers.groups.edit', [$row->id]).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('customers.groups.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('contacts.customer_group.index');
    }

    // Get all customer group by ajax
    public function edit($id)
    {
        $groups = CustomerGroup::find($id);

        return view('contacts.customer_group.ajax_view.edit', compact('groups'));
    }

    // public function allBanks()
    // {
    //     $groups = CustomerGroup::orderBy('id', 'DESC')->get();
    //     return view('contacts.customer_group.ajax_view.group_list', compact('groups'));
    // }

    // Store customer group
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:customer_groups,group_name',
        ]);

        CustomerGroup::insert([
            'group_name' => $request->name,
            'calc_percentage' => $request->calculation_percent ? $request->calculation_percent : 0.00,
        ]);

        return response()->json('Customer group created successfully');
    }

    // Update customer group
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:customer_groups,group_name',
        ]);

        $updateBank = CustomerGroup::find($id);
        // $updateBank = CustomerGroup::where('id', $request->id)->first();
        $updateBank->update([
            'group_name' => $request->name,
            'calc_percentage' => $request->calculation_percent ? $request->calculation_percent : 0.00,
        ]);

        return response()->json('Customer group updated successfully');
    }

    // delete customer group
    public function delete(Request $request, $id)
    {
        $deleteCustomerGroup = CustomerGroup::find($id);
        if (! is_null($deleteCustomerGroup)) {
            $deleteCustomerGroup->delete();
        }

        return response()->json('Customer group deleted successfully');
    }
}
