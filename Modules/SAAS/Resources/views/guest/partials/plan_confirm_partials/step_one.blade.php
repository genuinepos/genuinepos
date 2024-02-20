<div class="single-tab active" id="cartTab">
    <div class="table-wrap revel-table">
        <div class="period_buttons_area mb-1 w-100">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary {{ $pricePeriod == 'month' ? 'bg-danger active' : '' }}">
                    <input type="radio" name="price_period" id="price_period" value="month" autocomplete="off" {{ $pricePeriod == 'month' ? 'checked' : '' }}> {{ __('Monthly') }}
                </label>

                <label class="btn btn-secondary {{ $pricePeriod == 'year' ? 'bg-danger active' : '' }}">
                    <input type="radio" name="price_period" id="price_period" value="year" {{ $pricePeriod == 'year' ? 'checked' : '' }} autocomplete="off"> {{ __('Yearly') }}
                </label>

                @if ($plan->has_lifetime_period == 1)
                    <label class="btn btn-secondary {{ $pricePeriod == 'lifetime' ? 'bg-danger active' : '' }}">
                        <input type="radio" name="price_period" id="price_period" value="lifetime" {{ $pricePeriod == 'lifetime' ? 'checked' : '' }} autocomplete="off"> {{ __('Lifetime') }}
                    </label>
                @endif

                <input type="hidden" name="plan_id" id="plan_id" value="{{ $plan->id }}" />
                <input type="hidden" name="has_lifetime_period" id="has_lifetime_period" value="{{ $plan->has_lifetime_period }}" />
                <input type="hidden" name="price_per_month" id="price_per_month" value="{{ $plan->price_per_month }}">
                <input type="hidden" name="price_per_year" id="price_per_year" value="{{ $plan->price_per_year }}">
                <input type="hidden" name="lifetime_price" id="lifetime_price" value="{{ $plan->lifetime_price }}">
                <input type="hidden" name="business_price_per_month" id="business_price_per_month" value="{{ $plan->business_price_per_month }}">
                <input type="hidden" name="business_price_per_year" id="business_price_per_year" value="{{ $plan->business_price_per_year }}">
                <input type="hidden" name="business_lifetime_price" id="business_lifetime_price" value="{{ $plan->business_lifetime_price }}">
            </div>

            <label class="btn btn-danger float-end" id="has_business_btn">
                <input type="checkbox" name="has_business" id="has_business" value="1" autocomplete="off">
                {{ __('I Need Multi Store Management System') }}
            </label>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th>{{ __('Plan Name') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Store Quantity') }}</th>
                        <th id="period_count_header">
                            @if ($pricePeriod == 'month')
                                {{ __('Months') }}
                            @elseif ($pricePeriod == 'year')
                                {{ __('Years') }}
                            @elseif ($pricePeriod == 'lifetime')
                                {{ __('Years') }}
                            @endif
                        </th>
                        <th>{{ __('Total') }}</th>
                    </tr>
                </thead>

                <tbody id="plan_price_table">
                    <tr>
                        <td>{{ $plan->name }}</td>
                        <td>
                            @php
                                $defaultPricePeriod = 0;
                                if ($pricePeriod == 'month') {
                                    $defaultPricePeriod = $plan->price_per_month;
                                } elseif ($pricePeriod == 'year') {
                                    $defaultPricePeriod = $plan->price_per_year;
                                } elseif ($pricePeriod == 'lifetime') {
                                    $defaultPricePeriod = $plan->lifetime_price;
                                }
                            @endphp
                            <input type="hidden" name="plan_price" id="plan_price" value="{{ $defaultPricePeriod }}">
                            <span class="price-txt"><span id="span_plan_price">{{ $defaultPricePeriod }}</span></span>
                        </td>
                        <td>
                            <div class="product-count cart-product-count">
                                <div class="quantity rapper-quantity">
                                    <input readonly name="shop_count" id="shop_count" type="number" min="1" step="1" value="1">
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
                            <div class="product-count cart-product-count period_count {{ $pricePeriod == 'lifetime' ? 'd-none' : '' }}">
                                <div class="quantity rapper-quantity">
                                    <input readonly name="period_count" id="period_count" type="number" min="1" step="1" value="1">
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
                            <div id="fixed_period_text">
                                {{ $pricePeriod == 'lifetime' ? 'Lifetime' : '' }}
                            </div>
                        </td>

                        <td>
                            <input type="hidden" name="subtotal" id="subtotal" value="{{ $defaultPricePeriod }}">
                            <span class="price-txt"><span id="span_subtotal">{{ $defaultPricePeriod }}</span></span>
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
                                    <span class="span_subtotal_after_discount">{{ $defaultPricePeriod }}</span>
                                </span>
                            </li>
                            <li>{{ __('Discount') }}
                                <input type="hidden" name="discount" id="discount">
                                <span class="price-txt" class="span_discount">
                                    <span>0.00</span>
                                </span>
                            </li>
                            <li class="total-price-wrap">{{ __('Total Payable') }}
                                <input type="hidden" name="total_payable" id="total_payable" value="{{ $defaultPricePeriod }}">
                                <span class="price-txt"><span class="span_total_payable">{{ $defaultPricePeriod }}</span></span>
                            </li>
                        </ul>
                        <a class="def-btn tab-next-btn" id="proceedToCheckout">{{ __('Next Step') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
