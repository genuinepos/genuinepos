<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Warranty/Guaranty') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_warranty_form" action="{{ route('warranties.update', $warranty->id) }}">
                @csrf
                <div class="form-group">
                    <label><b>{{ __('Name') }}</b></label> <span class="text-danger">*</span>
                    <input required type="text" name="name" class="form-control" id="warranty_name" data-next="warranty_type" placeholder="{{ __('Warranty Name') }}" value="{{ $warranty->name }}" />
                    <span class="error error_warranty_name"></span>
                </div>

                <div class="row mt-1">
                    <div class="col-md-4">
                        <label><b>{{ __('Type') }}</b></label>
                        <select required name="type" class="form-control" id="warranty_type" data-next="warranty_duration">
                            <option {{ $warranty->type == 1 ? 'SELECTED' : '' }} value="1">{{ __('Warranty') }}</option>
                            <option {{ $warranty->type == 2 ? 'SELECTED' : '' }} value="2">{{ __('Guaranty') }}</option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label><b>{{ __('Duration') }}</b></label> <span class="text-danger">*</span>
                        <div class="input-group">
                            <input required type="number" name="duration" class="form-control" id="warranty_duration" data-next="warranty_duration_type" value="{{ $warranty->duration }}" placeholder="{{ __("Warranty Duration") }}">
                            <select required name="duration_type" class="form-control form-select" id="warranty_duration_type" data-next="warranty_description">
                                <option {{ $warranty->duration_type == 'Months' ? 'SELECTED' : '' }} value="Months">{{ __('Months') }}</option>
                                <option {{ $warranty->duration_type == 'Days' ? 'SELECTED' : '' }} value="Days">{{ __('Days') }}</option>
                                <option {{ $warranty->duration_type == 'Years' ? 'SELECTED' : '' }} value="Years">{{ __('Years') }}</option>
                            </select>
                        </div>

                        <span class="error error_warranty_duration"></span>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label><b>{{ __('Description') }}</b></label>
                    <input name="description" id="warranty_description" class="form-control" value="{{ $warranty->description }}" data-next="warranty_save_changes" placeholder="{{ __('Description') }}">
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button warranty_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="warranty_save_changes" class="btn btn-sm btn-success warranty_submit_button">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.warranty_submit_button').prop('type', 'button');
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
    $(document).on('click', '.warranty_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    $('#edit_warranty_form').on('submit', function(e) {
        e.preventDefault();

        $('.warranty_loading_btn').show();
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
                $('.warranty_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                } else {

                    toastr.success(data);
                    $('#warrantyAddOrEditModal').modal('hide');
                    warrantiesTable.ajax.reload();
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.warranty_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_warranty_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
