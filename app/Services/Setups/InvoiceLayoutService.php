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
                $layouts->where('invoice_layout.branch_id', null);
            } else {
                $layouts->where('invoice_layout.branch_id', $request->branch_id);
            }
        }

        return DataTables::of($layouts)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {

                return $row->name.' '.($row->is_default == 1 ? '<span class="badge bg-primary">'.__('Default').'</span>' : '');
            })
            ->editColumn('is_header_less', function ($row) {
                return $row->is_header_less == 1 ? '<span class="badge bg-info">'.__('Yes').'</span>' : '<span class="badge bg-secondary">'.__('No').'</span>';
            })
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                $html .= '<a href="'.route('invoices.layouts.edit', [$row->id]).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';

                if ($row->is_default == 0) {

                    $html .= '<a href="'.route('invoices.layouts.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';

                    $html .= '<a href="'.route('invoices.layouts.set.default', [$row->id]).'" class="bg-primary text-white rounded pe-1" id="set_default_btn">
                    '.__('Set As Default').'
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
        $addLayout->layout_design = isset($request->design) ? $request->design : 1;
        $addLayout->show_shop_logo = isset($request->show_shop_logo) ? 1 : 0;
        $addLayout->show_total_in_word = isset($request->show_total_in_word) ? 1 : 0;
        $addLayout->is_header_less = isset($request->is_header_less) ? 1 : 0;
        $addLayout->gap_from_top = isset($request->is_header_less) ? $request->gap_from_top : null;
        $addLayout->header_text = $request->header_text;
        $addLayout->sub_heading_1 = $request->sub_heading_1;
        $addLayout->sub_heading_2 = $request->sub_heading_2;
        $addLayout->sub_heading_3 = $request->sub_heading_3;
        $addLayout->invoice_heading = isset($request->invoice_heading) ? $request->invoice_heading : 'Invoice';
        $addLayout->quotation_heading = isset($request->quotation_heading) ? $request->quotation_heading : 'Quotation';
        $addLayout->sales_order_heading = isset($request->sales_order_heading) ? $request->sales_order_heading : 'Sales Order';
        $addLayout->challan_heading = isset($request->challan_heading) ? $request->challan_heading : 'Challan';
        $addLayout->branch_city = isset($request->branch_city) ? $request->branch_city : 1;
        $addLayout->branch_state = isset($request->branch_state) ? $request->branch_state : 1;
        $addLayout->branch_zipcode = isset($request->branch_zipcode) ? $request->branch_zipcode : 1;
        $addLayout->branch_phone = isset($request->branch_phone) ? $request->branch_phone : 1;
        $addLayout->branch_alternate_number = isset($request->branch_alternate_number) ? $request->branch_alternate_number : 1;
        $addLayout->branch_email = isset($request->branch_email) ? $request->branch_email : 1;
        $addLayout->product_w_type = isset($request->product_w_type) ? $request->product_w_type : 1;
        $addLayout->product_w_duration = isset($request->product_w_duration) ? $request->product_w_duration : 1;
        $addLayout->product_w_discription = isset($request->product_w_discription) ? $request->product_w_discription : 0;
        $addLayout->product_discount = isset($request->product_discount) ? $request->product_discount : 1;
        $addLayout->product_tax = isset($request->product_tax) ? $request->product_tax : 1;
        $addLayout->customer_address = isset($request->customer_address) ? $request->customer_address : 1;
        $addLayout->customer_tax_no = isset($request->customer_tax_no) ? $request->customer_tax_no : 1;
        $addLayout->customer_phone = isset($request->customer_phone) ? $request->customer_phone : 1;
        $addLayout->bank_name = isset($request->bank_name) ? $request->bank_name : null;
        $addLayout->bank_branch = isset($request->bank_branch) ? $request->bank_branch : null;
        $addLayout->account_name = isset($request->account_name) ? $request->account_name : null;
        $addLayout->account_no = isset($request->account_no) ? $request->account_no : null;
        $addLayout->invoice_notice = isset($request->invoice_notice) ? $request->invoice_notice : null;
        $addLayout->footer_text = isset($request->footer_text) ? $request->footer_text : null;
        $addLayout->save();

        $invoiceLayouts = InvoiceLayout::where('branch_id', auth()->user()->branch_id)->get();

        if (count($invoiceLayouts) == 1) {

            $defaultLayouts = InvoiceLayout::first();
            $defaultLayouts->is_default = 1;
            $defaultLayouts->save();
        }
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
