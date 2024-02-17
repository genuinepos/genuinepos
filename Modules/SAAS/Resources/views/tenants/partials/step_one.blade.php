<div class="single-tab active" id="cartTab">
    <div class="table-wrap revel-table">
        <div class="period_buttons_area mb-1">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary">
                    <input type="radio" name="price_period" id="price_period" value="month" autocomplete="off"> {{ __('Monthly') }}
                </label>

                <label class="btn btn-secondary">
                    <input type="radio" name="price_period" id="price_period" value="year" autocomplete="off"> {{ __('Yearly') }}
                </label>

                <label class="btn btn-secondary">
                    <input type="radio" name="price_period" id="price_period" value="lifetime" autocomplete="off"> {{ __('Lifetime') }}
                </label>

                <input type="hidden" name="is_trial_plan" id="is_trial_plan" value="0">
                <input type="hidden" name="price_per_month" id="price_per_month" value="">
                <input type="hidden" name="price_per_year" id="price_per_year" value="">
                <input type="hidden" name="lifetime_price" id="lifetime_price" value="">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th>{{ __('Plan Name') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Store Quantity') }}</th>
                        <th id="period_count_header">{{ __("Period") }}</th>
                        <th>{{ __('Total') }}</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            <select name="plan_id" id="plan_id" class="form-control plan-select select wide">
                                <option value="">{{ __("Please Select A Plan") }}</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->name }}
                                        @if ($plan->is_trial_plan == 1)
                                            ({{ __('Trial Period'). ' : ' . $plan->trial_days }} {{ __("Days") }})
                                        @else
                                            ({{ __("Monthly") }} : {{ $plan->price_per_month .' '. $plan?->currency?->code }} | {{ __("Yearly") }} : {{ $plan->price_per_year }} | {{ __("Lifetime") }} : {{ $plan->lifetime_price }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="plan_price" id="plan_price" value="">
                            <span class="price-txt"><span id="span_plan_price"></span></span>
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
                            <div class="product-count cart-product-count period_count">
                                <div class="quantity rapper-quantity">
                                    <input readonly name="period_count" id="period_count" type="number" min="1" step="1" value="">
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
                            <div id="fixed_period_text"></div>
                        </td>

                        <td>
                            <input type="hidden" name="subtotal" id="subtotal" value="">
                            <span class="price-txt"><span id="span_subtotal"></span></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="btn-box">
            <div class="cart-coupon-form">
                <input type="text" name="cart-coupon-input" id="cart-coupon-input" placeholder="{{ __('Enter Your Coupon Code') }}">
                <button type="submit" class="def-btn coupon-apply-btn">{{ __('Apply Coupon') }}</button>
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
                            <li>{{ __('Total Store Quantity') }}
                                <span class="price-txt">
                                    <span class="span_total_shop_count">1</span>
                                </span>
                            </li>
                            <li>{{ __('Sub Total') }}
                                <span class="price-txt">
                                    <span class="span_subtotal_after_discount"></span>
                                </span>
                            </li>
                            <li>{{ __('Discount') }}
                                <input type="hidden" name="discount" id="discount">
                                <span class="price-txt" class="span_discount">
                                    <span>0.00</span>
                                </span>
                            </li>
                            <li class="total-price-wrap">{{ __('Total Payable') }}
                                <input type="hidden" name="total_payable" id="total_payable" value="">
                                <span class="price-txt"><span class="span_total_payable"></span></span>
                            </li>
                        </ul>
                        <a class="def-btn tab-next-btn" id="proceedToCheckout">{{ __('Next Step') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
