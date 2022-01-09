<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\InvoiceSchema;
use App\Utils\Util;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    protected $util;
    public function __construct(Util $util)
    {
        $this->util = $util;
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        $addons = DB::table('addons')->select('branches')->first();
        if ($addons->branches == 0) {
            abort(403, 'Access Forbidden.');
        }

        if (auth()->user()->permission->setup['branch'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $accounts = DB::table('accounts')->select('id', 'name', 'account_number')->get();
        $invSchemas = DB::table('invoice_schemas')->select('id', 'name')->get();
        $invLayouts = DB::table('invoice_layouts')->select('id', 'name')->get();
        return view('settings.branches.index', compact('accounts', 'invSchemas', 'invLayouts'));
    }

    public function getAllBranch()
    {
        $addons = DB::table('addons')->select('branches')->first();
        if ($addons->branches == 0) {
            abort(403, 'Access Forbidden.');
        }

        $branches = '';
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 1) {
            $branches = Branch::all();
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get();
        }
        return view('settings.branches.ajax_view.branch_list', compact('branches'));
    }

    public function store(Request $request)
    {
        $addons = DB::table('addons')->select('branches')->first();
        if ($addons->branches == 0) {
            abort(403, 'Access Forbidden.');
        }

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

        $allAccountTypes = $this->util->creatableDefaultAccount();
        foreach ($allAccountTypes as $type => $name) {
            $addAccount = new Account();
            $addAccount->name = $name;
            $addAccount->account_type = $type;
            $addAccount->branch_id = $addBranch->id;
            $addAccount->save();
        }

        return response()->json('Business Location created successfully');
    }

    public function edit($branchId)
    {
        $branch = DB::table('branches')->where('id', $branchId)->first();
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number')->get();
        $invSchemas = DB::table('invoice_schemas')->select('id', 'name')->get();
        $invLayouts = DB::table('invoice_layouts')->select('id', 'name')->get();
        return view('settings.branches.ajax_view.edit', compact('branch', 'accounts', 'invSchemas', 'invLayouts'));
    }

    public function update(Request $request, $branchId)
    {
        $addons = DB::table('addons')->select('branches')->first();
        if ($addons->branches == 0) {
            abort(403, 'Access Forbidden.');
        }

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

        $updateBranch = Branch::where('id', $branchId)->first();
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
        return response()->json('Business location updated successfully');
    }

    public function delete(Request $request, $id)
    {
        $addons = DB::table('addons')->select('branches')->first();
        if ($addons->branches == 0) {
            abort(403, 'Access Forbidden.');
        }

        $deleteBranch = Branch::where('id', $id)->first();
        if ($deleteBranch->logo != 'default.png') {
            if (file_exists(public_path('uploads/branch_logo/' . $deleteBranch->logo))) {
                unlink(public_path('uploads/branch_logo/' . $deleteBranch->logo));
            }
        }

        $deleteBranch->delete();
        return response()->json('Business location deleted successfully');
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

    public function quickInvoiceSchema(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:invoice_schemas,name',
            'prefix' => 'required',
        ]);

        $addSchema = new InvoiceSchema();
        $addSchema->name = $request->name;
        $addSchema->format = $request->format;
        $addSchema->prefix = $request->prefix;
        $addSchema->start_from = $request->start_from;
        $addSchema->save();

        $invoiceSchemas = DB::table('invoice_schemas')->get();
        if (count($invoiceSchemas) == 1) {
            $defaultSchema = InvoiceSchema::first();
            $defaultSchema->is_default = 1;
            $defaultSchema->save();
        }

        return response()->json($addSchema);
    }
}
