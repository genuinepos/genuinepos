<?php

namespace App\Http\Controllers;

use App\Utils\BranchUtil;
use App\Models\Branch;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\InvoiceSchema;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    protected $branchUtil;

    public function __construct(BranchUtil $branchUtil)
    {
        $this->branchUtil = $branchUtil;

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

        return view('settings.branches.index');
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

    public function create()
    {
        $invSchemas = DB::table('invoice_schemas')->select('id', 'name')->get();
        $invLayouts = DB::table('invoice_layouts')->select('id', 'name')->get();

        $roles = DB::table('roles')->select('id', 'name')->get();

        return view('settings.branches.ajax_view.create', compact('invSchemas', 'invLayouts', 'roles'));
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

        if ($request->add_opening_user) {
            $this->validate($request, [
                'first_name' => 'required',
                'user_phone' => 'required',
                'role_id' => 'required',
                'username' => 'required|unique:admin_and_users,username',
                'password' => 'required|confirmed',
            ]);
        }

        $branchLogoName = '';
        if ($request->hasFile('logo')) {
            $branchLogo = $request->file('logo');
            $branchLogoName = uniqid() . '-' . '.' . $branchLogo->getClientOriginalExtension();
            $branchLogo->move(public_path('uploads/branch_logo/'), $branchLogoName);
        }

        $addBranchGetId = Branch::insertGetId([
            'name' => $request->name,
            'branch_code' => $request->code,
            'phone' => $request->phone,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'alternate_phone_number' => $request->alternate_phone_number,
            'email' => $request->email,
            'website' => $request->website,
            'purchase_permission' => $request->purchase_permission ? $request->purchase_permission : 0,
            'invoice_schema_id' => $request->invoice_schema_id,
            'add_sale_invoice_layout_id' => $request->add_sale_invoice_layout_id,
            'pos_sale_invoice_layout_id' => $request->pos_sale_invoice_layout_id,
            'logo' => $branchLogoName ? $branchLogoName : 'default.png',
        ]);

        $this->branchUtil->addBranchDefaultAccounts($addBranchGetId);

        if ($request->add_opening_user) {
            $this->branchUtil->addBranchOpeningUser($request, $addBranchGetId);
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

    public function getAllAccounts()
    {
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number')->get();
        return response()->json($accounts);
    }

    public function quickInvoiceSchemaModal()
    {
        return view('settings.branches.ajax_view.add_quick_invoice_schema');
    }

    public function quickInvoiceSchemaStore(Request $request)
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
