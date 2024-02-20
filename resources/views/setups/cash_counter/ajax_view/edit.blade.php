<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Cash Counter') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_cash_counter_form" action="{{ route('cash.counters.update', $cashCounter->id) }}" method="POST">
                <div class="form-group row">
                    <div class="col-md-12">
                        <label><b>{{ __('Name') }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="counter_name" class="form-control" id="cash_counter_name" value="{{ $cashCounter->counter_name }}" data-next="short_name" placeholder="{{ __('Counter Name') }}" />
                        <span class="error error_cash_counter_counter_name"></span>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12">
                        <label for=""><b>{{ __('Short Name') }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="short_name" class="form-control" id="short_name" value="{{ $cashCounter->short_name }}" data-next="cash_counter_save_changes" placeholder="{{ __('Cash Counter Short Name') }}">
                        <span class="error error_cash_counter_short_name"></span>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button cash_counter_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="cash_counter_save_changes" class="btn btn-sm btn-success cash_counter_submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.cash_counter_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.cash_counter_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_cash_counter_form').on('submit', function(e) {
        e.preventDefault();

        $('.cash_counter_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.cash_counter_loading_btn').hide();
                $('#cashCounterAddOrEditModal').modal('hide');
                $('#cashCounterAddOrEditModal').empty();
                toastr.success("{{ __('Cash counter is created successfully') }}");
                cashCounterTable.ajax.reload();
            },
            error: function(err) {

                $('.cash_counter_loading_btn').hide();
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

                    $('.error_cash_counter_' + key + '').html(error[0]);
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
</script>
