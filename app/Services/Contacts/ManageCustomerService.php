<?php

namespace App\Services\Contacts;

use App\Enums\ContactType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ManageCustomerService
{
    public function customerListTable($request)
    {
        $customers = DB::table('contacts')
            ->leftJoin('customer_groups', 'contacts.customer_group_id', 'customer_groups.id')
            ->select(
                'contacts.id',
                'contacts.contact_id',
                'contacts.name',
                'contacts.business_name',
                'contacts.status',
                'contacts.phone',
                'contacts.credit_limit',
                'customer_groups.group_name',
            )->where('contacts.type', ContactType::Customer->value);

        return DataTables::of($customers)
            ->addColumn('action', function ($row) {
                $html = '';
                $html .= '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';

                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="'.route('contacts.manage.customer.manage', [$row->id]).'">'.__('Manage').'</a>';

                $html .= '<a class="dropdown-item" id="money_receipts" href="'.route('contacts.money.receipts.index', [$row->id]).'">'.__('Money Receipt Vouchers').'</a>';

                if (auth()->user()->can('customer_edit')) {

                    $html .= '<a class="dropdown-item" href="'.route('contacts.edit', [$row->id, ContactType::Customer->value]).'" id="editContact">'.__('Edit').'</a>';
                }

                if (auth()->user()->can('customer_delete')) {

                    $html .= '<a class="dropdown-item" id="deleteContact" href="'.route('contacts.delete', [$row->id]).'">'.__('Delete').'</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('group_name', fn ($row) => $row->group_name ? $row->group_name : '...')

            ->editColumn('credit_limit', function ($row) {

                return $row->credit_limit || $row->credit_limit > 0 ? $row->credit_limit : 'No Limit';
            })

            ->editColumn('opening_balance', function ($row) {

                // $openingBalance = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['opening_balance'];
                // return '<span class="opening_balance" data-value="' . $openingBalance . '">' . \App\Utils\Converter::format_in_bdt($openingBalance) . '</span>';

                return 0;
            })

            ->editColumn('total_sale', function ($row) {

                // $totalSale = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_sale'];
                // return '<span class="total_sale" data-value="' . $totalSale . '">' . \App\Utils\Converter::format_in_bdt($totalSale) . '</span>';

                return 0.00;
            })

            ->editColumn('total_return', function ($row) {

                return 0.00;
            })

            ->editColumn('total_received', function ($row) {

                // $totalPaid = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_paid'];
                // return '<span class="total_paid" data-value="' . $totalPaid . '">' . \App\Utils\Converter::format_in_bdt($totalPaid) . '</span>';

                return 0.00;
            })

            ->editColumn('total_paid', function ($row) {

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
            ->rawColumns(['action', 'credit_limit', 'business_name', 'group_name', 'opening_balance', 'total_sale', 'total_return', 'total_received', 'total_paid',  'current_balance', 'status'])
            ->make(true);
    }
}
