<form id="invoice_layout_settings_form" class="setting_form hide-all" action="{{ route('settings.invoice.layout.settings') }}" method="post">
    @csrf
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('Invoice Layout Settings') }}</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6">
            <label class="fw-bold">{{ __('Add Sale Default Invoice Layout') }}</label>
            <select name="add_sale_invoice_layout_id" class="form-control" id="add_sale_invoice_layout_id">
                @foreach ($invoiceLayouts as $invoiceLayout)
                    <option @selected($generalSettings['invoice_layout__add_sale_invoice_layout_id'] == $invoiceLayout->id) value="{{ $invoiceLayout->id }}">{{ $invoiceLayout->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-6 col-md-6">
            <label class="fw-bold">{{ __('Pos Sale Default Invoice Layout') }}</label>
            <select name="pos_sale_invoice_layout_id" class="form-control" id="pos_sale_invoice_layout_id">
                @foreach ($invoiceLayouts as $invoiceLayout)
                    <option @selected($generalSettings['invoice_layout__pos_sale_invoice_layout_id'] == $invoiceLayout->id) value="{{ $invoiceLayout->id }}">{{ $invoiceLayout->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button invoice_layout_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button type="submit" id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
