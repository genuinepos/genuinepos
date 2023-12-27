<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __('Add Leave') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_leave_form" action="{{ route('hrm.leaves.store') }}">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('Employee') }} <span class="text-danger">*</span></label>
                        <select required class="form-control" name="user_id" id="leave_user_id" data-next="leave_type_id">
                            <option value="">{{ __('Select Employee') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '(' . $user->emp_id . ')' }}</option>
                            @endforeach
                        </select>
                        <span class="error error_leave_user_id"></span>
                    </div>

                    <div class="form-group col-6">
                        <label class="fw-bold">{{ __('Leave Type') }} <span class="text-danger">*</span></label>
                        <select required class="form-control" name="leave_type_id" id="leave_type_id" data-next="leave_start_date">
                            <option value="">{{ __('Select Leave Type') }}</option>
                            @foreach ($leaveTypes as $lt)
                                <option value="{{ $lt->id }}">{{ $lt->name }}</option>
                            @endforeach
                        </select>
                        <span class="error error_leave_type_id"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="form-group col-6">
                        <label class="fw-bold">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="start_date" class="form-control" id="leave_start_date" data-next="leave_end_date" placeholder="{{ __('Start Date') }}" autocomplete="off">
                    </div>

                    <div class="form-group col-6">
                        <label class="fw-bold">{{ __('End Date') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="end_date" class="form-control" id="leave_end_date" data-next="leave_reason" placeholder="{{ __('End Date') }}" autocomplete="off">
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="form-group col-12">
                        <label>{{ __('Reason') }}</label>
                        <input type="text" name="reason" class="form-control" id="leave_reason" data-next="leave_save_btn" placeholder="{{ __('Reason') }}">
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button leave_loading_btn d-hide">
                                <i class="fas fa-spinner"></i><span>{{ __('Loading') }}...</span>
                            </button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="leave_save_btn" class="btn btn-sm btn-success leave_submit_button">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#leave_user_id').select2();
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.leave_submit_button').prop('type', 'button');
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

    var isAllowSubmit = true;
    $(document).on('click', '.leave_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_leave_form').on('submit', function(e) {
        e.preventDefault();

        $('.leave_loading_btn').show();
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
                $('.leave_loading_btn').hide();
                $('.error').html('');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);
                $('#leaveAddOrEditModal').modal('hide');
                leavesTable.ajax.reload();
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.leave_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_leave_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>

<script>
    function litepicker(idName) {

        var dateFormat = "{{ $generalSettings['business__date_format'] }}";
        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
        new Litepicker({
            singleMode: true,
            element: document.getElementById(idName),
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
            format: _expectedDateFormat
        });
    }

    litepicker('leave_start_date');
    litepicker('leave_end_date');
</script>
