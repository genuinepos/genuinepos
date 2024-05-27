<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="{{ asset('favicon.png') }}">
<title>{{ __('Startup') }}</title>

@php
    $rtl = app()->isLocale('ar');
@endphp

@if ($rtl)
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic&display=swap" rel="stylesheet">
@else
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
@endif

<link rel="stylesheet" href="{{ asset('assets/fontawesome6/css/all.min.css') }}">
<link href="{{ asset('assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/css/cart.css') }}">
<link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<style>
    .error{
        font-size: 11px;
        color: red;
    }

    .tab-section .tab-nav .single-nav {
        height: 35px;
        font-size: 15px;
    }

    .def-btn {
        height: 40px;
        line-height: 40px;
        padding: 0 30px;
        font-size: 13px;
        cursor: pointer;
    }

    .tab-section .tab-contents .tab-next-btn {
        font-size: 13px;
        text-align: center;
    }

    .tab-section .tab-contents .billing-details .form-row {
        gap: 10px 20px;
    }

    .tab-section .tab-contents .billing-details .form-row .form-control {
        font-size: 14px;
        height: 35px;
        line-height: 33px;
        padding: 0 15px;
    }

    .domain-field span.txt {
        font-size: 17px;
    }

    .tab-section .tab-contents .billing-details .title {
        font-size: 16px;
    }

    .form-row .col-md-4 {
        width: 32%;
    }

    .col-md-8 {
        flex: 0 0 auto;
        width: 65.666667%;
    }

    label {
        font-size: 13px !important;
    }

    .tab-section .tab-nav {
        margin-bottom: 28px;
    }

    span.selection {
        width: 100%;
    }

    .select2-container .select2-selection--single {
        height: 35px;
        background: rgba(241, 241, 241, 0.5);
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 33px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px;
    }

    .dropify-wrapper {
        height: 120px !important;
    }

    .tab-section .tab-contents .billing-details .form-row {
        margin-bottom: 14px;
    }

    .form-control {
        -webkit-appearance: listbox;
    }

    /* Startup form header style start */
    .startup-form-header .navigation {
        display: flex;
        align-items: center;
        width: 100%;
        z-index: 99;
        padding: 0px;
        left: 0px;
    }

    .startup-form-header .panel__nav {
        background: linear-gradient(#444444, #0a0b0c);
        border-top: 1px solid #29b0fd;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .top-menu {
        width: 100%;
    }

    .logo__sec {
        width: 142px;
        height: 45px;
        display: flex;
        justify-content: center;
        align-items: center;
        float: left;
    }

    img.logo__img {
        width: auto !important;
        height: auto !important;
        max-height: 35px;
        max-width: 110px;
        vertical-align: middle;
    }

    .notify-menu {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .company-name {
        border-left: 1px solid rgba(255, 255, 255, 0.2);
        padding-left: 15px;
    }

    .company-name p {
        font-size: 13px;
        line-height: 100%;
        font-weight: 500;
        color: #fff;
        margin-bottom: 4px;
    }

    .company-name span {
        display: block;
        font-size: 11px;
        line-height: 100%;
        color: #d1d1d1;
    }

    .company-name span strong {
        color: #e9e9e9;
    }

    .startup-form-header ul.head__cn {
        border-left: 1px solid #000000;
        display: flex;
    }

    .startup-form-header .top-icon {
        background: #0a0b0c;
    }

    .startup-form-header ul.head__cn li .nav-btn {
        background: linear-gradient(#424242, #000000) !important;
        border-left: 1px solid #424242;
        border-right: 1px solid #000000;

        display: flex !important;
        align-items: center;
        text-align: left;
        height: 45px !important;
        max-height: none;
        color: #fff;
        border: 0;
        border-radius: 0;
        padding: 0 10px;
        position: relative;
    }

    .startup-form-header ul.head__cn li a {
        color: #ffffff;
    }

    .startup-form-header ul.head__cn li a span {
        font-size: 11px;
        font-weight: 300;
    }
</style>
</head>

<body class="inner">
@include('startup.partials.header')

<div class="tab-section py-120">
    <div class="container">
        <div class="row mt-2">
            <div class="col-12">
                <div class="tab-nav">
                    <button class="single-nav businessSetupTab active" id="single-nav" data-tab="businessSetupTab">
                        <span class="txt">{{ __('Business Setup') }}</span>
                    </button>

                    <button class="single-nav createBranchTab" id="single-nav" data-tab="createBranchTab">
                        <span class="txt">{{ __('Create Store') }}</span>
                    </button>
                </div>

                <div class="tab-contents">
                    <form id="startup_from" action="{{ route('startup.finish') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="single-tab active" id="businessSetupTab">
                            @include('startup.partials.business_setup_partial')
                        </div>

                        <div class="single-tab" id="createBranchTab">
                            @include('startup.partials.create_branch_partial')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout form for global -->
<form id="logout_form" class="d-hide" action="{{ route('logout') }}" method="POST">@csrf</form>
<!-- Logout form for global end -->

<!-- js files -->
<script src="{{ asset('backend/asset/cdn/js/jquery-3.6.0.js') }}"></script>
<script src="{{ asset('backend/asset/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/dropify/js/dropify.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
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

                toastr.error("{{ __('Business name is required.') }}");
                return;
            }

            if ($('#business_address').val() == '') {

                toastr.error("{{ __('Business address is required.') }}");
                return;
            }

            if ($('#business_email').val() == '') {

                toastr.error("{{ __('Business email address is required.') }}");
                return;
            }

            if ($('#business_currency_id').val() == '') {

                toastr.error("{{ __('Business currency is required.') }}");
                return;
            }

            if ($('#business_timezone').val() == '') {

                toastr.error("{{ __('Business timezone is required.') }}");
                return;
            }

            if ($('#business_account_start_date').val() == '') {

                toastr.error("{{ __('Business account start date is required.') }}");
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
            }, error: function(err) {

                $('.loading_button').addClass('d-none');
                $('.submit_blue_btn').addClass('d-none');
                $('.submit_button').removeClass('d-none');
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error("{{ __('Please check again all form fields.') }}", "{{ __('Some thing went wrong.') }}");

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
</body>

</html>
