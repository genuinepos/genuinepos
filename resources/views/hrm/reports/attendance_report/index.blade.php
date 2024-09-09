@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {
            display: inline-block;
            margin-right: 3px;
        }

        .top-menu-area a {
            border: 1px solid lightgray;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 11px;
        }

        .form-control {
            padding: 4px !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Attendance Report - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Attendance Report') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                </a>
            </div>
        </div>

        <div class="p-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end">
                                    {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                    @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                        <div class="col-md-4">
                                            <label><strong>{{ location_label() }}</strong></label>
                                            <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                <option data-branch_name="All" value="">{{ __('All') }}</option>
                                                <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] . __('Company') }}" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                @foreach ($branches as $branch)
                                                    @php
                                                        $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                        $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                        $branchCode = '-' . $branch->branch_code;
                                                    @endphp
                                                    <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                                        {{ $branchName . $areaName . $branchCode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-md-2">
                                        <label><strong>{{ __('Month & Year') }}</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week"></i></span>
                                            </div>
                                            <input type="month" name="month_year" class="form-control" id="month_year" value="{{ date('Y-m') }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                        <a href="{{ route('reports.attendances.print') }}" class="btn btn-sm btn-primary float-end " id="printReport"><i class="fas fa-print"></i> {{ __('Print') }}</a>
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
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee') }}</th>
                                    @foreach ($datesAndDays as $key => $value)
                                        @php
                                            $__value = explode(' ', $value);
                                            $date = $__value[0];
                                            $day = $__value[1];
                                        @endphp
                                        <th>{!! $date . '<br/>' . $day !!}</th>
                                    @endforeach
                                    <th>{{ __('Total') }}</th>
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

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function getAttendances() {

            var branch_id = $('#branch_id').val();
            var branch_name = $('#branch_id').find('option:selected').data('branch_name');
            var month_year = $('#month_year').val();

            $('.data_preloader').show();

            var route = "{{ route('reports.attendances.list.of.index') }}";

            $.ajax({
                url: route,
                type: 'get',
                data: {
                    branch_id,
                    branch_name,
                    month_year
                },
                success: function(data) {

                    $('.data_preloader').hide();
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    $('#data-list').empty();
                    $('#data-list').html(data);
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        }
        getAttendances();

        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            getAttendances();
        });

        $(document).on('click', '#printReport', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var branch_id = $('#branch_id').val();
            var branch_name = $('#branch_id').find('option:selected').data('branch_name');
            var month_year = $('#month_year').val();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    branch_name,
                    month_year
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                        footer: null,
                    });

                    $('.data_preloader').hide();
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });
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
