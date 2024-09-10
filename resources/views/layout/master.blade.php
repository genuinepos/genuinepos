<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $rtl = app()->isLocale('ar');
@endphp

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>@yield('title') {{ config('app.name') }}</title>
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    @include('layout._stylesheet')
    @stack('stylesheets')

    <!-- Vite and Laravel-Vite used as Asset Build Tools (For SASS/VueJS/ReactJS or any other build process ) -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/scripts/main.ts']) --}}
</head>

<body id="dashboard-8" class="{{ $generalSettings['system__theme_color'] ?? 'dark-theme' }}
@if ($rtl) rtl @endif" @if ($rtl) dir="rtl" @endif>

    <div class="all__content">
        @include('partials.sidebar')

        <div class="main-woaper">
            @include('partials.header')
            <div class="bg-color-body">
                @yield('content')
            </div>
        </div>

        @include('partials.right_sidebar')

        <footer>
            <div class="logo_wrapper">
                @if (config('speeddigit.dynamic_app_logo') == true)
                    @if ($generalSettings['business_or_shop__business_logo'])
                        <img src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="System Logo" class="logo">
                    @else
                        <h6 class="text-white fw-bold text-uppercase logo text-center">{{ $generalSettings['business_or_shop__business_name'] }}</h6>
                    @endif
                @else
                    <img src="{{ asset(config('speeddigit.app_logo')) }}" class="logo" alt="{{ config('speeddigit.app_logo_alt') }}">
                @endif
            </div>

            <span class="version-txt float-end text-white pe-2" style="margin-top: -20px"><small><a href="{{ route('settings.release.note.index') }}" class="text-deep-green fw-bold">V - 2.0.6</a></small></span>
        </footer>
    </div>

    <div class="modal fade" id="appInstallModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="appInstallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">

                    <h1 class="modal-title fs-5" id="appInstallModalLabel">Welcome to GPOS!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __("If you want to add a shortcut on your desktop, click the OK button.") }}
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" id="closeInstallModal" data-bs-dismiss="modal">Close</button> --}}
                    <button type="button" id="installPwa" class="btn btn-primary">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="todaySummeryModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

    @include('layout._script')
    @stack('scripts')
    <script>
        $(document).on('click', '#todaySummeryBtn', function(e) {
            e.preventDefault();
            todaySummery();
        });

        function todaySummery() {
            var branch_id = $('#today_summary_branch_id').val();

            $('.loader').show();
            $.ajax({
                url: "{{ route('today.summary.index') }}",
                type: 'get',
                data: {
                    branch_id
                },
                success: function(data) {

                    $('.loader').hide();
                    $('#todaySummeryModal').empty();
                    $('#todaySummeryModal').html(data);
                    $('#todaySummeryModal').modal('show');
                },
                error: function(err) {

                    $('.loader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        }

        $(document).on('change', '#today_summary_branch_id', function() {
            todaySummery();
        });

        function printTodaySummary(e) {

            var url = $(e).attr('href');

            var branch_id = $('#today_summary_branch_id').val();
            var branch_name = $('#today_summary_branch_id').find('option:selected').data('branch_name');

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    branch_name,
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 500,
                        header: "",
                        pageTitle: "",
                        // footer: 'Footer Text',
                    });
                }
            });
        };

        $('.calculator-bg__main button').prop('type', 'button');

        // POS read manual button
        $('#readDocument').click(function() {

            if ($('#readDocument div.doc').css('display', 'none')) {

                $('#readDocument div.doc').toggleClass('d-block')
            }
        })

        $(document).on('click', '#show_cost_button', function() {
            $('#show_cost_section').toggle(500);
        });

        $('#todaySummeryModal').on('hide.bs.modal', function(e) {
            $('#todaySummeryModal').empty();
        });
    </script>
    <!-- Logout form for global -->
    <form id="logout_form" class="d-hide" action="{{ route('logout') }}" method="POST">@csrf</form>
    <!-- Logout form for global end -->
    <script src="{{ asset('/sw.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/install-pwa-app.js') }}"></script>
</body>

</html>
