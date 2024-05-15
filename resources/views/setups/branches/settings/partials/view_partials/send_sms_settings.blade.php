<form id="sms_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.sms', $branch->id) }}" method="post">
    @csrf
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('Send SMS Setttings') }}</h6>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4 mt-1">
            <div class="row mt-4">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['send_sms__send_invoice_via_sms'] == '1' ? 'CHECKED' : '' }} name="send_invoice_via_sms"> &nbsp; <b>{{ __('Send Invoice After Sale Via Sms') }}</b>
                </p>
            </div>
        </div>

        <div class="col-md-4 mt-1">
            <div class="row mt-4">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['send_sms__send_notification_via_sms'] == '1' ? 'CHECKED' : '' }} name="send_notification_via_sms"> &nbsp; <b>{{ __('Send Notification Via Sms') }}</b>
                </p>
            </div>
        </div>

        <div class="col-md-4 mt-1">
            <div class="row mt-4">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" name="customer_due_reminder_via_sms" {{ $generalSettings['send_sms__customer_due_reminder_via_sms'] == '1' ? 'CHECKED' : '' }}> &nbsp; <b>{{ __('Customer Due Remainder Via Sms') }}</b>
                </p>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button sms_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
