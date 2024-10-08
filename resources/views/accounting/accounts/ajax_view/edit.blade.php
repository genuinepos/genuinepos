<div class="modal-dialog col-50-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Account') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_account_form" action="{{ route('accounts.update', $account->id) }}">
                @csrf
                <div class="form-group">
                    <label><strong>{{ __('Name') }} </strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="account_name" value="{{ $account->name }}" data-next="account_group_id" placeholder="{{ __('Account Name') }}" autocomplete="off" autofocus />
                    <span class="error error_name"></span>
                </div>

                @if ($account->is_main_pl_account == 0)
                    <div class="form-group mt-1">
                        <label><strong>{{ __('Account Group') }} <span class="text-danger">*</span></strong></label>
                        <div class="input-group flex-nowrap">
                            <select required name="account_group_id" class="form-control select2 form-select" id="account_group_id">
                                <option value="">{{ __('Select Account Group') }}</option>
                                @foreach ($groups as $group)
                                    @if (($group->sub_sub_group_number == 1 || $group->sub_sub_group_number == 11) && auth()->user()->can('accounts_bank_account_create'))
                                        <option {{ $account->account_group_id == $group->id ? 'SELECTED' : '' }} value="{{ $group->id }}" data-is_allowed_bank_details="{{ $group->is_allowed_bank_details }}" data-is_bank_or_cash_ac="{{ $group->is_bank_or_cash_ac }}" data-is_fixed_tax_calculator="{{ $group->is_fixed_tax_calculator }}" data-is_default_tax_calculator="{{ $group->is_default_tax_calculator }}" data-main_group_number="{{ $group->main_group_number }}" data-sub_group_number="{{ $group->sub_group_number }}" data-sub_sub_group_number="{{ $group->sub_sub_group_number }}">
                                            {{ $group->name }}{{ $group->parentGroup ? '-(' . $group->parentGroup->name . ')' : '' }}
                                        </option>
                                    @else
                                        <option @disabled($group->is_main_group == 1) {{ $account->account_group_id == $group->id ? 'SELECTED' : '' }} value="{{ $group->id }}" data-is_allowed_bank_details="{{ $group->is_allowed_bank_details }}" data-is_bank_or_cash_ac="{{ $group->is_bank_or_cash_ac }}" data-is_fixed_tax_calculator="{{ $group->is_fixed_tax_calculator }}" data-is_default_tax_calculator="{{ $group->is_default_tax_calculator }}" data-main_group_number="{{ $group->main_group_number }}" data-sub_group_number="{{ $group->sub_group_number }}" data-sub_sub_group_number="{{ $group->sub_sub_group_number }}">
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
                @else
                    <div class="form-group mt-1">
                        <label><strong>{{ __('Account Group') }}</strong></label>
                        <input readonly required type="text" class="form-control fw-bold" value="{{ $account?->group?->name }}" />
                        <input type="hidden" name="account_group_id" value="{{ $account->account_group_id }}">
                    </div>
                @endif

                <div class="form-group row mt-1 customer_account_field {{ $account->group->sub_sub_group_number == 6 ? '' : 'd-hide' }}">
                    <div class="col-md-12">
                        <label><strong> {{ __('Phone No.') }} : </strong><span class="text-danger">*</span></label>
                        <input {{ $account->group->sub_sub_group_number == 6 ? 'required' : '' }} type="text" name="customer_phone_no" class="form-control hidden_required" id="customer_phone_no" value="{{ $account->phone }}" data-next="customer_credit_limit" placeholder="{{ __('Phone Number') }}" />
                        <span class="error error_customer_phone_no"></span>
                    </div>

                    <div class="col-md-12">
                        <label><strong> {{ __('Credit Limit') }}</strong></label>
                        <input type="number" name="customer_credit_limit" class="form-control" id="customer_credit_limit" value="{{ $account?->contact?->credit_limit }}" data-next="customer_address" placeholder="{{ __('Credit Limit') }}" />
                    </div>

                    <div class="col-md-12">
                        <label><strong> {{ __('Address') }}</strong></label>
                        <input type="text" name="customer_address" class="form-control" id="customer_address" data-next="opening_balance" value="{{ $account->address }}" placeholder="{{ __('Customer Address') }}" />
                    </div>
                </div>

                <div class="form-group row mt-1 supplier_account_field {{ $account->group->sub_sub_group_number == 10 ? '' : 'd-hide' }}">
                    <div class="col-md-12">
                        <label><strong> {{ __('Phone No.') }} </strong><span class="text-danger">*</span></label>
                        <input {{ $account->group->sub_sub_group_number == 10 ? 'required' : '' }} type="text" name="supplier_phone_no" class="form-control hidden_required" id="supplier_phone_no" value="{{ $account->phone }}" data-next="supplier_address" placeholder="{{ __('Phone number') }}" />
                        <span class="error error_customer_phone_no"></span>
                    </div>

                    <div class="col-md-12">
                        <label><strong> {{ __('Address') }} </strong></label>
                        <input type="text" name="supplier_address" class="form-control" id="supplier_address" value="{{ $account->address }}" data-next="opening_balance" placeholder="{{ __('Supplier Address') }}" />
                    </div>
                </div>

                <div class="form-group row mt-1 duties_and_tax_account_field  {{ $account->group->is_default_tax_calculator == 1 ? '' : 'd-hide' }}">
                    <div class="col-md-12">
                        <label><strong>{{ __('Duties & Tax Calculation Percent') }}</strong> <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="tax_percent" class="form-control" id="tax_percent" value="{{ $account->tax_percent }}" data-next="opening_balance" placeholder="{{ __('Duties & Tax Calculation Percent') }}" />
                        <span class="error error_tax_percent"></span>
                    </div>
                </div>

                <div class="form-group row mt-1 bank_details_field {{ $account->group->is_allowed_bank_details == 1 ? '' : 'd-hide' }}">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>{{ __('Account Number') }}</strong></label>
                                <input type="text" name="account_number" class="form-control" id="account_number" value="{{ $account->account_number }}" data-next="bank_id" placeholder="{{ __('Account Number') }}" />
                            </div>

                            <div class="col-md-6">
                                <label><strong>{{ __('Bank Name') }}</strong></label>
                                <select name="bank_id" class="form-control" id="bank_id" data-next="bank_code">
                                    <option value="">{{ __('Select Bank') }}</option>
                                    @foreach ($banks as $bank)
                                        <option {{ $account->bank_id == $bank->id ? 'SELECTED' : '' }} value="{{ $bank->id }}">
                                            {{ $bank->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>{{ __('Bank Code') }}</strong></label>
                                <input type="text" name="bank_code" class="form-control" id="bank_code" value="{{ $account->bank_code }}" data-next="swift_code" placeholder="{{ __('Bank Code') }}" />
                            </div>

                            <div class="col-md-6">
                                <label><strong>{{ __('Swift Code') }}</strong></label>
                                <input type="text" name="swift_code" class="form-control" id="swift_code" data-next="bank_branch" value="{{ $account->swift_code }}" placeholder="{{ __('Swift Code') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>{{ __('Branch') }} </strong></label>
                                <input type="text" name="bank_branch" class="form-control" id="bank_branch" value="{{ $account->bank_branch }}" data-next="bank_address" placeholder="{{ __('Bank Branch') }}" />
                            </div>

                            <div class="col-md-6">
                                <label><strong>{{ __('Address') }}</strong></label>
                                <input type="text" name="bank_address" class="form-control" id="bank_address" value="{{ $account->bank_address }}" data-next="opening_balance" placeholder="{{ __('Bank Address') }}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <label><strong>{{ __('Opening Balance') }}</strong></label>
                    <div class="input-group">
                        @php
                            $openingBalanceAmount = $account?->accountOpeningBalance ? $account?->accountOpeningBalance?->opening_balance : $account?->opening_balance;
                            $openingBalanceType = $account?->accountOpeningBalance ? $account?->accountOpeningBalance?->opening_balance_type : $account?->opening_balance_type;
                        @endphp
                        <input readonly type="text" name="opening_balance_date" class="form-control w-25 fw-bold" id="opening_balance_date" value="{{ __('On') }} : {{ date('d-M-y', strtotime($generalSettings['business_or_shop__account_start_date'])) }}" tabindex="-1" />
                        <input type="number" step="any" name="opening_balance" class="form-control w-50 fw-bold text-end" id="opening_balance" value="{{ $openingBalanceAmount }}" data-next="opening_balance_type" />
                        <select name="opening_balance_type" class="form-control w-25 text-end" id="opening_balance_type" data-next="remarks">
                            <option value="dr">{{ __('Debit') }}</option>
                            <option {{ $openingBalanceType == 'cr' ? 'SELECTED' : '' }} value="cr">{{ __('Credit') }}</option>
                        </select>
                    </div>
                </div>

                @if (auth()->user()->can('has_access_to_all_area'))

                    <div class="form-group mt-1 {{ ($account->group->sub_sub_group_number == 1 || $account->group->sub_sub_group_number == 11) && $account->group->is_allowed_bank_details == 1 ? '' : 'd-hide' }}" id="access_branches">
                        <label><strong>{{ __('Store Access') }}</strong></label>
                        <input type="hidden" name="branch_count" id="branch_count" value="yes">
                        <select name="branch_ids[]" id="branch_id" class="form-control select2" multiple="multiple">
                            @foreach ($branches as $branch)
                                <option @foreach ($account->bankAccessBranches as $bankAccessBranch)
                                        {{ $bankAccessBranch->branch_id == $branch->id ? 'SELECTED' : '' }} @endforeach value="{{ $branch->id }}">
                                    @php
                                        $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                        $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
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
                    <input type="text" name="remark" class="form-control" id="remarks" value="{{ $account->remark }}" data-next="account_save_changes" placeholder="{{ __('Remarks') }}" />
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button account_loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="account_save_changes" class="btn btn-sm btn-success account_submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#account_group_id').select2();
    $('#branch_id').select2();

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.account_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.account_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    $('#edit_account_form').on('submit', function(e) {
        e.preventDefault();

        $('.account_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.account_loading_button').hide();
                toastr.success(data);
                $('#accountAddOrEditModal').modal('hide');
                $('#accountAddOrEditModal').empty();
                if (typeof lastChartListClass === 'undefined') {

                    if (typeof accounts_table != 'undefined') {

                        accounts_table.ajax.reload(null, false);
                    }

                    if (typeof account_ledger_table != 'undefined') {

                        account_ledger_table.ajax.reload(null, false);
                        var phone = data.data.phone != null ? ' / ' + data.data.phone : '';
                        var accountNumber = data.data.account_number != null ? ' / ' + data.data.account_number : '';
                        $('#ledger_heading').html(data.data.name + phone + accountNumber);
                        getAccountClosingBalance();
                    }
                } else {

                    getAjaxList();
                }
            },
            error: function(err) {

                $('.account_loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
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

        if (is_fixed_tax_calculator == 1 || is_default_tax_calculator == 1) {

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

            var sub_sub_group_number = $('#account_group_id').select2().find(":selected").data("sub_sub_group_number");

            if ($(this).attr('id') == 'opening_balance_type' && (sub_sub_group_number == 1 || sub_sub_group_number == 11)) {

                $('#branch_id').focus();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });
</script>
