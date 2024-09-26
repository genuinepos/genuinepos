<?php

namespace App\Services\Setups;

use App\Enums\RoleType;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Models\Setups\InvoiceLayout;
use Illuminate\Support\Facades\Cache;
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

        // if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

        //     $query->where('invoice_layouts.branch_id', auth()->user()->branch_id);
        // }

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == 1) {

            $query->where('invoice_layouts.branch_id', auth()->user()->branch_id);
        }

        $layouts = $query->select(
            'invoice_layouts.id',
            'invoice_layouts.branch_id',
            'invoice_layouts.name',
            'invoice_layouts.is_default',
            'invoice_layouts.is_header_less',
            'branches.name as branch_name',
            'branches.branch_code',
            'branches.area_name',
            'parentBranch.name as parent_branch_name',
        )->orderBy('id', 'asc')->get();

        return DataTables::of($layouts)
            ->addIndexColumn()
            ->editColumn('is_header_less', function ($row) {

                return $row->is_header_less ==  BooleanType::True->value ? '<span class="badge bg-info">' . __('Yes') . '</span>' : '<span class="badge bg-secondary">' . __('No') . '</span>';
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'] . '(<b>' . __('Company') . '</b>)';
                }
            })
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('invoice_layouts_edit')) {

                    $html .= '<a href="' . route('invoices.layouts.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->can('invoice_layouts_delete') && $row->branch_id == auth()->user()->branch_id) {

                    $html .= '<a href="' . route('invoices.layouts.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                }
                
                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['action', 'branch', 'is_header_less'])
            ->make(true);
    }

    public function addInvoiceLayout(object $request, $branchId = null, $defaultName = null): object
    {
        $addLayout = new InvoiceLayout();
        $addLayout->branch_id = $branchId ? $branchId : auth()->user()->branch_id;
        $addLayout->name = $defaultName ? $defaultName : $request->name;
        $addLayout->show_shop_logo = $request->show_shop_logo ? $request->show_shop_logo : BooleanType::True->value;
        $addLayout->show_total_in_word = $request->show_total_in_word ? $request->show_total_in_word : BooleanType::True->value;
        $addLayout->is_header_less = $request->is_header_less ? $request->is_header_less : BooleanType::False->value;
        $addLayout->gap_from_top = $request->is_header_less == BooleanType::True->value ? $request->gap_from_top : null;
        $addLayout->header_text = $request->header_text ? $request->header_text : '';
        $addLayout->sub_heading_1 = $request->sub_heading_1 ? $request->sub_heading_1 : '';
        $addLayout->sub_heading_2 = $request->sub_heading_2 ? $request->sub_heading_2 : '';
        $addLayout->sub_heading_3 = $request->sub_heading_3 ? $request->sub_heading_3 : '';
        $addLayout->invoice_heading = isset($request->invoice_heading) ? $request->invoice_heading : 'Invoice';
        $addLayout->quotation_heading = isset($request->quotation_heading) ? $request->quotation_heading : 'Quotation';
        $addLayout->sales_order_heading = isset($request->sales_order_heading) ? $request->sales_order_heading : 'Sales Order';
        $addLayout->delivery_note_heading = isset($request->delivery_note_heading) ? $request->delivery_note_heading : 'Delivery Note';
        $addLayout->branch_city = isset($request->branch_city) ? $request->branch_city : BooleanType::True->value;
        $addLayout->branch_state = isset($request->branch_state) ? $request->branch_state : BooleanType::True->value;
        $addLayout->branch_zipcode = isset($request->branch_zipcode) ? $request->branch_zipcode : BooleanType::True->value;
        $addLayout->branch_phone = isset($request->branch_phone) ? $request->branch_phone : BooleanType::True->value;
        $addLayout->branch_alternate_number = isset($request->branch_alternate_number) ? $request->branch_alternate_number : BooleanType::True->value;
        $addLayout->branch_email = isset($request->branch_email) ? $request->branch_email : BooleanType::True->value;
        $addLayout->product_w_type = isset($request->product_w_type) ? $request->product_w_type : BooleanType::True->value;
        $addLayout->product_w_duration = isset($request->product_w_duration) ? $request->product_w_duration : BooleanType::True->value;
        $addLayout->product_w_discription = isset($request->product_w_discription) ? $request->product_w_discription : BooleanType::False->value;
        $addLayout->product_discount = isset($request->product_discount) ? $request->product_discount : BooleanType::False->value;
        $addLayout->product_tax = isset($request->product_tax) ? $request->product_tax : BooleanType::True->value;
        $addLayout->product_brand = isset($request->product_brand) ? $request->product_brand : BooleanType::False->value;
        $addLayout->product_details = isset($request->product_tax) ? $request->product_details : BooleanType::False->value;
        $addLayout->product_price_inc_tax = isset($request->product_price_inc_tax) ? $request->product_price_inc_tax : BooleanType::True->value;
        $addLayout->product_price_exc_tax = isset($request->product_price_exc_tax) ? $request->product_price_exc_tax : BooleanType::True->value;
        $addLayout->product_code = isset($request->product_code) ? $request->product_code : BooleanType::False->value;
        $addLayout->customer_address = isset($request->customer_address) ? $request->customer_address : BooleanType::True->value;
        $addLayout->customer_tax_no = isset($request->customer_tax_no) ? $request->customer_tax_no : BooleanType::True->value;
        $addLayout->customer_phone = isset($request->customer_phone) ? $request->customer_phone : BooleanType::True->value;
        $addLayout->customer_current_balance = isset($request->customer_current_balance) ? $request->customer_current_balance : BooleanType::True->value;
        $addLayout->bank_name = isset($request->bank_name) ? $request->bank_name : null;
        $addLayout->bank_branch = isset($request->bank_branch) ? $request->bank_branch : null;
        $addLayout->account_name = isset($request->account_name) ? $request->account_name : null;
        $addLayout->account_no = isset($request->account_no) ? $request->account_no : null;
        $addLayout->invoice_notice = isset($request->invoice_notice) ? $request->invoice_notice : null;
        $addLayout->footer_text = isset($request->footer_text) ? $request->footer_text : null;
        $addLayout->save();

        return $addLayout;
    }

    public function updateInvoiceLayout(int $id, object $request): void
    {
        $updateInvoiceLayout = $this->singleInvoiceLayout(id: $id);

        // dd($request->all());
        //  dd($updateInvoiceLayout);

        $updateInvoiceLayout->name = $request->name;
        $updateInvoiceLayout->show_shop_logo = $request->show_shop_logo;
        $updateInvoiceLayout->show_total_in_word = $request->show_total_in_word;
        $updateInvoiceLayout->is_header_less = $request->is_header_less;
        $updateInvoiceLayout->gap_from_top = $request->is_header_less == BooleanType::True->value ? $request->gap_from_top : null;
        $updateInvoiceLayout->header_text = $request->header_text;
        $updateInvoiceLayout->sub_heading_1 = $request->sub_heading_1;
        $updateInvoiceLayout->sub_heading_2 = $request->sub_heading_2;
        $updateInvoiceLayout->sub_heading_3 = $request->sub_heading_3;
        $updateInvoiceLayout->invoice_heading = isset($request->invoice_heading) ? $request->invoice_heading : 'Invoice';
        $updateInvoiceLayout->quotation_heading = isset($request->quotation_heading) ? $request->quotation_heading : 'Quotation';
        $updateInvoiceLayout->sales_order_heading = isset($request->sales_order_heading) ? $request->sales_order_heading : 'Sales Order';
        $updateInvoiceLayout->delivery_note_heading = isset($request->delivery_note_heading) ? $request->delivery_note_heading : 'Delivery Note';
        $updateInvoiceLayout->branch_city = $request->branch_city;
        $updateInvoiceLayout->branch_state = $request->branch_state;
        $updateInvoiceLayout->branch_zipcode = $request->branch_zipcode;
        $updateInvoiceLayout->branch_phone = $request->branch_phone;
        $updateInvoiceLayout->branch_alternate_number = $request->branch_alternate_number;
        $updateInvoiceLayout->branch_email = $request->branch_email;
        $updateInvoiceLayout->product_w_type = $request->product_w_type;
        $updateInvoiceLayout->product_w_duration = $request->product_w_duration;
        $updateInvoiceLayout->product_w_discription = isset($request->product_w_discription) ? $request->product_w_discription : BooleanType::False->value;
        $updateInvoiceLayout->product_discount = $request->product_discount;
        $updateInvoiceLayout->product_tax = $request->product_tax;
        $updateInvoiceLayout->product_brand = $request->product_brand;
        $updateInvoiceLayout->product_details = $request->product_details;
        $updateInvoiceLayout->product_price_inc_tax = $request->product_price_inc_tax;
        $updateInvoiceLayout->product_price_exc_tax = $request->product_price_exc_tax;
        $updateInvoiceLayout->product_code = $request->product_code;
        $updateInvoiceLayout->customer_address = $request->customer_address;
        $updateInvoiceLayout->customer_tax_no = $request->customer_tax_no;
        $updateInvoiceLayout->customer_phone = $request->customer_phone;
        $updateInvoiceLayout->customer_current_balance = $request->customer_current_balance;
        $updateInvoiceLayout->bank_name = isset($request->bank_name) ? $request->bank_name : null;
        $updateInvoiceLayout->bank_branch = isset($request->bank_branch) ? $request->bank_branch : null;
        $updateInvoiceLayout->account_name = isset($request->account_name) ? $request->account_name : null;
        $updateInvoiceLayout->account_no = isset($request->account_no) ? $request->account_no : null;
        $updateInvoiceLayout->invoice_notice = isset($request->invoice_notice) ? $request->invoice_notice : null;
        $updateInvoiceLayout->footer_text = isset($request->footer_text) ? $request->footer_text : null;
        $updateInvoiceLayout->save();

        $this->forgetCache(id: $id);
    }

    public function deleteInvoiceLayout(int $id): array
    {
        $generalSettings = config('generalSettings');
        $deleteInvoice = $this->singleInvoiceLayout(id: $id);

        $branchInvoiceLayout = $this->invoiceLayouts(branchId: $deleteInvoice->branch_id);

        if (isset($deleteInvoice)) {

            if (count($branchInvoiceLayout) == 1) {

                return ['pass' => false, 'msg' => __('Invoice Layout can not be deleted, At least one invoice layout is required for a shop.')];
            } elseif ($generalSettings['invoice_layout__add_sale_invoice_layout_id'] == $deleteInvoice->id) {

                return ['pass' => false, 'msg' => __('Invoice Layout can not be deleted, This invoice layout has already been set as add sale invoice layout')];
            } elseif ($generalSettings['invoice_layout__pos_sale_invoice_layout_id'] == $deleteInvoice->id) {

                return ['pass' => false, 'msg' => __('Invoice Layout can not be deleted, This invoice layout has already been set as pos sale invoice layout')];
            }

            if (!is_null($deleteInvoice)) {

                $deleteInvoice->delete();
            }
        }

        $this->forgetCache(id: $id);

        return ['pass' => true];
    }

    public function invoiceLayouts(?int $branchId = null, array $with = null): object
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

    public static function invoicePageSizeNames(int $index, $fullLine = true): string
    {
        $arr = [
            1 => $fullLine ? __('A4 Page | Height 11.7Incs, Width: 8.3Incs') : __('A4'),
            2 => $fullLine ? __('A5 Page | Height 8.3Incs, Width: 5.8Incs') : __('A5'),
            3 => $fullLine ? __('POS Printer | Width: 3Incs') : __('POS Print'),
        ];

        return $arr[$index];
    }

    function invoiceLayoutStoreValidation(object $request)
    {
        $request->validate([
            'name' => 'required|unique:invoice_layouts,name',
            'invoice_heading' => 'required',
            'quotation_heading' => 'required',
            'sales_order_heading' => 'required',
            'delivery_note_heading' => 'required',
        ]);

        if ($request->is_header_less == BooleanType::True->value) {

            $request->validate([
                'gap_from_top' => 'required',
            ]);
        }
    }

    function invoiceLayoutUpdateValidation(object $request, int $id)
    {
        $request->validate([
            'name' => 'required|unique:invoice_layouts,name,' . $id,
            'invoice_heading' => 'required',
            'quotation_heading' => 'required',
            'sales_order_heading' => 'required',
            'delivery_note_heading' => 'required',
        ]);

        if ($request->is_header_less == BooleanType::True->value) {

            $request->validate([

                'gap_from_top' => 'required',
            ]);
        }
    }

    private function forgetCache(int $id): void
    {
        $tenantId = tenant('id');
        $cacheKey = "{$tenantId}_invoiceAddSaleLayout_{$id}";
        Cache::forget($cacheKey);
        $cacheKey = "{$tenantId}_invoicePosSaleLayout_{$id}";
        Cache::forget($cacheKey);
    }
}
