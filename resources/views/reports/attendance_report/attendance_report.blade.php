@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
        .form-control {padding: 4px!important;}
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css"/>
@endpush
@section('title', 'Attendance Report - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="far fa-file-alt"></span>
                                <h5>Attendance Report</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_form" class="px-2">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-3">
                                                            <label><strong>Business Location :</strong></label>
                                                            <select name="branch_id"
                                                                class="form-control submit_able" id="branch_id" autofocus>
                                                                <option value="">All</option>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif
                                                
                                                <div class="col-md-3">
                                                    <label><strong>Department :</strong></label>
                                                    <select name="department_id"
                                                        class="form-control submit_able" id="department_id" autofocus>
                                                        <option value="">All</option>
                                                        @foreach ($departments as $department)
                                                            <option value="{{ $department->id }}">
                                                                {{ $department->department_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <label><strong>From Date :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="datepicker"
                                                            class="form-control from_date date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>Date Range :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input readonly type="text" name="date_range" id="date_range"
                                                            class="form-control daterange submit_able_input"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->
               
                    <div class="row mt-1">
                        <div class="card">
                            <div class="widget_content">
                                <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6></div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Employee</th>
                                                <th>Clock IN - CLock Out</th>
                                                <th>Work Duration</th>
                                                <th>Shift</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                    <a href="{{ route('reports.attendance.print') }}" class="btn btn-sm btn-primary float-end" id="print_report"><i class="fas fa-print"></i> Print</a>
                                </div>
                            </div>

                            <form id="deleted_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
               
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('public') }}/assets/plugins/custom/moment/moment.min.js"></script>
<script src="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.js"></script>
<script src="{{ asset('public') }}/assets/plugins/custom/print_this/printThis.js"></script>
<script>
     var att_table = $('.data_tbl').DataTable({
        "processing": true,
        "serverSide": true,
        "searching" : false,
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: 'Export To Excel',className: 'btn btn-primary',},
            {extend: 'pdf',text: 'Export To Pdf',className: 'btn btn-primary',},
        ],
        aaSorting: [[1, 'asc']],
        "lengthMenu" : [50, 100, 500, 1000, 2000],
        "ajax": {
            "url": "{{ route('reports.attendance') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.department_id = $('#department_id').val();
                d.from_date = $('.from_date').val();
                d.to_date = $('.to_date').val();
            }
        },
        columns: [{data: 'date', name: 'date'},
            {data: 'name', name: 'admin_and_users.name'},
            {data: 'clock_in_out', name: 'clock_in_out'},
            {data: 'work_duration', name: 'work_duration'},
            {data: 'shift_name', name: 'hrm_shifts.shift_name'},
        ],
    });

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        att_table.ajax.reload();
    });

    //Submit filter form by date-range field blur 
    $(document).on('blur', '.submit_able_input', function () {
        setTimeout(function() {
            att_table.ajax.reload();
        }, 800);
    });

    //Submit filter form by date-range apply button
    $(document).on('click', '.applyBtn', function () {
        setTimeout(function() {
            $('.submit_able_input').addClass('.form-control:focus');
            $('.submit_able_input').blur();
        }, 1000);
    });


    $(document).on('click', '#print_report',function (e) {
        e.preventDefault(); 
        $('.data_preloader').show();
        var branch_id = $('#branch_id').val();
        var department_id = $('#department_id').val();
        var from_date = $('.tofrom_date_date').val();
        var to_date = $('.to_date').val();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            data: { branch_id, department_id, from_date, to_date },
            success:function(data){
                $(data).printThis({
                    debug: false,                   
                    importCSS: true,                
                    importStyle: true,          
                    loadCSS: "{{ asset('public/assets/css/print/sale.print.css') }}",                      
                    removeInline: true, 
                    printDelay: 500,
                    header : null,   
                    footer : null,      
                });
                $('.data_preloader').hide();
            }
        });
    });    
</script>

<script type="text/javascript">
    $(function() {
        var start = moment().startOf('year');
        var end = moment().endOf('year');
        $('.daterange').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            startDate: start,
            endDate: end,
            locale: {cancelLabel: 'Clear'},
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year').subtract(1, 'year')],
            }
        });
        $('.daterange').val('');
    });

    $(document).on('click', '.cancelBtn ', function () {
        $('.daterange').val('');
    });
</script>
@endpush
