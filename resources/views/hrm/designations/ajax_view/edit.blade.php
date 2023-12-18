<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __('Edit Designation') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_designation_form" action="{{ route('hrm.designations.update', $designation->id) }}">
                @csrf
                <div class="form-group">
                    <label class="fw-bold">{{ __("Name") }} <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" id="designation_name" data-next="designation_description" value="{{ $designation->name }}" placeholder="{{ __("Designation name") }}" />
                    <span class="error error_designation_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label class="fw-bold">{{ __('Designation') }}</label>
                    <input name="description" class="form-control" id="designation_description" data-next="designation_save_changes_btn" placeholder="{{ __('Designation Details') }}">
                </div>

                <div class="form-group d-flex justify-content-end mt-3">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button designation_loading_btn d-hide">
                            <i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span>
                        </button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        <button type="submit" id="designation_save_changes_btn" class="btn btn-sm btn-success designation_submit_button">{{ __("Save Changes") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.designation_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.designation_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_designation_form').on('submit', function(e) {
        e.preventDefault();

        $('.designation_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.designation_loading_btn').hide();
                $('.error').html('');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);
                $('#designationAddOrEditModal').modal('hide');
                designationsTable.ajax.reload();
            }, error: function(err) {

                $('.designation_loading_btn').hide();
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

                    $('.error_designation_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
