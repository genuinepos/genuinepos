<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Currency Rate') }} - (<strong>{{ $currencyRate?->currency?->country . '-' . $currencyRate?->currency?->currency . '-' . $currencyRate?->currency?->code . '-' . $currencyRate?->currency?->symbol }}</strong>)</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_currency_rate_form" action="{{ route('currencies.rates.update', $currencyRate->id) }}" method="post">
                @csrf
                <input type="hidden" name="currency_id" value="{{ $currencyRate?->currency?->id }}">
                <div class="form-group">
                    <label><b>{{ __('Date') }}</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="date" class="form-control" id="currency_rate_date" data-next="currency_rate_rate" value="{{ date($generalSettings['business_or_shop__date_format'], strtotime($currencyRate?->date_ts)) }}" placeholder="{{ __('Date') }}" autocomplete="off" />
                    <span class="error error_currency_rate_date"></span>
                </div>

                <div id="currency_rate_fields">
                    <div class="form-group mt-2">
                        <p><b>{{ __('Currency Rate') }}</b></p>
                        <hr class="p-0 m-0">
                    </div>

                    <div class="form-group mt-1 row g-2">
                        <div class="col-md-3">
                            <p class="fw-bold">{{ __('1') }} <span id="currency_name">{{ $currencyRate?->currency?->currency }}</span></p>
                        </div>

                        <div class="col-md-1">
                            <p class="fw-bold"> = </p>
                        </div>

                        <div class="col-md-3">
                            <input required type="text" name="rate" class="form-control fw-bold" id="currency_rate_rate" data-next="currency_rate_save_changes" value="{{ $currencyRate?->rate }}" placeholder="{{ __('0.00') }}" />
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
                            <button type="button" class="btn loading_button currency_rate_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="currency_rate_save_changes" class="btn btn-sm btn-success currency_rate_submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.currency_rate_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.currency_rate_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_currency_rate_form').on('submit', function(e) {
        e.preventDefault();

        $('.currency_rate_loading_btn').show();
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
                $('.currency_rate_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);

                currencyRatesTable.ajax.reload();

                $('#currencyRateAddOrEditModal').modal('hide');
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.currency_rate_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
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

    $(document).on('input keypress', '#currency', function(e) {

        var value = $(this).val() ? $(this).val() : '';

        $('#currency_rate').html(value);
    });
</script>

<script>
    var dateFormat = "{{ $generalSettings['business_or_shop__date_format'] }}";
    var _expectedDateFormat = '';
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('currency_rate_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });
</script>
