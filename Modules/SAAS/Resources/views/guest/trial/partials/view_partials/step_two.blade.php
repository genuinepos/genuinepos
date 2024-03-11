<div class="row">
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 offset-md-3 offset-xl-3 mt-5">
        <div class="billing-details text-center email-verification-section">
            <h3 class="title m-0">{{ __('Verify Your Email Address') }}</h3>
            <small>{{ __('Send a verification code to') }} <span class="fw-bold" id="showEmail">example@email.com</span> <a class="text-primary" id="resendVerificationEmail" href="#">{{ __('Resend') }}</a></small>
            <div class="form-row">
                <div class="col-md-12">
                    <input type="hidden" id="sendVerificationEmailAddress">
                    <input required type="text" name="verification_code" id="verification_code" class="form-control" placeholder="{{ __('Email Verification Code') }}" autocomplete="off">
                </div>
            </div>

            <div class="form-row mt-3">
                <div class="col-md-12 text-end">
                    <a href="#" class="def-btn btn-success" id="checkEmailVerificationCode">{{ __('Click To Verify') }}</a>
                </div>
            </div>
        </div>

        <div class="billing-details d-none text-center email-verification-success">
            <h3 class="badge bg-success">{{ __('Email Address is verified.') }}</h3>
        </div>
    </div>
</div>
