@extends('layout.master')
@section('title', 'HRM Dashboard - ')
    @push('stylesheets')
        <style>
            #small-badge {font-size: 12px !important;padding: 0px !important;}
            .leave_application table.display thead th {padding: 0px 10px 0px 10px;border-top: none;border-bottom: none;}
            .leave_application .dataTables_wrapper {border-bottom: none;border-top: none;-webkit-box-shadow: none;}
        </style>
    @endpush
@section('content')
    <section>
        <div class="main__content">
            {{-- <div class="sec-name">
                <div class="breadCrumbHolder module w-100">
                    <div id="breadCrumb3" class="breadCrumb module">
                        <ul>
                            @if(auth()->user()->can('hrm_dashboard'))
                                <li>
                                    <a href="{{ route('hrm.dashboard.index') }}" class="text-white"><i class="fas fa-tachometer-alt text-primary"></i> <b>@lang('menu.hrm')</b></a>
                                </li>
                            @endif

                            @if(auth()->user()->can('leave_type'))
                                <li>
                                    <a href="{{ route('hrm.leave.type') }}" class="text-white "><i class="fas fa-th-large"></i> <b>{{ __('Leave Types') }}</b></a>
                                </li>
                            @endif

                            @if(auth()->user()->can('leave_assign'))
                                <li>
                                    <a href="{{ route('hrm.leave') }}" class="text-white"><i class="fas fa-level-down-alt"></i> <b>@lang('menu.leave')</b></a>
                                </li>
                            @endif

                            @if(auth()->user()->can('shift'))
                                <li>
                                    <a href="{{ route('hrm.attendance.shift') }}" class="text-white"><i class="fas fa-network-wired"></i> <b>@lang('menu.shift')</b></a>
                                </li>
                            @endif

                            @if(auth()->user()->can('attendance'))
                                <li>
                                    <a href="{{ route('hrm.attendance') }}" class="text-white"><i class="fas fa-paste"></i> <b>@lang('menu.attendance')</b></a>
                                </li>
                            @endif

                            @if(auth()->user()->can('view_allowance_and_deduction'))
                                <li>
                                    <a href="{{ route('hrm.allowance') }}" class="text-white"><i class="fas fa-plus"></i> <b>@lang('menu.allowance_deduction')</b></a>
                                </li>
                            @endif

                            @if(auth()->user()->can('payroll'))
                                <li>
                                    <a href="{{ route('hrm.payroll.index') }}" class="text-white "><i class="far fa-money-bill-alt"></i> <b>@lang('menu.payroll')</b></a>
                                </li>
                            @endif

                            @if(auth()->user()->can('holiday'))
                                <li>
                                    <a href="{{ route('hrm.holidays') }}" class="text-white "><i class="fas fa-toggle-off"></i> <b>@lang('menu.holiday')</b></a>
                                </li>
                            @endif

                            @if(auth()->user()->can('department'))
                                <li>
                                    <a href="{{ route('hrm.departments') }}" class="text-white "><i class="far fa-building"></i> <b>@lang('menu.department')</b></a>
                                </li>
                            @endif

                            @if(auth()->user()->can('designation'))
                                <li>
                                    <a href="{{ route('hrm.designations') }}" class="text-white "><i class="fas fa-map-marker-alt"></i> <b>@lang('menu.designation')</b></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div> --}}

            <div class="p-3">
                <div class="card">
                    <div class="card-title mt-4 ps-4">
                        <h1 class="text-start text-primary pl-5">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="">HRM</span> @lang('menu.dashboard')
                        </h1>
                    </div>

                    @if ($addons->branches == 1)
                        <div class="card-title mt-2 ps-4">
                            <select name="branch_id" id="branch_id" class="form-control w-25 submit_able" autofocus>
                                <option value="">{{ __('All Business Location') }}</option>
                                <option value="NULL">{{ $generalSettings['business__shop_name'] }} (@lang('menu.head_office'))</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name.'/'.$branch->branch_code }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="preloader_area" style="position: relative;">
                                    <div class="data_preloader mt-4">
                                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                    </div>
                                </div>

                                <div class="form_element rounded m-0 users_data">
                                    <div class="section-header d-flex justify-content-between align-items-center px-3">
                                        <h6><span class="fas fa-users"></span>@lang('menu.users')</h6>
                                        <span class="badge bg-secondary text-white">
                                            <div id="small-badge">@lang('menu.total'): 4324</div>
                                        </span>
                                    </div>
                                    <div class="widget_content">
                                        <div class="mtr-table">
                                            <div class="table-responsive" id="user_data">
                                                <table id="users_table" class="display data__table data_tble stock_table compact" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('menu.department')</th>
                                                            <th>@lang('menu.total')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ __('Branch Manger') }}</td>
                                                            <td>125</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="preloader_area" style="position: relative;">
                                    <div class="data_preloader mt-4">
                                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                    </div>
                                </div>

                                <div class="form_element rounded m-0 today_attendance_table">
                                    <div class="section-header d-flex justify-content-between align-items-center px-3">
                                        <h6>
                                            <span class="fas fa-user-check"></span>
                                            {{ __('Todays Attendance') }}
                                        </h6>
                                    </div>

                                    <div class="widget_content">
                                        <div class="mtr-table">
                                            <div class="table-responsive" id="today_attendance_table">
                                                <table class="display data__table data_tble stock_table compact"
                                                    width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Employee') }}</th>
                                                            <th>{{ __('Clock-in Time') }}</th>
                                                            <th>{{ __('Clock-out Time') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>John Doe</td>
                                                            <td>10:00am</td>
                                                            <td>04:00pm</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="preloader_area" style="position: relative;">
                                    <div class="data_preloader mt-4">
                                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                    </div>
                                </div>
                                <div class="form_element rounded m-0">
                                    <div class="section-header d-flex justify-content-between align-items-center px-3">
                                        <h6>
                                            <span class="far fa-file-alt"></span>
                                            {{ __('Leave Applications') }}
                                        </h6>
                                    </div>
                                    <div class="widget_content">
                                        <div class="mtr-table">
                                            <div class="table-responsive leave_application">
                                                <table id="leave_application_table"
                                                    class="display data__table data_tble stock_table compact mt-2" width="100%">
                                                    <tbody class="mx-2 mt-5" id="leaves">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form_element rounded m-0">
                                    <div class="section-header d-flex justify-content-between align-items-center px-3">
                                        <h6>
                                            <span class="far fa-file-alt"></span>
                                            {{ __('Holidays') }}
                                        </h6>
                                    </div>
                                    <div class="widget_content">
                                        <div class="px-3 pt-2 pb-2">
                                            <div class="px-1">
                                                <span><strong>{{ __('Upcoming Holidays') }}:</strong></span>
                                            </div>
                                            <ul class="list-group list-group-flush upcoming_holiday_list">
                                                <li class="list-group-item list-group-item-warning">A simple warning list group item</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        function getUserTable(){
            $('.data_preloader').show();
            var branch_id = $('#branch_id').val();
            $.ajax({
                url:"{{ route('hrm.dashboard.user.count.table') }}",
                type:'get',
                data: { branch_id },
                success:function(data){
                    $('.users_data').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getUserTable();

        function getTodayAttTable(){
            $('.data_preloader').show();
            var branch_id = $('#branch_id').val();
            $.ajax({
                url:"{{ route('hrm.dashboard.today.attr.table') }}",
                type:'get',
                data: { branch_id },
                success:function(data){
                    $('#today_attendance_table').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getTodayAttTable();

        function getLeaveTable(){
            $('.data_preloader').show();
            var branch_id = $('#branch_id').val();
            $.ajax({
                url:"{{ route('hrm.dashboard.leave.table') }}",
                type:'get',
                data: { branch_id },
                success:function(data){
                    $('#leaves').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getLeaveTable();

        function upcomingHolidays(){
            $('.data_preloader').show();
            $.ajax({
                url:"{{ route('hrm.dashboard.upcoming.holidays') }}",
                type:'get',
                success:function(data){
                    $('.upcoming_holiday_list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        upcomingHolidays();

        $(document).on('change', '.submit_able', function () {
            getUserTable();
            getTodayAttTable();
            getLeaveTable();
        });
    </script>
@endpush
