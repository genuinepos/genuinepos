<?php

namespace App\Services\Sales;

use App\Models\Sales\CashRegister;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CashRegisterService
{
    public function addCashRegister(object $request)
    {
        $generalSettings = config('generalSettings');
        $dateFormat = $generalSettings['business__date_format'];
        $timeFormat = $generalSettings['business__time_format'];

        $__timeFormat = '';
        if ($timeFormat == '12') {

            $__timeFormat = ' h:i:s';
        } elseif ($timeFormat == '24') {

            $__timeFormat = ' H:i:s';
        }

        $addCashRegister = new CashRegister();
        $addCashRegister->user_id = auth()->user()->id;
        $addCashRegister->date = date($dateFormat.$__timeFormat);
        $addCashRegister->cash_counter_id = $request->cash_counter_id;
        $addCashRegister->cash_account_id = $request->cash_account_id;
        $addCashRegister->sale_account_id = $request->sale_account_id;
        $addCashRegister->opening_cash = $request->opening_cash;
        $addCashRegister->branch_id = auth()->user()->branch_id;
        $addCashRegister->save();
    }

    public function closeCashRegister(int $id, object $request): void
    {
        $closeCashRegister = $this->singleCashRegister()->where('id', $id)->first();
        $closeCashRegister->closing_cash = $request->closing_cash;
        $closeCashRegister->closing_note = $request->closing_note;
        $closeCashRegister->closed_at = Carbon::now()->format('Y-m-d H:i:s');
        $closeCashRegister->status = 0;
        $closeCashRegister->save();
    }

    public function cashRegisterData($id): array
    {
        $receivedByAccounts = DB::table('cash_register_transactions')
            ->leftJoin('accounting_voucher_descriptions', 'cash_register_transactions.voucher_description_id', 'accounting_voucher_descriptions.id')
            ->leftJoin('accounts', 'accounting_voucher_descriptions.account_id', 'accounts.id')
            ->where('cash_register_transactions.cash_register_id', $id)
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                DB::raw('SUM(accounting_voucher_descriptions.amount) as total_received'),
            )->groupBy('cash_register_transactions.cash_register_id', 'accounts.id', 'accounts.name', 'accounts.account_number')
            ->having('total_received', '>', 0)
            ->get();

        $receivedByPaymentMethods = DB::table('cash_register_transactions')
            ->leftJoin('accounting_voucher_descriptions', 'cash_register_transactions.voucher_description_id', 'accounting_voucher_descriptions.id')
            ->leftJoin('payment_methods', 'accounting_voucher_descriptions.payment_method_id', 'payment_methods.id')
            ->where('cash_register_transactions.cash_register_id', $id)
            ->select(
                'payment_methods.id',
                'payment_methods.name',
                DB::raw('SUM(accounting_voucher_descriptions.amount) as total_received'),
            )->groupBy('cash_register_transactions.cash_register_id', 'payment_methods.id', 'payment_methods.name')
            ->having('total_received', '>', 0)
            ->get();

        $totalCashReceived = DB::table('cash_register_transactions')
            ->leftJoin('accounting_voucher_descriptions', 'cash_register_transactions.voucher_description_id', 'accounting_voucher_descriptions.id')
            ->leftJoin('accounts', 'accounting_voucher_descriptions.account_id', 'accounts.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('cash_register_transactions.cash_register_id', $id)
            ->select(
                DB::raw('SUM(CASE WHEN account_groups.sub_sub_group_number = 2 THEN accounting_voucher_descriptions.amount ELSE 0 END) as total_cash_received')
            )->groupBy('cash_register_transactions.cash_register_id')
            ->get();

        $totalSaleAndDue = DB::table('cash_register_transactions')
            ->leftJoin('sales', 'cash_register_transactions.sale_id', 'sales.id')
            ->where('cash_register_transactions.cash_register_id', $id)
            ->select(
                DB::raw('SUM(sales.total_invoice_amount) as total_sale'),
                DB::raw('SUM(sales.due) as total_due')
            )->groupBy('cash_register_transactions.cash_register_id')
            ->get();

        return [
            'receivedByAccounts' => $receivedByAccounts,
            'receivedByPaymentMethods' => $receivedByPaymentMethods,
            'totalCashReceived' => $totalCashReceived,
            'totalSaleAndDue' => $totalSaleAndDue,
        ];
    }

    public function singleCashRegister(array $with = null): ?object
    {
        $query = CashRegister::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
