<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Account Group') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="edit_account_group_form" action="{{ route('account.groups.update', $group->id) }}">
                @csrf
                <div class="form-group">
                    <label><strong>{{ __('Name') }} </strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="account_group_name" value="{{ $group->name }}" data-next="{{ $group->is_parent_sub_group == 1 || $group->is_parent_sub_sub_group ? 'is_default_tax_calculator' : 'parent_group_id' }}" placeholder="@lang('menu.name')" autocomplete="off" autofocus />
                    <span class="error error_name"></span>
                </div>

                @if ($group->is_parent_sub_group == 1 || $group->is_parent_sub_sub_group)
                    <div class="form-group mt-1">
                        <label><b>{{ __('Under The Primary Group Of') }} </b> <strong>{{ $group->parentGroup ? $group->parentGroup->name : '' }}</strong> </label>
                        <input type="hidden" name="parent_group_id" id="parent_group_id" value="{{ $group->parent_group_id }}">
                    </div>
                @else
                    <div class="form-group mt-1">
                        <label><strong>{{ __('Under Group') }} <span class="text-danger">*</span></strong></label>
                        <select required name="parent_group_id" class="form-control select2" id="parent_group_id" data-next="is_default_tax_calculator">
                            <option value="">{{ __('Select Group') }}</option>
                            @foreach ($formGroups as $formGroup)
                                <option data-is_allowed_bank_details="{{ $formGroup->is_allowed_bank_details }}" data-is_default_tax_calculator="{{ $formGroup->is_default_tax_calculator }}" {{ $formGroup->id == $group->parent_group_id ? 'SELECTED' : '' }} value="{{ $formGroup->id }}">
                                    {{ $formGroup->name }}{{ $formGroup->parentGroup ? ' - (' . $formGroup->parentGroup->name . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <span class="error error_account_type"></span>
                    </div>
                @endif

                <div class="form-group row mt-1">
                    <div class="col-md-12">
                        <label><strong> {{ __('Is Default Tax Calculator') }} </strong></label>
                        <select name="is_default_tax_calculator" class="form-control" id="is_default_tax_calculator" data-next="is_allowed_bank_details">
                            <option value="0">{{ __('No') }}</option>
                            <option {{ $group->is_default_tax_calculator == 1 ? 'SELECTED' : '' }} value="1">{{ __('Yes') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-12">
                        <label><strong>{{ __('Is Allowed Bank Details') }}</strong></label>
                        <select name="is_allowed_bank_details" class="form-control" id="is_allowed_bank_details" data-next="account_group_save_changes">
                            <option value="0">{{ __('No') }}</option>
                            <option {{ $group->is_allowed_bank_details == 1 ? 'SELECTED' : '' }} value="1">{{ __('Yes') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button group_loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                            <button type="submit" id="account_group_save_changes" class="btn btn-sm btn-success account_group_submit_button">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.select2').select2();

    $(document).on('change', '#parent_group_id', function(e) {

        var e_is_allowed_bank_details = $(this).find('option:selected').data('is_allowed_bank_details');
        $('#e_is_allowed_bank_details').val(e_is_allowed_bank_details);
        var e_is_default_tax_calculator = $(this).find('option:selected').data('is_default_tax_calculator');
        $('#e_is_default_tax_calculator').val(e_is_default_tax_calculator);
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.account_group_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.account_group_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    $('#edit_account_group_form').on('submit', function(e) {
        e.preventDefault();

        $('.group_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.group_loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                } else {

                    toastr.success(data);
                    getAjaxList();
                    $('#accountGroupAddOrEditModal').modal('hide');
                    $('#accountGroupAddOrEditModal').empty();
                }
            },
            error: function(err) {

                $('.group_loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
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
