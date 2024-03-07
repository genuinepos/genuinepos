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
                                    <li>
                                        {{ __('Total Store Quantity') }}
                                        <span class="price-txt">
                                            <span class="span_total_shop_count">1</span>
                                        </span>
                                    </li>
                                    <li>{{ __('Sub Total') }}
                                        <span class="price-txt">
                                            <span class="span_subtotal_after_discount">{{ $plan->price }}</span>
                                        </span>
                                    </li>
                                    <li>{{ __('Discount') }}
                                        <span class="price-txt">
                                            <span class="span_discount">0.00</span>
                                        </span>
                                    </li>
                                    <li class="total-price-wrap">{{ __('Total Payable') }}
                                        <span class="price-txt">
                                            <span class="span_total_payable">{{ $plan->price }}</span>
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
                                <input checked type="radio" class="form-check-input" name="google-pay" value="aamarpay">
                                <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                            </div>
                            <span class="type">
                                {{ __("Cash On Delivery") }}
                            </span>
                        </div>
                        <span class="icon">
                            <img src="{{ asset('backend/images/google-pay.png') }}" alt="google-pay">
                        </span>
                    </div>

                    {{-- <div class="panel-header">
                        <div class="left-wrap">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="google-pay" value="sslcommarze">
                                <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                            </div>
                            <span class="type">
                                SSLCOMMMERZE
                            </span>
                        </div>
                        <span class="icon">
                            <img src="{{ asset('backend/images/google-pay.png') }}" alt="google-pay">
                        </span>
                    </div> --}}
                </div>
            </div>
            <input type="hidden" id="plan-id" value="{{ $plan->id }}" />
            <button type="submit" class="def-btn palce-order tab-next-btn btn-success text-center" id="submit_button">
                {{ __("Confirm") }}
            </button>

            <button type="button" class="def-btn palce-order tab-next-btn btn-success d-none" id="loading_button">
                {{ __("Loading...") }}
            </button>
        </div>
    </div>
</div>
