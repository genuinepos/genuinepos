<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __('Add Shift') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_shift_form" action="{{ route('hrm.shifts.store') }}">
                @csrf
                <div class="form-group row">
                    <div class="col-md-12">
                        <label>{{ __('Shift Name') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="shift_name" data-next="shift_start_date" placeholder="{{ __('Shift Name') }}" />
                        <span class="error error_shift_name"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="form-group col-12">
                        <label>{{ __('Start Time') }} <span class="text-danger">*</span></label>
                        <input required type="time" name="start_time" class="form-control" id="shift_start_date" data-next="shift_end_date" placeholder="{{ __('Start Time') }}" />
                        <span class="error error_shift_start_date"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="form-group col-12">
                        <label>{{ __('End Time') }} <span class="text-danger">*</span></label>
                        <input required type="time" name="end_time" class="form-control" id="shift_end_date" data-next="shift_save_btn" placeholder="{{ __('End Time') }}" />
                        <span class="error error_shift_end_date"></span>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button shift_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="shift_save_btn" class="btn btn-sm btn-success shift_submit_button">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.shift_submit_button').prop('type', 'button');
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
    $(document).on('click', '.shift_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_shift_form').on('submit', function(e) {
        e.preventDefault();

        $('.shift_loading_btn').show();
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
                $('.shift_loading_btn').hide();
                $('.error').html('');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success("{{ __('Shift is added successfully') }}");
                $('#shiftAddOrEditModal').modal('hide');
                var shift_id = $('#shift_id').val();

                if (shift_id != undefined) {

                    $('#shift_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                    $('#shift_id').val(data.id);

                    var nextId = $('#shift_id').data('next');
                    $('#' + nextId).focus().select();
                } else {

                    shiftsTable.ajax.reload();
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.shift_loading_btn').hide();
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

                    $('.error_shift_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
