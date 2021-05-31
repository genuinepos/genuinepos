<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerGroup;

class CustomerGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }
    
    // Customer main page/index page
    public function index()
    {
        return view('contacts.customer_group.index');
    }

    // Get all customer group by ajax
    public function allBanks()
    {
        $groups = CustomerGroup::orderBy('id', 'DESC')->get();
        return view('contacts.customer_group.ajax_view.group_list', compact('groups'));
    }

    // Store customer group
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        CustomerGroup::insert([
            'group_name' => $request->name,
            'calc_percentage' => $request->calculation_percent ? $request->calculation_percent : 0.00,
        ]);
        return response()->json('Successfully customer group is added');
    }

    // Update customer group
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $updateBank = CustomerGroup::where('id', $request->id)->first();
        $updateBank->update([
            'group_name' => $request->name,
            'calc_percentage' => $request->calculation_percent ? $request->calculation_percent : 0.00,
        ]);
        return response()->json('Successfully customer group is updated');
    }

    // delete customer group
    public function delete(Request $request, $groupId)
    {
        $deleteCustomerGroup = CustomerGroup::find($groupId);
        if (!is_null($deleteCustomerGroup)) {
            $deleteCustomerGroup->delete();  
        }
        return response()->json('Successfully customer group is deleted');
    }
}
