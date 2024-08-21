<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Change Job Card Status') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="change_status_form" action="{{ route('services.job.cards.change.status', $jobCard->id) }}" method="post">
                @csrf
                <div class="form-group">
                    <label><b>{{ __('Status') }}</b> <span class="text-danger">*</span></label>
                    <select name="status_id" class="form-control" id="job_card_status_id" data-next="status_save">
                        <option value="">{{ __('Select Status') }}</option>
                        @foreach ($status as $status)
                            <option @selected($status->id == $jobCard->status_id) value="{{ $status->id }}" data-icon="fa-solid fa-circle" data-color="{{ $status->color_code }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                    <span class="error error_status_status_id"></span>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button status_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="status_save" class="btn btn-sm btn-success status_submit_button">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // $("#job_card_status_id").select2();
    $(document).ready(function() {

        function formatState(state) {
            if (!state.id) {
                return state.text; // optgroup
            }

            var icon = $(state.element).data('icon');
            var color = $(state.element).data('color');

            var $state = $(
                '<span><i class="' + icon + '" style="color:' + color + '"></i> ' + state.text + '</span>'
            );
            return $state;
        };

        $("#job_card_status_id").select2({
            templateResult: formatState,
            templateSelection: formatState
        });
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.status_submit_button').prop('type', 'button');
    });

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    var isAllowSubmit = true;
    $(document).on('click', '.status_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#change_status_form').on('submit', function(e) {
        e.preventDefault();

        $('.status_loading_btn').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            processData: false,
            cache: false,
            contentType: false,
            success: function(data) {

                $('.status_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                }

                $('#changeStatusModal').modal('hide');
                jobCardsTable.ajax.reload(null, false);
            },
            error: function(err) {

                $('.status_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_status_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
