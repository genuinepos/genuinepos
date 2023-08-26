<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Shop Settings") }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_branch_settings_form" action="{{ route('branches.settings.update', $branchSetting->branch_id) }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <label><b>{{ __("Shop") }} : </b>
                        @if ($branchSetting?->branch?->parent_branch_id)
                           {{ __("Chain Shop Of") }} <strong>{{ $branchSetting?->branch?->parentBranch->name }}-({{ $branchSetting?->branch?->branch_code }})</strong>
                        @else
                            {{ $branchSetting?->branch?->name.'-('.$branchSetting?->branch?->branch_code.')' }}
                        @endif

                    </label>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6 branch_name_field">
                        <label><b>{{ __("Invoice Prefix") }}</b></label>
                        <input type="text" name="invoice_prefix" class="form-control" id="branch_setting_invoice_prefix" data-next="branch_setting_quotation_prefix" value="{{ $branchSetting->invoice_prefix }}" placeholder="{{ __("Invoice Prefix") }}"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Quotation Prefix") }}</b></label>
                        <input required type="text" name="quotation_prefix" class="form-control" id="branch_setting_quotation_prefix" data-next="branch_setting_sales_order_prefix" value="{{ $branchSetting->quotation_prefix }}" placeholder="{{ __("Shop ID") }}"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Sales Order Prefix") }}</b></label>
                        <input required type="text" name="sales_order_prefix" class="form-control" id="branch_setting_sales_order_prefix" data-next="branch_setting_sales_return_prefix" value="{{ $branchSetting->sales_order_prefix }}" placeholder="{{ __("Sales Order Prefix") }}" />
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Sales Return Prefix") }}</b> </label>
                        <input type="text" name="sales_return_prefix" class="form-control" id="branch_setting_sales_return_prefix" data-next="branch_setting_payment_voucher_prefix" value="{{ $branchSetting->sales_return_prefix }}" placeholder="{{ __("Sales Return Prefix") }}"/>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Payment Voucher Prefix") }}</b></label>
                        <input required type="text" name="payment_voucher_prefix" class="form-control" id="branch_setting_payment_voucher_prefix" data-next="branch_setting_receipt_voucher_prefix" value="{{ $branchSetting->payment_voucher_prefix }}" placeholder="{{ __("Payment Voucher Prefix") }}"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Receipt Voucher Prefix") }}</b></label>
                        <input required type="text" name="receipt_voucher_prefix" class="form-control" id="branch_setting_receipt_voucher_prefix" data-next="branch_setting_purchase_invoice_prefix" value="{{ $branchSetting->receipt_voucher_prefix }}" placeholder="{{ __("Receipt Voucher Prefix") }}" />
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Purchase Invoice Prefix") }}</b></label>
                        <input required type="text" name="purchase_invoice_prefix" class="form-control" id="branch_setting_purchase_invoice_prefix" data-next="branch_setting_purchase_order_prefix" value="{{ $branchSetting->purchase_invoice_prefix }}" placeholder="{{ __("Purchase Invoice Prefix") }}"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Purchase Order Voucher Prefix") }}</b></label>
                        <input required type="text" name="purchase_order_prefix" class="form-control" id="branch_setting_purchase_order_prefix" data-next="branch_setting_purchase_return_prefix" value="{{ $branchSetting->purchase_order_prefix }}" placeholder="{{ __("Purchase Order Prefix") }}" />
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Purchase Return Voucher Prefix") }}</b></label>
                        <input type="text" name="purchase_return_prefix" class="form-control" id="branch_setting_purchase_return_prefix" data-next="branch_setting_stock_adjustment_prefix" value="{{ $branchSetting->purchase_return_prefix }}" placeholder="{{ __("Purchase Return Prefix") }}" />
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Stock Adjustment Voucher Prefix") }}</b></label>
                        <input type="text" name="stock_adjustment_prefix" class="form-control" id="branch_setting_stock_adjustment_prefix" data-next="branch_setting_add_sale_invoice_layout_id" value="{{ $branchSetting->stock_adjustment_prefix }}" placeholder="{{ __("Stock Adjustment Voucher Prefix") }}" />
                    </div>
                </div>

                <div class="form-group row mt-1">

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Add Sale Default Invoice Layout") }}</b></label>
                        <select name="add_sale_invoice_layout_id" class="form-control" id="branch_setting_add_sale_invoice_layout_id" data-next="branch_setting_pos_sale_invoice_layout_id">
                            @foreach ($invoiceLayouts as $invoiceLayout)
                                    <option {{ $branchSetting->add_sale_invoice_layout_id == $invoiceLayout->id ? 'SELECTED' : '' }} value="{{ $invoiceLayout->id }}">{{ $invoiceLayout->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Add Sale Default Invoice Layout") }}</b></label>
                        <select name="pos_sale_invoice_layout_id" class="form-control" id="branch_setting_pos_sale_invoice_layout_id" data-next="branch_setting_default_tax_ac_id">
                            @foreach ($invoiceLayouts as $invoiceLayout)
                                    <option {{ $branchSetting->pos_sale_invoice_layout_id == $invoiceLayout->id ? 'SELECTED' : '' }} value="{{ $invoiceLayout->id }}">{{ $invoiceLayout->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Default Sale Tax") }}</b></label>
                        <select name="default_tax_ac_id" class="form-control" id="branch_setting_default_tax_ac_id" data-next="branch_settings_save">
                            <option value="">{{ __("Select Sales Default Tax") }}</option>
                            @foreach ($taxAccounts as $taxAccount)
                                    <option {{ $branchSetting->default_tax_ac_id == $taxAccount->id ? 'SELECTED' : '' }} value="{{ $taxAccount->id }}">{{ $taxAccount->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-end mt-1">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button branch_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        <button type="button" id="branch_settings_save" class="btn btn-sm btn-success branch_settings_submit_button">{{ __("Save") }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.branch_settings_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.branch_settings_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_branch_settings_form').on('submit', function(e) {
        e.preventDefault();
        $('.branch_settings_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.branch_settings_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $('#branchSettingEditModal').modal('hide');
                toastr.success(data);
                branchTable.ajax.reload();
            },
            error: function(err) {

                $('.branch_settings_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }

                toastr.error('Please check all form fields.', 'Something Went Wrong');

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });
</script>
