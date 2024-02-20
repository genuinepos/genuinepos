<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Allowance/Deduction') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_allowance_deduction_form" action="{{ route('hrm.allowances.deductions.update', $allowance->id) }}">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('Name or Title') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="name" data-next="type" value="{{ $allowance->name }}" placeholder="{{ __('Name or Title') }}" />
                        <span class="error error_name"></span>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('Type') }}</label>
                        <select class="form-control" name="type" id="type" data-next="amount_type">
                            <option value="1">{{ __('Allowance') }}</option>
                            <option {{ $allowance->type == 2 ? 'SELECTED' : '' }} value="2">{{ __('Deduction') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-6">
                        <label>{{ __('Amount Type') }}</label>
                        <select class="form-control" name="amount_type" id="amount_type" data-next="amount">
                            <option value="1">{{ __('Fixed') }} (0.0)</option>
                            <option {{ $allowance->amount_type == 2 ? 'SELECTED' : '' }} value="2">{{ __('Percentage') }} (%)</option>
                        </select>
                    </div>

                    <div class="col-6">
                        <label class="fw-bold">{{ __('Amount') }} <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="amount" class="form-control" id="amount" data-next="allowance_deduction_save_changes_btn" value="{{ $allowance->amount }}" placeholder="{{ __('Amount') }}" />
                        <span class="error error_amount"></span>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button allowance_deduction_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="submit" id="allowance_deduction_save_changes_btn" class="btn btn-sm btn-success allowance_deduction_submit_button">{{ __("Save Changes") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.allowance_deduction_submit_button').prop('type', 'button');
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
    $(document).on('click', '.allowance_deduction_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_allowance_deduction_form').on('submit', function(e) {
        e.preventDefault();

        $('.allowance_deduction_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.allowance_deduction_loading_btn').hide();
                $('.error').html('');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                $('#allowanceAndDeductionAddOrEditModal').modal('hide');
                toastr.success(data);
                allowancesDeductionsTable.ajax.reload();
            },
            error: function(err) {

                $('.allowance_deduction_loading_btn').hide();
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

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
