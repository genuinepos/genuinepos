<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }
    
    // Bank main page/index page
    public function index()
    {
        return view('accounting.banks.index');
    }

    // Get all banks by ajax
    public function allBanks()
    {
        $banks = Bank::orderBy('id', 'DESC')->get();
        return view('accounting.banks.ajax_view.bank_list', compact('banks'));
    }

    // Store bank
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'branch_name' => 'required',
        ]);

        Bank::insert([
            'name' => $request->name,
            'branch_name' => $request->branch_name,
            'address' => $request->address,
        ]);
        
        return response()->json('Bank created successfully');
    }

    // Update bank
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'branch_name' => 'required',
        ]);

        $updateBank = Bank::where('id', $request->id)->first();
        $updateBank->update([
            'name' => $request->name,
            'branch_name' => $request->branch_name,
            'address' => $request->address,
        ]);
        
        return response()->json('Bank updated successfully');
    }

    public function delete(Request $request, $bankId)
    {
        $deleteBank = Bank::find($bankId);
        if (!is_null($deleteBank)) {
            $deleteBank->delete();  
        }
 
        return response()->json('Bank deleted successfully');
    }
}
