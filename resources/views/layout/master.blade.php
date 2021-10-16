<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title>@yield('title') Genuine POS</title>

    <!-- Icon -->
    <link rel="shortcut icon" href="{{ asset('public/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layout._stylesheet')
    @stack('stylesheets')

</head>

<body id="dashboard-8" class="blue-theme">

    {{-- color changing option  --}}
    <div class="color_change_wrapper">
        <ul>
            <li class="red"></li>
            <li class="blue"></li>
            <li class="dark"></li>
            <li class="ash"></li>
        </ul>
    </div>

    <div class="all__content">
        @include('partials.sidebar')

        <div class="main-woaper">
            @include('partials.header')
            <div class="bg-color-body">
                @yield('content')
            </div>
        </div>
        <footer>
            <div class="logo_wrapper">
                <img src="{{ asset('public/backend/images/static/app_logo.png') }}" class="logo">
            </div>
        </footer>
    </div>

    <div class="modal fade" id="todaySummeryModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Today Summery</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="today_summery_modal_body">
                    <div class="today_summery_modal_contant">

                    </div>
                    <div class="print-button-area">
                        <a href="#" class="btn btn-sm btn-primary float-end" id="today_summery_print_btn">Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layout._script')
    @stack('scripts')
    <script>
        $(document).on('click', '#today_summery',function (e) {
            e.preventDefault();
            todaySummery();
        });

        function todaySummery() {
            var branch_id = $('#today_branch_id').val();
            $('.loader').show();
            $.ajax({
                url: "{{ route('dashboard.today.summery') }}",
                type: 'get',
                data: {branch_id},
                success: function(data) {
                    $('.today_summery_modal_contant').html(data);
                    $('#todaySummeryModal').modal('show');
                    $('.loader').hide();
                }
            });
        }

        $(document).on('change', '#today_branch_id',function () {
            todaySummery();
        });

        $(document).on('click', '#today_summery_print_btn', function (e) {
            e.preventDefault();
            var body = $('.print_body').html();
            var header = $('.print_today_summery_header').html();
            var footer = $('.print_today_summery_footer').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{asset('public/assets/css/print/purchase.print.css')}}",
                removeInline: true,
                printDelay: 500,
                header: header,
                footer: footer
            });
        });
    </script>
    <!-- Logout form for global -->
    <form id="logout_form" class="d-none" action="{{ route('logout') }}" method="POST">@csrf</form>
    <!-- Logout form for global end -->
</body>

</html>
