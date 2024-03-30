<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>{{ __("Upgrade Plan") }} - GPOS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $rtl = app()->isLocale('ar');
    @endphp

    @if ($rtl)
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic&display=swap" rel="stylesheet">
        <link href="{{ asset('assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="{{ asset('assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    @endif

    <link rel="stylesheet" href="{{ asset('assets/fontawesome6/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/cart.css') }}">
</head>

@php
    $planPriceCurrency = \Modules\SAAS\Utils\PlanPriceCurrencySymbol::currencySymbol();

    function getMonths($startDate, $endDate)
    {
        if ($startDate <= $endDate) {

            $startDate = new \DateTime($startDate);
            $endDate = new \DateTime($endDate);

            // Calculate the difference in months
            $interval = $startDate->diff($endDate);
            $months = $interval->y * 12 + $interval->m;
            return (int) $months;
        } else {

            return 0;
        }
    }

    function getMonthsForTotalPriceCalculation($startDate, $endDate)
    {
        if ($startDate <= $endDate) {
            $start_date = new \DateTime($startDate);
            $end_date = new \DateTime($endDate);

            // Calculate the difference in days
            $interval = $start_date->diff($end_date);
            $days_difference = $interval->days + 1;

            // Calculate the total number of days in the month
            $total_days_in_month = cal_days_in_month(CAL_GREGORIAN, $start_date->format('m'), $start_date->format('Y'));

            // Calculate the fraction of a month
            return $days_difference / $total_days_in_month;
        } else {

            return 0;
        }
    }

    $totalAdjustableAmount = 0;
    $totalPrice = 0;
    foreach ($branches as $branch) {
        $shopExpireDateHistory = $branch->shopExpireDateHistory;
        if ($shopExpireDateHistory->price_period == 'lifetime') {

            $totalPrice += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->lifetime_price);
        } elseif ($shopExpireDateHistory->price_period == 'month') {

            $leftMonth = getMonthsForTotalPriceCalculation(startDate: date('Y-m-d'), endDate: $shopExpireDateHistory->expire_date);
            $totalPrice += $leftMonth * \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month);
        } elseif ($shopExpireDateHistory->price_period == 'year') {

            $leftMonth = getMonthsForTotalPriceCalculation(startDate: date('Y-m-d'), endDate: $shopExpireDateHistory->expire_date);
            $totalPrice += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount(($plan->price_per_year / 12) * $leftMonth);
        }

        if ($shopExpireDateHistory->price_period == 'lifetime') {

            $totalAdjustableAmount += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->lifetime_price) - \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($shopAdjustablePrice);
            $branch->adjusted_amount = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->lifetime_price) - \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($shopAdjustablePrice);
        } elseif ($shopExpireDateHistory->price_period == 'month') {

            $shopAdjustablePrice = $branch->shopExpireDateHistory->adjustable_price;
            $leftMonth = getMonths(startDate: date('Y-m-d'), endDate: $shopExpireDateHistory->expire_date);
            $totalAdjustableAmount += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($shopAdjustablePrice) * $leftMonth;
            $branch->adjusted_amount = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($shopAdjustablePrice) * $leftMonth;
        } elseif ($shopExpireDateHistory->price_period == 'year') {

            $shopAdjustablePrice = $branch->shopExpireDateHistory->adjustable_price;
            $leftMonth = getMonths(startDate: date('Y-m-d'), endDate: $shopExpireDateHistory->expire_date);
            $totalAdjustableAmount += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($shopAdjustablePrice / 12) * $leftMonth;
            $branch->adjusted_amount = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($shopAdjustablePrice / 12) * $leftMonth;
        }
    }

    foreach ($leftBranchExpireDateHistories as $leftBranchExpireDateHistory) {

        for ($i = 0; $i < $leftBranchExpireDateHistory->left_count; $i++) {

            if ($leftBranchExpireDateHistory->price_period == 'lifetime') {

                $totalPrice += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->lifetime_price);
            } elseif ($leftBranchExpireDateHistory->price_period == 'month') {

                $leftMonth = getMonthsForTotalPriceCalculation(startDate: date('Y-m-d'), endDate: $leftBranchExpireDateHistory->expire_date);
                $totalPrice += $leftMonth * \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month);
            } elseif ($leftBranchExpireDateHistory->price_period == 'year') {

                $leftMonth = getMonthsForTotalPriceCalculation(startDate: date('Y-m-d'), endDate: $leftBranchExpireDateHistory->expire_date);
                $totalPrice += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_year / 12) * $leftMonth;
            }

            if ($leftBranchExpireDateHistory->price_period == 'lifetime') {

                $totalAdjustableAmount += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->lifetime_price) - \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($currentSubscription->adjustable_price);

                $leftBranchExpireDateHistory->adjusted_amount = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->lifetime_price) - \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($currentSubscription->adjustable_price);
            } elseif ($leftBranchExpireDateHistory->price_period == 'month') {

                $shopAdjustablePrice = $leftBranchExpireDateHistory->adjustable_price;
                $leftMonth = getMonths(startDate: date('Y-m-d'), endDate: $leftBranchExpireDateHistory->expire_date);
                $totalAdjustableAmount += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($shopAdjustablePrice) * $leftMonth;

                $leftBranchExpireDateHistory->adjusted_amount = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($shopAdjustablePrice) * $leftMonth;
            } elseif ($leftBranchExpireDateHistory->price_period == 'year') {

                $shopAdjustablePrice = $leftBranchExpireDateHistory->adjustable_price;
                $leftMonth = getMonths(startDate: date('Y-m-d'), endDate: $leftBranchExpireDateHistory->expire_date);
                $totalAdjustableAmount += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($shopAdjustablePrice / 12) * $leftMonth;

                $leftBranchExpireDateHistory->adjusted_amount = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($shopAdjustablePrice / 12) * $leftMonth;
            }
        }
    }

    if ($currentSubscription->has_business == 1) {

        if ($currentSubscription->price_period == 'lifetime') {

            $totalPrice += $plan->business_lifetime_price;
        } elseif ($currentSubscription->price_period == 'month') {

            $leftMonth = getMonthsForTotalPriceCalculation(startDate: date('Y-m-d'), endDate: $currentSubscription->business_expire_date);
            $totalPrice += $leftMonth * \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->business_price_per_month);
        } elseif ($currentSubscription->price_period == 'year') {

            $leftMonth = getMonthsForTotalPriceCalculation(startDate: date('Y-m-d'), endDate: $currentSubscription->business_expire_date);
            $totalPrice += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->business_price_per_year / 12) * $leftMonth;
        }

        if ($currentSubscription->business_price_period == 'lifetime') {

            $totalAdjustableAmount += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->business_lifetime_price) - \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($currentSubscription->business_adjustable_price);

            $currentSubscription->adjusted_amount = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->business_lifetime_price) - \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($currentSubscription->business_adjustable_price);
        } elseif ($currentSubscription->business_price_period == 'month') {

            $leftMonth = getMonths(startDate: date('Y-m-d'), endDate: $currentSubscription->business_expire_date);
            $totalAdjustableAmount += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($currentSubscription->business_adjustable_price) * $leftMonth;

            $currentSubscription->adjusted_amount = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($currentSubscription->business_adjustable_price) * $leftMonth;
        } elseif ($currentSubscription->business_price_period == 'year') {

            $leftMonth = getMonths(startDate: date('Y-m-d'), endDate: $currentSubscription->business_expire_date);
            $totalAdjustableAmount += \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($currentSubscription->business_adjustable_price / 12) * $leftMonth;

            $currentSubscription->adjusted_amount = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($currentSubscription->business_adjustable_price / 12) * $leftMonth;
        }
    }

    $subtotal = $totalPrice - $totalAdjustableAmount;
