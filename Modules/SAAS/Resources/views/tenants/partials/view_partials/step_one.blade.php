<div class="table-wrap revel-table">
    <div class="period_buttons_area mb-1">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-secondary">
                <input type="radio" name="shop_price_period" id="shop_price_period" value="month" autocomplete="off"> {{ __('Monthly') }}
            </label>

            <label class="btn btn-secondary">
                <input type="radio" name="shop_price_period" id="shop_price_period" value="year" autocomplete="off"> {{ __('Yearly') }}
            </label>

            <label class="btn btn-secondary">
                <input type="radio" name="shop_price_period" id="shop_price_period" value="lifetime" autocomplete="off"> {{ __('Lifetime') }}
            </label>

            <input type="hidden" name="is_trial_plan" id="is_trial_plan" value="0">
            <input type="hidden" name="shop_price_per_month" id="shop_price_per_month">
            <input type="hidden" name="shop_price_per_year" id="shop_price_per_year">
            <input type="hidden" name="has_lifetime_period" id="has_lifetime_period" value="1" />
            <input type="hidden" name="shop_lifetime_price" id="shop_lifetime_price">
            <input type="hidden" name="lifetime_applicable_years" id="lifetime_applicable_years">
            <input type="hidden" name="business_price_per_month" id="business_price_per_month">
            <input type="hidden" name="business_price_per_year" id="business_price_per_year">
            <input type="hidden" name="business_lifetime_price" id="business_lifetime_price">
        </div>

        <label class="btn btn-danger float-end" id="has_business_btn">
            <input type="checkbox" name="has_business" id="has_business" value="1" autocomplete="off">
            {{ __('Back Office') }}
        </label>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th style="width: 30%;">{{ __('Plan Name') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Store Quantity') }}</th>
                    <th id="period_count_header">{{ __('Period') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>

            <tbody id="plan_price_table">
                <tr>
                    <td style="width: 30%;">
                        <select name="plan_id" id="plan_id" class="form-control form-control-sm plan-select select wide">
                            <option value="">{{ __('Please Select A Plan') }}</option>
                            @foreach ($plans as $plan)
                                <option data-is_tral_plan="{{ $plan->is_trial_plan }}" value="{{ $plan->id }}">
                                    {{ $plan->name }} {{ $plan->plan_type == 2 ? '[' . __('Custom Plan') . ']' : '[' . __('Fixed Plan') . ']' }}
                                    @if ($plan->is_trial_plan == 1)
                                        ({{ __('Trial Period') . ' : ' . $plan->trial_days }} {{ __('Days') }})
                                    @else
                                        ({{ __('Monthly') }} : {{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month)) . ' ' . $planPriceCurrency }} |
                                        {{ __('Yearly') }} : {{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_year)) . ' ' . $planPriceCurrency }} |
                                        {{ __('Lifetime') }} : {{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->lifetime_price)) . ' ' . $planPriceCurrency }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <input type="hidden" name="shop_price" id="shop_price" value="">
                        <span class="price-txt">
                            {{ $planPriceCurrency }} <span id="span_shop_price"></span>
                        </span>
                    </td>

                    <td>
                        <div class="product-count cart-product-count">
                            <div class="quantity rapper-quantity">
                                <input readonly name="shop_count" id="shop_count" type="number" min="1" step="1" value="">
                                <div class="quantity-nav">
                                    <div class="quantity-button quantity-down">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                    <div class="quantity-button quantity-up">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="product-count cart-product-count shop_price_period_count">
                            <div class="quantity rapper-quantity">
                                <input readonly name="shop_price_period_count" id="shop_price_period_count" type="number" min="1" step="1" value="">
                                <div class="quantity-nav">
                                    <div class="quantity-button quantity-down">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                    <div class="quantity-button quantity-up">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="shop_fixed_price_period_text" class="fw-bold"></div>
                    </td>

                    <td>
                        <input type="hidden" name="shop_subtotal" id="shop_subtotal" value="">
                        <span class="price-txt">
                            {{ $planPriceCurrency }} <span id="span_shop_subtotal"></span>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="btn-box">
        <div>
            <label for="discount">{{ __("Discount Amount") }}</label>
            <input type="hidden" name="discount_percent" id="discount_percent" value="0">
            <input type="text" name="discount" class="form-control form-control-sm fw-bold" id="discount" placeholder="{{ __('0') }}">
        </div>
    </div>

    {{-- <div class="btn-box" id="coupon_success_msg" style="display:none;">
        <p class="bg-success d-block p-1 m-0"><span class="text-white">{{ __('Applied Coupon is') }} : <span id="applied_coupon_code" class="fw-bold"></span></span> <a href="#" class="btn btn-sm btn-danger" id="remove_applied_coupon">X</a></p>
    </div>

    <div class="btn-box">
        <div class="cart-coupon-form" id="coupon_code_applying_area">
            <input type="text" name="coupon_code" id="coupon_code" placeholder="{{ __('Enter Your Coupon Code') }}">
            <input type="hidden" name="coupon_id" id="coupon_id" value="">
            <button type="button" class="def-btn coupon-apply-btn" id="applyCouponBtn">{{ __('Apply Coupon') }}</button>
            <button type="button" style="display: none;" class="def-btn coupon-apply-btn" id="applyCouponLodingBtn">{{ __('Loading...') }}</button>
        </div>
    </div> --}}
</div>

<div class="cart-total-panel">
    <h3 class="title">{{ __('Cart Total') }}</h3>
    <div class="panel-body">
        <div class="row gy-5">
            <div class="col-12">
                <div class="calculate-area">
                    <ul>
                        <li>{{ __('Total Store Quantity') }}
                            <span class="price-txt">
                                <span class="span_total_shop_count">1</span>
                            </span>
                        </li>
                        <li>{{ __('Net Total') }}
                            <input type="hidden" name="net_total" id="net_total" value="0">
                            <span class="price-txt">
                                {{ $planPriceCurrency }} <span class="span_net_total"></span>
                            </span>
                        </li>
                        <li>
                            {{ __('Discount') }}
                            <span class="price-txt">
                                {{ $planPriceCurrency }} (<span class="span_discount text-danger">0.00</span>)
                            </span>
                        </li>
                        <li class="total-price-wrap">{{ __('Total Payable') }}
                            <input type="hidden" name="total_payable" id="total_payable" value="">
                            <span class="price-txt">
                                {{ $planPriceCurrency }} <span class="span_total_payable text-success"></span>
                            </span>
                        </li>
                    </ul>
                    <a class="def-btn tab-next-btn text-center single-nav" data-tab="stepTwoTab" style="cursor: pointer;">{{ __('Next Step') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
