<?php

namespace App\Http\Controllers\Setups;

use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Setups\InvoiceLayoutService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceLayoutController extends Controller
{
    public function __construct(private InvoiceLayoutService $invoiceLayoutService, private BranchService $branchService)
    {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('invoice_layout'), 403);

        if ($request->ajax()) {
            return $this->invoiceLayoutService->invoiceLayoutListTable($request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('setups.invoices.layouts.index', compact('branches'));
    }

    public function create()
    {
        return view('setups.invoices.layouts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:invoice_layouts,name',
            'invoice_heading' => 'required',
            'quotation_heading' => 'required',
            'sales_order_heading' => 'required',
            'challan_heading' => 'required',
        ]);

        if ($request->is_header_less == 1) {

            $this->validate($request, [
                'gap_from_top' => 'required',
            ]);
        }

        try {
            DB::beginTransaction();

            $this->invoiceLayoutService->addInvoiceLayout($request);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Invoice layout is created successfully'));
    }

    public function edit($id)
    {
        $invoiceLayout = $this->invoiceLayoutService->singleInvoiceLayout(id: $id);

        return view('setups.invoices.layouts.edit', compact('invoiceLayout'));
    }

    public function update(Request $request, $layoutId)
    {
        $this->validate($request, [

            'name' => 'required|unique:invoice_layouts,name,' . $layoutId,
            'invoice_heading' => 'required',
            'quotation_heading' => 'required',
            'sales_order_heading' => 'required',
            'challan_heading' => 'required',
        ]);

        if ($request->is_header_less == 1) {

            $this->validate($request, [

                'gap_from_top' => 'required',
            ]);
        }

        try {

            DB::beginTransaction();

            $this->invoiceLayoutService->updateInvoiceLayout(id: $layoutId, request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Invoice layout is updated successfully.'));
    }

    public function delete(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $deleteInvoiceLayout = $this->invoiceLayoutService->deleteInvoiceLayout(id: $id);

            if ($deleteInvoiceLayout['pass'] == false) {

                return response()->json(['errorMsg' => $deleteInvoiceLayout['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        // return response()->json(__('Invoice layout is deleted successfully'));
    }
}
