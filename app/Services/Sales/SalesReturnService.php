<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Enums\PaymentStatus;
use App\Models\Sales\SaleReturn;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesReturnService
{
    public function salesReturnListTable(object $request)
    {
        $generalSettings = config('generalSettings');
        $returns = '';

        $query = DB::table('sale_returns')
            ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
            ->leftJoin('accounts as customers', 'sale_returns.customer_account_id', 'customers.id')
            ->leftJoin('branches', 'sale_returns.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('currencies', 'branches.currency_id', 'currencies.id')
            ->leftJoin('users as created_by', 'sales.created_by_id', 'created_by.id');

        $this->filteredQuery($request, $query);

        $returns = $query->select(
            'sale_returns.id',
            'sale_returns.sale_id',
            'sale_returns.branch_id',
            'sale_returns.voucher_no',
            'sale_returns.date',
            'sale_returns.total_qty',
            'sale_returns.net_total_amount',
            'sale_returns.return_discount_type',
            'sale_returns.return_discount_amount',
            'sale_returns.return_tax_percent',
            'sale_returns.return_tax_amount',
            'sale_returns.total_return_amount',
            'sale_returns.paid',
            'sale_returns.due',
            'sales.invoice_id',
            'branches.name as branch_name',
            'branches.area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
            'currencies.currency_rate as c_rate'
        )->orderBy('sale_returns.date_ts', 'desc');

        return DataTables::of($returns)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('sales.returns.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('edit_sales_return')) {

                        $html .= '<a class="dropdown-item" href="' . route('sales.returns.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                    }
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('delete_sales_return')) {

                        $html .= '<a href="' . route('sales.returns.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                    }
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('voucher_no', function ($row) {

                return '<a href="' . route('sales.returns.show', [$row->id]) . '" id="details_btn">' . $row->voucher_no . '</a>';
            })
            ->editColumn('invoice_id', function ($row) {

                if ($row->sale_id) {

                    return '<a href="' . route('sales.show', [$row->sale_id]) . '" id="details_btn">' . $row->invoice_id . '</a>';
                }
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

            ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . curr_cnv($row->net_total_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->net_total_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('return_discount_amount', fn ($row) => '<span class="return_discount_amount" data-value="' . curr_cnv($row->return_discount_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->return_discount_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('return_tax_amount', fn ($row) => '<span class="return_tax_amount" data-value="' . curr_cnv($row->return_tax_amount, $row->c_rate, $row->branch_id) . '">' . '(' . $row->return_tax_percent . '%)=' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->return_tax_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('total_return_amount', fn ($row) => '<span class="total_return_amount" data-value="' . curr_cnv($row->total_return_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->total_return_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . curr_cnv($row->paid, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->paid, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="' . curr_cnv($row->due, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->due, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('payment_status', function ($row) {

                $payable = $row->total_return_amount;

                if ($row->due <= 0) {

                    return '<span class="text-success"><b>' . __('Paid') . '</span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    return '<span class="text-primary"><b>' . __('Partial') . '</b></span>';
                } elseif ($payable == $row->due) {

                    return '<span class="text-danger"><b>' . __('Due') . '</b></span>';
                }
            })

            ->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })

            ->rawColumns(['action', 'date', 'voucher_no', 'invoice_id', 'total_qty', 'net_total_amount', 'return_discount_amount', 'return_tax_amount', 'total_return_amount', 'paid', 'due', 'branch', 'customer', 'due', 'sale_return_amount', 'payment_status', 'created_by'])
            ->make(true);
    }

    public function addSalesReturn(object $request, object $codeGenerator, string $voucherPrefix = null): object
    {
        // generate invoice ID
        $voucherNo = $codeGenerator->generateMonthWise(table: 'sale_returns', column: 'voucher_no', prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addSalesReturn = new SaleReturn();
        $addSalesReturn->branch_id = auth()->user()->branch_id;
        $addSalesReturn->warehouse_id = $request->warehouse_id;
        $addSalesReturn->voucher_no = $voucherNo;
        $addSalesReturn->sale_id = $request->sale_id;
        $addSalesReturn->customer_account_id = $request->customer_account_id;
        $addSalesReturn->sale_account_id = $request->sale_account_id;
        $addSalesReturn->total_item = $request->total_item ? $request->total_item : 0;
        $addSalesReturn->total_qty = $request->total_qty ? $request->total_qty : 0;
        $addSalesReturn->net_total_amount = $request->net_total_amount ? $request->net_total_amount : 0;
        $addSalesReturn->return_discount = $request->return_discount ? $request->return_discount : 0;
        $addSalesReturn->return_discount_type = $request->return_discount_type;
        $addSalesReturn->return_discount_amount = $request->return_discount_amount ? $request->return_discount_amount : 0;
        $addSalesReturn->return_tax_ac_id = $request->return_tax_ac_id;
        $addSalesReturn->return_tax_percent = $request->return_tax_percent ? $request->return_tax_percent : 0;
        $addSalesReturn->return_tax_amount = $request->return_tax_amount ? $request->return_tax_amount : 0;
        $addSalesReturn->total_return_amount = $request->total_return_amount ? $request->total_return_amount : 0;
        $addSalesReturn->due = $request->total_return_amount ? $request->total_return_amount : 0;
        $addSalesReturn->date = $request->date;
        $addSalesReturn->date_ts = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addSalesReturn->note = $request->note;
        $addSalesReturn->created_by_id = auth()->user()->id;
        $addSalesReturn->save();

        return $addSalesReturn;
    }

    public function updateSalesReturn(object $request, object $return): object
    {
        foreach ($return->saleReturnProducts as $saleReturnProduct) {

            $saleReturnProduct->is_delete_in_update = BooleanType::True->value;
            $saleReturnProduct->save();
        }

        $return->warehouse_id = $request->warehouse_id;
        $return->customer_account_id = $request->customer_account_id;
        $return->sale_account_id = $request->sale_account_id;
        $return->total_item = $request->total_item ? $request->total_item : 0;
        $return->total_qty = $request->total_qty ? $request->total_qty : 0;
        $return->net_total_amount = $request->net_total_amount ? $request->net_total_amount : 0;
        $return->return_discount = $request->return_discount ? $request->return_discount : 0;
        $return->return_discount_type = $request->return_discount_type;
        $return->return_discount_amount = $request->return_discount_amount ? $request->return_discount_amount : 0;
        $return->return_tax_ac_id = $request->return_tax_ac_id;
        $return->return_tax_percent = $request->return_tax_percent ? $request->return_tax_percent : 0;
        $return->return_tax_amount = $request->return_tax_amount ? $request->return_tax_amount : 0;
        $return->total_return_amount = $request->total_return_amount ? $request->total_return_amount : 0;
        $return->date = $request->date;
        $time = date(' H:i:s', strtotime($return->date_ts));
        $return->date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $return->note = $request->note;
        $return->save();

        $this->adjustSalesReturnVoucherAmounts($return);

        return $return;
    }

    public function restrictions(object $request, bool $checkCustomerChangeRestriction = false, ?object $customerAccount = null, ?int $saleReturnId = null): array
    {
        if (isset($customerAccount) && $customerAccount->is_walk_in_customer == BooleanType::True->value && $request->current_balance != 0) {

            return ['pass' => false, 'msg' => __('Walk-In-Customer is not credit customer. So Walk-In-Customer current balance must be 0.')];
        }

        if (!isset($request->product_ids)) {

            return ['pass' => false, 'msg' => __('Product table is empty.')];
        } elseif (count($request->product_ids) > 60) {

            return ['pass' => false, 'msg' => __('Sales Return products must be less than 60 or equal.')];
        }

        if ($request->total_qty == 0) {

            return ['pass' => false, 'msg' => __('All product`s quantity is 0.')];
        }

        if ($checkCustomerChangeRestriction == true) {

            $salesReturn = $this->singleSalesReturn(id: $saleReturnId, with: ['references']);

            if (count($salesReturn->references)) {

                if ($salesReturn->customer_account_id != $request->customer_account_id) {

                    return ['pass' => false, 'msg' => __('Customer can not be changed. One or more payments is exists against this sales return voucher.')];
                }
            }
        }

        return ['pass' => true];
    }

    function deleteSalesReturn(int $id)
    {
        $deleteSaleReturn = $this->singleSalesReturn(id: $id, with: ['sale', 'saleReturnProducts', 'saleReturnProducts.product', 'saleReturnProducts.variant', 'references']);

        if (isset($deleteSaleReturn)) {

            if (count($deleteSaleReturn->references) > 0) {

                return ['pass' => false, 'msg' => __('Sale Return can not be deleted. There is one or more payments which is against this sales return.')];
            }
        }

        $deleteSaleReturn->delete();

        return $deleteSaleReturn;
    }

    public function adjustSalesReturnVoucherAmounts($salesReturn)
    {
        $totalPaid = DB::table('voucher_description_references')
            ->where('voucher_description_references.sale_return_id', $salesReturn->id)
            ->select(DB::raw('sum(voucher_description_references.amount) as total_paid'))
            ->groupBy('voucher_description_references.sale_return_id')
            ->get();

        $due = $salesReturn->total_return_amount - $totalPaid->sum('total_paid');
        $salesReturn->paid = $totalPaid->sum('total_paid');
        $salesReturn->due = $due;
        $salesReturn->save();
    }

    public function singleSalesReturn(int $id, array $with = null): ?object
    {
        $query = SaleReturn::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    private function filteredQuery($request, $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sale_returns.branch_id', null);
            } else {

                $query->where('sale_returns.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('sale_returns.created_by_id', $request->created_by_id);
        }

        if ($request->customer_account_id) {

            if ($request->customer_id == 'NULL') {

                $query->where('sale_returns.customer_account_id', null);
            } else {

                $query->where('sale_returns.customer_account_id', $request->customer_account_id);
            }
        }

        if ($request->payment_status) {

            if ($request->payment_status == PaymentStatus::Paid->value) {

                $query->where('sale_returns.due', '=', 0);
            } elseif ($request->payment_status == PaymentStatus::Partial->value) {

                $query->where('sale_returns.paid', '>', 0)->where('sale_returns.due', '>', 0);
            } elseif ($request->payment_status == PaymentStatus::Due->value) {

                $query->where('sale_returns.paid', '=', 0);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sale_returns.date_ts', $date_range); // Final
        }

        if (auth()->user()->can('view_only_won_transactions')) {

            $query->where('sale_returns.created_by_id', auth()->user()->id);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sale_returns.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
