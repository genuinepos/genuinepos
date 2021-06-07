<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AccountTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }
    
    // Account type main page/index page
    public function index()
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        return view('accounting.types.index');
    }

    // Get all Account type by ajax
    public function allTypes()
    {
        $types = AccountType::all();
        return view('accounting.types.ajax_view.type_list', compact('types'));
    }

    // Store Account type
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        AccountType::insert([
            'name' => $request->name,
            'remark' => $request->remark,
        ]);
        
        return response()->json('Successfully account type is added');
    }

    // Update Account type
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $updateAccountType = AccountType::where('id', $request->id)->first();
        $updateAccountType->update([
            'name' => $request->name,
            'remark' => $request->remark,
        ]);
        
        return response()->json('Successfully account type is updated');
    }

    public function delete(Request $request, $typeId)
    {
        $deleteAccountType = AccountType::find($typeId);
        if (!is_null($deleteAccountType)) {
            $deleteAccountType->delete();  
        }
        return response()->json('Successfully account type is deleted');
    }

    public function changeStatus($typeId)
    {
        $statusChange = AccountType::where('id', $typeId)->first();
        if ($statusChange->status == 1) {
            $statusChange->status = 0;
            $statusChange->save();
            return response()->json('Successfully account type is deactivated');
        } else {
            $statusChange->status = 1;
            $statusChange->save();
            return response()->json('Successfully account type is activated');
        }
    }
}
