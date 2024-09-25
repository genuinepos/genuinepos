<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Warehouse') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_warehouse_form" action="{{ route('warehouses.update', $warehouse->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label><b>{{ __('Warehouse Name') }}</b> <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" id="warehouse_name" data-next="warehouse_code" value="{{ $warehouse->warehouse_name }}" placeholder="{{ __('Warehouse Name') }}" />
                    <span class="error error_warehouse_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __('Warehouse Code') }}</b> <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Warehouse code must be unique." class="fas fa-info-circle tp"></i></label>
                    <input type="text" name="code" class="form-control" id="warehouse_code" data-next="warehouse_phone" value="{{ $warehouse->warehouse_code }}" placeholder="{{ __('Warehouse Code') }}" />
                    <span class="error error_warehouse_code"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __('Phone') }}</b> <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" id="warehouse_phone" data-next="warehouse_address" value="{{ $warehouse->phone }}" placeholder="{{ __('Phone No') }}" />
                    <span class="error error_warehouse_phone"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __('Address') }}</b> </label>
                    <input name="address" class="form-control" id="warehouse_address" data-next="is_global" value="{{ $warehouse->address }}" placeholder="{{ __('Warehouse address') }}">
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __('Is Global Warehouse') }}</b> </label>
                    <select name="is_global" class="form-control" id="is_global" data-next="warehouse_save_changes">
                        <option value="0">{{ __('No') }}</option>
                        <option {{ $warehouse->is_global == 1 ? 'SELECTED' : '' }} value="1">{{ __('Yes') }}</option>
                    </select>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button warehouse_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="warehouse_save_changes" class="btn btn-sm btn-success warehouse_submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.warehouse_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.warehouse_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_warehouse_form').on('submit', function(e) {
        e.preventDefault();

        $('.warehouse_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.warehouse_loading_btn').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $('#warehouseAddOrEditModal').modal('hide');
                $('#warehouseAddOrEditModal').empty();
                toastr.success(data);
                warehouseTable.ajax.reload();
            },
            error: function(err) {

                $('.warehouse_loading_btn').hide();
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

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_warehouse_' + key + '').html(error[0]);
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

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            if ($(this).attr('id') == 'warehouse_phone' && $('is_global').val() == undefined) {

                $('#warehouse_save').focus();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });
</script>
