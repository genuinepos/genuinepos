<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Add Account Group") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="add_account_group_form" action="{{ route('account.groups.store') }}">
                @csrf
                <div class="form-group">
                    <label><strong>{{ __("Name") }} </strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="account_group_name"
                        data-next="parent_group_id" placeholder="{{ __("Name") }}" autocomplete="off" autofocus />
                    <span class="error error_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><strong>{{ __("Under Group") }} <span class="text-danger">*</span></strong></label>
                    <select required name="parent_group_id" class="form-control select2" id="parent_group_id"
                        data-next="is_default_tax_calculator">
                        <option data-is_allowed_bank_details="0" data-is_default_tax_calculator="0" value="">
                            Select Group</option>
                        @foreach ($formGroups as $group)
                            <option data-is_allowed_bank_details="{{ $group->is_allowed_bank_details }}"
                                data-is_default_tax_calculator="{{ $group->is_default_tax_calculator }}"
                                value="{{ $group->id }}">
                                {{ $group->name }}{{ $group->parentGroup ? '-(' . $group->parentGroup->name . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error error_account_type"></span>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-12">
                        <label><strong>{{ __("Is Default Tax Calculator") }} </strong></label>
                        <select name="is_default_tax_calculator" class="form-control" id="is_default_tax_calculator"
                            data-next="is_allowed_bank_details">
                            <option value="0">{{ __("No") }}</option>
                            <option value="1">{{ __("Yes") }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-12">
                        <label><strong>{{ __("Is Allowed Bank Details") }} </strong></label>
                        <select name="is_allowed_bank_details" class="form-control" id="is_allowed_bank_details"
                            data-next="account_group_save">
                            <option value="0">{{ __("No") }}</option>
                            <option value="1">{{ __("Yes") }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button group_loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                            <button type="submit" id="account_group_save" class="btn btn-sm btn-success account_group_submit_button">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#parent_group_id').select2();

    $(document).on('change', '#parent_group_id', function(e) {

        var is_allowed_bank_details = $(this).find('option:selected').data('is_allowed_bank_details');
        $('#is_allowed_bank_details').val(is_allowed_bank_details);
        var is_default_tax_calculator = $(this).find('option:selected').data('is_default_tax_calculator');
        $('#is_default_tax_calculator').val(is_default_tax_calculator);
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.account_group_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.account_group_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_account_group_form').on('submit', function(e) {
        e.preventDefault();

        $('.group_loading_button').show();
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
                $('.group_loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                } else {

                    var account_group_id = $('#account_group_id').val();

                    if (account_group_id != undefined) {

                        var groupName = data.name + (data.parent_group != null ? ' (' + data
                            .parent_group.name + ')' : '');
                        $('#account_group_id').append(
                            '<option data-is_allowed_bank_details="' + data
                            .is_allowed_bank_details + '" data-is_bank_or_cash_ac="' + data
                            .is_bank_or_cash_ac + '" data-is_fixed_tax_calculator="' + data
                            .is_fixed_tax_calculator + '" data-is_default_tax_calculator="' +
                            data.is_default_tax_calculator + '" data-main_group_number="' + data
                            .main_group_number + '" data-sub_group_number="' + data
                            .sub_group_number + '" data-sub_sub_group_number="' + data
                            .sub_sub_group_number + '" value="' + data.id + '">' + groupName +
                            '</option>'
                        );

                        $('#account_group_id').val(data.id);
                        $('#account_group_id').change();

                        var nextId = '';
                        var sub_sub_group_number = data.sub_sub_group_number;
                        var is_allowed_bank_details = data.is_allowed_bank_details;

                        if (sub_sub_group_number == 6) {
                            nextId = 'customer_phone_no';
                        } else if (sub_sub_group_number == 10) {
                            nextId = 'supplier_phone_no';
                        } else if (sub_sub_group_number == 8) {
                            nextId = 'tax_percent';
                        } else if (sub_sub_group_number == 1 && is_allowed_bank_details == 1) {
                            nextId = 'account_number';
                        } else {

                            nextId = 'opening_balance';
                        }

                        $('#' + nextId).focus().select();
                    } else {

                        if (typeof getAjaxList != 'undefined') {


                            lastChartListClass = data.id + 'group';
                            getAjaxList();
                        }
                    }

                    $('#accountGroupAddOrEditModal').modal('hide');
                    $('#accountGroupAddOrEditModal').empty();
                    toastr.success("{{ __('Account group is created successfully') }}");
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.group_loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
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

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });
</script>
