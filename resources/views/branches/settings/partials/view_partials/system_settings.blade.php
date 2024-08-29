<form id="system_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.system', $branch->id) }}" method="post">
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('System Settings') }}</h6>
        </div>
    </div>
    @csrf
    <div class="form-group row">
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Theme Color') }}</label>
            <select name="theme_color" class="form-control" id="theme_color">
                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'dark-theme' ? 'SELECTED' : '' }} value="dark-theme">{{ __('Default Theme') }}</option>
                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'red-theme' ? 'SELECTED' : '' }} value="red-theme">{{ __('Red Theme') }}</option>
                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'blue-theme' ? 'SELECTED' : '' }} value="blue-theme">{{ __('Blue Theme') }}</option>
                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'light-theme' ? 'SELECTED' : '' }} value="light-theme">{{ __('Light Theme') }}</option>
                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'orange-theme' ? 'SELECTED' : '' }} value="orange-theme">{{ __('Orange Theme') }}</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Default datatable page entries') }}</label>
            <select name="datatable_page_entry" class="form-control" id="datatable_page_entry">
                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 10 ? 'SELECTED' : '' }} value="10">{{ __('10') }}</option>
                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 25 ? 'SELECTED' : '' }} value="25">{{ __('25') }}</option>
                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 50 ? 'SELECTED' : '' }} value="50">{{ __('50') }}</option>
                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 100 ? 'SELECTED' : '' }} value="100">{{ __('100') }}</option>
                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 500 ? 'SELECTED' : '' }} value="500">{{ __('500') }}</option>
                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 1000 ? 'SELECTED' : '' }} value="1000">{{ __('1000') }}</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button system_setting_loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
