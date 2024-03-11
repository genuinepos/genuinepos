<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="billing-details business-setup">
            <h3 class="title">{{ __('Business Setup') }}</h3>
            <div class="form-row">
                <div class="form-col-5">
                    <div class="position-relative">
                        <label for="business">{{ __('Business Name') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="name" id="name" class="form-control" placeholder="{{ __('Enter Business Name') }}" autocomplete="off">
                    </div>
                </div>

                <div class="form-col-5">
                    <div class="domain-field" class="position-relative">
                        <label for="domain">{{ __('Store URL') }} <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input required type="text" name="domain" id="domain" class="form-control" placeholder="{{ __("Store URL") }}" autocomplete="off">
                            <span class="txt">{{ __('.gposs.com') }}</span>
                        </div>
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
                </div>

                <div class="form-col-5">
                    <label for="email-address">{{ __('Email') }} <span class="text-danger">*</span></label>
                    <input required type="email" name="email" class="form-control" id="email" placeholder="{{ __('Enter Email Address') }}" autocomplete="off">
                </div>

                <div class="form-col-5">
                    <label for="currency_id">{{ __('Country') }} <span class="text-danger">*</span></label>
                    <select required name="currency_id" id="currency_id" class="form-control select wide">
                        <option value="" disabled="" selected="" hidden="">{{ __("Select country") }}</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->country }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-col-5">
                    <label for="phone">{{ __('Phone') }} <span class="text-danger">*</span></label>
                    <input required type="tel" name="phone" class="form-control" id="phone" placeholder="{{ __('Enter Phone Number') }}" autocomplete="off">
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
                            <label for="email-address">{{ __('Password') }} <span class="text-danger">*</span></label>
                            <input required type="text" name="password" id="password" class="form-control" placeholder="{{ __('Enter Password') }}" autocomplete="off"/>
                        </div>

                        <div class="form-col-5">
                            <label for="email-address">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                            <input required type="text" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('Confirm Password') }}" autocomplete="off"/>
                        </div>
                    </div>
                </div>

                <div class="form-col-10">
                    <div class="form-row">
                        <div class="form-col-10">
                            <a class="def-btn float-end" id="single-nav" data-tab="stepTwoTab">{{ __('Next Step') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

