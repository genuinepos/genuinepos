@php
    $shopPricePerMonth = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month);
    $shopPricePerYear = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_year);
    $shopLifetimePrice = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->lifetime_price);
    $businessPricePerMonth = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->business_price_per_month);
    $businessPricePerYear = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->business_price_per_year);
    $businessLifetimePrice = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->business_lifetime_price);
@endphp

<div class="table-wrap revel-table">
    <input type="hidden" name="plan_id" id="plan_id" value="{{ $plan->id }}" />
    <input type="hidden" name="has_lifetime_period" id="has_lifetime_period" value="{{ $plan->has_lifetime_period }}" />
    <input type="hidden" name="shop_price_per_month" id="shop_price_per_month" value="{{ $shopPricePerMonth }}">
    <input type="hidden" name="shop_price_per_year" id="shop_price_per_year" value="{{ $shopPricePerYear }}">
    <input type="hidden" name="shop_lifetime_price" id="shop_lifetime_price" value="{{ $shopLifetimePrice }}">
    <input type="hidden" name="business_price_per_month" id="business_price_per_month" value="{{ $businessPricePerMonth}}">
    <input type="hidden" name="business_price_per_year" id="business_price_per_year" value="{{ $businessPricePerYear }}">
    <input type="hidden" name="business_lifetime_price" id="business_lifetime_price" value="{{ $businessLifetimePrice }}">

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th>{{ __('Shop Name') }}</th>
                    <th>{{ __('Expire Date') }}</th>
                    <th>{{ __('Price(As Per Period)') }}</th>
                    <th>{{ __('Renewable Price Period') }}</th>
                    <th>{{ __('Renewable Period Count') }}</th>
                    <th>{{ __('Subtotal') }}</th>
                    <th>...</th>
                </tr>
            </thead>

            <tbody>
                @if ($currentSubscription->has_business == 1)
                    @if ($currentSubscription->business_price_period == 'lifetime' && date('Y-m-d') > $currentSubscription->business_expire_date)
                        <tr>
                            <td class="fw-bold">
                                <input type="hidden" name="has_business" value="1">
                                <input type="hidden" name="business_name" value="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})">
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            </td>

                            <td class="{{ date('Y-m-d') > $currentSubscription->business_expire_date ? 'text-danger' : 'text-success' }}">
                                {{ $currentSubscription->business_expire_date }}
                            </td>

                            <td>
                                <span class="price-txt">
                                    {{ $planPriceCurrency }} <span id="span_business_price">{{ App\Utils\Converter::format_in_bdt($businessLifetimePrice) }}</span>
                                </span>
                                <input type="hidden" name="business_price" id="business_price" value="{{ $businessLifetimePrice }}">
                            </td>

                            <td>
                                <select name="business_price_period" id="business_price_period" class="form-control">
                                    <option value="lifetime">{{ __('Lifetime') }}</option>
                                </select>
                            </td>

                            <td>
                                <div class="product-count cart-product-count business_price_period_count {{ $currentSubscription->business_price_period == 'lifetime' ? 'd-none' : '' }}">
                                    <div class="quantity rapper-quantity">
                                        <input readonly name="business_price_period_count" id="business_price_period_count" type="number" min="1" step="1" value="1">
                                        <div class="quantity-nav">
                                            <div class="quantity-button quantity-down business_period_down_btn">
                                                <i class="fa-solid fa-minus"></i>
                                            </div>
                                            <div class="quantity-button quantity-up business_period_up_btn">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="fixed_business_price_period_text">
                                    {{ $currentSubscription->business_price_period == 'lifetime' ? 'Lifetime' : '' }}
                                </div>
                            </td>

                            <td>
                                <input type="hidden" name="business_subtotal" id="business_subtotal" value="{{ $businessLifetimePrice }}">
                                <span class="price-txt">
                                    {{ $planPriceCurrency }} <span id="span_business_subtotal">{{ App\Utils\Converter::format_in_bdt($businessLifetimePrice) }}</span>
                                </span>
                            </td>

                            <td><a href="#" class="btn btn-sm btn-danger" id="remove_btn">X</a></td>
                        </tr>
                    @elseif ($currentSubscription->business_price_period == 'month' || $currentSubscription->business_price_period == 'year')
                        @php
                            $defaultBusinessPrice = 0;
                        @endphp
                        @if ($currentSubscription->business_price_period == 'month')
                            @php
                                $defaultBusinessPrice = $businessPricePerMonth;
                            @endphp
                        @elseif ($currentSubscription->business_price_period == 'year')
                            @php
                                $defaultBusinessPrice = $businessPricePerYear;
                            @endphp
                        @endif
                        <tr>
                            <td class="fw-bold">
                                <input type="hidden" name="has_business" value="1">
                                <input type="hidden" name="business_name" value="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})">
                                {{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})
                            </td>

                            <td class="{{ date('Y-m-d') > $currentSubscription->business_expire_date ? 'text-danger' : 'text-success' }}">
                                {{ $currentSubscription->business_expire_date }}
                            </td>

                            <td>
                                <span class="price-txt">
                                    {{ $planPriceCurrency }} <span id="span_business_price">{{ App\Utils\Converter::format_in_bdt($defaultBusinessPrice) }}</span>
                                </span>
                                <input type="hidden" name="business_price" id="business_price" value="{{ $defaultBusinessPrice }}">
                            </td>

                            <td>
                                <select name="business_price_period" id="business_price_period" class="form-control">
                                    @if ($currentSubscription->business_price_period != 'year' && $currentSubscription->business_price_period != 'lifetime')
                                        <option @selected($currentSubscription->business_price_period == 'month') value="month">{{ __('Monthly') }}</option>
                                    @endif

                                    @if ($currentSubscription->business_price_period != 'lifetime')
                                        <option @selected($currentSubscription->business_price_period == 'year') value="year">{{ __('Yearly') }}</option>
                                    @endif

                                    <option value="lifetime">{{ __('Lifetime') }}</option>
                                </select>
                            </td>

                            <td>
                                <div class="product-count cart-product-count business_price_period_count {{ $currentSubscription->business_price_period == 'lifetime' ? 'd-none' : '' }}">
                                    <div class="quantity rapper-quantity">
                                        <input readonly name="business_price_period_count" id="business_price_period_count" type="number" min="1" step="1" value="1">
                                        <div class="quantity-nav">
                                            <div class="quantity-button quantity-down business_period_down_btn">
                                                <i class="fa-solid fa-minus"></i>
                                            </div>
                                            <div class="quantity-button quantity-up business_period_up_btn">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="fixed_business_price_period_text">
                                    {{ $currentSubscription->business_price_period == 'lifetime' ? 'Lifetime' : '' }}
                                </div>
                            </td>

                            <td>
                                <input type="hidden" name="business_subtotal" id="business_subtotal" value="{{ $defaultBusinessPrice }}">
                                <span class="price-txt">
                                    {{ $planPriceCurrency }} <span id="span_business_subtotal">{{ App\Utils\Converter::format_in_bdt($defaultBusinessPrice) }}</span>
                                </span>
                            </td>
                            <td><a href="#" class="btn btn-sm btn-danger" id="remove_btn">X</a></td>
                        </tr>
                    @endif
                @endif

                @foreach ($branches as $branch)
                    @if ($branch?->shopExpireDateHistory?->price_period == 'lifetime' && date('Y-m-d') > $branch->expire_date)
                        <tr>
                            <td class="fw-bold">
                                <input type="hidden" name="shop_expire_date_history_ids[]" value="{{ $branch?->shopExpireDateHistory?->id }}">
                                @if ($branch?->parentBranch)
                                    {{ $branch->parentBranch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}
                                    <input type="hidden" name="branch_names[]" value="{{ $branch->parentBranch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}">
                                @else
                                    {{ $branch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}
                                    <input type="hidden" name="branch_names[]" value="{{ $branch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}">
                                @endif
                            </td>

                            <td class="{{ date('Y-m-d') > $branch->expire_date ? 'text-danger' : 'text-success' }}">
                                {{ $branch->expire_date }}
                            </td>

                            <td>
                                <span class="price-txt">
                                    {{ $planPriceCurrency }} <span id="span_shop_price">{{ App\Utils\Converter::format_in_bdt($shopLifetimePrice) }}</span>
                                </span>
                                <input type="hidden" name="shop_prices[]" id="shop_price" value="{{ $shopLifetimePrice }}">
                            </td>

                            <td>
                                <select name="shop_price_periods[]" id="shop_price_period" class="form-control">
                                    <option value="lifetime">{{ __('Lifetime') }}</option>
                                </select>
                            </td>

                            <td>
                                <div class="product-count cart-product-count shop_price_period_count {{ $branch?->shopExpireDateHistory?->price_period == 'lifetime' ? 'd-none' : '' }}">
                                    <div class="quantity rapper-quantity">
                                        <input readonly name="shop_price_period_counts[]" id="shop_price_period_count" type="number" min="1" step="1" value="1">
                                        <div class="quantity-nav">
                                            <div class="quantity-button quantity-down shop_period_qty_down">
                                                <i class="fa-solid fa-minus"></i>
                                            </div>
                                            <div class="quantity-button quantity-up shop_period_qty_up">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="fixed_shop_price_period_text">
                                    {{ $branch?->shopExpireDateHistory?->price_period == 'lifetime' ? 'Lifetime' : '' }}
                                </div>
                            </td>

                            <td>
                                <input type="hidden" name="shop_subtotals[]" id="shop_subtotal" value="{{ $shopLifetimePrice }}">
                                <span class="price-txt">
                                    {{ $planPriceCurrency }} <span id="span_shop_subtotal">{{ App\Utils\Converter::format_in_bdt($shopLifetimePrice) }}</span>
                                </span>
                            </td>

                            <td><a href="#" class="btn btn-sm btn-danger" id="remove_btn">X</a></td>
                        </tr>
                    @elseif ($branch?->shopExpireDateHistory?->price_period == 'month' || $branch?->shopExpireDateHistory?->price_period == 'year')
                        @php
                            $defaultShopPrice = 0;
                            if ($branch?->shopExpireDateHistory?->price_period == 'month') {
                                $defaultShopPrice = $shopPricePerMonth;
                            } elseif ($branch?->shopExpireDateHistory?->price_period == 'year') {
                                $defaultShopPrice = $shopPricePerYear;
                            }
                        @endphp

                        <tr>
                            <td class="fw-bold">
                                <input type="hidden" name="shop_expire_date_history_ids[]" value="{{ $branch?->shopExpireDateHistory?->id }}">
                                @if ($branch?->parentBranch)
                                    {{ $branch->parentBranch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}
                                    <input type="hidden" name="branch_names[]" value="{{ $branch->parentBranch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}">
                                @else
                                    {{ $branch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}
                                    <input type="hidden" name="branch_names[]" value="{{ $branch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}">
                                @endif
                            </td>

                            <td class="{{ date('Y-m-d') > $branch?->shopExpireDateHistory?->expire_date ? 'text-danger' : 'text-success' }}">
                                {{ $branch?->shopExpireDateHistory?->expire_date }}
                            </td>

                            <td>
                                <span class="price-txt">
                                    {{ $planPriceCurrency }} <span id="span_shop_price">{{ App\Utils\Converter::format_in_bdt($defaultShopPrice) }}</span>
                                </span>
                                <input type="hidden" name="shop_prices[]" id="shop_price" value="{{ $defaultShopPrice }}">
                            </td>

                            <td>
                                <select name="shop_price_periods[]" id="shop_price_period" class="form-control">
                                    @if ($branch?->shopExpireDateHistory?->price_period != 'year' && $branch?->shopExpireDateHistory?->price_period != 'lifetime')
                                        <option @selected($branch?->shopExpireDateHistory?->price_period == 'month') value="month">{{ __('Monthly') }}</option>
                                    @endif

                                    @if ($branch?->shopExpireDateHistory?->price_period != 'lifetime')
                                        <option @selected($branch?->shopExpireDateHistory?->price_period == 'year') value="year">{{ __('Yearly') }}</option>
                                    @endif

                                    <option value="lifetime">{{ __('Lifetime') }}</option>
                                </select>
                            </td>

                            <td>
                                <div class="product-count cart-product-count shop_price_period_count {{ $branch?->shopExpireDateHistory?->price_period == 'lifetime' ? 'd-none' : '' }}">
                                    <div class="quantity rapper-quantity">
                                        <input readonly name="shop_price_period_counts[]" id="shop_price_period_count" type="number" min="1" step="1" value="1">
                                        <div class="quantity-nav">
                                            <div class="quantity-button quantity-down shop_period_qty_down">
                                                <i class="fa-solid fa-minus"></i>
                                            </div>
                                            <div class="quantity-button quantity-up shop_period_qty_up">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="fixed_shop_price_period_text">
                                    {{ $branch?->shopExpireDateHistory?->price_period == 'lifetime' ? 'Lifetime' : '' }}
                                </div>
                            </td>

                            <td>
                                <input type="hidden" name="shop_subtotals[]" id="shop_subtotal" value="{{ $defaultShopPrice }}">
                                <span class="price-txt">
                                    {{ $planPriceCurrency }} <span id="span_shop_subtotal">{{ App\Utils\Converter::format_in_bdt($defaultShopPrice) }}</span>
                                </span>
                            </td>

                            <td><a href="#" class="btn btn-sm btn-danger" id="remove_btn">X</a></td>
                        </tr>
                    @endif
                @endforeach

                @foreach ($leftBranchExpireDateHistories as $leftBranchExpireDateHistory)
                    @if ($leftBranchExpireDateHistory?->price_period == 'lifetime' && date('Y-m-d') > $leftBranchExpireDateHistory->expire_date)
                        <tr>
                            <td>
                                <input type="hidden" name="shop_expire_date_history_ids[]" value="{{ $leftBranchExpireDateHistory->id }}">
                                <span class="text-danger fw-bold"></span>{{ __('Shop Not Yet to be created') }}
                                <input type="hidden" name="branch_names[]" value="{{ __('Shop Not Yet to be created') }}">
                            </td>

                            <td class="{{ date('Y-m-d') > $leftBranchExpireDateHistory->expire_date ? 'text-danger' : 'text-success' }}">
                                {{ $leftBranchExpireDateHistory->expire_date }}
                            </td>

                            <td>
                                <span class="price-txt">
                                    {{ $planPriceCurrency }} <span id="span_shop_price">{{ App\Utils\Converter::format_in_bdt($shopLifetimePrice) }}</span>
                                </span>
                                <input type="hidden" name="shop_prices[]" id="shop_price" value="{{ $shopLifetimePrice }}">
                            </td>

                            <td>
                                <select name="shop_price_periods[]" id="shop_price_period" class="form-control">
                                    <option value="lifetime">{{ __('Lifetime') }}</option>
                                </select>
                            </td>

                            <td>
                                <div class="product-count cart-product-count shop_price_period_count {{ $leftBranchExpireDateHistory?->price_period == 'lifetime' ? 'd-none' : '' }}">
                                    <div class="quantity rapper-quantity">
                                        <input readonly name="shop_price_period_count" id="shop_price_period_count" type="number" min="1" step="1" value="1">
                                        <div class="quantity-nav">
                                            <div class="quantity-button quantity-down shop_period_qty_down">
                                                <i class="fa-solid fa-minus"></i>
                                            </div>
                                            <div class="quantity-button quantity-up shop_period_qty_up">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="fixed_shop_price_period_text">
                                    {{ $leftBranchExpireDateHistory?->price_period == 'lifetime' ? 'Lifetime' : '' }}
                                </div>
                            </td>

                            <td>
                                <input type="hidden" name="shop_subtotals[]" id="shop_subtotal" value="{{ $shopLifetimePrice }}">
                                <span class="price-txt">
                                    {{ $planPriceCurrency }} <span id="span_shop_subtotal">{{ App\Utils\Converter::format_in_bdt($shopLifetimePrice) }}</span>
                                </span>
                            </td>

                            <td><a href="#" class="btn btn-sm btn-danger" id="remove_btn">X</a></td>
                        </tr>
                    @elseif ($leftBranchExpireDateHistory?->price_period == 'month' || $leftBranchExpireDateHistory?->price_period == 'year')
                        @php
                            $_defaultShopPrice = 0;
                            if ($leftBranchExpireDateHistory?->price_period == 'month') {
                                $_defaultShopPrice = $shopPricePerMonth;
                            } elseif ($leftBranchExpireDateHistory?->price_period == 'year') {
                                $_defaultShopPrice = $shopPricePerYear;
                            }
                        @endphp

                        <tr>
                            <td>
                                <input type="hidden" name="shop_expire_date_history_ids[]" value="{{ $leftBranchExpireDateHistory?->id }}">
                                <span class="text-danger fw-bold">{{ __('Shop Not Yet to be created') }}</span>
                                <input type="hidden" name="branch_names[]" value="{{ __('Shop Not Yet to be created') }}">
                            </td>

                            <td class="{{ date('Y-m-d') > $leftBranchExpireDateHistory?->expire_date ? 'text-danger' : 'text-success' }}">
                                {{ $leftBranchExpireDateHistory?->expire_date }}
                            </td>

                            <td>
                                <span class="price-txt">
                                    {{ $planPriceCurrency }} <span id="span_shop_price">{{ App\Utils\Converter::format_in_bdt($_defaultShopPrice) }}</span>
                                </span>
                                <input type="hidden" name="shop_prices[]" id="shop_price" value="{{ $_defaultShopPrice }}">
                            </td>

                            <td>
                                <select name="shop_price_periods[]" id="shop_price_period" class="form-control">
                                    @if ($leftBranchExpireDateHistory?->price_period != 'year' && $leftBranchExpireDateHistory?->price_period != 'lifetime')
                                        <option @selected($leftBranchExpireDateHistory?->price_period == 'month') value="month">{{ __('Monthly') }}</option>
                                    @endif

                                    @if ($leftBranchExpireDateHistory?->price_period != 'lifetime')
                                        <option @selected($leftBranchExpireDateHistory?->price_period == 'year') value="year">{{ __('Yearly') }}</option>
                                    @endif

                                    <option value="lifetime">{{ __('Lifetime') }}</option>
                                </select>
                            </td>

                            <td>
                                <div class="product-count cart-product-count shop_price_period_count {{ $leftBranchExpireDateHistory?->price_period == 'lifetime' ? 'd-none' : '' }}">
                                    <div class="quantity rapper-quantity">
                                        <input readonly name="shop_price_period_counts[]" id="shop_price_period_count" type="number" min="1" step="1" value="1">
                                        <div class="quantity-nav">
                                            <div class="quantity-button quantity-down shop_period_qty_down">
                                                <i class="fa-solid fa-minus"></i>
                                            </div>
                                            <div class="quantity-button quantity-up shop_period_qty_up">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="fixed_shop_price_period_text">
                                    {{ $leftBranchExpireDateHistory->price_period == 'lifetime' ? 'Lifetime' : '' }}
                                </div>
                            </td>

                            <td>
                                <input type="hidden" name="shop_subtotals[]" id="shop_subtotal" value="{{ $_defaultShopPrice }}">
                                <span class="price-txt">
                                    {{ $planPriceCurrency }} <span id="span_shop_subtotal">{{ App\Utils\Converter::format_in_bdt($_defaultShopPrice) }}</span>
                                </span>
                            </td>

                            <td><a href="#" class="btn btn-sm btn-danger" id="remove_btn">X</a></td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="btn-box" id="coupon_success_msg" style="display:none;">
        <p class="bg-success d-block p-2 m-0">
            <span class="text-white">{{ __('Applied Coupon is') }} : <span class="fw-bold" id="applied_coupon_code"></span></span>
            <a href="#" class="btn btn-sm btn-danger" id="remove_applied_coupon">X</a>
        </p>
    </div>

    <div class="btn-box">
        <div class="cart-coupon-form" id="coupon_code_applying_area">
            <input type="text" name="coupon_code" id="coupon_code" placeholder="{{ __('Enter Your Coupon Code') }}">
            <input type="hidden" name="coupon_id" id="coupon_id" value="">
            <button type="button" class="def-btn coupon-apply-btn" id="applyCouponBtn">{{ __('Apply Coupon') }}</button>
            <button type="button" style="display: none;" class="def-btn coupon-apply-btn" id="applyCouponLodingBtn">{{ __('Loading...') }}</button>
        </div>
    </div>
</div>

<div class="cart-total-panel">
    <h3 class="title">{{ __("Cart Total") }}</h3>
    <div class="panel-body">
        <div class="row gy-5">
            <div class="col-12">
                <div class="calculate-area">
                    <ul>
                        <li>
                            {{ __('Net Total') }}
                            <input type="hidden" name="net_total" id="net_total" value="0.00">
                            <span class="price-txt">
                                {{ $planPriceCurrency }} <span class="span_net_total">0.00</span>
                            </span>
                        </li>
                        <li>
                            {{ __('Tax') }}
                            <span class="price-txt" id="tax">
                                <span class="text-success">Free</span>
                            </span>
                        </li>
                        <li>
                            {{ __('Discount') }}
                            <span class="price-txt">
                                {{ $planPriceCurrency }} <span class="span_discount">0</span>
                            </span>
                            <input type="hidden" name="discount_percent" id="discount_percent" value="0">
                            <input type="hidden" name="discount" id="discount" value="0">
                        </li>
                        <li class="total-price-wrap">
                            {{ __('Total Payable') }}
                            <span class="price-txt">
                                {{ $planPriceCurrency }} <span class="span_total_payable">0.00</span>
                            </span>
                            <input type="hidden" name="total_payable" id="total_payable" value="0.00">
                        </li>
                    </ul>
                    <a class="single-nav def-btn tab-next-btn" data-tab="stepTwoTab">{{ __('Next Step') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
