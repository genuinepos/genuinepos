<!DOCTYPE html>
<html lang="en">

<head>
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

    <link href="{{ asset('assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/css/cart.css') }}">
    <link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">

    <style>
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
            margin-bottom: -10px;
        }

        .form-control {
            -webkit-appearance: listbox;
        }
    </style>
</head>

<body class="inner">
    <div class="tab-section py-120">
        <div class="container">
            <div class="row mt-2">
                <div class="col-12">
                    <div class="tab-nav">
                        <button class="single-nav active" id="single-nav">
                            <span class="txt">{{ __('Create Store') }}</span>
                        </button>
                    </div>

                    <div class="tab-contents">
                        @include('setups.startup.partials.create_branch_partial')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- js files -->
    <script src="{{ asset('backend/asset/cdn/js/jquery-3.6.0.js') }}"></script>
    <script src="{{ asset('backend/asset/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/dropify/js/dropify.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
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
    </script>
</body>

</html>
