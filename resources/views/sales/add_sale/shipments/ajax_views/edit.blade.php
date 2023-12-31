<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __('Edit Shipment Details') }} | ({{ __('Invoice ID :') }} {{ $sale->invoice_id }})</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body" id="edit_shipment_modal_body">
            <form id="edit_shipment_form" action="{{ route('sale.shipments.update', $sale->id) }}" method="post">
                @csrf
                <div class="form-group row">
                    <div class="col-md-12">
                        <label><strong>{{ __('Shipment Address') }}</strong> <span class="text-danger">*</span></label>
                        <input required type="text" name="shipment_address" class="form-control" id="shipment_shipment_address" value="{{ $sale->shipment_address }}" data-next="shipment_shipment_details" placeholder="{{ __('Shipment Address') }}">
                        <span class="error error_shipment_address"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label><strong>{{ __('Shipment Details') }}</strong></label>
                        <input type="text" name="shipment_details" class="form-control" id="shipment_shipment_details" value="{{ $sale->shipment_details }}" data-next="shipment_shipment_status" placeholder="{{ __('Shipment Details') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label><strong>{{ __('Shipment Status') }}</strong> <span class="text-danger">*</span></label>
                        <select required name="shipment_status" class="form-control" id="shipment_shipment_status" data-next="shipment_delivered_to">
                            <option value="">{{ __('Select Shipment Status') }}</option>
                            @foreach (\App\Enums\ShipmentStatus::cases() as $shipmentStatus)
                                <option {{ $sale->shipment_status == $shipmentStatus->value ? 'SELECTED' : '' }} value="{{ $shipmentStatus->value }}">{{ $shipmentStatus->name }}</option>
                            @endforeach
                        </select>
                        <span class="error error_shipment_shipment_status"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label><strong>{{ __('Delivered To') }}</strong></label>
                        <input type="text" name="delivered_to" id="shipment_delivered_to" class="form-control" value="{{ $sale->delivered_to }}" data-next="shipment_details_save_changes" placeholder="{{ __('Delivered To') }}">
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button shipment_details_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="button" id="shipment_details_save_changes" class="btn btn-sm btn-success shipment_details_submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.shipment_details_submit_button').prop('type', 'button');
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
    $(document).on('click', '.shipment_details_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_shipment_form').on('submit', function(e) {
        e.preventDefault();

        $('.shipment_details_loading_btn').show();
        var url = $(this).attr('action');

        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.shipment_details_loading_btn').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);
                $('#editShipmentDetailsModal').modal('hide');

                if ($('#sales-order-table').html() != undefined) {

                    $('#sales-order-table').DataTable().ajax.reload();
                }

                $('#sales-table').DataTable().ajax.reload();
            },
            error: function(err) {

                $('.shipment_details_loading_btn').hide();
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

                    $('.error_shipment_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
