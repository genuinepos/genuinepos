<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Shop') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_branch_form" action="{{ route('branches.update', $branch->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-9" style="border-right: 1px solid #000;">
                        <input type="hidden" name="branch_type" id="branch_type" value="{{ $branch->branch_type }}">
                        <input type="hidden" name="parent_branch_id" id="parent_branch_id" value="{{ $branch->parent_branch_id }}">
                        @if ($branch->branch_type == \App\Enums\BranchType::ChainShop->value)
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <label><b>{{ __('Parent Shop') }}</b> </label>
                                    <input readonly type="text" name="name" class="form-control fw-bold" value="{{ $branch?->parentBranch?->name . '/' . $branch?->parentBranch?->branch_code }}" />
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <label> <b>{{ __('Shop Type') }}</b></label>
                                    <select name="branch_type" class="form-control" id="branch_type" data-next="branch_name">
                                        @foreach (\App\Enums\BranchType::cases() as $branchType)
                                            <option {{ $branchType->value == $branch->branch_type ? 'SELECTED' : '' }} value="{{ $branchType->value }}">{{ preg_replace('/[A-Z]/', ' ' . "$0", $branchType->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-6 parent_branches_field {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? '' : 'd-hide' }}">
                                    <label> <b>{{ __('Parent Shop') }}</b> <span class="text-danger">*</span></label>
                                    <select name="parent_branch_id" class="form-control" id="branch_parent_branch_id" data-next="branch_code">
                                        <option value="">{{ __('Select Parent Shop') }}</option>
                                        @foreach ($branches as $br)
                                            <option {{ $br->id == $branch->parent_branch_id ? 'SELECTED' : '' }} value="{{ $br->id }}">{{ $br->name . ' / ' . $br->branch_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                        @endif

                        <div class="form-group row mt-1">

                            @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                                <div class="col-lg-3 col-md-6 branch_name_field">
                                    <label><b>{{ __('Shop Name') }}</b> <span class="text-danger">*</span></label>
                                    <input required type="text" name="name" class="form-control" id="branch_name" data-next="area_name" value="{{ $branch->name }}" placeholder="{{ __('Shop Name') }}" />
                                    <span class="error error_branch_name"></span>
                                </div>

                                {{-- <div class="col-lg-3 col-md-6 branch_name_field {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? 'd-hide' : '' }}">
                                    <label><b>{{ __('Shop Name') }}</b> <span class="text-danger">*</span></label>
                                    <input {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? '' : 'required' }} type="text" name="name" class="form-control" id="branch_name" data-next="area_name" value="{{ $branch->name }}" placeholder="{{ __('Shop Name') }}" />
                                    <span class="error error_branch_name"></span>
                                </div> --}}
                            @endif

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Area Name') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="area_name" class="form-control" id="area_name" data-next="branch_code" value="{{ $branch->area_name }}" placeholder="{{ __('Area Name') }}" />
                                <span class="error error_branch_code"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Shop ID') }}</b> <span class="text-danger">*</span></label>
                                <input required readonly type="text" name="branch_code" class="form-control fw-bold" id="branch_code" data-next="branch_phone" value="{{ $branch->branch_code }}" placeholder="{{ __('Shop ID') }}" />
                                <span class="error error_branch_code"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Phone') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="phone" class="form-control" data-name="Phone number" id="branch_phone" data-next="branch_alternate_phone_number" value="{{ $branch->phone }}" placeholder="{{ __('Phone No') }}" />
                                <span class="error error_branch_phone"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Alternative Phone') }}</b> </label>
                                <input type="text" name="alternate_phone_number" class="form-control" id="branch_alternate_phone_number" data-next="branch_bin" value="{{ $branch->alternate_phone_number }}" placeholder="{{ __('Alternative Phone') }}" />
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Business Indentification No.') }} </label>
                                <input type="text" name="bin" class="form-control" id="branch_bin" data-next="branch_tin" value="{{ $branch->bin }}" placeholder="{{ __('Business Indentification Number') }}" autocomplete="off" />
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Vat/Tax No.') }} </label>
                                <input type="text" name="tin" class="form-control" id="branch_tin" data-next="branch_country" value="{{ $branch->tin }}" placeholder="{{ __('Vat/Tax Number.') }}" autocomplete="off" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Country') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="country" class="form-control" id="branch_country" data-next="branch_state" value="{{ $branch->country }}" placeholder="{{ __('Country') }}" />
                                <span class="error error_branch_country"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('State') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="state" class="form-control" id="branch_state" data-next="branch_city" value="{{ $branch->state }}" placeholder="{{ __('State') }}" />
                                <span class="error error_branch_state"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label> <b>{{ __('City') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="city" class="form-control" id="branch_city" data-next="branch_zip_code" value="{{ $branch->city }}" placeholder="{{ __('City') }}" />
                                <span class="error error_branch_city"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Zip-Code') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="zip_code" class="form-control" id="branch_zip_code" data-next="branch_address" value="{{ $branch->zip_code }}" placeholder="Zip code" />
                                <span class="error error_branch_zip_code"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-6 col-md-6">
                                <label><b>{{ __('Address') }}</b></label>
                                <input required type="text" name="address" class="form-control" id="branch_address" data-next="branch_email" value="{{ $branch->address }}" placeholder="{{ __('Address') }}" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Email') }}</b></label>
                                <input type="text" name="email" class="form-control" id="branch_email" data-next="branch_website" value="{{ $branch->email }}" placeholder="{{ __('Email address') }}" />
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Website') }}</b></label>
                                <input type="text" name="website" class="form-control" id="branch_website" data-next="branch_date_format" value="{{ $branch->website }}" placeholder="{{ __('Website Url') }}" />
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Logo') }}</b> <small class="text-danger">{{ __('Logo size 200px * 70px') }}</small></label>
                                <input type="file" name="logo" class="form-control " id="logo" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Date Format') }}</label>
                                <select name="date_format" class="form-control" id="branch_date_format" data-next="branch_time_format">
                                    <option value="d-m-Y" {{ $branchSettings['business_or_shop__date_format'] == 'd-m-Y' ? 'SELECTED' : '' }}>{{ date('d-m-Y') }}</option>
                                    <option value="m-d-Y" {{ $branchSettings['business_or_shop__date_format'] == 'm-d-Y' ? 'SELECTED' : '' }}>{{ date('m-d-Y') }}</option>
                                    <option value="Y-m-d" {{ $branchSettings['business_or_shop__date_format'] == 'Y-m-d' ? 'SELECTED' : '' }}>{{ date('Y-m-d') }}</option>
                                </select>
                                <span class="error error_date_format"></span>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Time Format') }}</label>
                                <select name="time_format" class="form-control" id="branch_time_format" data-next="branch_timezone">
                                    <option {{ $branchSettings['business_or_shop__time_format'] == '12' ? 'SELECTED' : '' }} value="12">{{ __('12 Hour') }}</option>
                                    <option {{ $branchSettings['business_or_shop__time_format'] == '24' ? 'SELECTED' : '' }} value="24">{{ __('24 Hour') }}</option>
                                </select>
                                <span class="error error_time_format"></span>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Time Zone') }} <span class="text-danger">*</span> {{ now()->format('Y-m-d') }}</label>
                                <select required name="timezone" class="form-control" id="branch_timezone" data-next="branch_stock_accounting_method">
                                    <option value="">{{ __('Time Zone') }}</option>
                                    @foreach ($timezones as $key => $timezone)
                                        <option {{ ($branchSettings['business_or_shop__timezone'] ?? 'Asia/Dhaka') == $key ? 'SELECTED' : '' }} value="{{ $key }}">{{ $timezone }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_time_format"></span>
                            </div>
                        </div>

                        @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                            <div class="row mt-1 {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? 'd-hide' : '' }}" id="stock_accounting_method_field">
                                <div class="col-md-12">
                                    <label class="fw-bold">{{ __('Stock Accounting Method') }}</label>
                                    <select name="stock_accounting_method" class="form-control" id="branch_stock_accounting_method" data-next="branch_account_start_date">
                                        @php
                                            $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'] ?? null;
                                        @endphp
                                        @foreach (App\Utils\Util::stockAccountingMethods() as $key => $item)
                                            <option {{ $stockAccountingMethod == $key ? 'SELECTED' : '' }} value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error error_financial_year_start"></span>
                                </div>
                            </div>

                            <div class="form-group row mt-1 {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? 'd-hide' : '' }}" id="account_start_date_field">
                                <div class="col-md-12">
                                    <label class="fw-bold">{{ __('Account Start Date') }} <span class="text-danger">*</span></label>
                                    @php
                                        $accountStartDate = $generalSettings['business_or_shop__account_start_date'] ?? null;
                                    @endphp
                                    <input type="text" name="account_start_date" class="form-control" id="branch_account_start_date" value="{{ $accountStartDate }}" data-next="branch_financial_year_start_month" autocomplete="off">
                                    <span class="error error_account_start_date"></span>
                                </div>
                            </div>

                            <div class="form-group row mt-1 {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? 'd-hide' : '' }}" id="financial_year_start_month_field">
                                <div class="col-md-12">
                                    <label class="fw-bold">{{ __('Financial Year Start Month') }}</label>
                                    <div class="input-group">
                                        <select name="financial_year_start_month" id="branch_financial_year_start_month" class="form-control select2" data-next="branch_currency_id">
                                            @php
                                                $months = \App\Enums\Months::cases();
                                                $financialYearStartMonth = $branchSettings['business_or_shop__financial_year_start_month'] ?? null;
                                            @endphp
                                            @foreach ($months as $month)
                                                <option {{ $financialYearStartMonth == $month ? 'SELECTED' : '' }} value="{{ $month->value }}">{{ $month->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <span class="error error_financial_year_start_month"></span>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Currency') }}</label>
                                <div class="input-group">
                                    <select required name="currency_id" class="form-control select2" id="branch_currency_id" data-next="branch_save_changes">
                                        @foreach ($currencies as $currency)
                                            <option data-currency_symbol="{{ $currency->symbol }}" {{ $branchSettings['business_or_shop__currency_id'] == $currency->id ? 'SELECTED' : '' }} value="{{ $currency->id }}">
                                                {{ $currency->country . ' - ' . $currency->currency . '(' . $currency->code . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="currency_symbol" id="branch_currency_symbol" value="{{ $branchSettings['business_or_shop__currency_symbol'] }}">
                                </div>
                                <span class="error error_currency_id"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-end mt-2">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button branch_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        <button type="button" id="branch_save_changes" class="btn btn-sm btn-success branch_submit_button">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#branch_timezone').select2();
    $('#branch_currency_id').select2();
    $('#branch_financial_year_start_month').select2();

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.branch_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.branch_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_branch_form').on('submit', function(e) {
        e.preventDefault();
        $('.branch_loading_btn').show();
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

                $('.branch_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $('#branchAddOrEditModal').modal('hide');
                toastr.success(data);
                branchTable.ajax.reload(false, null);
            },
            error: function(err) {

                $('.branch_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error('Please check all form fields.', 'Something Went Wrong');

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        var branchType = $('#branch_type').val();
        if (nextId == 'branch_stock_accounting_method' && branchType == 2) {

            setTimeout(function() {

                $('#branch_currency_id').focus();
            }, 100);

            return;
        }

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
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

            if ($(this).attr('id') == 'branch_type' && $('#branch_type').val() == 2) {

                $('#branch_parent_branch_id').focus();
                return;
            }

            if ($(this).attr('id') == 'branch_parent_branch_id') {

                $('#branch_code').focus();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    // $('#branch_type').on('click', function() {

    //     $('.parent_branches_field').hide();
    //     $('#parent_branch_id').val('');

    //     if ($(this).val() == 2) {

    //         $('.parent_branches_field').show();
    //         $('#branch_parent_branch_id').prop('required', true);
    //         $('.branch_name_field').hide();
    //         $('#branch_name').prop('required', false);
    //     } else {

    //         $('.parent_branches_field').hide();
    //         $('#branch_parent_branch_id').prop('required', false);
    //         $('.branch_name_field').show();
    //         $('#branch_name').prop('required', true);
    //     }
    // });

    $(document).on('change', '#branch_currency_id', function(e) {
        var currencySymbol = $(this).find('option:selected').data('currency_symbol');
        $('#branch_currency_symbol').val(currencySymbol);
    });

    var dateFormat = "{{ $generalSettings['business_or_shop__date_format'] }}";
    var _expectedDateFormat = '';
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('branch_account_start_date'),
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
