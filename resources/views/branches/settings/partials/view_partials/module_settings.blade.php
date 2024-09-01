<form id="module_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.module', $branch->id) }}" method="post">
    @csrf
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary"><b>{{ __('Module Settings') }}</b></h6>
        </div>
    </div>

    <div class="form-group row mt-2">

        <div class="col-md-4">
            <div class="row ">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['modules__purchases'] == '1' ? 'CHECKED' : '' }} name="purchases" autocomplete="off"> &nbsp; <b>{{ __('Purchases') }}</b>
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['modules__add_sale'] == '1' ? 'CHECKED' : '' }} name="add_sale" autocomplete="off"> &nbsp; <b>{{ __('Add Sale') }}</b>
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['modules__pos'] == '1' ? 'CHECKED' : '' }} name="pos" autocomplete="off"> &nbsp; <b>{{ __('POS') }}</b>
                </p>
            </div>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-4">
            <div class="row ">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['modules__transfer_stock'] == '1' ? 'CHECKED' : '' }} name="transfer_stock" autocomplete="off">
                    &nbsp; <b>{{ __('Transfers Stock') }}</b>
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['modules__stock_adjustments'] == '1' ? 'CHECKED' : '' }} name="stock_adjustments" autocomplete="off"> &nbsp; <b>{{ __('Stock Adjustments') }}</b>
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" @checked(isset($generalSettings['modules__service']) && $generalSettings['modules__service'] == '1') name="service" autocomplete="off"> &nbsp; <b>{{ __('Services') }}</b>
                </p>
            </div>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-4">
            <div class="row ">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['modules__accounting'] == '1' ? 'CHECKED' : '' }} name="accounting" autocomplete="off"> &nbsp; <b>{{ __('Accounting') }}</b>
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['modules__contacts'] == '1' ? 'CHECKED' : '' }} name="contacts" autocomplete="off"> &nbsp; <b>{{ __('Contacts') }}</b>
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['modules__hrms'] == '1' ? 'CHECKED' : '' }} name="hrms" autocomplete="off"> &nbsp; <b>{{ __('Human Resource Management') }}</b>
                </p>
            </div>
        </div>
    </div>

    <div class="form-group row mt-2">

        <div class="col-md-4">
            <div class="row">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['modules__manage_task'] == '1' ? 'CHECKED' : '' }} name="manage_task" autocomplete="off"> &nbsp; <b>{{ __('Manage Task') }}</b>
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" @if (isset($generalSettings['modules__manufacturing'])) {{ $generalSettings['modules__manufacturing'] == '1' ? 'CHECKED' : '' }} @endif name="manufacturing" autocomplete="off">
                    &nbsp;<b>{{ __('Manufacturing') }}</b>
                </p>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button module_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
