<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Currency') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <form id="edit_currency_form" action="{{ route('currencies.update', $currency->id) }}" method="post">
                @csrf
                <div class="form-group">
                    <label><b>{{ __('Country Name') }}</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="country" class="form-control" data-next="currency" id="country" value="{{ $currency->country }}" placeholder="{{ __('Country Name') }}" />
                    <span class="error error_currency_country"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __('Currency Name') }}</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="currency" class="form-control" data-next="code" id="currency" value="{{ $currency->currency }}" placeholder="{{ __('Currency Name') }}" />
                    <span class="error error_currency_currency"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __('Currency Code') }}</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="code" class="form-control" data-next="symbol" id="code" value="{{ $currency->code }}" placeholder="{{ __('Currency Code') }}" />
                    <span class="error error_currency_code"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __('Currency Symbol') }}</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="symbol" class="form-control" data-next="currency_save" id="symbol" value="{{ $currency->symbol }}" placeholder="{{ __('Currency Symbol') }}" />
                    <span class="error error_currency_symbol"></span>
                </div>

                <div id="currency_rate_fields">
                    <div class="form-group mt-2 row g-2">
                        <div class="col-md-3">
                            <p class="fw-bold">{{ __('1') }} <span id="currency_name">{{ $currency->currency }}</span></p>
                        </div>

                        <div class="col-md-1">
                            <p class="fw-bold"> = </p>
                        </div>

                        <div class="col-md-4">
                            <input type="text" name="currency_rate" class="form-control fw-bold" id="currency_rate" data-next="currency_save" value="{{ $currency->currency_rate }}" placeholder="{{ __('0.00') }}" />
                            <span class="error error_currency_rate"></span>
                        </div>

                        <div class="col-md-3">
                            <p class="fw-bold"><span id="base_currency_name">{{ session('base_currency_symbol') }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button currency_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="currency_save" class="btn btn-sm btn-success currency_submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.currency_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.currency_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_currency_form').on('submit', function(e) {
        e.preventDefault();

        $('.currency_loading_btn').show();
        var url = $(this).attr('action');

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: new FormData(this),
            processData: false,
            cache: false,
            contentType: false,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.currency_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);

                currenciesTable.ajax.reload();

                $('#currencyAddOrEditModal').modal('hide');
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.currency_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_currency_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
