<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Unit') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_unit_form" action="{{ route('units.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label><b>{{ __('Name') }}</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="unit_name" data-next="unit_short_name" placeholder="{{ __('Unit Name') }}" />
                    <span class="error error_unit_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __('Short Name') }}</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="short_name" class="form-control" id="unit_short_name" data-next="unit_as_a_multiplier_of_other_unit" placeholder="{{ __('Short Name') }}" />
                    <span class="error error_unit_short_name"></span>
                </div>

                @if ($isAllowedMultipleUnit == 1)
                    <div class="form-group mt-1">
                        <label><b>{{ __('As A Multiplier Of Other Unit') }}</b></label>
                        <select name="as_a_multiplier_of_other_unit" class="form-control" id="unit_as_a_multiplier_of_other_unit" data-next="unit_base_unit_multiplier">
                            <option value="0">{{ __('No') }}</option>
                            <option value="1">{{ __('Yes') }}</option>
                        </select>
                    </div>

                    <div class="d-hide" id="multiple_unit_fields">
                        <div class="form-group mt-2 row g-2">
                            <div class="col-md-3">
                                <p class="fw-bold">{{ __('1') }} <span id="base_unit_name">{{ __('Unit') }}</span></p>
                            </div>

                            <div class="col-md-1">
                                <p class="fw-bold"> = </p>
                            </div>

                            <div class="col-md-4">
                                <input type="text" name="base_unit_multiplier" class="form-control fw-bold" id="unit_base_unit_multiplier" data-next="unit_base_unit_id" placeholder="{{ __('Amount Of Base Unit') }}" />
                                <span class="error error_unit_base_unit_multiplier"></span>
                            </div>

                            <div class="col-md-4">
                                <select name="base_unit_id" class="form-control select2" id="unit_base_unit_id" data-next="unit_save">
                                    <option value="">{{ __('Select Base Unit') }}</option>
                                    @foreach ($baseUnits as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->code_name }})
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_unit_base_unit_id"></span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button unit_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="unit_save" class="btn btn-sm btn-success unit_submit_button">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.select2').select2();

    $(document).on('input', '#unit_name', function(event) {

        var val = $(this).val();
        $('#base_unit_name').html(val);
    });

    $(document).on('change', '#unit_as_a_multiplier_of_other_unit', function(event) {

        if ($(this).val() == 1) {

            $('#multiple_unit_fields').show();
        } else {

            $('#multiple_unit_fields').hide();
        }
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.unit_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            if ($('#' + nextId).val() == undefined) {

                $('#unit_save').focus().select();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            if ($(this).attr('id') == 'unit_as_a_multiplier_of_other_unit' && $(
                    '#unit_as_a_multiplier_of_other_unit').val() == 0) {

                $('#unit_save').focus();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.unit_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_unit_form').on('submit', function(e) {
        e.preventDefault();

        $('.unit_loading_btn').show();
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
                $('.unit_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                } else {

                    toastr.success("{{ __('Unit is created successfully') }}");
                    $('#unitAddOrEditModal').modal('hide');
                    var unit_id = $('#unit_id').val();
                    var quick_product_unit_id = $('#quick_product_unit_id').val();

                    if (unit_id != undefined) {

                        $('#unit_id').append('<option value="' + data.id + '">' + data.name + ' (' + data.code_name + ')' + '</option>');
                        $('#unit_id').val(data.id);

                        var nextId = $('#unit_id').data('next');
                        $('#' + nextId).focus().select();
                    } else if (quick_product_unit_id != undefined) {

                        $('#quick_product_unit_id').append('<option value="' + data.id + '">' + data.name + ' (' + data.code_name + ')' + '</option>');
                        $('#quick_product_unit_id').val(data.id);

                        var nextId = $('#quick_product_unit_id').data('next');
                        $('#' + nextId).focus().select();
                    } else {

                        unitsTable.ajax.reload();
                    }
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.unit_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_unit_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
