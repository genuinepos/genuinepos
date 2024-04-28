<?php

namespace Modules\SAAS\Services;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Modules\SAAS\Utils\GioInfo;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Enums\SubscriptionTransactionType;
use Modules\SAAS\Entities\UserSubscriptionTransaction;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class UserSubscriptionTransactionService implements UserSubscriptionTransactionServiceInterface
{
    public function subscriptionTransactionsTable(object $request, ?int $userId = null): object
    {
        $transactions = null;
        $query = DB::table('user_subscription_transactions')
            ->leftJoin('user_subscriptions', 'user_subscription_transactions.user_subscription_id', 'user_subscriptions.id')
            ->leftJoin('plans', 'user_subscription_transactions.plan_id', 'plans.id')
            ->leftJoin('users', 'user_subscriptions.user_id', 'users.id')
            ->leftJoin('tenants', 'users.tenant_id', 'tenants.id');

        if (isset($userId)) {
            $query->where('users.id', $userId);
        }

        if ($request->user_id) {

            $query->where('users.id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('user_subscriptions.created_at', $date_range); // Final
        }

        $query->select(
            'user_subscription_transactions.id',
            'user_subscription_transactions.transaction_type',
            'user_subscription_transactions.user_subscription_id',
            'user_subscription_transactions.plan_id',
            'user_subscription_transactions.payment_method_provider_name',
            'user_subscription_transactions.payment_method_name',
            'user_subscription_transactions.payment_trans_id',
            'user_subscription_transactions.net_total',
            'user_subscription_transactions.discount',
            'user_subscription_transactions.total_payable_amount',
            'user_subscription_transactions.paid',
            'user_subscription_transactions.due',
            'user_subscription_transactions.payment_status',
            'user_subscription_transactions.payment_date',
            'user_subscription_transactions.details',
            'user_subscription_transactions.created_at',
            'users.name as user_name',
            'tenants.id as tenant_id',
        );

        $transactions = $query;

        return DataTables::of($transactions)

            ->addColumn('action', function ($row) {

                $html = '';
                $html .= '<a href="' . route('saas.tenants.user.subscription.transaction.pdf.details', $row->id) . '" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa-solid fa-eye"></i></a>';
                // $html .= '<a href="#" class="btn btn-sm btn-primary text-white ms-1"><i class="fa-solid fa-download"></i></a>';

                return $html;
            })

            ->editColumn('created_at', function ($row) {

                return date('d-m-Y', strtotime($row->created_at));
            })

            ->editColumn('payment_date', function ($row) {

                if ($row->payment_date) {

                    return date('d-m-Y', strtotime($row->payment_date));
                }
            })

            ->editColumn('user', function ($row) {

                return $row->user_name . '(' . $row->tenant_id . ')';
            })

            ->editColumn('transaction_type', function ($row) {

                return SubscriptionTransactionType::tryFrom($row->transaction_type)->name;
            })

            ->editColumn('payment_status', function ($row) {

                if ($row->payment_status == BooleanType::True->value) {

                    return '<span class="text-success">' . __('Paid') . '</span>';
                } else {

                    return '<span class="text-success">' . __('Due') . '</span>';
                }
            })

            ->editColumn('net_total', function ($row) {

                $netTotal = 0;
                $details = json_decode($row?->details, true);
                if ($details['country'] != 'bangladesh') {

                    $netTotal = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($row->net_total);
                } else {

                    $netTotal = $row->net_total;
                }

                return '<span class="net_total" data-value="' . $netTotal . '">' . \App\Utils\Converter::format_in_bdt($netTotal) . '</span>';
            })

            ->editColumn('discount', function ($row) {

                $discount = 0;
                $details = json_decode($row?->details, true);
                if ($details['country'] != 'bangladesh') {

                    $discount = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($row->discount);
                } else {

                    $discount = $row->discount;
                }

                return '<span class="discount" data-value="' . $discount . '">' . \App\Utils\Converter::format_in_bdt($discount) . '</span>';
            })

            ->editColumn('total_payable_amount', function ($row) {

                $totalPayable = 0;
                $details = json_decode($row?->details, true);
                if ($details['country'] != 'bangladesh') {

                    $totalPayable = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($row->total_payable_amount);
                } else {

                    $totalPayable = $row->total_payable_amount;
                }

                return '<span class="total_payable_amount" data-value="' . $totalPayable . '">' . \App\Utils\Converter::format_in_bdt($totalPayable) . '</span>';
            })

            ->editColumn('paid', function ($row) {

                $paid = 0;
                $details = json_decode($row?->details, true);
                if ($details['country'] != 'bangladesh') {

                    $paid = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($row->paid);
                } else {

                    $paid = $row->paid;
                }

                return '<span class="paid" data-value="' . $paid . '">' . \App\Utils\Converter::format_in_bdt($paid) . '</span>';
            })

            ->editColumn('due', function ($row) {

                $due = 0;
                $details = json_decode($row?->details, true);
                if ($details['country'] != 'bangladesh') {

                    $due = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($row->due);
                } else {
                    
                    $due = $row->due;
                }

                return '<span class="due" data-value="' . $due . '">' . \App\Utils\Converter::format_in_bdt($due) . '</span>';
            })

            ->rawColumns(['action', 'created_at', 'payment_date', 'user', 'transaction_type', 'payment_status', 'net_total', 'discount', 'total_payable_amount', 'paid', 'due'])
            ->make(true);
    }

    public function addUserSubscriptionTransaction(object $request, object $userSubscription, int $transactionType, string $transactionDetailsType, ?object $plan = null): void
    {
        $addTransaction = new UserSubscriptionTransaction();
        $addTransaction->transaction_type = $transactionType;
        $addTransaction->user_subscription_id = $userSubscription->id;
        $addTransaction->plan_id = $plan?->id;
        $addTransaction->payment_method_name = $request->payment_method_name;
        $addTransaction->payment_trans_id = $request->payment_trans_id;
        $addTransaction->net_total = isset($request->net_total) ? $request->net_total : 0;
        $addTransaction->coupon_code = isset($request->coupon_code) ? $request->coupon_code : 0;
        $addTransaction->discount_percent = isset($request->discount_percent) ? $request->discount_percent : 0;
        $addTransaction->discount = isset($request->discount) ? $request->discount : 0;
        $addTransaction->total_payable_amount = isset($request->total_payable) ? $request->total_payable : 0;
        $addTransaction->paid = $request->payment_status == BooleanType::True->value ? $request->total_payable : 0;
        $addTransaction->due = $request->payment_status == BooleanType::False->value ? $request->total_payable : 0;
        $addTransaction->payment_status = $request->payment_status;
        $addTransaction->payment_date = $request->payment_status == BooleanType::True->value ? Carbon::now() : null;
        // $addTransaction->currency = $gioInfo['country'] == 'bangladesh' ? 'TK' : 'USD';
        // $addTransaction->currency_rate_in_bdt = $gioInfo['currency_rate'];
        $addTransaction->details_type = $transactionDetailsType;

        $transactionDetails = $this->transactionDetails(request: $request, detailsType: $transactionDetailsType, plan: $plan);
        $addTransaction->details = json_encode($transactionDetails);

        $addTransaction->save();
    }

    private function transactionDetails(object $request, string $detailsType, ?object $plan)
    {
        $gioInfo = GioInfo::getInfo();
        if ($detailsType == 'upgrade_plan_from_trial') {

            $businessPricePeriodCount = null;
            $businessPrice = 0;
            if (isset($request->has_business)) {

                $businessPricePeriodCount = $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count;
                $businessPrice = $request->business_price ? $request->business_price : 0;
            }

            return [
                'country' => $gioInfo['country'],
                'has_business' => isset($request->has_business) ? 1 : 0,
                'business_price_period' => isset($request->has_business) ? $request->business_price_period : null,
                'business_price_period_count' => $businessPricePeriodCount,
                'business_price' => $businessPrice,
                'business_subtotal' => isset($request->has_business) ? $request->business_subtotal : 0,
                'shop_count' => $request->shop_count,
                'shop_price_period' => $request->shop_price_period,
                'shop_price_period_count' => $request->shop_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->shop_price_period_count,
                'shop_price' => isset($request->shop_price) ? $request->shop_price : 0,
                'shop_subtotal' => isset($request->shop_subtotal) ? $request->shop_subtotal : 0,
                'net_total' => isset($request->net_total) ? $request->net_total : 0,
                'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : 0,
                'discount_percent' => isset($request->discount_percent) ? $request->discount_percent : 0,
                'discount' => isset($request->discount) ? $request->discount : 0,
                'total_amount' => isset($request->total_payable) ? $request->total_payable : 0,
            ];
        } elseif ($detailsType == 'direct_buy_plan') {

            $businessPricePeriodCount = null;
            $businessPrice = 0;
            if (isset($request->has_business)) {

                $businessPricePeriodCount = $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count;
                $businessPrice = $request->business_price ? $request->business_price : 0;
            }

            return [
                'country' => $gioInfo['country'],
                'has_business' => isset($request->has_business) ? 1 : 0,
                'business_price_period' => isset($request->has_business) ? $request->business_price_period : null,
                'business_price_period_count' => $businessPricePeriodCount,
                'business_price' => $businessPrice,
                'business_subtotal' => isset($request->has_business) ? $request->business_subtotal : 0,
                'shop_count' => $request->shop_count,
                'shop_price_period' => $request->shop_price_period,
                'shop_price_period_count' => $request->shop_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->shop_price_period_count,
                'shop_price' => isset($request->shop_price) ? $request->shop_price : 0,
                'shop_subtotal' => isset($request->shop_subtotal) ? $request->shop_subtotal : 0,
                'net_total' => isset($request->net_total) ? $request->net_total : 0,
                'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : 0,
                'discount_percent' => isset($request->discount_percent) ? $request->discount_percent : 0,
                'discount' => isset($request->discount) ? $request->discount : 0,
                'total_amount' => isset($request->total_payable) ? $request->total_payable : 0,
            ];
        } elseif ($detailsType == 'upgrade_plan_from_real_plan') {

            return [
                'country' => $gioInfo['country'],
                'net_total' => isset($request->net_total) ? $request->net_total : 0,
                'total_adjusted_amount' => isset($request->total_adjusted_amount) ? $request->total_adjusted_amount : 0,
                'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : 0,
                'discount_percent' => isset($request->discount_percent) ? $request->discount_percent : 0,
                'discount' => isset($request->discount) ? $request->discount : 0,
                'total_amount' => isset($request->total_payable) ? $request->total_payable : 0,
            ];
        } elseif ($detailsType == 'add_shop') {

            return [
                'country' => $gioInfo['country'],
                'increase_shop_count' => isset($request->increase_shop_count) ? $request->increase_shop_count : 0,
                'shop_price' => isset($request->shop_price) ? $request->shop_price : 0,
                'shop_price_period' => isset($request->shop_price_period) ? $request->shop_price_period : null,
                'shop_price_period_count' => $request->shop_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->shop_price_period_count,
                'net_total' => isset($request->net_total) ? $request->net_total : 0,
                'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : 0,
                'discount_percent' => isset($request->discount_percent) ? $request->discount_percent : 0,
                'discount' => isset($request->discount) ? $request->discount : 0,
                'total_amount' => isset($request->total_payable) ? $request->total_payable : 0,
            ];
        } elseif ($detailsType == 'shop_renew') {

            return [
                'country' => $gioInfo['country'],
                'data' => $request->all(),
                'has_business' => isset($request->has_business) ? 1 : 0,
                'total_renew_shop' => isset($request->shop_expire_date_history_ids) ? count($request->shop_expire_date_history_ids) : 0,
                'net_total' => isset($request->net_total) ? $request->net_total : 0,
                'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : 0,
                'discount_percent' => isset($request->discount_percent) ? $request->discount_percent : 0,
                'discount' => isset($request->discount) ? $request->discount : 0,
                'total_amount' => isset($request->total_payable) ? $request->total_payable : 0,
            ];
        } elseif ($detailsType == 'add_business') {

            return [
                'country' => $gioInfo['country'],
                'data' => $request->all(),
                'net_total' => isset($request->net_total) ? $request->net_total : 0,
                'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : 0,
                'discount_percent' => isset($request->discount_percent) ? $request->discount_percent : 0,
                'discount' => isset($request->discount) ? $request->discount : 0,
                'total_amount' => isset($request->total_payable) ? $request->total_payable : 0,
            ];
        }
    }

    public function updateDueTransactionStatus(object $request, ?object $dueSubscriptionTransaction): void
    {
        if (isset($dueSubscriptionTransaction)) {

            $dueSubscriptionTransaction->payment_status = $request->payment_status;
            $dueSubscriptionTransaction->payment_date = Carbon::now();
            $dueSubscriptionTransaction->paid = $dueSubscriptionTransaction->total_payable_amount;
            $dueSubscriptionTransaction->due = 0;
            $dueSubscriptionTransaction->payment_method_name = $request->payment_method_name;
            $dueSubscriptionTransaction->payment_trans_id = $request->payment_trans_id;
            $dueSubscriptionTransaction->save();
        }
    }

    public function userSubscriptionTransactions(array $with = null): object
    {
        $query = UserSubscriptionTransaction::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function subscriptionTransactions(?array $with = null): ?object
    {
        $query = UserSubscriptionTransaction::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singleUserSubscriptionTransaction(int $id, ?array $with = null): ?object
    {
        $query = UserSubscriptionTransaction::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id);
    }
}
