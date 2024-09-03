<div class="modal-dialog col-50-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Add Account") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_account_form" action="{{ route('accounts.store') }}">
                @csrf
                <div class="form-group">
                    <label><strong>{{ __('Name') }}</strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="account_name" data-next="account_group_id" placeholder="{{ __("Account Name") }}" autocomplete="off" />
                    <span class="error error_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><strong>{{ __('Account Group') }} <span class="text-danger">*</span></strong></label>
                    <div class="input-group flex-nowrap">
                        <select required name="account_group_id" class="form-control select2" id="account_group_id">
                            <option value="">{{ __("Select Account Group") }}</option>
                            @foreach ($groups as $group)
                                @if (
                                    ($group->sub_sub_group_number == 1 || $group->sub_sub_group_number == 11) &&
                                    (auth()->user()->can('account_bank_account_create'))
                                )

                                    <option value="{{ $group->id }}" data-is_allowed_bank_details="{{ $group->is_allowed_bank_details }}" data-is_bank_or_cash_ac="{{ $group->is_bank_or_cash_ac }}" data-is_fixed_tax_calculator="{{ $group->is_fixed_tax_calculator }}" data-is_default_tax_calculator="{{ $group->is_default_tax_calculator }}" data-main_group_number="{{ $group->main_group_number }}" data-sub_group_number="{{ $group->sub_group_number }}" data-sub_sub_group_number="{{ $group->sub_sub_group_number }}">
                                        {{ $group->name }}{{ $group->parentGroup ? '-(' . $group->parentGroup->name . ')' : '' }}
                                    </option>
                                @else
                                    <option @disabled($group->is_main_group == 1) value="{{ $group->id }}" data-is_allowed_bank_details="{{ $group->is_allowed_bank_details }}" data-is_bank_or_cash_ac="{{ $group->is_bank_or_cash_ac }}" data-is_fixed_tax_calculator="{{ $group->is_fixed_tax_calculator }}" data-is_default_tax_calculator="{{ $group->is_default_tax_calculator }}" data-main_group_number="{{ $group->main_group_number }}" data-sub_group_number="{{ $group->sub_group_number }}" data-sub_sub_group_number="{{ $group->sub_sub_group_number }}">
                                        {{ $group->name }}{{ $group->parentGroup ? '-(' . $group->parentGroup->name . ')' : '' }}
                                    </option>
                                @endif
                            @endforeach
                        </select>

                        <span class="error error_account_group_id"></span>

                        <div style="display: inline-block;" class="style-btn">
                            <div class="input-group-prepend">
                                <span href="{{ route('account.groups.create') }}" class="input-group-text add_button mr-1" id="addAccountGroupBtn" data-group_id=""><i class="fas fa-plus-square text-dark"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-1 customer_account_field d-hide">
                    <div class="col-md-12">
                        <label><strong>{{ __('Phone No') }}</strong><span class="text-danger">*</span></label>
                        <input required type="text" name="customer_phone_no" class="form-control hidden_required" id="customer_phone_no" data-next="customer_credit_limit" placeholder="{{ __("Phone numbe") }}r" />
                        <span class="error error_customer_phone_no"></span>
                    </div>

                    <div class="col-md-12">
                        <label><strong>{{ __('Credit Limit') }}</strong></label>
                        <input type="number" name="customer_credit_limit" class="form-control" id="customer_credit_limit" data-next="customer_address" placeholder="{{ __("Credit Limit") }}" />
                        <span class="error error_customer_credit_limit"></span>
                    </div>

                    <div class="col-md-12">
                        <label><strong>{{ __('Address') }}</strong></label>
                        <input type="text" name="customer_address" class="form-control" id="customer_address" placeholder="{{ __("Address") }}" />
                    </div>
                </div>

                <div class="form-group row mt-1 supplier_account_field d-hide">
                    <div class="col-md-12">
                        <label><strong>{{ __('Phone No') }}</strong><span class="text-danger">*</span></label>
                        <input required type="text" name="supplier_phone_no" class="form-control hidden_required" id="supplier_phone_no" data-next="supplier_address" placeholder="{{ __("Phone number") }}" />
                        <span class="error error_supplier_phone_no"></span>
                    </div>

                    <div class="col-md-12">
                        <label><strong>{{ __('Address') }}</strong></label>
                        <input type="text" name="supplier_address" class="form-control" id="supplier_address" data-next="opening_balance" placeholder="{{ __("Address") }}" />
                    </div>
                </div>

                <div class="form-group row mt-1 duties_and_tax_account_field d-hide">
                    <div class="col-md-12">
                        <label><strong>{{ __('Duties And Tax Calculation Percent') }} </strong><span class="text-danger">*</span></label>
                        <input type="number" step="any" name="tax_percent" class="form-control" id="tax_percent" data-next="opening_balance" placeholder="{{ __("Duties & Tax Calculation Percent") }}" />
                        <span class="error error_tax_percent"></span>
                    </div>
                </div>

                <div class="form-group row mt-1 bank_details_field d-hide">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>{{ __('Account Number') }}</strong></label>
                                <input type="text" name="account_number" class="form-control" id="account_number" data-next="bank_id" placeholder="{{ __('Account Number') }}" />
                            </div>

                            <div class="col-md-6">
                                <label><strong>{{ __('Bank Name') }}</strong></label>
                                <select name="bank_id" class="form-control" id="bank_id" data-next="bank_code">
                                    <option value="">{{ __('Select Bank') }}</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>{{ __('Bank Code') }}</strong></label>
                                <input type="text" name="bank_code" class="form-control" id="bank_code" data-next="swift_code" placeholder="{{ __('Bank Code') }}" />
                            </div>

                            <div class="col-md-6">
                                <label><strong>{{ __('Swift Code') }}</strong></label>
                                <input type="text" name="swift_code" class="form-control" id="swift_code" data-next="bank_branch" placeholder="{{ __('Swift Code') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>{{ __('Branch') }}</strong></label>
                                <input type="text" name="bank_branch" class="form-control" id="bank_branch" data-next="bank_address" placeholder="{{ __('Branch') }}" />
                            </div>

                            <div class="col-md-6">
                                <label><strong>{{ __('Address') }}</strong></label>
                                <input type="text" name="bank_address" class="form-control" id="bank_address" data-next="opening_balance" placeholder="{{ __('Address') }}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <label><strong>{{ __('Opening Balance') }}</strong></label>
                    <div class="input-group">
                        <input readonly type="text" name="opening_balance_date" class="form-control w-25 fw-bold" id="opening_balance_date" value="{{ __('On') }} : {{ date('d-M-y', strtotime($generalSettings['business_or_shop__account_start_date'])) }}" tabindex="-1" />
                        <input type="number" step="any" name="opening_balance" class="form-control w-50 fw-bold text-end" id="opening_balance" data-next="opening_balance_type" value="0.00" placeholder="0.00" />
                        <select name="opening_balance_type" class="form-control w-25" id="opening_balance_type" data-next="remarks">
                            <option value="dr">{{ __('Debit') }}</option>
                            <option value="cr">{{ __('Credit') }}</option>
                        </select>
                    </div>
                </div>

                {{-- @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) --}}
                @if (auth()->user()->can('has_access_to_all_area') == 1)
                    <div class="form-group mt-1 d-hide" id="access_branches">
                        <label><strong>{{ __('Store Access') }}</strong></label>
                        <input type="hidden" name="branch_count" id="branch_count" value="yes">
                        <select name="branch_ids[]" id="branch_id" class="form-control select2" multiple="multiple">
                            @foreach ($branches as $branch)
                                <option {{ auth()->user()->branch_id == $branch->id ? 'SELECTED' : '' }} value="{{ $branch->id }}">
                                    @php
                                        $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                        $areaName = $branch->area_name ? '('.$branch->area_name . ')' : '';
                                        $branchCode = '-' . $branch->branch_code;
                                    @endphp
                                    {{ $branchName . $areaName . $branchCode }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-group mt-1">
                    <label><strong>{{ __('Remarks') }}</strong></label>
                    <input type="text" name="remark" class="form-control" id="remarks" data-next="account_save" placeholder="{{ __('Remarks') }}" />
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button account_loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="submit" id="account_save" class="btn btn-sm btn-success account_submit_button">{{ __("Save") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('#account_group_id').select2();
    $('#branch_id').select2();

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.account_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.account_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_account_form').on('submit', function(e) {
        e.preventDefault();

        $('.account_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        isAjaxIn = false;
        isAllowSubmit = false;

        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.account_loading_button').hide();
                $('#accountAddOrEditModal').modal('hide');
                $('#accountAddOrEditModal').empty();
                toastr.success('Account is created successfully');

                if (typeof lastChartListClass === 'undefined') {

                    accounts_table.ajax.reload();
                } else {

                    lastChartListClass = data.id + 'account';
                    getAjaxList();
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.account_loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    $('#account_group_id').on('change', function() {

        $('.hidden_required').prop('required', false);
        $('.duties_and_tax_account_field').hide();
        $('.bank_details_field').hide();
        $('.customer_account_field').hide();
        $('.supplier_account_field').hide();
        $('#access_branches').hide();
        var is_allowed_bank_details = $(this).find('option:selected').data('is_allowed_bank_details');
        var is_bank_or_cash_ac = $(this).find('option:selected').data('is_bank_or_cash_ac');
        var is_fixed_tax_calculator = $(this).find('option:selected').data('is_fixed_tax_calculator');
        var is_default_tax_calculator = $(this).find('option:selected').data('is_default_tax_calculator');
        var sub_sub_group_number = $(this).find('option:selected').data('sub_sub_group_number');

        if (sub_sub_group_number == 6) {

            $('#customer_phone_no').prop('required', true);
            $('.customer_account_field').show();
        }

        if (sub_sub_group_number == 10) {

            $('#supplier_phone_no').prop('required', true);
            $('.supplier_account_field').show();
        }

        if (is_allowed_bank_details == 1) {

            $('.bank_details_field').show();
        }

        if (is_default_tax_calculator == 1) {

            $('.duties_and_tax_account_field').show();
        }

        if (sub_sub_group_number == 1 || sub_sub_group_number == 11) {

            $('#access_branches').show();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $('select').on('select2:close', function(e) {

        var nextId = '';
        var sub_sub_group_number = $(this).select2().find(":selected").data("sub_sub_group_number");
        var is_allowed_bank_details = $(this).select2().find(":selected").data("is_allowed_bank_details");

        if (sub_sub_group_number == 6) {

            nextId = 'customer_phone_no';
        } else if (sub_sub_group_number == 10) {

            nextId = 'supplier_phone_no';
        } else if (sub_sub_group_number == 8) {

            nextId = 'tax_percent';
        } else if ((sub_sub_group_number == 1 || sub_sub_group_number == 11) && is_allowed_bank_details == 1) {

            nextId = 'account_number';
        } else {

            nextId = 'opening_balance';
        }

        if ($(this).attr('id') == 'branch_id') {
            $(this).focus();
            return;
        }

        setTimeout(function() {

            $('#' + nextId).focus().select();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var value = $(this).val();

        var nextId = $(this).data('next');

        if (e.which == 0) {

            if ($(this).attr('id') == 'customer_type' && $('#customer_type').val() == 1) {

                $('#customer_address').focus();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });
</script>
