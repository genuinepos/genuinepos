<script>
    $('.select2').select2();

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

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });
</script>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.contra_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.contra_submit_button', function() {

        var value = $(this).val();
        $('#action').val(value);
        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    //Add payment request by ajax
    $('#add_contra_form').on('submit', function(e) {
        e.preventDefault();

        $('.contra_loading_btn').show();
        var url = $(this).attr('action');

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;

                $('.error').html('');
                $('.contra_loading_btn').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                } else if (data.successMsg) {

                    toastr.success(data.successMsg);
                    $('#addOrEditContraModal').modal('hide');
                    $('#addOrEditContraModal').empty();
                    contraTable.ajax.reload();
                    return;
                } else {

                    toastr.success("{{ __('Contra added successfully.') }}");

                    $('#addOrEditContraModal').modal('hide');
                    $('#addOrEditContraModal').empty();

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000
                    });

                    contraTable.ajax.reload();
                    return;
                }
            }, error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.contra_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error("{{ __('Please check again all form fields.') }}", "{{ __('Some thing went wrong.') }}");

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_contra_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>

<script>
    new Litepicker({
        singleMode: true,
        element: document.getElementById('contra_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });
</script>
