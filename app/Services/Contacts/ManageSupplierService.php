<?php

namespace App\Services\Contacts;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ManageSupplierService
{
    public function supplierListTable($request)
    {
        $suppliers = DB::table('contacts')
            ->select(
                'contacts.id',
                'contacts.contact_id',
                'contacts.name',
                'contacts.business_name',
                'contacts.status',
                'contacts.phone',
            )->where('contacts.type', \App\Enums\ContactType::Supplier->value);

        return DataTables::of($suppliers)
            ->addColumn('action', function ($row) {
                $html = '';
                $html .= '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';

                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="'.route('contacts.manage.supplier.manage', [$row->id]).'">'.__('Manage').'</a>';

                if (auth()->user()->can('supplier_edit')) {

                    $html .= '<a class="dropdown-item" href="'.route('contacts.edit', [$row->id, \App\Enums\ContactType::Supplier->value]).'" id="editContact">'.__('Edit').'</a>';
                }

                if (auth()->user()->can('supplier_delete')) {

                    $html .= '<a class="dropdown-item" id="deleteContact" href="'.route('contacts.delete', [$row->id]).'">'.__('Delete').'</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('opening_balance', function ($row) {

                // $openingBalance = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['opening_balance'];
                // return '<span class="opening_balance" data-value="' . $openingBalance . '">' . \App\Utils\Converter::format_in_bdt($openingBalance) . '</span>';

                return 0;
            })

            ->editColumn('total_purchase', function ($row) {

                // $totalSale = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_sale'];
                // return '<span class="total_sale" data-value="' . $totalSale . '">' . \App\Utils\Converter::format_in_bdt($totalSale) . '</span>';

                return 0.00;
            })

            ->editColumn('total_return', function ($row) {

                return 0.00;
            })

            ->editColumn('total_paid', function ($row) {

                // $totalPaid = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_paid'];
                // return '<span class="total_paid" data-value="' . $totalPaid . '">' . \App\Utils\Converter::format_in_bdt($totalPaid) . '</span>';

                return 0.00;
            })

            ->editColumn('total_received', function ($row) {

                // $totalPaid = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_paid'];
                // return '<span class="total_paid" data-value="' . $totalPaid . '">' . \App\Utils\Converter::format_in_bdt($totalPaid) . '</span>';

                return 0.00;
            })

            ->editColumn('current_balance', function ($row) {

                // $totalSaleDue = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_sale_due'];
                // return '<span class="total_sale_due" data-value="' . $totalSaleDue . '">' . \App\Utils\Converter::format_in_bdt($totalSaleDue) . '</span>';

                return 0.00;
            })

            ->editColumn('status', function ($row) {

                if ($row->status == 1) {

                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input" id="change_status" data-url="'.route('contacts.change.status', [$row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked/>';
                    $html .= '</div>';

                    return $html;
                } else {

                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input" id="change_status" data-url="'.route('contacts.change.status', [$row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox" />';
                    $html .= '</div>';

                    return $html;
                }
            })
            ->rawColumns(['action', 'credit_limit', 'opening_balance', 'total_purchase', 'total_return', 'total_received', 'total_paid',  'current_balance', 'status'])
            ->make(true);
    }
}
