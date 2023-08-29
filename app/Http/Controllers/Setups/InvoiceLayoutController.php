<?php

namespace App\Http\Controllers\Setups;

use App\Http\Controllers\Controller;
use App\Services\Setups\InvoiceLayoutService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceLayoutController extends Controller
{
    public function __construct(private InvoiceLayoutService $invoiceLayoutService)
    {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('inv_lay')) {
            abort(403, 'Access Forbidden.');
        }
        if ($request->ajax()) {
            return $this->invoiceLayoutService->invoiceLayoutListTable($request);
        }

        return view('setups.invoices.layouts.index');
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

        return response()->json('Successfully invoice layout is created');
    }

    public function edit($id)
    {
        $invoiceLayout = $this->invoiceLayoutService->singleInvoiceLayout(id: $id);

        return view('setups.invoices.layouts.edit', compact('invoiceLayout'));
    }

    public function update(Request $request, $layoutId)
    {
        $this->validate($request, [

            'name' => 'required|unique:invoice_layouts,name,'.$layoutId,
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

        return response()->json('Successfully invoice layout is updated');
    }

    public function delete(Request $request, $id)
    {
        try {

            DB::beginTransaction();

            $this->invoiceLayoutService->deleteInvoiceLayout(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Successfully Invoice layout is deleted');
    }

    public function setDefault($id)
    {
        $this->invoiceLayoutService->setDefaultInvoiceLayout($id);

        return response()->json('Default set successfully');
    }
}
