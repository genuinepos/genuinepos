<?php

namespace App\Services\Setups;

use App\Models\InvoiceLayout;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class InvoiceLayoutService
{
    public function invoiceLayoutListTable($request)
    {
        $layouts = DB::table('invoice_layouts')->orderBy('id', 'DESC')->select('id', 'name', 'is_default', 'is_header_less')->get();

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('invoice_layout.branch_id', NULL);
            } else {

                $query->where('invoice_layout.branch_id', $request->branch_id);
            }
        }

        return DataTables::of($layouts)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {

                return $row->name . ' ' . ($row->is_default == 1 ? '<span class="badge bg-primary">' . __("Default") . '</span>' : '');
            })
            ->editColumn('is_header_less', function ($row) {
                return $row->is_header_less == 1 ? '<span class="badge bg-info">' . __('Yes') . '</span>' : '<span class="badge bg-secondary">' . __('No') . '</span>';
            })
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                $html .= '<a href="' . route('invoices.layouts.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';

                if ($row->is_default == 0) {

                    $html .= '<a href="' . route('invoices.layouts.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';

                    $html .= '<a href="' . route('invoices.layouts.set.default', [$row->id]) . '" class="bg-primary text-white rounded pe-1" id="set_default_btn">
                    ' . __("Set As Default") . '
                    </a>';
                }
                $html .= '</div>';

                return $html;
            })

            ->rawColumns(['action', 'name', 'is_header_less'])
            ->make(true);
    }

    public function addInvoiceLayout(object $request): void
    {
        $addLayout = new InvoiceLayout();
        $addLayout->branch_id = auth()->user()->branch_id;
        $addLayout->name = isset($request->name) ? $request->name : 'Invoice Layout';
        $addLayout->layout_design = $request->design ? $request->design : 1;
        $addLayout->show_shop_logo = $request->show_shop_logo;
        $addLayout->show_total_in_word = $request->show_total_in_word;
        $addLayout->is_header_less = $request->is_header_less ? $request->is_header_less : 0;
        $addLayout->gap_from_top = $request->is_header_less == 1 ? $request->gap_from_top : null;
        $addLayout->header_text = $request->header_text;
        $addLayout->sub_heading_1 = $request->sub_heading_1;
        $addLayout->sub_heading_2 = $request->sub_heading_2;
        $addLayout->sub_heading_3 = $request->sub_heading_3;
        $addLayout->invoice_heading = isset($request->invoice_heading) ? $request->invoice_heading : 'Invoice';
        $addLayout->quotation_heading = isset($request->quotation_heading) ? $request->quotation_heading : 'Quotation';
        $addLayout->sales_order_heading = isset($request->sales_order_heading) ? $request->sales_order_heading : 'Sales Order';
        $addLayout->challan_heading = isset($request->challan_heading) ? $request->challan_heading : 'Challan';
        $addLayout->branch_city = $request->branch_city;
        $addLayout->branch_state = $request->branch_state;
        $addLayout->branch_zipcode =  $request->branch_zipcode;
        $addLayout->branch_phone = $request->branch_phone;
        $addLayout->branch_alternate_number = $request->branch_alternate_number;
        $addLayout->branch_email = $request->branch_email;
        $addLayout->product_w_type = $request->product_w_type;
        $addLayout->product_w_duration = $request->product_w_duration;
        $addLayout->product_w_discription = $request->product_w_discription;
        $addLayout->product_discount = $request->product_discount;
        $addLayout->product_tax = $request->product_tax;
        $addLayout->customer_address = $request->customer_address;
        $addLayout->customer_tax_no = $request->customer_tax_no;
        $addLayout->customer_phone = $request->customer_phone;
        $addLayout->bank_name = isset($request->bank_name) ? $request->bank_name : NULL;
        $addLayout->bank_branch = isset($request->bank_branch) ? $request->bank_branch : NULL;
        $addLayout->account_name = isset($request->account_name) ? $request->account_name : NULL;
        $addLayout->account_no = isset($request->account_no) ? $request->account_no : NULL;
        $addLayout->invoice_notice = isset($request->invoice_notice) ? $request->invoice_notice : NULL;
        $addLayout->footer_text = isset($request->footer_text) ? $request->footer_text : NULL;
        $addLayout->save();

        $invoiceLayouts = InvoiceLayout::where('branch_id', auth()->user()->branch_id)->get();

        if (count($invoiceLayouts) == 1) {

            $defaultLayouts = InvoiceLayout::first();
            $defaultLayouts->is_default = 1;
            $defaultLayouts->save();
        }
    }

    function updateInvoiceLayout(int $id, object $request): void
    {
        // dd($request->all());
        $updateInvoiceLayout = InvoiceLayout::where('id', $id)->first();
        $updateInvoiceLayout->branch_id = auth()->user()->branch_id;
        $updateInvoiceLayout->name = $request->name;
        $updateInvoiceLayout->layout_design = $request->design;
        $updateInvoiceLayout->show_shop_logo = $request->show_shop_logo;
        $updateInvoiceLayout->show_total_in_word = $request->show_total_in_word;
        $updateInvoiceLayout->is_header_less = $request->is_header_less;
        $updateInvoiceLayout->gap_from_top = $request->is_header_less == 1 ? $request->gap_from_top : null;
        $updateInvoiceLayout->header_text = $request->header_text;
        $updateInvoiceLayout->sub_heading_1 = $request->sub_heading_1;
        $updateInvoiceLayout->sub_heading_2 = $request->sub_heading_2;
        $updateInvoiceLayout->sub_heading_3 = $request->sub_heading_3;
        $updateInvoiceLayout->invoice_heading = isset($request->invoice_heading) ? $request->invoice_heading : 'Invoice';
        $updateInvoiceLayout->quotation_heading = isset($request->quotation_heading) ? $request->quotation_heading : 'Quotation';
        $updateInvoiceLayout->sales_order_heading = isset($request->sales_order_heading) ? $request->sales_order_heading : 'Sales Order';
        $updateInvoiceLayout->challan_heading = isset($request->challan_heading) ? $request->challan_heading : 'Challan';
        $updateInvoiceLayout->branch_city =  $request->branch_city;
        $updateInvoiceLayout->branch_state = $request->branch_state;
        $updateInvoiceLayout->branch_zipcode = $request->branch_zipcode;
        $updateInvoiceLayout->branch_phone = $request->branch_phone;
        $updateInvoiceLayout->branch_alternate_number = $request->branch_alternate_number;
        $updateInvoiceLayout->branch_email = $request->branch_email;
        $updateInvoiceLayout->product_w_type = $request->product_w_type;
        $updateInvoiceLayout->product_w_duration = $request->product_w_duration;
        $updateInvoiceLayout->product_w_discription = 0;
        $updateInvoiceLayout->product_discount = $request->product_discount;
        $updateInvoiceLayout->product_tax = $request->product_tax;
        $updateInvoiceLayout->customer_address = $request->customer_address;
        $updateInvoiceLayout->customer_tax_no = $request->customer_tax_no;
        $updateInvoiceLayout->customer_phone = $request->customer_phone;
        $updateInvoiceLayout->bank_name = $request->bank_name;
        $updateInvoiceLayout->bank_branch = isset($request->bank_branch) ? $request->bank_branch : NULL;
        $updateInvoiceLayout->account_name = isset($request->account_name) ? $request->account_name : NULL;
        $updateInvoiceLayout->account_no = isset($request->account_no) ? $request->account_no : NULL;
        $updateInvoiceLayout->invoice_notice = isset($request->invoice_notice) ? $request->invoice_notice : NULL;
        $updateInvoiceLayout->footer_text = isset($request->footer_text) ? $request->footer_text : NULL;
        $updateInvoiceLayout->save();
    }

    function deleteInvoiceLayout(int $id): void
    {
        $deleteInvoice = InvoiceLayout::find($schemaId);

        if (!is_null($deleteInvoice)) {

            $deleteInvoice->delete();
        }
    }

    function setDefaultInvoiceLayout(int $id): void
    {
        $defaultLayout = InvoiceLayout::where('is_default', 1)->first();
        if ($defaultLayout) {

            $defaultLayout->is_default = 0;
            $defaultLayout->save();
        }

        $updateLayout = InvoiceLayout::where('id', $id)->first();
        $updateLayout->is_default = 1;
        $updateLayout->save();
    }

    public function singleInvoiceLayout(int $id, array $with = null)
    {
        $query = InvoiceLayout::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}