<?php

namespace App\Http\Controllers\Setups;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Setups\CashCounterService;
use App\Services\Setups\InvoiceLayoutService;

class BranchController extends Controller
{
    public function __construct(
        private BranchService $branchService,
        private InvoiceLayoutService $invoiceLayoutService,
        private CashCounterService $cashCounterService
    ) {
    }

    public function index()
    {
        $generalSettings = config('generalSettings');

        if (! auth()->user()->can('branch')) {

            abort(403, 'Access Forbidden.');
        }

        return view('setups.branches.index');
    }

    public function getAllBranch()
    {
        $generalSettings = config('generalSettings');

        $branches = '';
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $branches = Branch::all();
        } else {

            $branches = Branch::where('id', auth()->user()->branch_id)->get();
        }

        return view('settings.branches.ajax_view.branch_list', compact('branches'));
    }

    public function create()
    {
        $roles = DB::table('roles')->select('id', 'name')->get();
        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('setups.branches.ajax_view.create', compact('branches', 'roles'));
    }

    public function store(Request $request)
    {
        $generalSettings = config('generalSettings');
        $branchLimit = $generalSettings['addons__branch_limit'];
        $branchCount = DB::table('branches')->count();

        if ($branchLimit <= $branchCount) {

            return response()->json(['errorMsg' => "Shop limit is ${branch_limit}"]);
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
                'username' => 'required|unique:users,username',
                'password' => 'required|confirmed',
            ]);
        }

        $addBranch = $this->branchService->addBranch($request);

        $this->branchService->addBranchDefaultAccountGroups($addBranch->id);

        $this->branchService->addBranchDefaultAccounts($addBranch->id);

        $this->cashCounterService->addCashCounter(branchId: $addBranch->id, cashCounterName: 'Cash Counter 1', shortName: 'CN1');

        $addInvoiceLayout = $this->invoiceLayoutService->addInvoiceLayout(request: $request, branchId: $addBranch->id, defaultName: 'Default Invoice Layout');

        if ($request->add_opening_user) {

            $this->branchUtil->addBranchOpeningUser($request, $addBranch->id);
        }

        return response()->json('Shop created successfully');
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
        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__branches'] == 0) {
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

                if (file_exists(public_path('uploads/branch_logo/'.$updateBranch->logo))) {

                    unlink(public_path('uploads/branch_logo/'.$updateBranch->logo));
                }
            }

            $branchLogo = $request->file('logo');
            $branchLogoName = uniqid().'-'.'.'.$branchLogo->getClientOriginalExtension();
            $branchLogo->move(public_path('uploads/branch_logo/'), $branchLogoName);
            $updateBranch->logo = $branchLogoName;
        }

        $updateBranch->save();

        return response()->json('Shop updated successfully');
    }

    public function delete(Request $request, $id)
    {
        $generalSettings = config('generalSettings');

        if ($generalSettings['addons__branches'] == 0) {

            abort(403, 'Access Forbidden.');
        }

        $deleteBranch = Branch::with(['sales', 'purchases'])->where('id', $id)->first();

        if (count($deleteBranch->sales) > 0) {

            return response()->json('Can not delete this business location. This location has one or more sales.');
        }

        if (count($deleteBranch->purchases) > 0) {

            return response()->json('Can not delete this business location. This location has one or more purchases.');
        }

        if ($deleteBranch->logo != 'default.png') {

            if (file_exists(public_path('uploads/branch_logo/'.$deleteBranch->logo))) {

                unlink(public_path('uploads/branch_logo/'.$deleteBranch->logo));
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
