@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
        .form-control {padding: 4px!important;}
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css"/>
@endpush
@section('title', 'HRM Attendances - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="breadCrumbHolder module w-100">
                                <div id="breadCrumb3" class="breadCrumb module">
                                    <ul class="list-unstyled">
                                        <li>
                                            <a href="{{ route('hrm.dashboard.index') }}" class="text-white "><i class="fas fa-tachometer-alt"></i> <b>@lang('menu.hrm')</b></a>
                                        </li>
                                        
                                        @if (auth()->user()->permission->hrms['leave_type'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.leave.type') }}" class="text-white "><i class="fas fa-th-large"></i> <b>Leave Types</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['leave_approve'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.leave') }}" class="text-white "><i class="fas fa-level-down-alt"></i> <b>@lang('menu.leave')</b></a>
                                            </li>
                                        @endif
                                        
                                        <li>
                                            <a href="{{ route('hrm.attendance.shift') }}" class="text-white "><i class="fas fa-network-wired"></i> <b>@lang('menu.shift')</b></a>
                                        </li>
                                        
                                        @if (auth()->user()->permission->hrms['attendance_all'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.attendance') }}" class="text-primary"><i class="fas fa-paste"></i> <b>@lang('menu.attendance')</b></a>
                                            </li>
                                        @endif

                                        <li>
                                            <a href="{{ route('hrm.allowance') }}" class="text-white "><i class="fas fa-plus"></i> <b>@lang('menu.allowance_deduction')</b></a>
                                        </li>

                                        <li>
                                            <a href="{{ route('hrm.payroll.index') }}" class="text-white "><i class="far fa-money-bill-alt"></i> <b>@lang('menu.payroll')</b></a>
                                        </li>

                                        <li>
                                            <a href="{{ route('hrm.holidays') }}" class="text-white "><i class="fas fa-toggle-off"></i> <b>@lang('menu.holiday')</b></a>
                                        </li>

                                        <li>
                                            <a href="{{ route('hrm.departments') }}" class="text-white "><i class="far fa-building"></i> <b>@lang('menu.department')</b></a>
                                        </li>

                                        <li>
                                            <a href="{{ route('hrm.designations') }}" class="text-white "><i class="fas fa-map-marker-alt"></i> <b>@lang('menu.designation')</b></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <i class="fas fa-funnel-dollar ms-2"></i> <b>Filter</b>
                                        <form action="" method="get" class="px-2">
                                            <div class="form-group row">
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <div class="col-md-3">
                                                        <label><strong>Branch :</strong></label>
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

                                                <div class="col-md-3">
                                                    <label><strong>Users :</strong></label>
                                                    <select name="user_id"
                                                        class="form-control submit_able" id="user_id" autofocus>
                                                        <option value="">All</option>
                                                        @foreach($employee as $row)
                                                            <option value="{{ $row->id }}">{{$row->prefix.' '.$row->name.' '.$row->last_name }}</option>
                                                        @endforeach
                                                    </select>
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

                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>Attendances <i data-bs-toggle="tooltip" data-bs-placement="right" title="Note: Initially current year's data is available here, if need another year's data go to the data filter." class="fas fa-info-circle tp"></i></h6> 
                                    </div>

                                    <div class="col-md-6">
                                        <div class="btn_30_blue float-end">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                                    class="fas fa-plus-square"></i> Add</a>
                                        </div>
                                    </div>
                                </div>

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
                                                    <th>Clockin note</th>
                                                    <th>Clockout note</th>
                                                    <th>Shift</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
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
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-65-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Attendance</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_attendance_form" action="{{ route('hrm.attendance.store') }}" method="POST"> 
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="text-navy-blue"><b>Department :</b></label>
                                <select  class="form-control employee" required="" id="department_id">
                                    <option> Select Employee </option>
                                    @foreach($departments as $dep)
                                       <option value="{{ $dep->id }}">{{$dep->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="text-navy-blue"><b>Employee :</b></label>
                                <select  class="form-control" id="employee">
                                    <option disabled selected> Select Employee </option>
                                    @foreach($employee as $row)
                                       <option value="{{ $row->id }}">{{$row->prefix.' '.$row->name.' '.$row->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="attendance_table">
                            <div class="data_preloader d-none" id="attendance_row_loader"> <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6></div>
                            <table class="table modal-table table-sm" id="table_data">

                            </table>
                        </div>
                        
                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn me-0 btn_blue float-end">Save</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->

    <!-- Add Modal -->
    <div class="modal fade" id="editAttendanceModel" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-45-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Attendance</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('public') }}/assets/plugins/custom/moment/moment.min.js"></script>
<script src="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.js"></script>
<script>
    var att_table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        "processing": true,
        "serverSide": true,
        "searching" : false,
        aaSorting: [
            [1, 'asc']
        ],
        "ajax": {
            "url": "{{ route('hrm.attendance') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.user_id = $('#user_id').val();
                d.date_range = $('#date_range').val();
            }
        },
        columns: [{data: 'date', name: 'date'},
            {data: 'name', name: 'name'},
            {data: 'clock_in_out', name: 'clock_in_out'},
            {data: 'work_duration', name: 'work_duration'},
            {data: 'clock_in_note', name: 'clock_in_note'},
            {data: 'clock_out_note', name: 'clock_out_note'},
            {data: 'shift_name', name: 'shift_name'},
            {data: 'action'},
        ],
    });

   $('#department_id').on('change', function(e){
        e.preventDefault();
        var department_id = $(this).val();
        console.log(department_id);
        $.ajax({
            url:"{{ url('hrm/leave/department/employees/') }}"+"/"+department_id,
            type:'get',
            success:function(employees){
                $('#employee').empty();
                $('#employee').append('<option value="">Select Employee</option>');
                $.each(employees, function (key, emp) {
                    $('#employee').append('<option value="'+emp.id+'">'+ emp.prefix+' '+emp.name+' '+emp.last_name +'</option>');
                });
            }
        });
    });

    $(document).on('change', '#employee', function () {	    
        var user_id = $(this).val();
        var name = $(this).data('name');
        var count = 0;
        $('.attendance_table table').find('tr').each( function(){
            if ($(this).data('user_id') == user_id) {
                count++;
            }
        });

        if (user_id && count == 0) {
            $('#attendance_row_loader').show();
            $.ajax({
                url:"{{ url('hrm/attendances/get/user/attendance/row') }}"+"/"+user_id,
                type:'get',
                success:function(data){
                    $('#table_data').append(data);
                    $('#attendance_row_loader').hide();
                }
            });
        }
    });

    $(document).on('click', '.btn_remove', function(e){  
        e.preventDefault();
        $(this).closest('tr').remove();   
    });

   // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

   // call jquery method 
   $(document).ready(function(){
       // Add attendance by ajax
       $('#add_attendance_form').on('submit', function(e){
           e.preventDefault();
           $('.loading_button').show();
           var url = $(this).attr('action');
           var request = $(this).serialize();
           var inputs = $('.add_input');
               inputs.removeClass('is-invalid');
               $('.error').html('');  
               var countErrorField = 0;  
           if(countErrorField > 0){
               $('.loading_button').hide();
               return;
           }

           $.ajax({
               url:url,
               type:'post',
               data: request,
               success:function(data){
                   if (!$.isEmptyObject(data.errorMsg)) {
                       toastr.error(data.errorMsg);
                       $('.loading_button').hide();
                   }else{
                       toastr.success(data);
                       $('#add_attendance_form')[0].reset();
                       $('.loading_button').hide();
                       att_table.ajax.reload();
                       $('#addModal').modal('hide');
                       $('#table_data').empty();
                   }
               }
           });
       });

       // Add attendance by ajax
       $(document).on('submit', '#edit_attendance_form', function(e){
           e.preventDefault();
           $('.loading_button').show();
           var url = $(this).attr('action');
           var request = $(this).serialize();
           var inputs = $('.add_input');
               inputs.removeClass('is-invalid');
               $('.error').html('');  
               var countErrorField = 0;  
           if(countErrorField > 0){
               $('.loading_button').hide();
               return;
           }

           $.ajax({
               url:url,
               type:'post',
               data: request,
               success:function(data){
                   toastr.success(data);
                   $('#add_attendance_form')[0].reset();
                   $('.loading_button').hide();
                   att_table.ajax.reload();
                   $('#editAttendanceModel').modal('hide');
                   $('#table_data').empty();
               }
           });
       });

       // Show attendance modal with date
       $(document).on('click', '#edit_attendance', function (e) {
           $('.data_preloader').show();
           e.preventDefault();
           var url = $(this).attr('href');
           $.ajax({
               url : url,
               type:'get',
               success:function (data) {
                   $('#edit_modal_body').html(data);
                   $('#editAttendanceModel').modal('show');
                   $('.data_preloader').hide();
               }
           });
       });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);           
            $.confirm({
                'title': 'Delete Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes bg-primary',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no bg-danger',
                        'action': function() {
                            // alert('Deleted canceled.')
                        } 
                    }
                }
            });
        });

       //data delete by ajax
       $(document).on('submit', '#deleted_form',function(e){
           e.preventDefault();
           var url = $(this).attr('action');
           var request = $(this).serialize();
           $.ajax({
               url:url,
               type:'post',
               async:false,
               data:request,
               success:function(data){
                    att_table.ajax.reload();
                   toastr.error(data);
                   $('#deleted_form')[0].reset();
               }
           });
       });
   });

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        att_table.ajax.reload();
   });

   //Submit filter form by date-range field blur 
   $(document).on('blur', '.submit_able_input', function () {
       setTimeout(function() {
            att_table.ajax.reload();
       }, 500);
   });

       //Submit filter form by date-range apply button
   $(document).on('click', '.applyBtn', function () {
       setTimeout(function() {
           $('.submit_able_input').addClass('.form-control:focus');
           $('.submit_able_input').blur();
       }, 500);
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
    });
</script>
@endpush
