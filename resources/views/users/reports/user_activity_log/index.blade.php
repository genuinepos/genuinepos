@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .log_table td {
            font-size: 9px !important;
            font-weight: 500 !important;
        }
    </style>
@endpush
@section('title', 'User Activities Log - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('User Activities Log') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row g-3">
                                    {{-- @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) --}}
                                    {{-- @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1) --}}
                                    @if (isset($branches) && count($branches) > 0)
                                        <div class="col-md-2">
                                            <label><strong>{{ __('Shop/Business') }}</strong></label>
                                            <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                <option value="">{{ __('All') }}</option>
                                                <option value="business">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        @php
                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                            $branchCode = '-' . $branch->branch_code;
                                                        @endphp
                                                        {{ $branchName . $areaName . $branchCode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    @if (isset($users) && count($users) > 0)
                                        <div class="col-md-2">
                                            <label><strong>{{ __('Action By') }} : </strong></label>
                                            <select name="user_id" class="form-control select2" id="user_id" autofocus>
                                                <option value="">{{ __('All') }}</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-md-2">
                                        <label><strong>{{ __('Action Name') }} : </strong></label>
                                        <select name="action" class="form-control" id="action" autofocus>
                                            <option value="">{{ __('All') }}</option>
                                            @foreach (\App\Enums\UserActivityLogActionType::cases() as $action)
                                                <option value="{{ $action->value }}">{{ $action->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>{{ __('Subject Type') }} : </strong></label>
                                        <select name="subject_type" class="form-control select2" id="subject_type" autofocus>
                                            <option value="">{{ __('All') }}</option>
                                            @foreach (\App\Enums\UserActivityLogSubjectType::cases() as $subjectType)
                                                <option value="{{ $subjectType->value }}">{{ str($subjectType->name)->headline() }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>{{ __('From Date') }} : </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>{{ __('To Date') }} : </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="fas fa-calendar-week input_f"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        {{-- <table class="display data_tbl data__table table-hover"> --}}
                        <table class="log_table display data_tbl table-sm ">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Shop/Business') }}</th>
                                    <th>{{ __('Action By') }}</th>
                                    <th>{{ __('Action Name') }}</th>
                                    <th>{{ __('Subject Type') }}</th>
                                    <th>{{ __('Description') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <form id="deleted_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var logTable = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Pdf',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-primary'
                },
            ],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.user.activities.log.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.user_id = $('#user_id').val();
                    d.action = $('#action').val();
                    d.subject_type = $('#subject_type').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [
                { data: 'date', name: 'date' },
                { data: 'branch', name: 'branches.name' },
                { data: 'action_by', name: 'users.name' },
                { data: 'action', name: 'action' },
                { data: 'subject_type', name: 'subject_type' },
                { data: 'descriptions', name: 'descriptions' },
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            logTable.ajax.reload();
        });

        $(document).on('change', '#branch_id', function(e) {

            var branch_id = $(this).val();
            getBrandAllowLoginUsers(branch_id)
        });

        function getBrandAllowLoginUsers(branchId) {

            var branchId = branchId ? branchId : 'null';

            var isOnlyAuthenticatedUser = 1;
            var allowAll = 1;
            var url = "{{ route('users.branch.users', [':isOnlyAuthenticatedUser', ':allowAll', ':branchId']) }}";
            var route = url.replace(':isOnlyAuthenticatedUser', isOnlyAuthenticatedUser);
            route = route.replace(':allowAll', allowAll);
            route = route.replace(':branchId', branchId);

            $.ajax({
                url: route,
                type: 'get',
                success: function(data) {

                    $('#user_id').empty();
                    $('#user_id').append('<option value="">@lang('menu.all')</option>');
                    $.each(data, function(key, val) {

                        var userPrefix = val.prefix != null ? val.prefix : '';
                        var userLastName = val.last_name != null ? val.last_name : '';
                        $('#user_id').append('<option value="' + val.id + '">' + userPrefix + ' ' + val.name + ' ' + userLastName + '</option>');
                    });
                }
            })
        }

        // getBrandAllowLoginUsers($('#branch_id').val());
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('from_date'),
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

        new Litepicker({
            singleMode: true,
            element: document.getElementById('to_date'),
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
            format: 'DD-MM-YYYY',
        });
    </script>
@endpush
