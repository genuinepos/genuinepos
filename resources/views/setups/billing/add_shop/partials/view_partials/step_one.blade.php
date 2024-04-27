<div class="table-wrap revel-table">
    <div class="period_buttons_area mb-1 w-100">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-secondary bg-danger shop_price_period_label">
                <input type="radio" name="shop_price_period" checked id="shop_price_period" value="month" autocomplete="off"> {{ __('Monthly') }}
            </label>

            <label class="btn btn-secondary shop_price_period_label">
                <input type="radio" name="shop_price_period" id="shop_price_period" value="year" autocomplete="off"> {{ __('Yearly') }}
            </label>

            @if ($plan->has_lifetime_period == 1)
                <label class="btn btn-secondary shop_price_period_label">
                    <input type="radio" name="shop_price_period" id="shop_price_period" value="lifetime" autocomplete="off"> {{ __('Lifetime') }}
                </label>
            @endif

            <input type="hidden" name="has_lifetime_period" id="has_lifetime_period" value="{{ $plan->has_lifetime_period }}" />
            <input type="hidden" name="shop_price_per_month" id="shop_price_per_month" value="{{ \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month) }}">
            <input type="hidden" name="shop_price_per_year" id="shop_price_per_year" value="{{ \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_year) }}">
            <input type="hidden" name="shop_lifetime_price" id="shop_lifetime_price" value="{{ \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->lifetime_price) }}">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th>{{ __("Plan Name") }}</th>
                    <th>{{ __("Increase Shop Quantity") }}</th>
                    <th>{{ __("Price Per Shop") }}</th>
                    <th id="period_count_header">{{ __('Months') }}</th>
                    <th>{{ __("Subtotal") }}</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>{{ $plan->name }} <small style="font-size:9px;">({{ __("Current Shop Count") }} : <span class="fw-bold">{{ $generalSettings['subscription']->current_shop_count }})</span></small></td>
                    <td>
                        <div class="product-count cart-product-count">
                            <div class="quantity rapper-quantity">
                                <input type="number" name="increase_shop_count" id="increase_shop_count" min="1" step="1" value="1" readonly>
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
                        <input type="hidden" name="plan_price" id="plan_price" value="{{ \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month) }}">
                        <span class="price-txt">{{ $planPriceCurrency }}
                            <span id="span_plan_price">{{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month)) }}</span>
                        </span>
                    </td>

                    <td>
                        <div class="product-count cart-product-count shop_price_period_count">
                            <div class="quantity rapper-quantity">
                                <input readonly name="shop_price_period_count" id="shop_price_period_count" type="number" min="1" step="1" value="1">
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

                        <div id="fixed_shop_price_period_text"></div>
                    </td>

                    <td>
                        <input type="hidden" name="shop_subtotal" id="shop_subtotal" value="{{ \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month) }}">
                        <span class="price-txt">{{ $planPriceCurrency }}<span id="span_shop_subtotal">{{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month)) }}</span></span>
                    </td>
                </tr>
            </tbody>
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
    <h3 class="title">{{ __("Cart Total") }}</h3>
    <div class="panel-body">
        <div class="row gy-5">
            <div class="col-12">
                <div class="calculate-area">
                    <ul>
                        <li>
                            {{ __("Increased Store Quantity") }}
                            <span class="price-txt" class="span_increase_shop_count">
                                <span class="">1</span>
                            </span>
                        </li>
                        <li>
                            {{ __("Net Total") }}
                            <input type="hidden" name="net_total" id="net_total" value="{{ \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month) }}">
                            <span class="price-txt">{{ $planPriceCurrency }}
                                <span class="span_net_total">{{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month)) }}</span>
                            </span>
                        </li>
                        <li>{{ __("Tax") }}
                            <span class="price-txt" id="tax"><span class="text-success">{{ __("Free") }}</span></span>
                        </li>
                        <li>
                            {{ __("Discount") }}
                            <input type="hidden" name="discount_percent" id="discount_percent" value="0">
                            <input type="hidden" name="discount" id="discount" value="0">
                            <span class="price-txt">{{ $planPriceCurrency }}
                                <span class="span_discount">0.00</span>
                            </span>
                        </li>

                        <li class="total-price-wrap">
                            {{ __("Total Payable") }}
                            <input type="hidden" name="total_payable" id="total_payable" value="{{ \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month) }}">
                            <span class="price-txt">{{ $planPriceCurrency }}
                                <span class="span_total_payable">{{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month)) }}
                            </span>
                        </li>
                    </ul>
                    <a class="single-nav def-btn tab-next-btn text-center" data-tab="stepTwoTab">{{ __('Next Step') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
