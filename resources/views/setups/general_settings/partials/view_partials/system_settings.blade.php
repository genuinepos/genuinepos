<form id="system_settings_form" class="setting_form hide-all" action="{{ route('settings.system.settings') }}" method="post">
    @csrf
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('System Settings') }}</h6>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4">
            <label><strong>{{ __('Theme Color') }}</strong></label>
            <select name="theme_color" class="form-control" id="theme_color">
                <option @selected(($generalSettings['system__theme_color'] ?? '') == 'dark-theme') value="dark-theme">{{ __('Default Theme') }}</option>
                <option @selected(($generalSettings['system__theme_color'] ?? '') == 'red-theme') value="red-theme">{{ __('Red Theme') }}</option>
                <option @selected(($generalSettings['system__theme_color'] ?? '') == 'blue-theme') value="blue-theme">{{ __('Blue Theme') }}</option>
                <option @selected(($generalSettings['system__theme_color'] ?? '') == 'light-theme') value="light-theme">{{ __('Light Theme') }}</option>
                <option @selected(($generalSettings['system__theme_color'] ?? '') == 'orange-theme') value="orange-theme">{{ __('Orange Theme') }}</option>
            </select>
        </div>

        <div class="col-md-4">
            <label><strong>{{ __("Default datatable page entries") }}</strong></label>
            <select name="datatable_page_entry" class="form-control" id="datatable_page_entry">
                <option @selected(($generalSettings['system__datatables_page_entry'] ?? 0) == 10) value="10">{{ __("10") }}</option>
                <option @selected(($generalSettings['system__datatables_page_entry'] ?? 0) == 25) value="25">{{ __("25") }}</option>
                <option @selected(($generalSettings['system__datatables_page_entry'] ?? 0) == 50) value="50">{{ __("50") }}</option>
                <option @selected(($generalSettings['system__datatables_page_entry'] ?? 0) == 100) value="100">{{ __("100") }}</option>
                <option @selected(($generalSettings['system__datatables_page_entry'] ?? 0) == 500) value="500">{{ __("500") }}</option>
                <option @selected(($generalSettings['system__datatables_page_entry'] ?? 0) == 1000) value="1000">{{ __("1000") }}</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button system_settings_loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
