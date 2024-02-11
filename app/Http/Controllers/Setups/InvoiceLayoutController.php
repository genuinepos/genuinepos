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
        $this->middleware('expireDate');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('invoice_layouts_index'), 403);

        if ($request->ajax()) {
            return $this->invoiceLayoutService->invoiceLayoutListTable($request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('setups.invoices.layouts.index', compact('branches'));
    }

    public function create()
    {
        abort_if(!auth()->user()->can('invoice_layouts_add'), 403);
        return view('setups.invoices.layouts.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('invoice_layouts_add'), 403);

        $this->invoiceLayoutService->invoiceLayoutValidation(request: $request);

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
        abort_if(!auth()->user()->can('invoice_layouts_edit'), 403);

        $invoiceLayout = $this->invoiceLayoutService->singleInvoiceLayout(id: $id);

        return view('setups.invoices.layouts.edit', compact('invoiceLayout'));
    }

    public function update($layoutId, Request $request)
    {
        abort_if(!auth()->user()->can('invoice_layouts_edit'), 403);

        $this->invoiceLayoutService->invoiceLayoutUpdateValidation(request: $request, id: $layoutId);

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
        abort_if(!auth()->user()->can('invoice_layouts_delete'), 403);

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

        return response()->json(__('Invoice layout is deleted successfully'));
    }
}
