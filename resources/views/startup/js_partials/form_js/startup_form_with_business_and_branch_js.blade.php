<script>
    $('#business_logo').dropify({
        messages: {
            'default': "{{ __('Drag and drop a file here or click') }}",
            'replace': "{{ __('Drag and drop or click to replace') }}",
            'remove': "{{ __('Remove') }}",
            'error': "{{ __('Ooops, something wrong happended.') }}",
        }
    });

    $('#branch_logo').dropify({
        messages: {
            'default': "{{ __('Drag and drop a file here or click') }}",
            'replace': "{{ __('Drag and drop or click to replace') }}",
            'remove': "{{ __('Remove') }}",
            'error': "{{ __('Ooops, something wrong happended.') }}",
        }
    });

    $(document).ready(function() {
        $('.select2').select2();
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('business_account_start_date'),
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
        format: 'YYYY-MM-DD',
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('branch_account_start_date'),
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
        format: 'YYYY-MM-DD',
    });

    $('#add_initial_user_btn').on('click', function() {
        $('.branch_initial_user_field').toggleClass('d-none');

        if ($('#add_initial_user').val() == 0) {

            $('#add_initial_user').val(1);
            $('#branch_user_first_name').focus();

            $('.branch-user-required-field').prop('required', true);
        } else {

            $('#add_initial_user').val(0);
            $('.branch-user-required-field').prop('required', false);
        }
    });

    $(document).on('click', '#single-nav', function(e) {

        e.preventDefault();

        var tabData = $(this).data('tab');
        if (tabData == 'createBranchTab') {

            if ($('#business_name').val() == '') {

                toastr.error("{{ __('Company name is required.') }}");
                return;
            }

            if ($('#business_address').val() == '') {

                toastr.error("{{ __('Company address is required.') }}");
                return;
            }

            if ($('#business_email').val() == '') {

                toastr.error("{{ __('Company email address is required.') }}");
                return;
            }

            if ($('#business_currency_id').val() == '') {

                toastr.error("{{ __('Company currency is required.') }}");
                return;
            }

            if ($('#business_timezone').val() == '') {

                toastr.error("{{ __('Company timezone is required.') }}");
                return;
            }

            if ($('#business_account_start_date').val() == '') {

                toastr.error("{{ __('Company account start date is required.') }}");
                return;
            }
        }

        $('.single-nav').removeClass('active');

        $('.single-tab').removeClass('active');
        $(this).addClass('active');
        $('#' + tabData).addClass('active');
        $('.' + tabData).addClass('active');
    });

    $(document).on('change', '#business_currency_id', function(e) {
        var currencySymbol = $(this).find('option:selected').data('currency_symbol');
        $('#business_currency_symbol').val(currencySymbol);
    });

    $(document).on('change', '#branch_currency_id', function(e) {
        var currencySymbol = $(this).find('option:selected').data('currency_symbol');
        $('#branch_currency_symbol').val(currencySymbol);
    });

    $('.single-nav').removeClass('active');
    $('.single-tab').removeClass('active');
    $('#businessSetupTab').addClass('active');
    $('.businessSetupTab').addClass('active');

    $(window).scroll(function() {
        if ($('.select2').is(':visible')) {
            $('.select2-dropdown').css({
                "display": "none"
            });
        }
    });

    $(document).on('click', '.select2', function(e) {
        e.preventDefault();
        $('.select2-dropdown').css({
            "display": ""
        });
    });

    $(document).on('select2:open', () => {

        if ($('.select2-search--dropdown .select2-search__field').length > 0) {

            document.querySelector('.select2-search--dropdown .select2-search__field').focus();
        }
    });

    $(document).on('click', '#logout_option', function(e) {
        e.preventDefault();
        $.confirm({
            'title': 'Logout Confirmation',
            'content': 'Are you sure, you want to logout?',
            'buttons': {
                'Yes': {
                    'btnClass': 'yes btn-modal-primary',
                    'action': function() {
                        $('#logout_form').submit();
                    }
                },
                'No': {
                    'btnClass': 'no btn-danger',
                    'action': function() {
                        console.log('Canceled.');
                    }
                }
            }
        });
    });

    $(document).on('submit', '#startup_from', function(e) {
        e.preventDefault();

        $('.loading_button').removeClass('d-none');
        $('.submit_blue_btn').removeClass('d-none');
        $('.submit_button').addClass('d-none');
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(res) {

                $('.error').html('');
                $('.loading_button').addClass('d-none');
                $('.submit_blue_btn').addClass('d-none');
                $('.submit_button').removeClass('d-none');

                window.location = res;
            },
            error: function(err) {

                $('.loading_button').addClass('d-none');
                $('.submit_blue_btn').addClass('d-none');
                $('.submit_button').removeClass('d-none');
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
