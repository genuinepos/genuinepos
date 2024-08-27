<form id="pos_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.pos', $branch->id) }}" method="post">
    @csrf
    <div class="form-group row">
        <div class="col-md-3">
            <label class="fw-bold">{{ __('Enable Multiple Payment') }} </strong></label>
            <select class="form-control" name="is_enabled_multiple_pay" id="is_enabled_multiple_pay" autofocus>
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['pos__is_enabled_multiple_pay'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="fw-bold">{{ __('Enable Draft') }}</label>
            <select class="form-control" name="is_enabled_draft" id="is_enabled_draft">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['pos__is_enabled_draft'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="fw-bold">{{ __('Enable Quotation') }}</label>
            <select class="form-control" name="is_enabled_quotation" id="is_enabled_quotation">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['pos__is_enabled_quotation'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="fw-bold">{{ __('Enable Suspend') }}</label>
            <select class="form-control" name="is_enabled_suspend" id="is_enabled_suspend">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['pos__is_enabled_suspend'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label class="fw-bold">{{ __('Enable Discount') }}</label>
            <select class="form-control" name="is_enabled_discount" id="is_enabled_discount">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['pos__is_enabled_discount'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="fw-bold">{{ __('Enable Sale Tax') }}</label>
            <select class="form-control" name="is_enabled_order_tax" id="is_enabled_order_tax">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['pos__is_enabled_order_tax'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="fw-bold">{{ __('POS Sale Default Tax') }}</label>
            <select class="form-control" name="default_tax_ac_id" id="pos_default_tax_ac_id">
                <option value="">{{ __('None') }}</option>
                @foreach ($taxAccounts as $tax)
                    <option {{ $generalSettings['pos__default_tax_ac_id'] == $tax->id ? 'SELECTED' : '' }} value="{{ $tax->id }}">{{ $tax->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="fw-bold">{{ __('Show Recent Transactions') }}</label>
            <select class="form-control" name="is_show_recent_transactions" id="is_show_recent_transactions">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['pos__is_show_recent_transactions'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="fw-bold">{{ __('Enable Full Credit Sale') }}</label>
            <select class="form-control" name="is_enabled_credit_full_sale" id="is_enabled_credit_full_sale">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['pos__is_enabled_credit_full_sale'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label class="fw-bold">{{ __('Enable Hold Invoice') }}</label>
            <select class="form-control" name="is_enabled_hold_invoice" id="is_enabled_hold_invoice">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['pos__is_enabled_hold_invoice'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button pos_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button type="submit" id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
