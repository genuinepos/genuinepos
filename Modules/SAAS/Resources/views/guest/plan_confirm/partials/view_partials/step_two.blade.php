<div class="row">
    <div class="col-xl-8 col-lg-7 col-md-6">
        <div class="billing-details business-setup">
            <h3 class="title">{{ __('Business Setup') }}</h3>
            <div class="form-row">
                <div class="form-col-5">
                    <div class="position-relative">
                        <label for="business">{{ __('Business Name') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="name" id="name" class="form-control" placeholder="{{ __('Enter Business Name') }}" autocomplete="off">
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    </div>
                </div>

                <div class="form-col-5">
                    <div class="domain-field" class="position-relative">
                        <label for="domain">{{ __('Store URL') }} <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input required type="text" name="domain" id="domain" class="form-control" placeholder="my-business" autocomplete="off">
                            <span class="txt">{{ __('.gposs.com') }}</span>
                        </div>
                        <span class="text-danger">{{ $errors->first('domain') }}</span>
                        <p class="mt-2">
                            <span id="domainPreview" class="monospace"></span>
                        </p>

                    </div>
                </div>
            </div>
        </div>

        <div class="billing-details">
            <h3 class="title">{{ __('Billing Details') }}</h3>
            <div class="form-row">
                <div class="form-col-5">
                    <label for="first-name">{{ __('Fullname') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="fullname" class="form-control" id="fullname" placeholder="{{ __('Enter First Name') }}" autocomplete="off">
                    <span class="text-danger">{{ $errors->first('fullname') }}</span>
                </div>

                <div class="form-col-5">
                    <label for="email-address">{{ __('Email') }} <span class="text-danger">*</span></label>
                    <input required type="email" name="email" class="form-control" id="email" placeholder="{{ __('Enter Email Address') }}" autocomplete="off">
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                </div>

                <div class="form-col-5">
                    <label for="currency_id">{{ __('Country') }} <span class="text-danger">*</span></label>
                    <select required name="currency_id" id="currency_id" class="form-control select wide">
                        <option value="" disabled="" selected="" hidden="">{{ __('Select country') }}</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->country }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger">{{ $errors->first('currency_id') }}</span>
                </div>

                <div class="form-col-5">
                    <label for="phone">{{ __('Phone') }} <span class="text-danger">*</span></label>
                    <input required type="tel" name="phone" class="form-control" id="phone" placeholder="{{ __('Enter Phone Number') }}" autocomplete="off">
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                </div>

                <div class="form-col-5">
                    <label for="city">{{ __('Town/ City') }}</label>
                    <input type="text" name="city" class="form-control" id="city" placeholder="{{ __('Enter Town/City') }}" autocomplete="off">
                </div>

                <div class="form-col-5">
                    <label for="post-code">{{ __('Post Code') }}</label>
                    <input type="text" name="postal_code" class="form-control" id="postal_code" placeholder="{{ __('Enter Post Code') }}" autocomplete="off">
                </div>

                <div class="form-col-5">
                    <label for="address">{{ __('Address') }}</label>
                    <input type="text" name="address" class="form-control" id="address" placeholder="{{ __('Enter Address') }}" autocomplete="off">
                </div>

                <div class="form-col-10">
                    <div class="form-row">
                        <div class="form-col-5">
                            <label for="username">{{ __('Username') }} <span class="text-danger">*</span></label>
                            <input required type="text" name="username" id="username" class="form-control" placeholder="{{ __('Username') }}" autocomplete="off" />
                            <span class="text-danger">{{ $errors->first('username') }}</span>
                        </div>
                    </div>

                    <div class="form-row mt-2">
                        <div class="form-col-5">
                            <label for="email-address">{{ __('Password') }} <span class="text-danger">*</span></label>
                            <input required type="password" name="password" id="password" class="form-control" placeholder="{{ __('Enter Password') }}" autocomplete="off" />
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        </div>

                        <div class="form-col-5">
                            <label for="email-address">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                            <input required type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('Confirm Password') }}" autocomplete="off" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                        <span class="price-txt">
                                            <span class="span_total_shop_count">1</span>
                                        </span>
                                    </li>
                                    <li>{{ __('Net Total') }}
                                        <span class="price-txt">
                                            <span class="span_net_total">{{ $plan->price }}</span>
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

            <div class="payment-option">
                <h6 class="p-2 mt-2">Choose Payment Method</h6>
                <hr class="p-0 m-0">
                {{-- <div class="single-payment-card">
                    <div class="panel-header">
                        <div class="left-wrap">
                            <div class="form-check">
                                <input class="form-check-input" name="credit-card" type="checkbox" disabled>
                                <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                            </div>
                            <span class="type">
                                Credit Card
                            </span>
                        </div>
                        <span class="icon">
                            <img src="assets/images/credit-card.png" alt="credit-card">
                        </span>
                    </div>

                    <div class="panel-body">
                        <div class="credit-card-form">
                            <div class="row g-lg-4 g-3">
                                <div class="col-12">
                                    <div class="input-box card-number">
                                        <span class="symbol">
                                            <img src="assets/images/visa.png" alt="Card Type">
                                        </span>
                                        <label>Card Number</label>
                                        <input type="text" id="creditCardNumber" placeholder="XXXX XXXX XXXX XXXX">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-box">
                                        <label>Expiry date</label>
                                        <input type="text" id="datepicker" placeholder="MM/YYYY">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-box">
                                        <label>Security code</label>
                                        <input type="number" id="securityCode" placeholder="e.g. 123">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input-box">
                                        <label>Enter card holder name</label>
                                        <input type="text" id="cardHolderName" placeholder="Card holder">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="single-payment-card">
                    <div class="panel-header">
                        <div class="left-wrap">
                            <div class="form-check">
                                <input class="form-check-input" name="paypal" type="checkbox" disabled>
                                <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                            </div>
                            <span class="type">
                                PayPal
                            </span>
                        </div>
                        <span class="icon">
                            <img src="assets/images/paypal.png" alt="paypal">
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="paypal-form">
                            <div class="row g-lg-4 g-3">
                                <div class="col-12">
                                    <label>Email or phone no. that used in paypal</label>
                                    <input type="email" name="paypal-id" id="paypalId" placeholder="Email or mobile number" required>
                                </div>
                                <div class="col-12">
                                    <button type="submit" id="confirmPaypal" class="def-btn">Confirm Paypal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="single-payment-card">
                    <div class="panel-header">
                        <div class="left-wrap">
                            <div class="form-check">
                                <input checked class="form-check-input" name="google-pay" type="radio" disabled>
                                <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                            </div>
                            <span class="type">
                                Aamer Pay
                            </span>
                        </div>
                        <span class="icon">
                            <img src="{{ asset('assets/images/aamarpay_logo.png') }}" alt="google-pay">
                        </span>
                    </div>

                    {{-- <div class="panel-body">
                        <div class="google-pay-form">
                            <div class="row g-lg-4 g-3">
                                <div class="col-12">
                                    <label>Email or phone no. that used in google pay</label>
                                    <input type="email" name="google-Pay-id" id="googlePayId" placeholder="Email or mobile number" required>
                                </div>
                                <div class="col-12">
                                    <button type="submit" id="confirmGooglePay" class="def-btn">Confirm Google Pay</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>

                {{-- <div class="single-payment-card">
                    <div class="panel-header">
                        <div class="left-wrap">
                            <div class="form-check">
                                <input class="form-check-input" name="cash" type="checkbox" disabled>
                                <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                            </div>
                            <span class="type">
                                Cash on delivery
                            </span>
                        </div>
                        <span class="icon">
                            <img src="assets/images/dollar.png" alt="cash">
                        </span>
                    </div>
                </div> --}}
            </div>

            {{-- <button type="submit" class="def-btn palce-order tab-next-btn btn-success" id="palceOrder">{{ __("Confirm") }}</button> --}}
            <a class="def-btn tab-next-btn single-nav" data-tab="stepThreeTab">{{ __('Next Step') }}</a>
            {{-- <button type="submit" class="def-btn palce-order tab-next-btn btn-success">{{ __('Confirm') }}</button> --}}
        </div>
    </div>
</div>
