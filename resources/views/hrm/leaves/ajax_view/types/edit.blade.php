<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Leave Type') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_leave_type_form" action="{{ route('hrm.leave.type.update', $leaveType->id) }}">
                @csrf
                <div class="form-group">
                    <label class="fw-bold">{{ __("Name") }} <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="leave_type_name" data-next="leave_type_max_leave_count" value="{{ $leaveType->name }}" placeholder="{{ __("Leave Type") }}"/>
                    <span class="error error_leave_type_name"></span>
                </div>

                <div class="form-group">
                    <label class="fw-bold">{{ __('Max leave count') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="max_leave_count" class="form-control" id="leave_type_max_leave_count" data-next="leave_type_leave_count_interval" value="{{ $leaveType->max_leave_count }}" placeholder="{{ __('Max leave count') }}"/>
                    <span class="error error_leave_type_max_leave_count"></span>
                </div>

                <div class="form-group">
                    <label class="fw-bold">{{ __('Leave Count Interval') }}</label>
                    <select name="leave_count_interval" class="form-control" id="leave_type_leave_count_interval" data-next="leave_type_save_changes_btn">
                        <option {{ $leaveType->leave_count_interval == 0 ? 'SELECTED' : '' }} value="0">{{ __("None") }}</option>
                        <option {{ $leaveType->leave_count_interval == 1 ? 'SELECTED' : '' }} value="1">{{ __("Current Month") }}</option>
                        <option {{ $leaveType->leave_count_interval == 2 ? 'SELECTED' : '' }} value="2">{{ __("Current Financial Year") }}</option>
                    </select>
                </div>

                <div class="form-group d-flex justify-content-end mt-3">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button leave_type_loading_btn d-hide">
                            <i class="fas fa-spinner text-primary"></i><span> {{ __("Loading") }}...</span>
                        </button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        <button type="submit" id="leave_type_save_changes_btn" class="btn btn-sm btn-success leave_type_submit_button">{{ __("Save Changes") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.leave_type_submit_button').prop('type', 'button');
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

    var isAllowSubmit = true;
    $(document).on('click', '.leave_type_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_leave_type_form').on('submit', function(e) {
        e.preventDefault();

        $('.leave_type_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.leave_type_loading_btn').hide();
                $('.error').html('');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);
                $('#leaveTypeAddOrEditModal').modal('hide');
                leaveTypesTable.ajax.reload();
            }, error: function(err) {

                $('.leave_type_loading_btn').hide();
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

                    $('.error_leave_type_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
