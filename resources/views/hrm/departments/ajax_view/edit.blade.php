<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __('Edit Department') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <form id="edit_department_form" action="{{ route('hrm.departments.update', $department->id) }}">
                @csrf
                <div class="form-group">
                    <label class="fw-bold">{{ __('Name') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="department_name" data-next="department_description" value="{{ $department->name }}" placeholder="{{ __('Department Name') }}" />
                    <span class="error error_department_name"></span>
                </div>

                <div class="form-group mt-1">
                    <div class="form-group">
                        <label class="fw-bold">{{ __('Description') }}</label>
                        <input name="description" class="form-control" id="department_description" data-next="department_save_changes_btn" value="{{ $department->description }}" placeholder="{{ __('Description') }}">
                    </div>
                </div>

                <div class="form-group d-flex justify-content-end mt-3">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button department_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        <button type="submit" id="department_save_changes_btn" class="btn btn-sm btn-success department_submit_btn">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.department_submit_button').prop('type', 'button');
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
    $(document).on('click', '.department_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_department_form').on('submit', function(e) {
        e.preventDefault();

        $('.department_loading_btn').show();
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

                $('.department_loading_btn').hide();
                $('.error').html('');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success("{{ __('Department added successfully') }}");
                $('#departmentAddOrEditModal').modal('hide');
                departmentsTable.ajax.reload();
            },
            error: function(err) {

                $('.department_loading_btn').hide();
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

                    $('.error_department_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
