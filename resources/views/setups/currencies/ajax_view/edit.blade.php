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
                    <input required type="text" name="symbol" class="form-control" data-next="currency_rate" id="symbol" value="{{ $currency->symbol }}" placeholder="{{ __('Currency Symbol') }}" />
                    <span class="error error_currency_symbol"></span>
                </div>

                <div id="currency_rate_fields">
                    <div class="form-group  mt-2">
                        <p><b>{{ __('Edit Last Currency Rete') }}</b></p>
                        <hr class="p-0 m-0">
                    </div>


                    <div class="form-group mt-2">
                        <div class="col-md-3">
                            <select name="type" id="currency_type">
                                <option value="1">{{ __('Greater Then Base Currency') }}</option>
                                <option @selected($currency->type == 2) value="2">{{ __('Less Then Base Currency') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mt-2 row g-1 no-gutters">
                        <div class="col-md-3" id="currency_name_position_one">
                            @if ($currency->type == 1)
                                <p class="fw-bold">{{ __('1') }} <span id="currency_name">{{ $currency->currency }}</span></p>
                            @else
                                <p class="fw-bold">{{ __('1') }}<span id="base_currency_name">{{ $generalSettings['base_currency_name'] }}</span></p>'
                            @endif
                        </div>

                        <div class="col-md-1">
                            <p class="fw-bold"> = </p>
                        </div>

                        <div class="col-md-3">
                            <input type="text" name="currency_rate" class="form-control fw-bold" id="currency_rate" data-next="currency_rate_date" value="{{ $currency?->currentCurrencyRate?->rate }}" placeholder="{{ __('0.00') }}" />
                            <span class="error error_currency_rate"></span>
                        </div>


                        <div class="col-md-2" id="currency_name_position_two">
                            @if ($currency->type == 1)
                                <p class="fw-bold"><span id="base_currency_name">{{ $generalSettings['base_currency_name'] }}</span></p>
                            @else
                                <p class="fw-bold"><span id="currency_name">{{ $currency->currency }}</span></p>
                            @endif
                            {{-- <p class="fw-bold"><span id="base_currency_name">{{ $generalSettings['base_currency_name'] }}</span></p> --}}
                        </div>

                        <div class="col-md-3">
                            <input type="text" name="currency_rate_date" class="form-control fw-bold " id="currency_rate_date" data-next="currency_save" value="{{ $currency?->currentCurrencyRate?->date_ts ? date($generalSettings['business_or_shop__date_format'], strtotime($currency?->currentCurrencyRate?->date_ts)) : date($generalSettings['business_or_shop__date_format']) }}" placeholder="{{ __('As Per Date') }}" />
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

        $('#currency_name').html(value);
    });

    $(document).on('change', '#currency_type', function(e) {

        var type = $(this).val();
        var currencyName = $('#currency').val() ? $('#currency').val() : "{{ __('Currency') }}";
        var typeOnecurrency = '<p class="fw-bold">' + "{{ __('1') }}" + ' ' + '<span id="currency_name">' + currencyName + '</span></p>';
        var typeOneBaseCurrency = '<p class="fw-bold"><span id="base_currency_name">' + "{{ $generalSettings['base_currency_name'] }}" + '</span></p>';
        var typeTwocurrency = '<p class="fw-bold" id="base_currency_name">' + "{{ $generalSettings['base_currency_name'] }}" + '</span></p>';
        var typeTwoBaseCurrency = '<p class="fw-bold"><span id="base_currency_name"></span></p>';

        if (type == 1) {

            $('#currency_name_position_one').html('<p class="fw-bold">' + "{{ __('1') }}" + ' ' + '<span id="currency_name">' + currencyName + '</span></p>');
            $('#currency_name_position_two').html('<p class="fw-bold"><span id="base_currency_name">' + "{{ $generalSettings['base_currency_name'] }}" + '</span></p>');
        } else {

            $('#currency_name_position_one').html('<p class="fw-bold">' + "{{ __('1') }}" + ' ' + '<span id="base_currency_name">' + "{{ $generalSettings['base_currency_name'] }}" + '</span></p>');
            $('#currency_name_position_two').html('<p class="fw-bold"><span id="currency_name">' + currencyName + '</span></p>');
        }
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
