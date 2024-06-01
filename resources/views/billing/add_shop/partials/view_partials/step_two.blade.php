<div class="row">
    <div class="col-xl-4 col-lg-5 col-md-6">
        <div class="payment-method">
            <div class="cart-total-panel">
                <h3 class="title">{{ __("Cart Total") }}</h3>
                <div class="panel-body">
                    <div class="row gy-5">
                        <div class="col-12">
                            <div class="calculate-area">
                                <ul>
                                    <li>
                                        {{ __('Total Store Quantity') }}
                                        <span class="price-txt ">
                                            <span class="span_increase_shop_count">1</span>
                                        </span>
                                    </li>

                                    <li>{{ __('Sub Total') }}
                                        <span class="price-txt">{{ $planPriceCurrency }}
                                            <span class="span_net_total">{{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month)) }}</span>
                                        </span>
                                    </li>

                                    <li>{{ __('Discount') }}
                                        <span class="price-txt">{{ $planPriceCurrency }}
                                            <span class="span_discount">0.00</span>
                                        </span>
                                    </li>

                                    <li class="total-price-wrap">{{ __('Total Payable') }}
                                        <span class="price-txt">{{ $planPriceCurrency }}
                                            <span class="span_total_payable">{{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month)) }}</span>
                                        </span>
                                    </li>
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
                                <input class="form-check-input" checked name="cash" type="checkbox" disabled>
                                <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                            </div>
                            <span class="type">
                                {{ __("Cash on delivery") }}
                            </span>
                        </div>
                        <span class="icon">
                            <img src="{{ asset('backend/images/dollar.png') }}" alt="cash">
                        </span>
                    </div>
                </div>
            </div>

            <button type="submit" class="def-btn palce-order tab-next-btn btn-success text-center" id="submit_button">
                {{ __("Confirm") }}
            </button>

            <button type="button" class="def-btn palce-order tab-next-btn btn-success d-none" id="loading_button">
                {{ __("Loading...") }}
            </button>
        </div>
    </div>
</div>
