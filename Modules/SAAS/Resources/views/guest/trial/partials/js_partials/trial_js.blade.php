<script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
<script>
    // Domain Check
    var sendVerificationCode = false;
    var typingTimer; //timer identifier
    var doneTypingInterval = 800; //time in ms, 5 seconds for example
    var $input = $('#domain');

    //on keyup, start the countdown
    $input.on('keyup', function() {

        if ($input.val() == '') {

            $('#domainPreview').html('');
            return;
        }

        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    //on keydown, clear the countdown
    $input.on('keydown', function() {
        clearTimeout(typingTimer);
    });

    //user is "finished typing," do something
    function doneTyping() {
        $('#domainPreview').html(`<span class="">🔍Checking availability...<span>`);
        var domain = $('#domain').val();

        if ($input.val() == '') {

            $('#domainPreview').html('');
            return;
        }

        $.ajax({
            url: "{{ route('saas.domain.checkAvailability') }}",
            type: 'GET',
            data: {
                domain: domain
            },
            success: function(res) {

                if ($input.val() == '') {

                    $('#domainPreview').html('');
                    return;
                }

                if (res.isAvailable) {
                    isAvailable = true;
                    $('#domainPreview').html(`<span class="text-success">✔ Doamin is available<span>`);
                }
            },
            error: function(err) {
                isAvailable = false;
                $('#domainPreview').html(`<span class="text-danger">❌ Doamin is not available<span>`);
            }
        });
    }
</script>

<script>
    $(document).on('click', '#single-nav', function(e) {

        e.preventDefault();

        var tabData = $(this).data('tab');
        if (tabData == 'stepTwoTab') {

            if ($('#name').val() == '') {

                toastr.error("{{ __('Business name is required.') }}");
                return;
            }

            if ($('#domain').val() == '') {

                toastr.error("{{ __('Store url is required.') }}");
                return;
            }

            if ($('#fullname').val() == '') {

                toastr.error("{{ __('Fullname is required.') }}");
                return;
            }

            if ($('#email').val() == '') {

                toastr.error("{{ __('Email is required.') }}");
                return;
            }

            var validEmail = $('#email').val().match(
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );

            if (validEmail == null) {

                toastr.error("{{ __('Email format is invalid.') }}");
                return;
            }

            if ($('#currency_id').val() == '') {

                toastr.error("{{ __('Country is required.') }}");
                return;
            }

            if ($('#phone').val() == '') {

                toastr.error("{{ __('Phone number is required.') }}");
                return;
            }

            if ($('#password').val() == '') {

                toastr.error("{{ __('Password is required.') }}");
                return;
            }

            if ($('#password_confirmation').val() == '') {

                toastr.error("{{ __('Confirm password is required.') }}");
                return;
            }

            if ($('#password_confirmation').val() != $('#password').val()) {

                toastr.error("{{ __('Password and comfirm password is mismatch.') }}");
                return;
            }

            if (sendVerificationCode == false) {

                sendVerificationEmail();
            }
        }

        $('.single-nav').removeClass('active');

        $('.single-tab').removeClass('active');
        $(this).addClass('active');
        $('#' + tabData).addClass('active');
        $('.' + tabData).addClass('active');
    });

    $('.single-nav').removeClass('active');
    $('.single-tab').removeClass('active');
    $('#stepOneTab').addClass('active');
    $('.stepOneTab').addClass('active');

    function sendVerificationEmail() {

        var email = $('#email').val();
        var url = "{{ route('saas.guest.email.send.verification.code') }}";

        $.ajax({
            url: url,
            type: 'get',
            data: {
                email
            },
            success: function(data) {

                sendVerificationCode = true;
            }, error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    console.log('Server error.');;
                    return;
                }
            }
        });
    }

    $(document).on('click', '#resendVerificationEmail', function(e) {

        sendVerificationEmail();
    });

    $(document).on('click', '#checkEmailVerificationCode', function(e) {

        var email = $('#email').val();
        var code = $('#verification_code').val();

        if (code == '') {

            toastr.error("{{ __('Please enter the verification code.') }}");
            return;
        }

        $('#checkEmailVerificationCode').addClass('d-none');
        var url = "{{ route('saas.guest.email.verification.code.match') }}";

        $.ajax({
            url: url,
            type: 'get',
            data: {
                email,
                code
            },
            success: function(data) {

                if (data == 0) {

                    toastr.error('Email Verification code does not match.');
                    $('#checkEmailVerificationCode').removeClass('d-none');
                    return;
                }

                $('#email-verification-section').addClass('d-none');
                $('#email-verification-success').removeClass('d-none');
                $('#tenantStoreForm').submit();
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    console.log('Server error.');;
                    return;
                }
            }
        });
    });

    $(document).on('input', '#email', function(e) {

        var value = $(this).val();
        $('#showEmail').html(value);
    });
</script>

<script>
    $(document).on('submit', '#tenantStoreForm', function(e) {
        e.preventDefault();

        let url = $('#tenantStoreForm').attr('action');
        $('#response-message').removeClass('d-none');
        var request = $(this).serialize();

        if (isAvailable == false) {

            $('#domainPreview').html(`<span class="text-danger">❌ Doamin is not available<span>`);
            $('#response-message').addClass('d-none');
            return;
        }

        $('.single-nav').addClass('d-none');
        $('.cart-header').addClass('d-none');

        $('.single-nav').removeClass('active');
        $('.single-tab').removeClass('active');
        $('#stepThreeTab').addClass('active');

        $('#timespan').text(0);
        setInterval(function() {
            let currentValue = parseInt($('#timespan').text() || 0);
            $('#timespan').text(currentValue + 1);
        }, 1000);

        $.ajax({
            url: url,
            type: 'POST',
            data: request,
            success: function(res) {

                $('#response-message').html('<span class="text-white"> Redirecting to <span class="fw-bold">https://demo.pos.test</span></span>');
                // $('#successSection').removeClass('d-none');

                window.location = res;
            }, error: function(err) {

                $('#response-message').addClass('d-none');
                toastr.error('Something went wrong');
                toastr.error(err.responseJSON.message);
                location.reload(true);
            }
        });
    });

    var res = setInterval(function() {
        $('#preloader-animitation-section').addClass('d-none');
        setTimeout(() => {
            $('#preloader-animitation-section').removeClass('d-none');
        }, 100);

    }, 13000);
</script>
