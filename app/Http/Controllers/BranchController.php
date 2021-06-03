<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        return view('settings.branches.index');
    }

    public function getAllBranch()
    {
        $branches = Branch::all();
        return view('settings.branches.ajax_view.branch_list', compact('branches'));
    }

    public function store(Request $request)
    {
        //return $request->all();
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'logo' => 'sometimes|image|max:2048',
        ]);

        $addBranch = new Branch();
        $addBranch->name = $request->name;
        $addBranch->branch_code = $request->code;
        $addBranch->phone = $request->phone;
        $addBranch->city = $request->city;
        $addBranch->state = $request->state;
        $addBranch->zip_code = $request->zip_code;
        $addBranch->country = $request->country;
        $addBranch->alternate_phone_number = $request->alternate_phone_number;
        $addBranch->email = $request->email;
        $addBranch->website = $request->website;
        $addBranch->purchase_permission = $request->purchase_permission ? $request->purchase_permission : 0;
        $addBranch->invoice_schema_id = $request->invoice_schema_id;
        $addBranch->add_sale_invoice_layout_id = $request->add_sale_invoice_layout_id;
        $addBranch->pos_sale_invoice_layout_id = $request->pos_sale_invoice_layout_id;
        $addBranch->default_account_id = $request->default_account_id;
        if ($request->hasFile('logo')) {
            $branchLogo = $request->file('logo');
            $branchLogoName = uniqid() . '-' . '.' . $branchLogo->getClientOriginalExtension();
            $branchLogo->move(public_path('uploads/branch_logo/'), $branchLogoName);
            $addBranch->logo = $branchLogoName;
        }
        $addBranch->save();
        return response()->json('Branch created successfully');
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'logo' => 'sometimes|image|max:2048',
        ]);

        $updateBranch = Branch::where('id', $request->id)->first();
        $updateBranch->name = $request->name;
        $updateBranch->branch_code = $request->code;
        $updateBranch->phone = $request->phone;
        $updateBranch->city = $request->city;
        $updateBranch->state = $request->state;
        $updateBranch->zip_code = $request->zip_code;
        $updateBranch->country = $request->country;
        $updateBranch->alternate_phone_number = $request->alternate_phone_number;
        $updateBranch->email = $request->email;
        $updateBranch->website = $request->website;
        $updateBranch->purchase_permission = $request->purchase_permission ? $request->purchase_permission : 0;
        $updateBranch->invoice_schema_id = $request->invoice_schema_id;
        $updateBranch->add_sale_invoice_layout_id = $request->add_sale_invoice_layout_id;
        $updateBranch->pos_sale_invoice_layout_id = $request->pos_sale_invoice_layout_id;
        $updateBranch->default_account_id = $request->default_account_id;
        if ($request->hasFile('logo')) {
            if ($updateBranch->logo != 'default.png') {
                if (file_exists(public_path('uploads/branch_logo/' . $updateBranch->logo))) {
                    unlink(public_path('uploads/branch_logo/' . $updateBranch->logo));
                }
            }

            $branchLogo = $request->file('logo');
            $branchLogoName = uniqid() . '-' . '.' . $branchLogo->getClientOriginalExtension();
            $branchLogo->move(public_path('uploads/branch_logo/'), $branchLogoName);
            $updateBranch->logo = $branchLogoName;
        }
        
        $updateBranch->save();
        return response()->json('Branch updated successfully');
    }

    public function delete(Request $request)
    {
        $updateBranch = Branch::where('id', $request->deleteId)->first();
        if ($updateBranch->is_main_branch == 1) {
            return response()->json(['errorMsg' => 'You can not delete main branch.']); 
        }else{
            $updateBranch->delete();
            return response()->json('Branch deleted successfully');
        }
    }

    public function allSchemas()
    {
        $schemas = DB::table('invoice_schemas')->get();
        return response()->json($schemas);
    }

    public function allLayouts()
    {
        $layouts = DB::table('invoice_layouts')->select('id', 'name')->get();
        return response()->json($layouts);
    }

    public function getAllAccounts()
    {
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number')->get();
        return response()->json($accounts);
    }
}
