<div class="modal-dialog col-50-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __('Edit Quotation Status') }} | ({{ __('Quotation ID :') }} {{ $quotation->quotation_id }})</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <form id="edit_quotation_status_form" action="{{ route('sale.quotations.status.update', $quotation->id) }}" method="post">
                @csrf
                <div class="form-group row">
                    <div class="col-md-12">
                        <label><strong>{{ __('Status') }}</strong></label>
                        <select required name="status" class="form-control" id="quotation_status_status" data-next="quotation_status_save_changes">
                            @php
                                $status = array_slice(\App\Enums\SaleStatus::cases(), 2, 2);
                            @endphp
                            @foreach ($status as $status)
                                <option {{ $quotation->status == $status->value ? 'SELECTED' : '' }} value="{{ $status->value }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button quotation_status_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="button" id="quotation_status_save_changes" class="btn btn-sm btn-success quotation_status_submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.quotation_status_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('click change keypress', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.quotation_status_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_quotation_status_form').on('submit', function(e) {
        e.preventDefault();

        $('.quotation_status_loading_btn').show();
        var url = $(this).attr('action');

        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.quotation_status_loading_btn').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                }

                toastr.success(data);
                $('#editQuotationStatusModal').modal('hide');
                table.ajax.reload();
            },
            error: function(err) {

                $('.quotation_status_loading_btn').hide();
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
            }
        });
    });
</script>
