<div class="row">
    <div class="col-xl-8 col-lg-7 col-md-6">
        <div class="billing-details business-setup">
            <h3 class="title">{{ __('Busienss Setup') }}</h3>
            <div class="form-row">
                <div class="form-col-5">
                    <div class="position-relative">
                        <label for="business">{{ __('Business Name') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="name" id="name" class="form-control" placeholder="{{ __('Enter Business Name') }}" autocomplete="off">
                        <span class="text-danger error error_name"></span>
                    </div>
                </div>

                <div class="form-col-5">
                    <div class="domain-field" class="position-relative">
                        <label for="domain">{{ __('Store URL') }} <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input required type="text" name="domain" id="domain" class="form-control" placeholder="Store URL" autocomplete="off">
                            <span class="txt">{{ __('.gposs.com') }}</span>
                        </div>
                        <p class="mt-2 mb-0">
                            <span id="domainPreview" class="monospace"></span>
                        </p>
                    </div>
                    <span class="text-danger error error_domain"></span>
                </div>
            </div>
        </div>

        <div class="billing-details">
            <h3 class="title">{{ __('Billing Details') }}</h3>
            <div class="form-row">
                <div class="form-col-5">
                    <label for="first-name">{{ __('Fullname') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="fullname" class="form-control" id="fullname" placeholder="{{ __('Enter First Name') }}" autocomplete="off">
                    <span class="text-danger error error_fullname"></span>
                </div>

                <div class="form-col-5">
                    <label for="email-address">{{ __('Email') }} <span class="text-danger">*</span></label>
                    <input required type="email" name="email" class="form-control" id="email" placeholder="{{ __('Enter Email Address') }}" autocomplete="off">
                    <span class="text-danger error error_email"></span>
                </div>

                <div class="form-col-5">
                    <label for="currency_id">{{ __('Country') }} <span class="text-danger">*</span></label>
                    <select required name="currency_id" id="currency_id" class="form-control select wide">
                        <option value="" disabled="" selected="" hidden="">{{ __('Select country') }}</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->country }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger error error_currency_id"></span>
                </div>

                <div class="form-col-5">
                    <label for="phone">{{ __('Phone') }} <span class="text-danger">*</span></label>
                    <input required type="tel" name="phone" class="form-control" id="phone" placeholder="{{ __('Enter Phone Number') }}" autocomplete="off">
                    <span class="text-danger error error_phone"></span>
                </div>

                {{-- <div class="form-col-5">
                        <label for="city">{{ __('Town/ City') }}</label>
                        <input type="text" name="city" class="form-control" id="city" placeholder="{{ __('Enter Town/City') }}" autocomplete="off">
                    </div>

                    <div class="form-col-5">
                        <label for="post-code">{{ __('Post Code') }}</label>
                        <input type="text" name="postal_code" class="form-control" id="postal_code" placeholder="{{ __('Enter Post Code') }}" autocomplete="off">
                    </div> --}}

                <div class="form-col-5">
                    <label for="address">{{ __('Address') }}</label>
                    <input type="text" name="address" class="form-control" id="address" placeholder="{{ __('Enter Address') }}" autocomplete="off">
                </div>

                <div class="form-col-10">
                    <div class="form-row">
                        <div class="form-col-5">
                            <label for="email-address">{{ __('Username') }} <span class="text-danger">*</span></label>
                            <input required type="text" name="username" id="username" class="form-control" placeholder="{{ __('Username') }}" autocomplete="off" />
                            <span class="text-danger error error_username"></span>
                        </div>
                    </div>

                    <div class="form-row mt-2">
                        <div class="form-col-5">
                            <label for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
                            <input required type="text" name="password" id="password" class="form-control" placeholder="{{ __('Enter Password') }}" autocomplete="off" />
                            <span class="text-danger error error_password"></span>
                        </div>

                        <div class="form-col-5">
                            <label for="password_confirmation">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                            <input required type="text" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('Confirm Password') }}" autocomplete="off" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5 col-md-6">
        <div class="payment-method">
            <div class="cart-total-panel">
                <h3 class="title">{{ __('Cart Total') }}</h3>
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
                                    <li>{{ __('Net Total') }}
                                        <span class="price-txt">
                                            {{ $planPriceCurrency }} <span class="span_net_total"></span>
                                        </span>
                                    </li>
                                    <li>{{ __('Discount') }}
                                        <span class="price-txt">
                                            {{ $planPriceCurrency }} <span class="span_discount">0.00</span>
                                        </span>
                                    </li>
                                    <li class="total-price-wrap">{{ __('Total Payable') }}
                                        <span class="price-txt">
                                            {{ $planPriceCurrency }} <span class="span_total_payable"></span>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cart-total-panel payment-section">
                <div class="panel-body">
                    <div class="row gy-5">
                        <div class="col-12">
                            <div class="form-col-5">
                                <label for="payment_status">{{ __('Payment Status') }} <span class="text-danger">*</span></label>
                                <select required name="payment_status" id="payment_status" class="form-control select wide">
                                    <option value="">{{ __('Select Payment Status') }}</option>
                                    <option value="0">{{ __('Pending') }}</option>
                                    <option value="1">{{ __('Paid') }}</option>
                                </select>
                                <span class="text-danger error error_payment_status"></span>
                            </div>

                            <div class="form-col-5 mt-2 repayment_field d-none">
                                <label for="payment_method_name">{{ __('Repayment/Expire Date') }}</label>
                                <input name="repayment_date" id="repayment_date" class="form-control">
                            </div>

                            <div class="form-col-5 mt-2 payment_details_field d-none">
                                <label for="payment_method_name">{{ __('Payment Method') }}</label>
                                <select name="payment_method_name" id="payment_method_name" class="form-control select wide">
                                    <option value="">{{ __('Select Payment Method') }}</option>
                                    <option value="Cash">{{ __('Cash') }}</option>
                                    <option value="Card">{{ __('Card') }}</option>
                                    <option value="Bkash">{{ __('Bkash') }}</option>
                                    <option value="Recket">{{ __('Recket') }}</option>
                                    <option value="Naged">{{ __('Naged') }}</option>
                                </select>
                            </div>

                            <div class="form-col-5 mt-2 payment_details_field d-none">
                                <label for="payment_method_name">{{ __('Payment Transaction ID') }}</label>
                                <input name="payment_trans_id" id="payment_trans_id" class="form-control select wide">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="response-message" class="mt-3 d-none text-start" style="height: 100px;">
                <div class="mt-2">
                    <h6 id="response-message-text">
                        {{ __('Creating The Company. please wait...') }}
                        {{ __('Elapsed Time') }}: <span id="timespan"></span> {{ __('Seconds.') }}

                        <div class="spinner-border text-dark" role="status">
                            <span class="visually-hidden">{{ __('Loading') }}...</span>
                        </div>
                    </h6>
                </div>
            </div>

            <button type="submit" class="def-btn palce-order tab-next-btn btn-success">{{ __('Confirm') }}</button>
        </div>
    </div>
</div>
