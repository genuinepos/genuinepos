@php
    $businessPricePerMonth = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->business_price_per_month);
    $businessPricePerYear = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->business_price_per_year);
    $businessLifetimePrice = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->business_lifetime_price);
@endphp

<div class="table-wrap revel-table">
    <input type="hidden" name="plan_id" id="plan_id" value="{{ $plan->id }}" />
    <input type="hidden" name="has_lifetime_period" id="has_lifetime_period" value="{{ $plan->has_lifetime_period }}" />
    <input type="hidden" name="business_price_per_month" id="business_price_per_month" value="{{ $businessPricePerMonth }}">
    <input type="hidden" name="business_price_per_year" id="business_price_per_year" value="{{ $businessPricePerYear }}">
    <input type="hidden" name="business_lifetime_price" id="business_lifetime_price" value="{{ $businessLifetimePrice }}">

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Price(As Per Period)') }}</th>
                    <th>{{ __('Price Period') }}</th>
                    <th>{{ __('Price Period Count') }}</th>
                    <th>{{ __('Subtotal') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="fw-bold">
                        {{ __('Multi Shop Management System') }}({{ __('Business') }})
                    </td>

                    <td>
                        <span class="price-txt">
                            {{ $planPriceCurrency }} <span id="span_business_price">{{ App\Utils\Converter::format_in_bdt($businessPricePerMonth) }}</span>
                        </span>
                        <input type="hidden" name="business_price" id="business_price" value="{{ $businessPricePerMonth }}">
                    </td>

                    <td>
                        <select name="business_price_period" id="business_price_period" class="form-control">
                            <option value="month">{{ __('Monthly') }}</option>
                            <option value="year">{{ __('Yearly') }}</option>
                            <option value="lifetime">{{ __('Lifetime') }}</option>
                        </select>
                    </td>

                    <td>
                        <div class="product-count cart-product-count business_price_period_count">
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
                        </div>
                    </td>

                    <td>
                        <input type="hidden" name="business_subtotal" id="business_subtotal" value="{{ $businessPricePerMonth }}">
                        <span class="price-txt">
                            {{ $planPriceCurrency }} <span id="span_business_subtotal">{{ App\Utils\Converter::format_in_bdt($businessPricePerMonth) }}</span>
                        </span>
                    </td>
                </tr>

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
    <h3 class="title">{{ __('Cart Total') }}</h3>
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
