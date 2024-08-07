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
                    $('#domainPreview').html(`<span class="text-success">✔ Domain is available<span>`);
                }else {

                    isAvailable = false;
                    $('#domainPreview').html(`<span class="text-danger">❌ Domain is not available<span>`);
                }
            }, error: function(err) {

                isAvailable = false;
                console.log(err);
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

                toastr.error("{{ __('Company name is required.') }}");
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

             if ($('#username').val() == '') {

                toastr.error("{{ __('Username is required.') }}");
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

            var pass = false;
            var request = $('#tenantStoreForm').serialize();
            $.ajax({
                url: "{{ route('saas.guest.trial.validation') }}",
                type: 'POST',
                data: request,
                async: false,
                success: function(res) {

                    pass = true;
                }, error: function(err) {

                    pass = false;
                    toastr.error(Object.values(err.responseJSON.errors)[0]);
                }
            });

            if (pass == false) {

                return;
            }

            if (sendVerificationCode == false || $('#sendVerificationEmailAddress').val() != $('#email').val()) {

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

    function sendVerificationEmail(showMessage = 0) {

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
                $('#sendVerificationEmailAddress').val(email);
                if (showMessage == 1) {

                    toastr.success("{{ __('Email verification code has been resend successfully.') }}");
                }
            }, error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    console.log('Server error.');
                    return;
                }
            }
        });
    }

    $(document).on('click', '#resendVerificationEmail', function(e) {
        var showMessage = 1;
        sendVerificationEmail(showMessage);
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
            }, success: function(data) {

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

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    console.log('Server error.');
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('submit', '#tenantStoreForm', function(e) {
        e.preventDefault();

        let url = $('#tenantStoreForm').attr('action');
        $('#response-message').removeClass('d-none');
        var request = $(this).serialize();

        if (isAvailable == false) {

            $('#domainPreview').html(`<span class="text-danger">❌ Domain is not available<span>`);
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

                $('#response-message').html('<span class="text-white"> Redirecting to <span class="fw-bold">'+res+'</span></span>');
                // $('#successSection').removeClass('d-none');

                window.location = res;
            }, error: function(err) {

                $('#response-message').addClass('d-none');
                toastr.error('Something went wrong');
                toastr.error(err.responseJSON.message);
                var domain = $('#domain').val();
                $('#delete_domain').val(domain);
                $('#deleteFailedTenant').submit();
            }
        });
    });

    $(document).on('submit', '#deleteFailedTenant', function(e) {
        e.preventDefault();

        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: request ,
            async: false,
            success: function(res) {

                location.reload(true);
            }, error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    location.reload(true);
                    return;
                }

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
