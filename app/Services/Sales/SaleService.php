<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Models\Sales\Sale;
use App\Enums\PaymentStatus;
use App\Enums\SaleScreenType;
use App\Enums\ShipmentStatus;
use Illuminate\Support\Facades\DB;
use App\Enums\PrintPageSize;
use Yajra\DataTables\Facades\DataTables;

class SaleService
{
    public function salesListTable(object $request, int $customerAccountId = null, int $saleScreen = null): object
    {
        $generalSettings = config('generalSettings');
        $sales = '';

        $query = DB::table('sales')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('users as created_by', 'sales.created_by_id', 'created_by.id')
            ->where('sales.status', SaleStatus::Final->value);

        $this->filteredQuery(request: $request, query: $query, customerAccountId: $customerAccountId, saleScreen: $saleScreen);

        $sales = $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.invoice_id',
            'sales.date',
            'sales.total_item',
            'sales.total_qty',
            'sales.total_invoice_amount',
            'sales.sale_return_amount',
            'sales.paid as received_amount',
            'sales.due',
            'sales.is_return_available',
            'sales.shipment_status',
            'sales.sale_screen',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->orderBy('sales.sale_date_ts', 'desc');

        return DataTables::of($sales)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('sales.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if ($row->sale_screen == SaleScreenType::AddSale->value) {

                        if (auth()->user()->can('edit_add_sale')) {

                            $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                        }
                    } elseif ($row->sale_screen == SaleScreenType::PosSale->value) {

                        if (auth()->user()->can('pos_edit')) {

                            $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                        }
                    }
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('delete_add_sale')) {

                        $html .= '<a href="' . route('sales.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                    }
                }

                if (auth()->user()->can('shipment_access')) {

                    $html .= '<a class="dropdown-item" id="editShipmentDetails" href="' . route('sale.shipments.edit', [$row->id]) . '">' . __('Edit Shipment Details') . '</a>';
                }

                // if (auth()->user()->can('shipment_access')) {

                //     $html .= '<a href="' . route('sale.shipments.print.packing.slip', [$row->id]) . '" class="dropdown-item" id="printPackingSlipBtn">' . __('Print Packing Slip') . '</a>';
                // }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('invoice_id', function ($row) {

                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->shipment_status != ShipmentStatus::NoStatus->value && $row->shipment_status != ShipmentStatus::Cancelled->value ? ' <i class="fas fa-shipping-fast text-dark"></i>' : '';
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';

                return '<a href="' . route('sales.show', [$row->id]) . '" id="details_btn">' . $html . '</a>';
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->branch_area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

            ->editColumn('total_invoice_amount', fn ($row) => '<span class="total_invoice_amount" data-value="' . $row->total_invoice_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_invoice_amount) . '</span>')

            ->editColumn('received_amount', fn ($row) => '<span class="paid received_amount text-success" data-value="' . $row->received_amount . '">' . \App\Utils\Converter::format_in_bdt($row->received_amount) . '</span>')

