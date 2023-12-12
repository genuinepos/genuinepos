<?php

namespace App\Services\Setups;

use App\Models\Setups\InvoiceLayout;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InvoiceLayoutService
{
    public function invoiceLayoutListTable($request)
    {
        $generalSettings = config('generalSettings');
        $layouts = '';

        $query = DB::table('invoice_layouts')
            ->leftJoin('branches', 'invoice_layouts.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('invoice_layouts.branch_id', null);
            } else {

                $query->where('invoice_layouts.branch_id', $request->branch_id);
            }
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('invoice_layouts.branch_id', auth()->user()->branch_id);
        }

        $layouts = $query->select(
            'invoice_layouts.id',
            'invoice_layouts.name',
            'invoice_layouts.is_default',
            'invoice_layouts.is_header_less',
            'branches.name as branch_name',
            'branches.parent_branch_id',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
        )->orderBy('id', 'asc')->get();

        return DataTables::of($layouts)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {

                return $row->name.' '.($row->is_default == 1 ? '<span class="badge bg-primary">'.__('Default').'</span>' : '');
            })
            ->editColumn('is_header_less', function ($row) {

                return $row->is_header_less == 1 ? '<span class="badge bg-info">'.__('Yes').'</span>' : '<span class="badge bg-secondary">'.__('No').'</span>';
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->parent_branch_id) {

                    return __('Chain Shop Of').'  <span class="badge badge-sm bg-success">'.$row->parent_branch_name.'</span>-('.$row->branch_code.')';
                } else {

                    if ($row->branch_name) {

                        return $row->branch_name.'-('.$row->branch_code.')';
                    } else {

                        return $generalSettings['business__business_name'].'(<b>'.__('Business').'</b>)';
                    }
                }
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
            ->rawColumns(['action', 'name', 'branch', 'is_header_less'])
            ->make(true);
    }

    public function addInvoiceLayout(object $request, $branchId = null, $defaultName = null): object
    {
        $addLayout = new InvoiceLayout();
        $addLayout->branch_id = $branchId ? $branchId : auth()->user()->branch_id;
        $addLayout->name = $defaultName ? $defaultName : $request->name;
        $addLayout->layout_design = $request->design ? $request->design : 1;
        $addLayout->show_shop_logo = $request->show_shop_logo ? $request->show_shop_logo : 1;
        $addLayout->show_total_in_word = $request->show_total_in_word ? $request->show_total_in_word : 1;
        $addLayout->is_header_less = $request->is_header_less ? $request->is_header_less : 0;
        $addLayout->gap_from_top = $request->is_header_less == 1 ? $request->gap_from_top : null;
        $addLayout->header_text = $request->header_text ? $request->header_text : '';
        $addLayout->sub_heading_1 = $request->sub_heading_1 ? $request->sub_heading_1 : '';
        $addLayout->sub_heading_2 = $request->sub_heading_2 ? $request->sub_heading_2 : '';
        $addLayout->sub_heading_3 = $request->sub_heading_3 ? $request->sub_heading_3 : '';
        $addLayout->invoice_heading = isset($request->invoice_heading) ? $request->invoice_heading : 'Invoice';
        $addLayout->quotation_heading = isset($request->quotation_heading) ? $request->quotation_heading : 'Quotation';
        $addLayout->sales_order_heading = isset($request->sales_order_heading) ? $request->sales_order_heading : 'Sales Order';
        $addLayout->challan_heading = isset($request->challan_heading) ? $request->challan_heading : 'Challan';
        $addLayout->branch_city = $request->branch_city ? $request->branch_city : 1;
        $addLayout->branch_state = $request->branch_state ? $request->branch_state : 1;
        $addLayout->branch_zipcode = $request->branch_zipcode ? $request->branch_zipcode : 1;
        $addLayout->branch_phone = $request->branch_phone ? $request->branch_phone : 1;
        $addLayout->branch_alternate_number = $request->branch_alternate_number ? $request->branch_alternate_number : 1;
        $addLayout->branch_email = $request->branch_email ? $request->branch_email : 1;
        $addLayout->product_w_type = $request->product_w_type ? $request->product_w_type : 1;
        $addLayout->product_w_duration = $request->product_w_duration ? $request->product_w_duration : 1;
        $addLayout->product_w_discription = $request->product_w_discription ? $request->product_w_discription : 0;
        $addLayout->product_discount = $request->product_discount ? $request->product_discount : 0;
        $addLayout->product_tax = $request->product_tax ? $request->product_tax : 1;
        $addLayout->customer_address = $request->customer_address ? $request->customer_address : 1;
        $addLayout->customer_tax_no = $request->customer_tax_no ? $request->customer_tax_no : 1;
        $addLayout->customer_phone = $request->customer_phone ? $request->customer_phone : 1;
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

        return $addLayout;
    }

    public function updateInvoiceLayout(int $id, object $request): void
    {
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
        $updateInvoiceLayout->branch_city = $request->branch_city;
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
        $updateInvoiceLayout->bank_branch = isset($request->bank_branch) ? $request->bank_branch : null;
        $updateInvoiceLayout->account_name = isset($request->account_name) ? $request->account_name : null;
        $updateInvoiceLayout->account_no = isset($request->account_no) ? $request->account_no : null;
        $updateInvoiceLayout->invoice_notice = isset($request->invoice_notice) ? $request->invoice_notice : null;
        $updateInvoiceLayout->footer_text = isset($request->footer_text) ? $request->footer_text : null;
        $updateInvoiceLayout->save();
    }

    public function deleteInvoiceLayout(int $id): void
    {
        $deleteInvoice = InvoiceLayout::find($schemaId);

        if (! is_null($deleteInvoice)) {

            $deleteInvoice->delete();
        }
    }

    public function setDefaultInvoiceLayout(int $id): void
    {
        $defaultLayout = InvoiceLayout::where('branch_id', auth()->user()->branch_id)->where('is_default', 1)->first();
        if ($defaultLayout) {

            $defaultLayout->is_default = 0;
            $defaultLayout->save();
        }

        $updateLayout = InvoiceLayout::where('id', $id)->first();
        $updateLayout->is_default = 1;
        $updateLayout->save();
    }

    public function invoiceLayouts(int $branchId = null, array $with = null): object
    {
        $query = InvoiceLayout::query();

        if (isset($branchId)) {

            $query->where('branch_id', $branchId);
        }

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('branch_id', $branchId)->get();
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
