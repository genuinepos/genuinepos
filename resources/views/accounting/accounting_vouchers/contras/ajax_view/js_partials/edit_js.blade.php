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

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    //Edit request by ajax
    $('#edit_contra_form').on('submit', function(e) {
        e.preventDefault();

        $('.contra_loading_btn').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.error').html('');
                $('.contra_loading_btn').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                toastr.success(data);
                $('#addOrEditContraModal').modal('hide');
                $('#addOrEditContraModal').empty();
                contraTable.ajax.reload();
                return;
            }, error: function(err) {

                $('.contra_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_contra_' + key + '').html(error[0]);
                });
            }
        });
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
