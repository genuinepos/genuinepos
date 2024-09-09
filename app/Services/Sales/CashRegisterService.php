<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Models\Sales\CashRegister;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CashRegisterService
{
    function cashRegistersTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $cashRegisters = null;
        $query = DB::table('cash_registers')
            ->leftJoin('cash_counters', 'cash_registers.cash_counter_id', 'cash_counters.id')
            ->leftJoin('branches', 'cash_registers.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('currencies', 'branches.currency_id', 'currencies.id')
            ->leftJoin('users', 'cash_registers.user_id', 'users.id');

        $this->filteredQuery(request: $request, query: $query);

        $cashRegisters = $query->select(
            'cash_registers.id',
            'cash_registers.branch_id',
            'cash_registers.opening_cash',
            'cash_registers.date',
            'cash_registers.closed_at',
            'cash_registers.closing_cash',
            'cash_registers.status',
            'cash_registers.closing_note',
            'cash_counters.counter_name as cash_counter_name',
            'cash_counters.short_name as cash_counter_short_name',
            'branches.name as branch_name',
            'branches.area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'users.prefix as user_prefix',
            'users.name as user_name',
            'users.last_name as user_last_name',
            'currencies.currency_rate as c_rate'
        )->orderBy('cash_registers.date', 'desc');

        $dataTables = DataTables::of($cashRegisters);

        $dataTables->addColumn('action', function ($row) {

            $html = '<div class="dropdown table-dropdown">';

            $html .= '<a href="' . route('cash.register.show', [$row->id]) . '" class="btn btn-sm btn-info" id="cashRegisterDetailsBtn" title="' . __('Cash Register Details') . '"><span class="far fa-eye"></span></a>';

            if ($row->status == BooleanType::True->value) {

                if (auth()->user()->can('register_close') && auth()->user()->can('another_register_close')) {

                    $html .= '<a href="' . route('cash.register.close', [$row->id]) . '" class="btn btn-sm btn-danger ms-1 px-1" id="closeCashRegisterBtn" title="' . __('Close Cash Register') . '"><span class="fas fa-times"></span></a>';
                } else if (auth()->user()->can('register_close') && !auth()->user()->can('another_register_close')) {

                    if (auth()->user()->id == $row->user_id) {

                        $html .= '<a href="' . route('cash.register.close', [$row->id]) . '" class="btn btn-sm btn-danger ms-1 px-1" id="closeCashRegisterBtn" title="' . __('Close Cash Register') . '"><span class="fas fa-times"></span></a>';
                    }
                } else if (!auth()->user()->can('register_close') && auth()->user()->can('another_register_close')) {

                    $html .= '<a href="' . route('cash.register.close', [$row->id]) . '" class="btn btn-sm btn-danger ms-1 px-1" id="closeCashRegisterBtn" title="' . __('Close Cash Register') . '"><span class="fas fa-times"></span></a>';
                }
            }
            $html .= '</div>';

            return $html;
        });

        $dataTables->editColumn('cash_counter', function ($row) {

            return $row->cash_counter_name . ' (' . $row->cash_counter_short_name . ')';
        });

        $dataTables->editColumn('opened_at', function ($row) use ($generalSettings) {

            $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

            return date($__date_format, strtotime($row->date));
        });

        $dataTables->editColumn('closed_at', function ($row) use ($generalSettings) {

            if ($row->closed_at) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row->closed_at));
            }
        });

        $dataTables->editColumn('branch', function ($row) use ($generalSettings) {

            if ($row->branch_id) {

                if ($row->parent_branch_name) {

                    return $row->parent_branch_name . '(' . $row->area_name . ')';
                } else {

                    return $row->branch_name . '(' . $row->area_name . ')';
                }
            } else {

                return $generalSettings['business_or_shop__business_name'];
            }
        });

        $dataTables->editColumn('opening_cash', fn($row) => '<span class="opening_cash" data-value="' . curr_cnv($row->opening_cash, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->opening_cash, $row->c_rate, $row->branch_id)) . '</span>');

        $dataTables->editColumn('closing_cash', fn($row) => '<span class="closing_cash" data-value="' . curr_cnv($row->closing_cash, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->closing_cash, $row->c_rate, $row->branch_id)) . '</span>');

        $dataTables->editColumn('status', function ($row) {

            if ($row->status == BooleanType::False->value) {

                return '<span class="text-danger"><b>' . __('Closed') . '</span>';
            } elseif ($row->status == BooleanType::True->value) {

                return '<span class="text-success"><b>' . __('Opened') . '</b></span>';
            }
        });

        $dataTables->editColumn('user', function ($row) {

            return $row->user_prefix . ' ' . $row->user_name . ' ' . $row->user_last_name;
        });

        $dataTables->rawColumns(['action', 'cash_counter', 'opened_at', 'closed_at', 'branch', 'opening_cash', 'closing_cash', 'status', 'user']);

        return $dataTables->make(true);
    }

    public function addCashRegister(object $request): ?array
    {
        $restrictions = $this->restrictions(cashCounterId: $request->cash_counter_id);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $dateFormat = $generalSettings['business_or_shop__date_format'];
        $timeFormat = $generalSettings['business_or_shop__time_format'];

        $__timeFormat = '';
        if ($timeFormat == '12') {

            $__timeFormat = ' h:i:s';
        } elseif ($timeFormat == '24') {

            $__timeFormat = ' H:i:s';
        }

        $addCashRegister = new CashRegister();
        $addCashRegister->user_id = auth()->user()->id;
        $addCashRegister->date = date($dateFormat . $__timeFormat);
        $addCashRegister->cash_counter_id = $request->cash_counter_id;
        $addCashRegister->cash_account_id = $request->cash_account_id;
        $addCashRegister->sale_account_id = $request->sale_account_id;
        $addCashRegister->opening_cash = $request->opening_cash;
        $addCashRegister->branch_id = auth()->user()->branch_id;
        $addCashRegister->save();

        return null;
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

    private function restrictions($cashCounterId)
    {
        $activeCashCounter = $this->singleCashRegister()
            ->where('cash_counter_id', $cashCounterId)
            ->where('status', BooleanType::True->value)->first();

        if (isset($activeCashCounter)) {

            return ['pass' => false, 'msg' => __('Selected cash counter has already been registered by another user.')];
        }

        return ['pass' => true];
    }

    private function filteredQuery(object $request, object $query): object
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('cash_registers.branch_id', null);
            } else {

                $query->where('cash_registers.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('cash_registers.user_id', $request->user_id);
        }

        if ($request->status) {

            $query->where('cash_registers.status', $request->status);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('cash_registers.date', $date_range); // Final
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('cash_registers.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
