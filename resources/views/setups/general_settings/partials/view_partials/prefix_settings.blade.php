<form id="prefix_settings_form" class="setting_form hide-all" action="{{ route('settings.prefix.settings') }}" method="post">
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('Prefix Settings') }}</h6>
        </div>
    </div>
    @csrf

    <div class="form-group row">
        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Sales Invoice Prefix') }}</label>
            <input type="text" name="sales_invoice_prefix" class="form-control" id="sales_invoice_prefix" value="{{ $generalSettings['prefix__sales_invoice_prefix'] }}" placeholder="{{ __('Invoice Prefix') }}" />
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Quotation Prefix') }}</label>
            <input type="text" name="quotation_prefix" class="form-control" id="quotation_prefix" value="{{ $generalSettings['prefix__quotation_prefix'] }}" placeholder="{{ __('Quotation Prefix') }}" />
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Sales Order Prefix') }}</label>
            <input type="text" name="sales_order_prefix" class="form-control" id="sales_order_prefix" value="{{ $generalSettings['prefix__sales_order_prefix'] }}" placeholder="{{ __('Sales Order Prefix') }}" />
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Sales Return Prefix') }}</label>
            <input type="text" name="sales_return_prefix" class="form-control" id="sales_return_prefix" value="{{ $generalSettings['prefix__sales_return_prefix'] }}" placeholder="{{ __('Sales Return Prefix') }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Payment Voucher Prefix') }}</label>
            <input type="text" name="payment_voucher_prefix" class="form-control" id="payment_voucher_prefix" value="{{ $generalSettings['prefix__payment_voucher_prefix'] }}" placeholder="{{ __('Payment Voucher Prefix') }}" />
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Receipt Voucher Prefix') }}</label>
            <input type="text" name="receipt_voucher_prefix" class="form-control" id="receipt_voucher_prefix" value="{{ $generalSettings['prefix__receipt_voucher_prefix'] }}" placeholder="{{ __('Receipt Voucher Prefix') }}" />
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Expense Voucher Prefix') }}</label>
            <input type="text" name="expense_voucher_prefix" class="form-control" id="expense_voucher_prefix" value="{{ $generalSettings['prefix__expense_voucher_prefix'] }}" placeholder="{{ __('Expense Voucher Prefix') }}" />
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Contra Voucher Prefix') }}</label>
            <input type="text" name="contra_voucher_prefix" class="form-control" id="contra_voucher_prefix" value="{{ $generalSettings['prefix__contra_voucher_prefix'] }}" placeholder="{{ __('Expense Voucher Prefix') }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Purchase Invoice Prefix') }}</label>
            <input type="text" name="purchase_invoice_prefix" class="form-control" id="purchase_invoice_prefix" value="{{ $generalSettings['prefix__purchase_invoice_prefix'] }}" placeholder="{{ __('Purchase Invoice Prefix') }}" />
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Purchase Order Voucher Prefix') }}</label>
            <input required type="text" name="purchase_order_prefix" class="form-control" id="purchase_order_prefix" value="{{ $generalSettings['prefix__purchase_order_prefix'] }}" placeholder="{{ __('Purchase Order Prefix') }}" />
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Purchase Return Voucher Prefix') }}</label>
            <input type="text" name="purchase_return_prefix" class="form-control" id="purchase_return_prefix" value="{{ $generalSettings['prefix__purchase_return_prefix'] }}" placeholder="{{ __('Purchase Return Prefix') }}" />
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Stock Adjustment Voucher Prefix') }}</label>
            <input type="text" name="stock_adjustment_prefix" class="form-control" id="stock_adjustment_prefix" value="{{ $generalSettings['prefix__stock_adjustment_prefix'] }}" placeholder="{{ __('Stock Adjustment Voucher Prefix') }}" />
        </div>
    </div>


    <div class="row mt-1">
        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Payroll Voucher Prefix') }}</label>
            <input type="text" name="payroll_voucher_prefix" class="form-control" id="payroll_voucher_prefix" value="{{ $generalSettings['prefix__payroll_voucher_prefix'] }}" placeholder="{{ __('Payroll Voucher Prefix') }}" />
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Payroll Payment Voucher Prefix') }}</label>
            <input type="text" name="payroll_payment_voucher_prefix" class="form-control" id="payroll_payment_voucher_prefix" value="{{ $generalSettings['prefix__payroll_payment_voucher_prefix'] }}" placeholder="{{ __('Payroll Voucher Prefix') }}" />
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="fw-bold">{{ __('Stock Issue Voucher Prefix') }}</label>
            <input type="text" name="stock_issue_voucher_prefix" class="form-control" id="stock_issue_voucher_prefix" value="{{ isset($generalSettings['prefix__stock_issue_voucher_prefix']) ? $generalSettings['prefix__stock_issue_voucher_prefix'] : '' }}" placeholder="{{ __('Stock Issue Voucher Prefix') }}" />
        </div>
    </div>


    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label class="fw-bold">{{ __('Supplier ID') }}</label>
            <input type="text" name="supplier_id" class="form-control" value="{{ $generalSettings['prefix__supplier_id'] }}" autocomplete="off" />
        </div>

        <div class="col-md-3">
            <label class="fw-bold">{{ __('Customer ID') }}</label>
            <input type="text" name="customer_id" class="form-control" autocomplete="off" value="{{ $generalSettings['prefix__customer_id'] }}" />
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button prefix_setting_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
