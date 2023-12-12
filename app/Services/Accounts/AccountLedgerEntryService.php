<?php

namespace App\Services\Accounts;

use App\Models\Accounts\AccountLedger;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AccountLedgerEntryService
{
    public function ledgerTable(object $request, int $id, object $account): ?object
    {
        $ledgers = '';
        $generalSettings = config('generalSettings');
        $accountStartDate = date('Y-m-d', strtotime($generalSettings['business__account_start_date']));

        $ledgers = $this->ledgerEntriesQuery(request: $request, id: $id, account: $account);

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $this->generateOpeningBalance(accountId: $id, ledgers: $ledgers, fromDateYmd: $fromDateYmd, request: $request, generalSettings: $generalSettings);
        }

        // return $ledgers;

        $runningDebit = 0;
        $runningCredit = 0;
        foreach ($ledgers as $ledger) {

            $runningDebit += $ledger->debit;
            $runningCredit += $ledger->credit;

            if ($runningDebit > $runningCredit) {

                $ledger->running_balance = $runningDebit - $runningCredit;
                $ledger->balance_type = ' Dr.';
            } elseif ($runningCredit > $runningDebit) {

                $ledger->running_balance = $runningCredit - $runningDebit;
                $ledger->balance_type = ' Cr.';
            }
        }

        return DataTables::of($ledgers)
            ->editColumn('date', function ($row) use ($generalSettings) {

                $dateFormat = $generalSettings['business__date_format'];
                $__date_format = str_replace('-', '/', $dateFormat);

                return $row->date ? date($__date_format, strtotime($row->date)) : '';
            })

            ->editColumn('particulars', function ($row) use ($request) {

                $voucherType = $row->voucher_type;
                $ledgerParticulars = new \App\Services\Accounts\AccountLedgerParticularService();

                return $ledgerParticulars->particulars($request, $voucherType, $row);
            })

            ->editColumn('voucher_type', function ($row) {

                //return $row->voucher_type;
                $accountLedgerService = new \App\Services\Accounts\AccountLedgerService();
                $type = $accountLedgerService->voucherType($row->voucher_type);

                return $row->voucher_type != 0 ? '<strong>' . $type['name'] . '</strong>' : '';
            })

            ->editColumn('voucher_no', function ($row) {

                //return $row->voucher_type;
                $accountLedgerService = new \App\Services\Accounts\AccountLedgerService();
                $type = $accountLedgerService->voucherType($row->voucher_type);

                return '<a href="' . (!empty($type['link']) ? route($type['link'], $row->{$type['details_id']}) : '#') . '" id="details_btn" class="fw-bold">' . $row->{$type['voucher_no']} . '</a>';
            })
            ->editColumn('debit', fn ($row) => '<span class="debit fw-bold" data-value="' . $row->debit . '">' . ($row->debit > 0 ? \App\Utils\Converter::format_in_bdt($row->debit) : '') . '</span>')
            ->editColumn('credit', fn ($row) => '<span class="credit fw-bold" data-value="' . $row->credit . '">' . ($row->credit > 0 ? \App\Utils\Converter::format_in_bdt($row->credit) : '') . '</span>')
            ->editColumn('running_balance', function ($row) {

                return $row->running_balance > 0 ? '<span class="running_balance fw-bold">' . \App\Utils\Converter::format_in_bdt(abs($row->running_balance)) . $row->balance_type . '</span>' : '';
            })
            ->rawColumns(['date', 'particulars', 'voucher_type', 'voucher_no', 'debit', 'credit', 'running_balance'])
            ->make(true);
    }

    public function ledgerEntriesPrint(object $request, int $id, object $account): ?object
    {
        $ledgers = '';
        $generalSettings = config('generalSettings');
        $accountStartDate = date('Y-m-d', strtotime($generalSettings['business__account_start_date']));

        $ledgers = $this->ledgerEntriesQuery(request: $request, id: $id, account: $account);

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $this->generateOpeningBalance(accountId: $id, ledgers: $ledgers, fromDateYmd: $fromDateYmd, request: $request, generalSettings: $generalSettings);
        }

        $runningDebit = 0;
        $runningCredit = 0;
        foreach ($ledgers as $ledger) {

            $runningDebit += $ledger->debit;
            $runningCredit += $ledger->credit;

            if ($runningDebit > $runningCredit) {

                $ledger->running_balance = $runningDebit - $runningCredit;
                $ledger->balance_type = ' Dr.';
            } elseif ($runningCredit > $runningDebit) {

                $ledger->running_balance = $runningCredit - $runningDebit;
                $ledger->balance_type = ' Cr.';
            }
        }

        return $ledgers;
    }

    public function ledgerEntriesQuery(object $request, int $id, object $account): ?object
    {
        $query = AccountLedger::query()
            ->whereRaw('concat(account_ledgers.debit,account_ledgers.credit) > 0')
            ->where('account_ledgers.account_id', $id);

        if ($request->customer_account_id) {

            $query->where('account_ledgers.account_id', $request->customer_account_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('account_ledgers.branch_id', null);
            } else {

                $query->where('account_ledgers.branch_id', $request->branch_id);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('account_ledgers.date', $date_range);
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            if ($account?->group?->sub_sub_group_number != 6) {

                $query->where('account_ledgers.branch_id', auth()->user()->branch_id);
            }
        }

        $query->leftJoin('sales', 'account_ledgers.sale_id', 'sales.id')
            ->leftJoin('sale_returns', 'account_ledgers.sale_return_id', 'sale_returns.id')
            ->leftJoin('sale_products', 'account_ledgers.sale_product_id', 'sale_products.id')
            ->leftJoin('sales as productSale', 'sale_products.sale_id', 'productSale.id')
            ->leftJoin('sale_return_products', 'account_ledgers.sale_return_product_id', 'sale_return_products.id')
            ->leftJoin('sale_returns as productSaleReturn', 'sale_return_products.sale_return_id', 'productSaleReturn.id')
            ->leftJoin('purchases', 'account_ledgers.purchase_id', 'purchases.id')
            ->leftJoin('purchase_products', 'account_ledgers.purchase_product_id', 'purchase_products.id')
            ->leftJoin('purchases as productPurchase', 'purchase_products.purchase_id', 'productPurchase.id')
            ->leftJoin('purchase_returns', 'account_ledgers.purchase_return_id', 'purchase_returns.id')
            ->leftJoin('purchase_return_products', 'account_ledgers.purchase_return_product_id', 'purchase_return_products.id')
            ->leftJoin('purchase_returns as productPurchaseReturn', 'purchase_return_products.purchase_return_id', 'productPurchaseReturn.id')
            ->leftJoin('stock_adjustments', 'account_ledgers.adjustment_id', 'stock_adjustments.id')
            ->leftJoin('accounting_voucher_descriptions', 'account_ledgers.voucher_description_id', 'accounting_voucher_descriptions.id')
            ->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id')
            ->with(
                [
                    'account:id,name,account_number,account_group_id',
                    'account.group:id,name,sub_group_number,sub_sub_group_number',
                    'branch:id,name,area_name,branch_code,parent_branch_id',
                    'branch.parentBranch:id,name',
                    'voucherDescription',
                    'voucherDescription.accountingVoucher:id,remarks',
                    'voucherDescription.accountingVoucher.voucherDescriptions',
                    'voucherDescription.accountingVoucher.voucherDescriptions.account:id,name,account_number,account_group_id',
                    'voucherDescription.accountingVoucher.voucherDescriptions.account.group:id,name,sub_group_number,sub_sub_group_number',
                    'voucherDescription.accountingVoucher.voucherDescriptions.paymentMethod:id,name',
                    'voucherDescription.accountingVoucher.voucherDescriptions.references:id,voucher_description_id,sale_id,sale_return_id,purchase_id,purchase_return_id,stock_adjustment_id,amount',
                    'voucherDescription.accountingVoucher.voucherDescriptions.references.sale:id,invoice_id,order_id,order_status',
                    'voucherDescription.accountingVoucher.voucherDescriptions.references.purchase:id,invoice_id,purchase_status',

                    'sale:id,customer_account_id,total_invoice_amount,note,sale_account_id,total_sold_qty,order_discount_amount,order_tax_amount',
                    'sale.salesAccount:id,name',
                    'sale.customer:id,name,phone,address',
                    'sale.saleProducts:id,sale_id,product_id,variant_id,quantity,unit_id,unit_price_inc_tax,subtotal',
                    'sale.saleProducts.product:id,name',
                    'sale.saleProducts.variant:id,variant_name',
                    'sale.saleProducts.unit:id,code_name,base_unit_multiplier',

                    'salesReturn:id,customer_account_id,total_qty,sale_id,sale_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount,note',
                    'salesReturn.salesAccount:id,name',
                    'salesReturn.customer:id,name,phone,address',
                    'salesReturn.saleReturnProducts:id,sale_return_id,product_id,variant_id,unit_id,unit_price_inc_tax,return_qty,return_subtotal',
                    'salesReturn.saleReturnProducts.product:id,name',
                    'salesReturn.saleReturnProducts.variant:id,variant_name',
                    'salesReturn.saleReturnProducts.unit:id,code_name,base_unit_multiplier',

                    'purchase:id,supplier_account_id,total_qty,net_total_amount,order_discount_amount,purchase_tax_amount,total_purchase_amount,purchase_note,purchase_account_id',
                    'purchase.purchaseAccount:id,name',
                    'purchase.purchaseProducts:id,purchase_id,product_id,variant_id,unit_id,quantity,net_unit_cost,line_total',
                    'purchase.purchaseProducts.product:id,name',
                    'purchase.purchaseProducts.variant:id,variant_name',
                    'purchase.purchaseProducts.unit:id,code_name,base_unit_multiplier',

                    'purchaseProduct:id,purchase_id,tax_ac_id',
                    'purchaseProduct.purchase:id,supplier_account_id,total_purchase_amount,purchase_note,purchase_account_id,total_qty,net_total_amount,order_discount_amount,purchase_tax_amount',
                    'purchaseProduct.purchase.purchaseAccount:id,name',
                    'purchaseProduct.purchase.supplier:id,name',
                    'purchaseProduct.purchase.purchaseProducts:id,purchase_id,product_id,variant_id,quantity,unit_id,tax_ac_id,unit_tax_percent,unit_tax_amount,net_unit_cost,line_total',
                    'purchaseProduct.purchase.purchaseProducts.product:id,name',
                    'purchaseProduct.purchase.purchaseProducts.variant:id,variant_name',
                    'purchaseProduct.purchase.purchaseProducts.unit:id,code_name,base_unit_multiplier',

                    'purchaseReturn:id,supplier_account_id,total_qty,purchase_id,purchase_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'purchaseReturn.purchaseAccount:id,name',
                    'purchaseReturn.supplier:id,name,phone,address',
                    'purchaseReturn.purchaseReturnProducts:id,purchase_return_id,product_id,variant_id,unit_cost_inc_tax,return_qty,unit_id,return_subtotal',
                    'purchaseReturn.purchaseReturnProducts.product:id,name',
                    'purchaseReturn.purchaseReturnProducts.variant:id,variant_name',
                    'purchaseReturn.purchaseReturnProducts.unit:id,code_name,base_unit_multiplier',

                    'stockAdjustment:id,expense_account_id,total_qty,net_total_amount,recovered_amount,type,reason',
                    'stockAdjustment:account:id,name',
                    'stockAdjustment:adjustmentProducts:id,adjustmentProducts,product_id,variant_id,quantity,unit_cost_inc_tax,subtotal',
                    'stockAdjustment:adjustmentProducts.product:id,name',
                    'stockAdjustment:adjustmentProducts.variant:id,variant_name',
                    'stockAdjustment:adjustmentProducts.unit:id,code_name,base_unit_multiplier',

                    'saleProduct:id,sale_id,tax_ac_id',
                    'saleProduct.sale:id,customer_account_id,total_invoice_amount,note,sale_account_id,total_sold_qty,order_discount_amount,order_tax_amount',
                    'saleProduct.sale.salesAccount:id,name',
                    'saleProduct.sale.customer:id,name',
                    'saleProduct.sale.saleProducts:id,sale_id,product_id,variant_id,quantity,unit_id,tax_ac_id,unit_tax_percent,unit_tax_amount,unit_price_inc_tax,subtotal',

                    'purchaseReturnProduct:id,purchase_return_id,tax_ac_id',
                    'purchaseReturnProduct:purchaseReturn:id,supplier_account_id,total_qty,purchase_id,purchase_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'purchaseReturnProduct.purchaseReturn.purchaseAccount:id,name',
                    'purchaseReturnProduct.purchaseReturn.supplier:id,name,phone,address',
                    'purchaseReturnProduct.purchaseReturn.purchaseReturnProducts:id,purchase_return_id,product_id,variant_id,unit_id,unit_tax_percent,unit_tax_amount,unit_cost_inc_tax,return_qty,return_subtotal',
                    'purchaseReturnProduct.purchaseReturn.purchaseReturnProducts.product:id,name',
                    'purchaseReturnProduct.purchaseReturn.purchaseReturnProducts.variant:id,variant_name',
                    'purchaseReturnProduct.purchaseReturn.purchaseReturnProducts.unit:id,code_name,base_unit_multiplier',

                    'salesReturnProduct:id,sale_return_id,tax_ac_id',
                    'salesReturnProduct:salesReturn:id,customer_account_id,total_qty,sale_id,sale_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'salesReturnProduct.salesReturn.salesAccount:id,name',
                    'salesReturnProduct.salesReturn.customer:id,name,phone,address',
                    'salesReturnProduct.salesReturn.saleReturnProducts:id,sale_return_id,product_id,variant_id,unit_id,unit_price_inc_tax,unit_tax_percent,unit_tax_amount,return_qty,return_subtotal',
                    'salesReturnProduct.salesReturn.saleReturnProducts.product:id,name',
                    'salesReturnProduct.salesReturn.saleReturnProducts.variant:id,variant_name',
                    'salesReturnProduct.salesReturn.saleReturnProducts.unit:id,code_name,base_unit_multiplier',
                ]
            )
            ->select(
                'account_ledgers.branch_id',
                'account_ledgers.date',
                'account_ledgers.voucher_type',
                'account_ledgers.account_id',
                'account_ledgers.sale_id',
                'account_ledgers.sale_product_id',
                'account_ledgers.sale_return_id',
                'account_ledgers.sale_return_product_id',
                'account_ledgers.purchase_id',
                'account_ledgers.purchase_product_id',
                'account_ledgers.purchase_return_id',
                'account_ledgers.purchase_return_product_id',
                'account_ledgers.adjustment_id',
                'account_ledgers.voucher_description_id',
                'account_ledgers.debit',
                'account_ledgers.credit',
                'account_ledgers.running_balance',
                'account_ledgers.amount_type',
                'sales.id as sale_id',
                'sales.invoice_id as sales_voucher',
                'sale_returns.id as sale_return_id',
                'sale_returns.voucher_no as sale_return_voucher',
                'productSaleReturn.id as product_sale_return_id',
                'productSaleReturn.voucher_no as product_sale_return_voucher',
                'productSale.id as product_sale_id',
                'productSale.invoice_id as product_sale_voucher',
                'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_voucher',
                'productPurchase.id as product_purchase_id',
                'productPurchase.invoice_id as product_purchase_voucher',
                'purchase_returns.id as purchase_return_id',
                'purchase_returns.voucher_no as purchase_return_voucher',
                'productPurchaseReturn.id as product_purchase_return_id',
                'productPurchaseReturn.voucher_no as product_purchase_return_voucher',
                'stock_adjustments.id as adjustment_id',
                'stock_adjustments.voucher_no as stock_adjustment_voucher',
                'accounting_vouchers.id as accounting_voucher_id',
                'accounting_vouchers.voucher_no as accounting_voucher_no',
            );

        return $query->orderBy('account_ledgers.date', 'asc')->orderBy('account_ledgers.id', 'asc')->get();
    }

    private function generateOpeningBalance(int $accountId, object $ledgers, string $fromDateYmd, object $request, array $generalSettings): void
    {
        $accountOpeningBalance = '';
        $accountOpeningBalanceQ = DB::table('account_ledgers')->where('account_ledgers.account_id', $accountId);

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $accountOpeningBalanceQ->where('account_ledgers.branch_id', null);
            } else {

                $accountOpeningBalanceQ->where('account_ledgers.branch_id', $request->branch_id);
            }
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            if ($account?->group?->sub_sub_group_number != 6) {

                $query->where('account_ledgers.branch_id', auth()->user()->branch_id);
            }
        }

        $accountOpeningBalance = $accountOpeningBalanceQ->select(
            DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
            DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
        )->groupBy('account_ledgers.account_id')->get();

        $openingBalanceDebit = $accountOpeningBalance->sum('opening_total_debit');
        $openingBalanceCredit = $accountOpeningBalance->sum('opening_total_credit');

        $currOpeningBalance = 0;
        $currOpeningBalanceSide = 'dr';
        if ($openingBalanceDebit > $openingBalanceCredit) {

            $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
            $currOpeningBalanceSide = 'dr';
        } elseif ($openingBalanceCredit > $openingBalanceDebit) {

            $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
            $currOpeningBalanceSide = 'cr';
        }

        $branchName = '';
        if ($request->branch_name) {

            $branchName = $request->branch_name;
        } else {

            if (auth()->user()?->branch) {

                if (auth()->user()?->branch?->parentBranch) {

                    $branchName = auth()->user()?->branch?->parentBranch->name . '(' . auth()->user()?->branch?->area_name . ')-' . auth()->user()?->branch->branch_code;
                } else {

                    $branchName = auth()->user()?->branch?->name . '(' . auth()->user()?->branch?->area_name . ')-' . auth()->user()?->branch->branch_code;
                }
            } else {

                $branchName = $generalSettings['business__business_name'] . '(' . __('Business') . ')';
            }
        }

        $arr = [
            'id' => 0,
            // 'branch_id' => $request->branch_id ? $request->branch_id : null,
            'branch' => (object) ['id' => null, 'name' => $branchName, 'area_name' => null, 'branch_code' => null, 'parentBranch' => null],
            'voucher_type' => 0,
            'sales_voucher' => null,
            'date' => null,
            'account_id' => $accountId,
            'amount_type' => $currOpeningBalanceSide == 'dr' ? 'debit' : 'credit',
            'debit' => $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0.00,
            'credit' => $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0.00,
            'running_balance' => 0,
            'balance_type' => ' Dr',
        ];

        $stdArr = (object) $arr;

        $ledgers->prepend($stdArr);
    }
}
