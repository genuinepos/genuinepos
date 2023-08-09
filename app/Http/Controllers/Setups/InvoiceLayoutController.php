<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use App\Models\InvoiceLayout;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Setups\InvoiceLayoutService;

class InvoiceLayoutController extends Controller
{
    public function __construct(private InvoiceLayoutService $invoiceLayoutService)
    {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('inv_lay')) {

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
            'name' => 'required|unique:invoice_layouts,name,' . $layoutId,
        ]);

        if (isset($request->is_header_less)) {

            $this->validate($request, [
                'gap_from_top' => 'required',
            ]);
        }

        $updateLayout = InvoiceLayout::where('id', $layoutId)->first();
        $updateLayout->name = $request->name;
        $updateLayout->layout_design = $request->design;
        $updateLayout->show_shop_logo = isset($request->show_shop_logo) ? 1 : 0;
        $updateLayout->show_seller_info = isset($request->show_seller_info) ? 1 : 0;
        $updateLayout->show_total_in_word = isset($request->show_total_in_word) ? 1 : 0;
        $updateLayout->is_header_less = isset($request->is_header_less) ? 1 : 0;
        $updateLayout->gap_from_top = isset($request->is_header_less) ? $request->gap_from_top : null;
        $updateLayout->header_text = $request->header_text;
        $updateLayout->sub_heading_1 = $request->sub_heading_1;
        $updateLayout->sub_heading_2 = $request->sub_heading_2;
        $updateLayout->sub_heading_3 = $request->sub_heading_3;
        $updateLayout->invoice_heading = $request->invoice_heading;
        $updateLayout->quotation_heading = $request->quotation_heading;
        $updateLayout->draft_heading = $request->draft_heading;
        $updateLayout->challan_heading = $request->challan_heading;
        $updateLayout->branch_landmark = isset($request->branch_landmark) ? 1 : 0;
        $updateLayout->branch_city = isset($request->branch_city) ? 1 : 0;
        $updateLayout->branch_state = isset($request->branch_state) ? 1 : 0;
        $updateLayout->branch_zipcode = isset($request->branch_zipcode) ? 1 : 0;
        $updateLayout->branch_phone = isset($request->branch_phone) ? 1 : 0;
        $updateLayout->branch_alternate_number = isset($request->branch_alternate_number) ? 1 : 0;
        $updateLayout->branch_email = isset($request->branch_email) ? 1 : 0;
        $updateLayout->product_img = isset($request->product_img) ? 1 : 0;
        $updateLayout->product_cate = isset($request->product_cate) ? 1 : 0;
        $updateLayout->product_brand = isset($request->product_brand) ? 1 : 0;
        $updateLayout->product_imei = isset($request->product_imei) ? 1 : 0;
        $updateLayout->product_w_type = isset($request->product_w_type) ? 1 : 0;
        $updateLayout->product_w_duration = isset($request->product_w_duration) ? 1 : 0;
        $updateLayout->product_w_discription = isset($request->product_w_discription) ? 1 : 0;
        $updateLayout->product_discount = isset($request->product_discount) ? 1 : 0;
        $updateLayout->product_tax = isset($request->product_tax) ? 1 : 0;
        $updateLayout->product_price_inc_tax = isset($request->product_price_inc_tax) ? 1 : 0;
        $updateLayout->product_price_exc_tax = isset($request->product_price_exc_tax) ? 1 : 0;
        $updateLayout->customer_name = isset($request->customer_name) ? 1 : 0;
        $updateLayout->customer_address = isset($request->customer_address) ? 1 : 0;
        $updateLayout->customer_tax_no = isset($request->customer_tax_no) ? 1 : 0;
        $updateLayout->customer_phone = isset($request->customer_phone) ? 1 : 0;
        $updateLayout->bank_name = $request->bank_name;
        $updateLayout->bank_branch = $request->bank_branch;
        $updateLayout->account_name = $request->account_name;
        $updateLayout->account_no = $request->account_no;
        $updateLayout->invoice_notice = $request->invoice_notice;
        $updateLayout->footer_text = $request->footer_text;
        $updateLayout->save();

        return response()->json('Successfully invoice layout is updated');
    }

    public function delete(Request $request, $schemaId)
    {
        $deleteInvoice = InvoiceLayout::find($schemaId);

        if (!is_null($deleteInvoice)) {

            $deleteInvoice->delete();
        }

        return response()->json('Successfully Invoice layout is deleted');
    }

    public function setDefault($schemaId)
    {
        $defaultLayout = InvoiceLayout::where('is_default', 1)->first();
        if ($defaultLayout) {

            $defaultLayout->is_default = 0;
            $defaultLayout->save();
        }

        $updateLayout = InvoiceLayout::where('id', $schemaId)->first();
        $updateLayout->is_default = 1;
        $updateLayout->save();

        return response()->json('Default set successfully');
    }
}
