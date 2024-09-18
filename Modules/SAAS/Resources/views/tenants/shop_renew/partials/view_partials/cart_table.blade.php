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
    <input type="hidden" name="business_price_per_month" id="business_price_per_month" value="{{ $businessPricePerMonth }}">
    <input type="hidden" name="business_price_per_year" id="business_price_per_year" value="{{ $businessPricePerYear }}">
    <input type="hidden" name="business_lifetime_price" id="business_lifetime_price" value="{{ $businessLifetimePrice }}">

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th class="text-start">{{ __('Store Name') }}</th>
                    <th class="text-start">{{ __('Expire Date') }}</th>
                    <th class="text-start">{{ __('Price(As Per Period)') }}</th>
                    <th>{{ __('Price Period') }}</th>
                    <th>{{ __('Period Count') }}</th>
                    <th class="text-start">{{ __('Subtotal') }}</th>
                    <th>...</th>
                </tr>
            </thead>

            <tbody>
                @if ($currentSubscription->has_business == 1)
                    @if ($currentSubscription->business_price_period == 'lifetime' && date('Y-m-d') > $currentSubscription->business_main_expire_date)
                        <tr>
                            <td class="text-start">
                                <input type="hidden" name="has_business" value="1">
                                <input type="hidden" name="business_name" value="{{ $business->value }}({{ __('Company') }})">
                                {{ $business->value }}
                            </td>

                            <td class="{{ date('Y-m-d') > $currentSubscription->business_main_expire_date ? 'text-danger' : 'text-success' }} text-start">
                                {{ $currentSubscription->business_main_expire_date }}
                            </td>

                            <td class="text-start">
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

                            <td><a href="#" class="btn btn-sm btn-danger text-white" id="remove_btn">X</a></td>
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
                            <td class="text-start">
                                <input type="hidden" name="has_business" value="1">
                                <input type="hidden" name="business_name" value="{{ $business->value }}({{ __('Company') }})">
                                {{ $business->value }}({{ location_label('business') }})
                            </td>

                            <td class="{{ date('Y-m-d') > $currentSubscription->business_main_expire_date ? 'text-danger' : 'text-success' }} text-start">
                                {{ $currentSubscription->business_main_expire_date }}
                            </td>

                            <td class="text-start">
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
                            <td><a href="#" class="btn btn-sm btn-danger text-white" id="remove_btn">X</a></td>
                        </tr>
                    @endif
                @endif

                @foreach ($branches as $branch)
                    @if ($branch?->shopExpireDateHistory?->price_period == 'lifetime' && date('Y-m-d') > $branch->main_expire_date)
                        <tr>
                            <td class="text-start">
                                <input type="hidden" name="shop_expire_date_history_ids[]" value="{{ $branch?->shopExpireDateHistory?->id }}">
                                @if ($branch?->parentBranch)
                                    <span class="ps-2"></span>{{ ' --- ' . $branch->parentBranch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}
                                    <input type="hidden" name="branch_names[]" value="{{ $branch->parentBranch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}">
                                @else
                                    {{ $branch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}
                                    <input type="hidden" name="branch_names[]" value="{{ $branch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}">
                                @endif
                            </td>

                            <td class="{{ date('Y-m-d') > $branch->main_expire_date ? 'text-danger' : 'text-success' }} text-start">
                                {{ $branch->main_expire_date }}
                            </td>

                            <td class="text-start">
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

                            <td><a href="#" class="btn btn-sm btn-danger text-white" id="remove_btn">X</a></td>
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
                            <td class="text-start">
                                <input type="hidden" name="shop_expire_date_history_ids[]" value="{{ $branch?->shopExpireDateHistory?->id }}">
                                @if ($branch?->parentBranch)
                                    <span class="ps-2"></span>{{ ' --- ' . $branch->parentBranch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}
                                    <input type="hidden" name="branch_names[]" value="{{ $branch->parentBranch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}">
                                @else
                                    {{ $branch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}
                                    <input type="hidden" name="branch_names[]" value="{{ $branch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}">
                                @endif
                            </td>

                            <td class="{{ date('Y-m-d') > $branch?->shopExpireDateHistory?->main_expire_date ? 'text-danger' : 'text-success' }} text-start">
                                {{ $branch?->shopExpireDateHistory?->main_expire_date }}
                            </td>

                            <td class="text-start">
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

                            <td><a href="#" class="btn btn-sm btn-danger text-white" id="remove_btn">X</a></td>
                        </tr>
                    @endif
                @endforeach

                @foreach ($leftBranchExpireDateHistories as $leftBranchExpireDateHistory)
                    @if ($leftBranchExpireDateHistory?->price_period == 'lifetime' && date('Y-m-d') > $leftBranchExpireDateHistory->main_expire_date)
                        <tr>
                            <td>
                                <input type="hidden" name="shop_expire_date_history_ids[]" value="{{ $leftBranchExpireDateHistory->id }}">
                                <span class="text-danger fw-bold"></span>{{ location_label('branch') }} {{ __('Not Yet to be created') }}
                                <input type="hidden" name="branch_names[]" value="{{ location_label('branch') }} {{ __('Not Yet to be created') }}">
                            </td>

                            <td class="{{ date('Y-m-d') > $leftBranchExpireDateHistory->main_expire_date ? 'text-danger' : 'text-success' }}">
                                {{ $leftBranchExpireDateHistory->main_expire_date }}
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

                            <td><a href="#" class="btn btn-sm btn-danger text-white" id="remove_btn">X</a></td>
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
                                <span class="text-danger fw-bold">{{ location_label('branch') }} {{ __('Not Yet to be created') }}</span>
                                <input type="hidden" name="branch_names[]" value="{{ location_label('branch') }} {{ __('Not Yet to be created') }}">
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

                            <td><a href="#" class="btn btn-sm btn-danger text-white" id="remove_btn">X</a></td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="btn-box">
        <div>
            <label for="discount">{{ __('Discount Amount') }}</label>
            <input type="hidden" name="discount_percent" id="discount_percent" value="0">
            <input type="text" name="discount" class="form-control form-control-sm fw-bold" id="discount" placeholder="{{ __('0.00') }}" autocomplete="off">
        </div>
    </div>
</div>

<div class="cart-total-panel">
    <h3 class="title">{{ __('Cart Total') }}</h3>
    <div class="panel-body">
        <div class="row gy-5">
            <div class="col-6">
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
                        </li>
                        <li class="total-price-wrap">
                            {{ __('Total Payable') }}
                            <span class="price-txt">
                                {{ $planPriceCurrency }} <span class="span_total_payable">0.00</span>
                            </span>
                            <input type="hidden" name="total_payable" id="total_payable" value="0.00">
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="cart-total-panel payment-section">
                    <input type="hidden" name="payment_status" value="1">
                    <div class="panel-body">
                        <div class="row gy-4">
                            <div class="col-12">
                                <div class="form-col-5">
                                    <label for="payment_date">{{ __('Payment Date') }} <span class="text-danger">*</span></label>
                                    <input required name="payment_date" id="payment_date" class="form-control form-control-sm" value="{{ date('d-m-Y') }}" autocomplete="off">
                                </div>

                                <div class="form-col-5 mt-2">
                                    <label for="payment_method_name">{{ __('Payment Method') }} <span class="text-danger">*</span></label>
                                    <select required name="payment_method_name" id="payment_method_name" class="form-control form-control-sm select wide">
                                        <option value="">{{ __('Select Payment Method') }}</option>
                                        <option value="Cash">{{ __('Cash') }}</option>
                                        <option value="Card">{{ __('Card') }}</option>
                                        <option value="Bkash">{{ __('Bkash') }}</option>
                                        <option value="Recket">{{ __('Recket') }}</option>
                                        <option value="Naged">{{ __('Naged') }}</option>
                                    </select>
                                </div>

                                <div class="form-col-5 mt-2">
                                    <label for="payment_method_name">{{ __('Payment Transaction ID') }}</label>
                                    <input name="payment_trans_id" id="payment_trans_id" class="form-control form-control-sm select wide">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="def-btn palce-order tab-next-btn btn-success text-center float-end" id="submit_button">
                    {{ __('Confirm') }}
                </button>

                <button type="button" class="def-btn palce-order tab-next-btn btn-success d-none float-end" id="loading_button">
                    {{ __('Loading...') }}
                </button>
            </div>
        </div>
    </div>
</div>