            ->editColumn('sale_return_amount', fn ($row) => '<span class="sale_return_amount" data-value="' . $row->sale_return_amount . '">' . \App\Utils\Converter::format_in_bdt($row->sale_return_amount) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="' . $row->due . '">' . \App\Utils\Converter::format_in_bdt($row->due) . '</span>')

            ->editColumn('payment_status', function ($row) {

                $receivable = $row->total_invoice_amount - $row->sale_return_amount;

                if ($row->due <= 0) {

                    return '<span class="text-success"><b>' . __('Paid') . '</span>';
                } elseif ($row->due > 0 && $row->due < $receivable) {

                    return '<span class="text-primary"><b>' . __('Partial') . '</b></span>';
                } elseif ($receivable == $row->due) {

                    return '<span class="text-danger"><b>' . __('Due') . '</b></span>';
                }
            })

            ->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })

            ->rawColumns(['action', 'date', 'total_item', 'total_qty', 'total_invoice_amount', 'received_amount', 'invoice_id', 'branch', 'customer', 'due', 'sale_return_amount', 'payment_status', 'created_by'])
            ->make(true);
    }

    public function addSale(object $request, int $saleScreenType, object $codeGenerator, ?string $invoicePrefix, ?string $quotationPrefix, ?string $salesOrderPrefix): object
    {
        $transId = '';
        if ($request->status == SaleStatus::Final->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'invoice_id', prefix: $invoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'quotation_id', prefix: $quotationPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Order->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'order_id', prefix: $salesOrderPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Draft->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'draft_id', prefix: 'DRF', splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        }

        $addSale = new Sale();
        $addSale->invoice_id = $request->status == SaleStatus::Final->value ? $transId : null;
        $addSale->quotation_id = $request->status == SaleStatus::Quotation->value ? $transId : null;
        $addSale->order_id = $request->status == SaleStatus::Order->value ? $transId : null;
        $addSale->draft_id = $request->status == SaleStatus::Draft->value ? $transId : null;
        $addSale->created_by_id = auth()->user()->id;
        $addSale->sale_account_id = $request->sale_account_id;
        $addSale->branch_id = auth()->user()->branch_id;
        $addSale->customer_account_id = $request->customer_account_id;
        $addSale->status = $request->status;
        $addSale->pay_term = $request->pay_term;
        $addSale->pay_term_number = $request->pay_term_number;
        $addSale->date = $request->date;
        $addSale->date_ts = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addSale->sale_date_ts = $request->status == SaleStatus::Final->value ? date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))) : null;
        $addSale->quotation_date_ts = $request->status == SaleStatus::Quotation->value ? date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))) : null;
        $addSale->order_date_ts = $request->status == SaleStatus::Order->value ? date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))) : null;
        $addSale->draft_date_ts = $request->status == SaleStatus::Draft->value ? date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))) : null;
        $addSale->quotation_status = $request->status == SaleStatus::Quotation->value ? 1 : 0;
        $addSale->order_status = $request->status == SaleStatus::Order->value ? 1 : 0;
        $addSale->draft_status = $request->status == SaleStatus::Draft->value ? 1 : 0;
        $addSale->total_item = $request->total_item;
        $addSale->total_qty = $request->total_qty;
        $addSale->total_sold_qty = $request->status == SaleStatus::Final->value ? $request->total_qty : 0;
        $addSale->total_ordered_qty = $request->status == SaleStatus::Order->value ? $request->total_qty : 0;
        $addSale->total_quotation_qty = $request->status == SaleStatus::Quotation->value ? $request->total_qty : 0;
        $addSale->net_total_amount = $request->net_total_amount;
        $addSale->order_discount_type = $request->order_discount_type;
        $addSale->order_discount = $request->order_discount;
        $addSale->order_discount_amount = $request->order_discount_amount;
        $addSale->sale_tax_ac_id = $request->sale_tax_ac_id;
        $addSale->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $addSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $addSale->shipment_details = $request->shipment_details;
        $addSale->shipment_address = $request->shipment_address;
        $addSale->shipment_status = $request->shipment_status ? $request->shipment_status : 0;
        $addSale->delivered_to = $request->delivered_to;
        $addSale->note = $request->note;
        $addSale->change_amount = $request->change_amount > 0 ? $request->change_amount : 0.00;
        $addSale->total_invoice_amount = $request->total_invoice_amount;
        $addSale->due = $request->total_invoice_amount;
        $addSale->save();

        return $addSale;
    }

    public function updateSale(object $request, object $updateSale): object
    {
        foreach ($updateSale->saleProducts as $saleProduct) {

            $saleProduct->is_delete_in_update = 1;
            $saleProduct->save();
        }

        $updateSale->sale_account_id = $request->sale_account_id;
        $updateSale->customer_account_id = $request->customer_account_id;
        $updateSale->pay_term = $request->pay_term;
        $updateSale->pay_term_number = $request->pay_term_number;
        $updateSale->date = $request->date;
        $time = date(' H:i:s', strtotime($updateSale->date_ts));
        $updateSale->date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updateSale->sale_date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updateSale->total_item = $request->total_item;
        $updateSale->total_qty = $request->total_qty;
        $updateSale->total_sold_qty = $request->total_qty;
        $updateSale->net_total_amount = $request->net_total_amount;
        $updateSale->order_discount_type = $request->order_discount_type;
        $updateSale->order_discount = $request->order_discount;
        $updateSale->order_discount_amount = $request->order_discount_amount;
        $updateSale->sale_tax_ac_id = $request->sale_tax_ac_id;
        $updateSale->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $updateSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $updateSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $updateSale->shipment_details = $request->shipment_details;
        $updateSale->shipment_address = $request->shipment_address;
        $updateSale->shipment_status = $request->shipment_status ? $request->shipment_status : 0;
        $updateSale->delivered_to = $request->delivered_to;
        $updateSale->note = $request->note;
        $updateSale->total_invoice_amount = $request->total_invoice_amount;
        $updateSale->save();

        return $updateSale;
    }

    public function deleteSale(int $id): array|object
    {
        $deleteSale = $this->singleSale(id: $id, with: [
            'references',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.purchaseSaleProductChains',
            'saleProducts.purchaseSaleProductChains.purchaseProduct',
        ]);

        $voucherName = SaleStatus::tryFrom($deleteSale->status)->name;
        $__voucherName = $voucherName == 'Final' ? 'Sale' : $voucherName;

        if (count($deleteSale->references) > 0) {

            return ['pass' => false, 'msg' => __("Data can not be deleted. There is one or more receipt which is against this ${__voucherName}.")];
        }

        if ($deleteSale->status == SaleStatus::Order->value && $deleteSale->total_delivered_qty > 0) {

            return ['pass' => false, 'msg' => __("Data can not be deleted. Invoice is exists against this ${__voucherName}.")];
        }

        $deleteSale->delete();

        return $deleteSale;
    }

    public function adjustSaleInvoiceAmounts(object $sale): object
    {
        $totalSaleReceived = DB::table('voucher_description_references')
            ->where('voucher_description_references.sale_id', $sale->id)
            ->select(DB::raw('sum(voucher_description_references.amount) as total_received'))
            ->groupBy('voucher_description_references.sale_id')
            ->get();

        $totalReturn = DB::table('sale_returns')
            ->where('sale_returns.sale_id', $sale->id)
            ->select(DB::raw('sum(total_return_amount) as total_returned_amount'))
            ->groupBy('sale_returns.sale_id')
            ->get();

        $due = $sale->total_invoice_amount
            - $totalSaleReceived->sum('total_received')
            - $totalReturn->sum('total_returned_amount');

        $sale->paid = $totalSaleReceived->sum('total_received');
        $sale->due = $due;
        $sale->sale_return_amount = $totalReturn->sum('total_returned_amount');
        $sale->is_return_available = $totalReturn->sum('total_returned_amount') > 0 ? BooleanType::True->value : BooleanType::False->value;
        $sale->save();

        return $sale;
    }

    public function updateInvoiceRewardPoint(object $sale, int $earnedPoint = 0, int $redeemedPoint = 0): void
    {
        $sale->earned_point += $earnedPoint;
        $sale->redeemed_point += $redeemedPoint;
        $sale->save();
    }

    public function singleSale(int $id, array $with = null): ?object
    {
        $query = Sale::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function singleSaleByAnyCondition(array $with = null): ?object
    {
        $query = Sale::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function restrictions(object $request, object $accountService, bool $checkCustomerChangeRestriction = false, int $saleId = null): ?array
    {
        $customer = $accountService->singleAccount(id: $request->customer_account_id);

        if (
            isset($request->print_page_size) &&
            $request->print_page_size == PrintPageSize::PosPrinterPageThreeIncs->value &&
            $request->status != SaleStatus::Final->value
        ) {

            return ['pass' => false, 'msg' => __('Pos printer only supported for final sale.')];
        }

        if ($request->ex_sale_id) {

            if ($request->status != SaleStatus::Final->value) {

                return ['pass' => false, 'msg' => __('Can not create another entry when exchange in going on.')];
            }

            if ($request->status != SaleStatus::Final->value) {

                return ['pass' => false, 'msg' => __('Can not create another entry when exchange in going on.')];
            }

            if ($request->total_invoice_amount < 0) {

                return ['pass' => false, 'msg' => __('Net Exchange amount must not be less then 0 (-). Net Exchange amount must be greater then or equal main invoice.')];
            }

            $sale = $this->singleSale(id: $saleId);

            if ($sale->customer_account_id != $request->customer_account_id) {

                return ['pass' => false, 'msg' => __('Customer can not be changed when exchange in going on.')];
            }
        }

        if (!isset($request->product_ids)) {

            return ['pass' => false, 'msg' => __('Products table must not be empty.')];
        }

        if ($request->status == SaleStatus::Final->value && $customer->is_walk_in_customer == 1 && $request->current_balance > 0) {

            return ['pass' => false, 'msg' => __('Due or partial sale is not allowed for walk-in-customer . Please select a listed customer.')];
        }

        if (
            isset($request->is_full_credit_sale)
            && $request->is_full_credit_sale == 0
            && $request->received_amount == 0
            && $request->current_balance > 0
            && $request->status == SaleStatus::Final->value
        ) {

            return ['pass' => false, 'msg' => __('If you want to sale in full credit, so click credit sale button.')];
        }

        if ($checkCustomerChangeRestriction == true) {

            $sale = $this->singleSale(id: $saleId, with: ['references']);

            if (count($sale->references) > 0) {

                if ($sale->customer_account_id != $request->customer_account_id) {

                    return ['pass' => false, 'msg' => __('Customer can not be changed. One or more receipts is exists against this sales.' . $sale->customer_account_id . $request->customer_account_id)];
                }
            }

            if ($sale->status == SaleStatus::Final->value && $request->status != SaleStatus::Final->value) {

                return ['pass' => false, 'msg' => __('Final sale status can not be changed to quotation, draft, hold or suspend.')];
            }
        }

        if (($request->status == SaleStatus::Order->value || $request->status == SaleStatus::Quotation->value) && $customer->is_walk_in_customer == 1) {

            return ['pass' => false, 'msg' => __('Listed customer is required for sales order and quotation.')];
        }

        if ($request->status == SaleStatus::Final->value && $request->current_balance > 0) {

            $customerCreditLimit = DB::table('accounts')
                ->where('accounts.id', $request->customer_account_id)
                ->leftJoin('contacts', 'accounts.contact_id', 'contacts.id')
                ->select('contacts.credit_limit')
                ->first();

            $creditLimit = $customerCreditLimit ? $customerCreditLimit->credit_limit : 0;
            $__credit_limit = $creditLimit ? $creditLimit : 0;
            $msg_1 = 'Customer does not have any credit limit.';
            $msg_2 = "Customer Credit Limit is ${__credit_limit}.";
            $__show_msg = $__credit_limit ? $msg_2 : $msg_1;

            if ($request->current_balance > $__credit_limit) {

                return ['pass' => false, 'msg' => $__show_msg];
            }
        }

        return ['pass' => true];
    }

    private function filteredQuery(object $request, object $query, int $customerAccountId = null, int $saleScreen = null)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sales.branch_id', null);
            } else {

                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('sales.created_by_id', $request->created_by_id);
        }

        if ($request->customer_account_id) {

            if ($request->customer_id == 'NULL') {

                $query->where('sales.customer_account_id', null);
            } else {

                $query->where('sales.customer_account_id', $request->customer_account_id);
            }
        }

        if ($request->payment_status) {

            if ($request->payment_status == PaymentStatus::Paid->value) {

                $query->where('sales.due', '=', 0);
            } elseif ($request->payment_status == PaymentStatus::Partial->value) {

                $query->where('sales.paid', '>', 0)->where('sales.due', '>', 0);
            } elseif ($request->payment_status == PaymentStatus::Due->value) {

                $query->where('sales.paid', '=', 0);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.sale_date_ts', $date_range); // Final
        }

        if (isset($customerAccountId)) {

            $query->where('sales.customer_account_id', $customerAccountId);
        }

        if (isset($saleScreen)) {

            $query->where('sales.sale_screen', $saleScreen);
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            if (auth()->user()->can('view_own_sale')) {

                $query->where('sales.created_by_id', auth()->user()->id);
            }

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }

    public function addSaleValidation(object $request): ?array
    {
        return $request->validate([
            'status' => 'required',
            'date' => 'required|date',
            'sale_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'sale_account_id.required' => 'Sales A/c is required',
            'account_id.required' => 'Debit A/c is required',
        ]);
    }
}
