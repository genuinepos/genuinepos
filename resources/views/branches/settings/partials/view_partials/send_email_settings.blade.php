<form id="email_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.send.email', $branch->id) }}" method="post">
    @csrf
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('Send Email Settings') }}</h6>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4 mt-1">
            <div class="row mt-4">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['send_email__send_invoice_via_email'] == '1' ? 'CHECKED' : '' }} name="send_invoice_via_email"> &nbsp; <b>{{ __('Send Invoice After Sale Via Email') }}</b>
                </p>
            </div>
        </div>

        <div class="col-md-4 mt-1">
            <div class="row mt-4">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['send_email__send_notification_via_email'] == '1' ? 'CHECKED' : '' }} name="send_notification_via_email"> &nbsp; <b>{{ __('Send Notification Via Email') }}</b>
                </p>
            </div>
        </div>

        <div class="col-md-4 mt-1">
            <div class="row mt-4">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['send_email__customer_due_reminder_via_email'] == '1' ? 'CHECKED' : '' }} name="customer_due_reminder_via_email"> &nbsp; <b>{{ __('Custome Due Remainder Via Email') }}</b>
                </p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4 mt-1">
            <div class="row mt-4">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['send_email__user_forget_password_via_email'] == '1' ? 'CHECKED' : '' }} name="user_forget_password_via_email"> &nbsp; <b> {{ __('User Forget Password Via Email') }}</b>
                </p>
            </div>
        </div>

        <div class="col-md-4 mt-1">
            <div class="row mt-4">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['send_email__coupon_offer_via_email'] == '1' ? 'CHECKED' : '' }} name="coupon_offer_via_email"> &nbsp; <b>{{ __('Coupon Offer Via Email') }}</b>
                </p>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button email_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
