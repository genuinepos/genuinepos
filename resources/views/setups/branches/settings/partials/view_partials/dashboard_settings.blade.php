<form id="dashboard_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.dashboard', $branch->id) }}" method="post">
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('Dashboard Settings') }}</h6>
        </div>
    </div>
    @csrf
    <div class="form-group row">
        <div class="col-md-6">
            <label><strong>{{ __('View Stock Expiry Alert For') }} </strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="number" name="view_stock_expiry_alert_for" class="form-control" id="view_stock_expiry_alert_for" data-name="Day amount" autocomplete="off" value="{{ $generalSettings['dashboard__view_stock_expiry_alert_for'] }}">
                <div class="input-group-prepend">
                    <span class="input-group-text input-group-text-sm" id="basic-addon1">{{ __('Days') }}</span>
                </div>
            </div>
            <span class="error error_view_stock_expiry_alert_for"></span>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button dashboard_setting_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
