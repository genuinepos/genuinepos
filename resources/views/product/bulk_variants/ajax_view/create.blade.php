<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Add Bulk Variant") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_bulk_variant_form" action="{{ route('product.bulk.variants.store') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-md-12">
                        <label><b>{{ __("Name") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="variant_name" data-next="variant_child" placeholder="{{ __("Variant Name") }}" />
                        <span class="error error_variant_name"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label><b>{{ __("Variant Child (Values)") }}</b> <span class="text-danger">*</span></label>
                    <table class="table table-sm w-100">
                        <tbody id="variant_child_fields">
                            <tr>
                                <td style="width: 70%!important; line-height:1!important;padding:3px;vertical-align: bottom;">
                                    <input required type="text" name="variant_child[]" class="form-control" id="variant_child" placeholder="{{ __("Variant Child") }}" />
                                    @php
                                        $uniqueId = uniqid();
                                    @endphp
                                    <input type="hidden" class="unique_id-{{ $uniqueId }}" id="unique_id" value="{{ $uniqueId }}">
                                </td>

                                <td style="width: 30%!important; line-height:1!important;padding:3px;vertical-align: bottom;">
                                    <select onkeypress="nextStep(this)" onchange="nextStep(this)" class="form-control" id="next_step">
                                        <option value="">{{ __("Next Step") }}</option>
                                        <option value="add_more">{{ __("Add More") }}</option>
                                        <option value="next_field">{{ __("Next Field") }}</option>
                                        <option value="list_end">{{ __("List End") }}</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button variant_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="button" id="variant_save" class="btn btn-sm btn-success variant_submit_button">{{ __("Save") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.variant_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.variant_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_bulk_variant_form').on('submit', function(e) {
        e.preventDefault();

        $('.variant_loading_btn').show();
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
                $('.variant_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);
                $('#variantAddOrEditModal').modal('hide');
                table.ajax.reload();
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.variant_loading_btn').hide();
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

                    $('.error_variant_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    function nextStep(e) {

        var tr = $(e).closest('tr');
        var nxt = tr.next();

        if (e.value == 'add_more') {

            var variantChild = document.querySelectorAll('#variant_child');
            var lastvariantChild = variantChild[variantChild.length - 1];

            tr.find('#next_step').val('');
            // if (nxt.length == 0) {

            //     if (tr.find('#variant_child').val() == '') {

            //         tr.find('#variant_child').focus();
            //         return;
            //     }

            //     addMore();
            // }else {

            //     nxt.find('#variant_child').focus().select();
            // }

            // if (nxt.find('#variant_child').val() == '') {
            if (tr.find('#variant_child').val() == '') {

                tr.find('#variant_child').focus();
                return;
            }

            if (lastvariantChild.value == '') {

                // nxt.find('#variant_child').focus().select();
                lastvariantChild.focus().select();
                return;
            }

            addMore();
        } else if(e.value == 'remove') {

            previousTr = tr.prev();
            nxtTr = tr.next();

            tr.remove();

            if (nxtTr.length == 1) {

                nxtTr.find('#variant_child').focus().select();
            } else if (previousTr.length == 1) {

                previousTr.find('#variant_child').focus().select();
            }
        }else if(e.value == 'next_field') {

            tr.find('#next_step').val('');
            nxt.find('#variant_child').focus().select();
        }else if(e.value == 'list_end') {

            tr.find('#next_step').val('');
            $('#variant_save').focus();
        }
    }

    function addMore() {

        var generate_unique_id = parseInt(Date.now() + Math.random());

        var html = '';
        html += '<tr>';
        html += '<td style="width: 70%!important; line-height:1!important;padding:3px;vertical-align: bottom;">';
        html += '<input required type="text" name="variant_child[]" class="form-control" id="variant_child" placeholder="{{ __("Variant Child") }}"/>';
        html += '<input type="hidden" class="unique_id-' + generate_unique_id + '" id="unique_id" value="' + generate_unique_id + '">';
        html += '</td>';

        html += '<td style="width: 30%!important; line-height:1!important;padding:3px;vertical-align: bottom;">';
        html += '<select onkeypress="nextStep(this)" onchange="nextStep(this)" class="form-control" id="next_step">';
        html += '<option value="">{{ __("Next Step") }}</option>';
        html += '<option value="add_more">{{ __("Add More") }}</option>';
        html += '<option value="next_field">{{ __("Next Field") }}</option>';
        html += '<option value="list_end">{{ __("List End") }}</option>';
        html += '<option value="remove">{{ __("Remove") }}</option>';
        html += '</select>';
        html += '</td>';
        html += '</tr>';

        $('#variant_child_fields').append(html);

        var tr = $('.unique_id-' + generate_unique_id).closest('tr');
        var previousTr = tr.prev();
        tr.find('#variant_child').focus().select();
    }

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('input keypress', '#variant_child', function(e) {

        if (e.which == 13) {

            $(this).closest('tr').find('#next_step').focus();
        }
    });
</script>
