<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $rtl  = app()->isLocale('ar');
@endphp
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>@yield('title') {{ config('app.name') }}</title>

    @include('layout._stylesheet')
    @stack('stylesheets')

    <!-- Vite and Laravel-Vite used as Asset Build Tools (For SASS/VueJS/ReactJS or any other build process ) -->
    @vite([
        'resources/sass/app.scss',
        'resources/js/app.js',
        'resources/scripts/main.ts',
    ])
</head>

<body id="dashboard-8"
class="{{ $generalSettings['system__theme_color'] ?? 'dark-theme' }}
@if($rtl) rtl @endif" @if($rtl) dir="rtl" @endif>

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
                <img src="{{ asset(config('speeddigit.app_logo')) }}" class="logo" alt="{{ config('speeddigit.app_logo_alt') }}">
            </div>

            <span class="version-txt float-end text-white pe-2" style="margin-top: -20px"><small>V - 1.0.1</small></span>
        </footer>
    </div>

    <div class="modal fade" id="todaySummeryModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

    @include('layout._script')
    @stack('scripts')
    <script>
        $(document).on('click', '#todaySummeryBtn',function (e) {
            e.preventDefault();
            todaySummery();
        });

        function todaySummery() {
            var branch_id = $('#today_summary_branch_id').val();

            $('.loader').show();
            $.ajax({
                url: "{{ route('today.summary.index') }}",
                type: 'get',
                data: { branch_id },
                success: function(data) {
                    console.log(data);
                    $('.loader').hide();
                    $('#todaySummeryModal').empty();
                    $('#todaySummeryModal').html(data);
                    $('#todaySummeryModal').modal('show');
                }, error: function(err) {

                    $('.loader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        }

        $(document).on('change', '#today_summary_branch_id',function () {
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
                }, success: function(data) {

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
        $('#readDocument').click(function () {

            if ($('#readDocument div.doc').css('display', 'none')) {

                $('#readDocument div.doc').toggleClass('d-block')
            }
        })

        $(document).on('click', '#show_cost_button', function () {
            $('#show_cost_section').toggle(500);
        });

        $('#todaySummeryModal').on('hide.bs.modal', function(e)
        {
            $('#todaySummeryModal').empty();
        });
    </script>
    <!-- Logout form for global -->
    <form id="logout_form" class="d-hide" action="{{ route('logout') }}" method="POST">@csrf</form>
    <!-- Logout form for global end -->
</body>

</html>
