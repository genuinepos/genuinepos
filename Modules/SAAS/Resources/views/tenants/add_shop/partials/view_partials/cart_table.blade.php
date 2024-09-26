<div class="table-wrap revel-table">
    <div class="period_buttons_area mb-1 w-100">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-secondary bg-danger shop_price_period_label">
                <input required type="radio" name="shop_price_period" id="shop_price_period" value="month" autocomplete="off"> {{ __('Monthly') }}
            </label>

            <label class="btn btn-secondary shop_price_period_label">
                <input required type="radio" name="shop_price_period" id="shop_price_period" value="year" autocomplete="off"> {{ __('Yearly') }}
            </label>

            @if ($plan->has_lifetime_period == 1)
                <label class="btn btn-secondary shop_price_period_label">
                    <input required type="radio" name="shop_price_period" id="shop_price_period" value="lifetime" autocomplete="off"> {{ __('Lifetime') }}
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
                    <th>{{ __('Plan Name') }}</th>
                    <th>{{ __('Increase Store Quantity') }}</th>
                    <th>{{ __('Price Per Store') }}</th>
                    <th id="period_count_header">{{ __('Months') }}</th>
                    <th>{{ __('Subtotal') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>{{ $plan->name }} <small style="font-size:9px;">({{ __('Current Store Count') }} : <span class="fw-bold">{{ $tenant?->user?->userSubscription?->current_shop_count }})</span></small></td>
                    <td>
                        <div class="product-count cart-product-count">
                            <div class="quantity rapper-quantity">
                                <input type="number" name="increase_shop_count" id="increase_shop_count" min="1" step="1" value="0" readonly>
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
                        <input type="hidden" name="shop_price" id="shop_price" value="0.00">
                        <span class="price-txt">{{ $planPriceCurrency }}
                            <span id="span_shop_price">0.00</span>
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
                        <input type="hidden" name="shop_subtotal" id="shop_subtotal" value="0.00">
                        <span class="price-txt">{{ $planPriceCurrency }}<span id="span_shop_subtotal">0.00</span></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="btn-box">
        <div>
            <label for="discount">{{ __('Discount Amount') }}</label>
            <input type="hidden" name="discount_percent" id="discount_percent" value="0">
            <input type="text" name="discount" class="form-control form-control-sm fw-bold" id="discount" value="0" placeholder="{{ __('0.00') }}" autocomplete="off">
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
                            {{ __("Increased Store Quantity") }}
                            <span class="price-txt" class="span_increase_shop_count">
                                <span class="span_shop_increase_shop_count">1</span>
                            </span>
                        </li>
                        <li>
                            {{ __("Net Total") }}
                            <input type="hidden" name="net_total" id="net_total" value="0">
                            <span class="price-txt">{{ $planPriceCurrency }}
                                <span class="span_net_total">0.00</span>
                            </span>
                        </li>
                        <li>{{ __("Tax") }}
                            <span class="price-txt" id="tax"><span class="text-success">{{ __("Free") }}</span></span>
                        </li>
                        <li>
                            {{ __("Discount") }}
                            <span class="price-txt">{{ $planPriceCurrency }}
                                (<span class="span_discount text-danger">0.00</span>)
                            </span>
                        </li>

                        <li class="total-price-wrap">
                            {{ __("Total Payable") }}
                            <input type="hidden" name="total_payable" id="total_payable" value="0.00">
                            <span class="price-txt">{{ $planPriceCurrency }}
                            <span class="span_total_payable text-success">0.00</span>
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