@endphp

<body class="inner">
    <div class="tab-section py-120">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tab-nav">
                        <button class="single-nav active" data-tab="stepOneTab">
                            <span class="txt">{{ __('Step One') }}</span>
                            <span class="sl-no">{{ __('01') }}</span>
                        </button>

                        <button class="single-nav" data-tab="stepTwoTab">
                            <span class="txt">{{ __('Step Two') }}</span>
                            <span class="sl-no">{{ __('02') }}</span>
                        </button>

                        <button class="single-nav" data-tab="stepThreeTab">
                            <span class="txt">{{ __('Step Three') }}</span>
                            <span class="sl-no">{{ __('03') }}</span>
                        </button>
                    </div>

                    <div class="tab-contents">
                        <div class="single-tab active" id="stepOneTab">
                            <div class="table-wrap revel-table">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Package Name') }}</th>
                                                <th>{{ __('Store Quantity') }}</th>
                                                <th>{{ __('Total Price') }}</th>
                                                <th>{{ __('Adjustable Amount') }}</th>
                                                <th>{{ __('Subtotal') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    <p>{{ __('Current Plan') }} (<b>{{ $currentSubscription->plan->name }}</b>) <i class="fa-solid fa-xmark text-danger"></i></p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{ $plan->name }}</td>
                                                <td>
                                                    {{ $currentSubscription->current_shop_count }}
                                                </td>

                                                <td>
                                                    <span class="price-txt">{{ $planPriceCurrency }} <span class="total-price">{{ \App\Utils\Converter::format_in_bdt($totalPrice) }}</span></span>
                                                </td>

                                                <td>
                                                    <span class="price-txt text-danger">{{ $planPriceCurrency }} <span class="adjusted-price">{{ \App\Utils\Converter::format_in_bdt($totalAdjustableAmount) }}</span></span>
                                                </td>

                                                <td><span class="price-txt">{{ $planPriceCurrency }} <span class="total-price">{{ \App\Utils\Converter::format_in_bdt($subtotal) }}</span></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h6 class="mt-3"><a href="#" id="togglePriceAdjustmentDetails" class="text-primary">{{ __('Price Adjustment Details') }}</a></h6>
                                <div class="table-responsive" style="display:none;" id="priceAdjustmentDetailsTable">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>{{ __('No.') }}</th>
                                                <th>{{ __('Shop/Business Name') }}</th>
                                                <th>{{ __('Expire On') }}</th>
                                                <th>{{ __('Adjusted Amount') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $index = 1;
                                            @endphp

                                            @if ($currentSubscription->has_business == 1)
                                                <tr>
                                                    <td>{{ $index }}</td>
                                                    <td>{{ $generalSettings['business_or_shop__business_name'] }}({{ __("Business") }})</td>
                                                    <td class="{{ date('Y-m-d') > $currentSubscription->business_expire_date ? 'text-danger' : 'text-success' }}">
                                                        @if ($currentSubscription->business_price_period == 'lifetime')

                                                            {{ __("Lifetime") }}
                                                        @elseif($currentSubscription->business_price_period == 'month')

                                                            {{ $currentSubscription->business_expire_date .'('. __('Monthly').')' }}
                                                        @elseif($branch?->shopExpireDateHistory->price_period == 'year')

                                                            {{ $currentSubscription->business_expire_date .'('. __('Yearly').')' }}
                                                        @endif
                                                    </td>

                                                    <td class="text-danger">
                                                        {{ \App\Utils\Converter::format_in_bdt($currentSubscription->adjusted_amount) }}
                                                    </td>
                                                </tr>
                                            @endif

                                            @foreach ($branches as $branch)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if ($branch?->parentBranch)
                                                            {{ $branch->parentBranch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}
                                                        @else
                                                            {{ $branch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}
                                                        @endif
                                                    </td>

                                                    <td class="{{ date('Y-m-d') > $branch->expire_date ? 'text-danger' : 'text-success' }}">
                                                        @if ($branch?->shopExpireDateHistory->price_period == 'lifetime')

                                                            {{ __('Lifetime') }}
                                                        @elseif($branch?->shopExpireDateHistory->price_period == 'month')

                                                            {{ $branch?->shopExpireDateHistory->expire_date .'('. __('Monthly').')' }}
                                                        @elseif($branch?->shopExpireDateHistory->price_period == 'year')

                                                            {{ $branch?->shopExpireDateHistory->expire_date .'('. __('Yearly').')' }}
                                                        @endif
                                                    </td>

                                                    <td class="text-danger">
                                                        {{ \App\Utils\Converter::format_in_bdt($branch->adjusted_amount) }}
                                                    </td>
                                                </tr>
                                                @php
                                                    $index++;
                                                @endphp
                                            @endforeach

                                            @foreach ($leftBranchExpireDateHistories as $leftBranchExpireDateHistory)
                                                @for ($i = 0; $i < $leftBranchExpireDateHistory->left_count; $i++)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ __("Shop Not Yet to be created") }}</td>

                                                        <td class="{{ date('Y-m-d') > $leftBranchExpireDateHistory->expire_date ? 'text-danger' : 'text-success' }}">
                                                            @if ($leftBranchExpireDateHistory->price_period == 'lifetime')

                                                                {{ __('Lifetime') }}
                                                            @elseif($leftBranchExpireDateHistory->price_period == 'month')

                                                                {{ $leftBranchExpireDateHistory->expire_date .'('. __('Monthly').')' }}
                                                            @elseif($leftBranchExpireDateHistory->price_period == 'year')

                                                                {{ $leftBranchExpireDateHistory->expire_date .'('. __('Yearly').')' }}
                                                            @endif
                                                        </td>

                                                        <td class="text-danger">
                                                            {{ \App\Utils\Converter::format_in_bdt($leftBranchExpireDateHistory->adjusted_amount) }}
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $index++;
                                                    @endphp
                                                @endfor
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">{{ __("Total") }} : </th>
                                                <th class="text-danger">{{ \App\Utils\Converter::format_in_bdt($totalAdjustableAmount) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="btn-box" id="coupon_success_msg" style="display:none;">
                                    <p class="bg-success d-block p-3 m-0"><span class="text-white">{{ __("Applied Coupon is") }} : <span id="applied_coupon_code"></span></span> <a href="#" class="btn btn-sm btn-danger" id="remove_applied_coupon">X</a></p>
                                </div>

                                <div class="btn-box">
                                    <div class="cart-coupon-form" id="coupon_code_applying_area">
                                        <input type="text" name="coupon_code" id="coupon_code" placeholder="{{ __("Enter Your Coupon Code") }}">
                                        <input type="hidden" name="coupon_id" id="coupon_id" value="">
                                        <button type="button" class="def-btn coupon-apply-btn" id="applyCouponBtn">{{ __('Apply Coupon') }}</button>
                                        <button type="button" style="display: none;" class="def-btn coupon-apply-btn" id="applyCouponLodingBtn">{{ __('Loading...') }}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="cart-total-panel">
                                <h3 class="title">{{ __('Cart Total') }}</h3>
                                <div class="panel-body">
                                    <div class="row gy-5">
                                        <div class="col-12">
                                            <div class="calculate-area">
                                                <ul>
                                                    <li>
                                                        {{ __('Total Store Quantity') }} <span class="price-txt"><span class="">{{ $currentSubscription->current_shop_count }}</span></span>
                                                    </li>
                                                    <li>
                                                        {{ __('Net Total') }} <span class="price-txt">{{ $planPriceCurrency }} <span class="sub-total">{{ \App\Utils\Converter::format_in_bdt($totalPrice) }}</span></span>
                                                        <input type="hidden" name="net_total" id="net_total" value="{{ round($totalPrice, 2) }}">
                                                    </li>
                                                    <li>
                                                        {{ __('Adjusted Amount') }}
                                                        <span class="price-txt text-danger">
                                                            {{ $planPriceCurrency }} <span class="sub-total">{{ \App\Utils\Converter::format_in_bdt($totalAdjustableAmount) }}</span>
                                                        </span>
                                                        <input type="hidden" name="total_adjusted_amount" id="total_adjusted_amount" value="{{ round($totalAdjustableAmount, 2) }}">
                                                    </li>
                                                    <li>
                                                        {{ __('Tax') }}
                                                        <span class="price-txt" id="tax">
                                                            <span class="text-success">{{ __('Free') }}</span>
                                                        </span>
                                                    </li>
                                                    <li>
                                                        {{ __('Discount') }}
                                                        <span class="price-txt">
                                                            {{ $planPriceCurrency }} <span class="discount">0.00</span>
                                                        </span>
                                                        <input type="hidden" name="discount_percent" id="discount_percent" value="0">
                                                        <input type="hidden" name="discount" id="discount" value="0">
                                                    </li>
                                                    <li class="total-price-wrap">
                                                        {{ __('Total Payable') }}
                                                        <span class="price-txt">
                                                            {{ $planPriceCurrency }} <span class="total_payable">{{ \App\Utils\Converter::format_in_bdt($subtotal) }}</span>
                                                            <input type="hidden" name="total_payable" id="total_payable" value="{{ round($subtotal, 2) }}">
                                                        </span>
                                                    </li>
                                                </ul>
                                                <button class="def-btn tab-next-btn" id="proceedToCheckout">{{ __('Next Step') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="single-tab" id="stepTwoTab">
                            <div class="row">
                                <div class="col-xl-4 col-lg-5 col-md-6">
                                    <div class="payment-method">
                                        <div class="cart-total-panel">
                                            <h3 class="title">Cart Total</h3>
                                            <div class="panel-body">
                                                <div class="row gy-5">
                                                    <div class="col-12">
                                                        <div class="calculate-area">
                                                            <ul>
                                                                <li>{{ __("Total Store Quantity") }} <span class="price-txt"><span class="">1</span></span></li>
                                                                <li>
                                                                    {{ __("Net Total") }}
                                                                    <span class="price-txt">
                                                                        $<span class="sub-total">{{ $plan->price_per_year }}</span>
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    {{ __("Adjusted Amount") }}
                                                                    <span class="price-txt">
                                                                        $<span class="sub-total">{{ \App\Utils\Converter::format_in_bdt($totalAdjustableAmount) }}</span>
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    {{ __("Tax") }} <span class="price-txt" id="tax">
                                                                        <span class="text-success">{{ __("Free") }}</span>
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    {{ __("Discount") }}
                                                                    <span class="price-txt" id="discount">
                                                                        <span>0</span>
                                                                    </span>
                                                                </li>
                                                                <li class="total-price-wrap">Total <span class="price-txt">$<span id="totalPrice">{{ $plan->price_per_year }}</span></span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-5 col-md-6">
                                    <div class="payment-method">
                                        <div class="payment-option">
                                            <div class="single-payment-card">
                                                <div class="panel-header">
                                                    <div class="left-wrap">
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="credit-card" type="checkbox" disabled>
                                                            <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                        </div>
                                                        <span class="type">
                                                            Credit Card
                                                        </span>
                                                    </div>
                                                    <span class="icon">
                                                        <img src="{{ asset('backend/images/credit-card.png') }}" alt="credit-card">
                                                    </span>
                                                </div>
                                                <div class="panel-body">
                                                    <form class="credit-card-form">
                                                        <div class="row g-lg-4 g-3">
                                                            <div class="col-12">
                                                                <div class="input-box card-number">
                                                                    <span class="symbol">
                                                                        <img src="{{ asset('backend/images/visa.png') }}" alt="Card Type">
                                                                    </span>
                                                                    <label>Card Number</label>
                                                                    <input type="text" id="creditCardNumber" placeholder="XXXX XXXX XXXX XXXX">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="input-box">
                                                                    <label>Expiry date</label>
                                                                    <input type="text" id="datepicker" placeholder="MM/YYYY">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="input-box">
                                                                    <label>Security code</label>
                                                                    <input type="number" id="securityCode" placeholder="e.g. 123">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="input-box">
                                                                    <label>Enter card holder name</label>
                                                                    <input type="text" id="cardHolderName" placeholder="Card holder">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="single-payment-card">
                                                <div class="panel-header">
                                                    <div class="left-wrap">
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="paypal" type="checkbox" disabled>
                                                            <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                        </div>
                                                        <span class="type">
                                                            PayPal
                                                        </span>
                                                    </div>
                                                    <span class="icon">
                                                        <img src="{{ asset('backend/images/paypal.png') }}" alt="paypal">
                                                    </span>
                                                </div>
                                                <div class="panel-body">
                                                    <form class="paypal-form">
                                                        <div class="row g-lg-4 g-3">
                                                            <div class="col-12">
                                                                <label>Email or phone no. that used in paypal</label>
                                                                <input type="email" name="paypal-id" id="paypalId" placeholder="Email or mobile number" required>
                                                            </div>
                                                            <div class="col-12">
                                                                <button type="submit" id="confirmPaypal" class="def-btn">Confirm Paypal</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="single-payment-card">
                                                <div class="panel-header">
                                                    <div class="left-wrap">
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="google-pay" type="checkbox" disabled>
                                                            <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                        </div>
                                                        <span class="type">
                                                            Google Pay
                                                        </span>
                                                    </div>
                                                    <span class="icon">
                                                        <img src="{{ asset('backend/images/google-pay.png') }}" alt="google-pay">
                                                    </span>
                                                </div>
                                                <div class="panel-body">
                                                    <form class="google-pay-form">
                                                        <div class="row g-lg-4 g-3">
                                                            <div class="col-12">
                                                                <label>Email or phone no. that used in google pay</label>
                                                                <input type="email" name="google-Pay-id" id="googlePayId" placeholder="Email or mobile number" required>
                                                            </div>
                                                            <div class="col-12">
                                                                <button type="submit" id="confirmGooglePay" class="def-btn">Confirm Google Pay</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="single-payment-card">
                                                <div class="panel-header">
                                                    <div class="left-wrap">
                                                        <div class="form-check">
                                                            <input class="form-check-input" id="cash-on-delivery" name="cash" type="checkbox" disabled>
                                                            <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                        </div>
                                                        <span class="type">
                                                            Cash on delivery
                                                        </span>
                                                    </div>
                                                    <span class="icon">
                                                        <img src="{{ asset('backend/images/dollar.png') }}" alt="cash">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="plan-id" value="{{ $plan->id }}" />
                                        <button class="def-btn palce-order tab-next-btn btn-success" type="button" id="palceOrder">
                                            Place Order <i class="fa-light fa-truck-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="single-tab" id="stepThreeTab">
                            <div class="check-icon">
                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                                </svg>
                            </div>
                            <div class="order-complete-msg">
                                <h2>{{ __("Plan is upgraded successfully.") }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--------------------------------- CART SECTION END --------------------------------->

    <!-- js files -->
    {{-- <script src="{{ asset('backend/js/jquery-1.7.1.min.js') }}"></script> --}}
    <script src="{{asset('backend/asset/cdn/js/jquery-3.6.0.js')}}"></script>
    <script src="{{ asset('backend/asset/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="{{ asset('backend/js/cart.js') }}"></script> --}}
    <script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
    <script src="{{asset('backend/js/number-bdt-formater.js')}}"></script>

    <script>
        $(document).on('click', '#togglePriceAdjustmentDetails', function(e) {
            e.preventDefault();
            $('#priceAdjustmentDetailsTable').toggle();
        });

        $(document).on('click', '#remove_applied_coupon', function(e) {
            e.preventDefault();
            var discount = $('#discount').val();
            var totalPayable = $('#total_payable').val();
            $('#coupon_code').val('');
            $('#coupon_id').val('');
            $('#coupon_success_msg').hide();
            $('#coupon_code_applying_area').show();

            var currentTotalPayable = parseFloat(totalPayable) + parseFloat(discount);
            $('#total_payable').val(parseFloat(currentTotalPayable));
            $('.total_payable').html(bdFormat(currentTotalPayable));
            $('#discount').val(0);
            $('.discount').html(parseFloat(0).toFixed(2));
        });

        $(document).on('click', '#applyCouponBtn', function(e) {
            e.preventDefault();

            var coupon_code = $('#coupon_code').val();
            var total_payable = $('#total_payable').val();
            if (coupon_code == '') {

                toastr.error("{{ __('Please enter a valid coupon code.') }}");
                return;
            }

            $('#applyCouponBtn').hide();
            $('#applyCouponLodingBtn').show();
            var url = "{{ route('software.service.billing.upgrade.plan.check.coupon.code') }}";

            $.ajax({
                url: url,
                type: 'get',
                data: {coupon_code, total_payable},
                success: function(data) {

                    $('#applyCouponBtn').show();
                    $('#applyCouponLodingBtn').hide();
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    $('#applied_coupon_code').html(data.code);
                    $('#coupon_id').val(data.id);
                    var discountPercent = data.percent;
                    $('#discount_percent').val(data.percent);
                    $('#coupon_success_msg').show();
                    $('#coupon_code_applying_area').hide();
                    var totalPayable = $('#total_payable').val();

                    var discount = ((parseFloat(totalPayable) / 100) * parseFloat(discountPercent));
                    $('#discount').val(parseFloat(discount));
                    $('.discount').html('('+data.percent+'%='+bdFormat(discount)+')');

                    var currentTotalPayable = parseFloat(totalPayable) - parseFloat(discount);
                    $('#total_payable').val(parseFloat(currentTotalPayable));
                    $('.total_payable').html(bdFormat(currentTotalPayable));

                    toastr.success("{{ __('Coupon is applied successfully.') }}");
                }, error: function(err) {

                    $('#applyCouponBtn').show();
                    $('#applyCouponLodingBtn').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });
    </script>
</body>

</html>
