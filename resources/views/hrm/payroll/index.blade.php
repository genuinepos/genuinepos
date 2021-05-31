@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
        .form-control {padding: 4px!important;}
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="top-menu-area">
                                        <ul class="list-unstyled">
                                            <li>
                                                <a href="" class="text-dark text-muted"><i class="fas fa-tachometer-alt"></i> <b>HRM</b></a>
                                            </li>
                                            
                                            @if (auth()->user()->permission->hrms['leave_type'] == '1')
                                                <li>
                                                    <a href="{{ route('hrm.leave.type') }}" class="text-dark text-muted"><i class="fas fa-th-large"></i> <b>Leave Types</b></a>
                                                </li>
                                            @endif

                                            @if (auth()->user()->permission->hrms['leave_approve'] == '1')
                                                <li>
                                                    <a href="{{ route('hrm.leave') }}" class="text-dark text-muted"><i class="fas fa-level-down-alt"></i> <b>@lang('menu.leave')</b></a>
                                                </li>
                                            @endif
                                            
                                            <li>
                                                <a href="{{ route('hrm.attendance.shift') }}" class="text-dark text-muted"><i class="fas fa-network-wired"></i> <b>@lang('menu.shift')</b></a>
                                            </li>
                                            
                                            @if (auth()->user()->permission->hrms['attendance_all'] == '1')
                                                <li>
                                                    <a href="{{ route('hrm.attendance') }}" class="text-dark text-muted"><i class="fas fa-paste"></i> <b>@lang('menu.attendance')</b></a>
                                                </li>
                                            @endif

                                            <li>
                                                <a href="{{ route('hrm.allowance') }}" class="text-dark text-muted"><i class="fas fa-plus"></i> <b>@lang('menu.allowance_deduction')</b></a>
                                            </li>

                                            <li>
                                                <a href="{{ route('hrm.payroll.index') }}" class="text-primary "><i class="far fa-money-bill-alt"></i> <b>@lang('menu.payroll')</b></a>
                                            </li>

                                            <li>
                                                <a href="{{ route('hrm.holidays') }}" class="text-dark text-muted"><i class="fas fa-toggle-off"></i> <b>@lang('menu.holiday')</b></a>
                                            </li>

                                            <li>
                                                <a href="{{ route('hrm.departments') }}" class="text-dark text-muted"><i class="far fa-building"></i> <b>@lang('menu.department')</b></a>
                                            </li>

                                            <li>
                                                <a href="{{ route('hrm.designations') }}" class="text-dark text-muted"><i class="fas fa-map-marker-alt"></i> <b>@lang('menu.designation')</b></a>
                                            </li>

                                            <li>
                                                <a href="{{ route('hrm.designations') }}" class="text-dark text-muted"><i class="fas fa-sliders-h"></i> <b>@lang('menu.hrm_settings')</b></a>
                                            </li>
                                        </ul>
                                    </div>
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
                                        <h6>All Payrolls</h6>
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
                                                    <th>Employee</th>
                                                    <th>Department</th>
                                                    <th>Designation</th>
                                                    <th>Month/Year</th>
                                                    <th>Referance No</th>
                                                    <th>Total Amount</th>
                                                    <th>Payment Status</th>
                                                    <th>Created By</th>
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
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Select Employee & Month</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form  action="{{ route('hrm.payroll.create') }}" method="get">
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
                                <select name="user_id" class="form-control" id="employee">
                                    <option disabled selected> Select Employee </option>
                                    @foreach($employee as $row)
                                       <option value="{{ $row->id }}">{{$row->prefix.' '.$row->name.' '.$row->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><strong>Month/Year :</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week"></i></span>
                                    </div>
                                    <input type="month" name="month_year" class="form-control" autocomplete="off" placeholder="Month-Year">
                                </div>
                            </div>
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

    <!--Payment View modal-->
    <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="modal-title" id="exampleModalLabel">Payroll Payments</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="payment_view_modal_body">
                
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="modal-title" id="modal_title">Add Payment</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>

                <div class="modal-body" id="payment_modal_body">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewPayrollModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content" id="view_payroll_modal_content">

            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Payment Details</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="payment_details_modal_body">
                    
                </div>
                <div class="modal-footer">
                    <div class="form-group text-end">
                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
                        <button type="submit" class="c-btn btn_blue" id="payment_details_print">Print</button>
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
    // Show session message by toster alert.
    @if (Session::has('successMsg'))
        toastr.success('{{ session('successMsg') }}');
    @endif

    var table = $('.data_tbl').DataTable({
        "processing": true,
        "serverSide": true,
        "searching" : true,
        aaSorting: [
            [1, 'asc']
        ],
        "ajax": {
            "url": "{{ route('hrm.payroll.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.user_id = $('#user_id').val();
                d.date_range = $('#date_range').val();
            }
        },
        columns: [
            {data: 'employee', name: 'employee'},
            {data: 'department_name', name: 'department_name'},
            {data: 'designation_name', name: 'designation_name'},
            {data: 'month_year', name: 'month_year'},
            {data: 'reference_no', name: 'reference_no'},
            {data: 'gross_amount', name: 'total_amount'},
            {data: 'payment_status', name: 'payment_status'},
            {data: 'created_by', name: 'created_by'},
            {data: 'action'},
        ],
    });

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        table.ajax.reload();
    });

    //Submit filter form by date-range field blur 
    $(document).on('blur', '.submit_able_input', function () {
        setTimeout(function() {
            table.ajax.reload();
        }, 800);
    });

    //Submit filter form by date-range apply button
    $(document).on('click', '.applyBtn', function () {
        setTimeout(function() {
            $('.submit_able_input').addClass('.form-control:focus');
            $('.submit_able_input').blur();
        }, 1000);
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

    // Show add payment modal with date 
    $(document).on('click', '#add_payment', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
               $('#modal_title').html('Add Payment');
               $('#payment_modal_body').html(data);
               $('.data_preloader').hide();
               $('#paymentModal').modal('show');
            }
        });
    });

    // //Show payment view modal with data
    $(document).on('click', '#view_payment', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(date){
                $('#payment_view_modal_body').html(date);
                $('#paymentViewModal').modal('show');
                $('.data_preloader').hide();
            }
        });
    });

    // Show payment list modal with date
    $(document).on('click', '#payment_details', function (e) {
        e.preventDefault();
        $('#payment_list_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
               $('#payment_details_modal_body').html(data);
               $('#payment_list_preloader').hide();
               $('#paymentDetailsModal').modal('show');
            }
        });
    });

    // Show add payment modal with date 
    $(document).on('click', '#edit_payment', function (e) {
        e.preventDefault();
        $('#payment_list_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
               $('#payment_modal_body').html(data);
               $('#modal_title').html('Edit Payment');
               $('#payment_list_preloader').hide();
               $('#paymentModal').modal('show');
            }
        });
    });

    //Add sale payment request by ajax
    $(document).on('submit', '#payroll_payment_form', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var available_amount = $('#available_amount').val();
        var paying_amount = $('#p_amount').val();
        if (parseFloat(paying_amount) > parseFloat(available_amount)) {
            $('.error_p_amount').html('Paying amount must not be greater then due amount.');
            $('.loading_button').hide();
            return;
        }

        var url = $(this).attr('action');
        var inputs = $('.p_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');  
            var countErrorField = 0;  
        $.each(inputs, function(key, val){
            var inputId = $(val).attr('id');
            var idValue = $('#'+inputId).val();
            if(idValue == ''){
                countErrorField += 1;
                var fieldName = $('#'+inputId).data('name');
                $('.error_'+inputId).html(fieldName+' is required.');
            }
        });

        if(countErrorField > 0){
            $('.loading_button').hide();
            toastr.error('Please check again all form fields.','Some thing want wrong.'); 
            return;
        }

        $.ajax({
            url:url,
            type:'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(data){
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg,'ERROR'); 
                    $('.loading_button').hide();
                }else{
                    table.ajax.reload();
                    $('.loading_button').hide();
                    $('.modal').modal('hide');
                    toastr.success(data); 
                }
            }
        });
    });

    // Show sweet alert for delete
    $(document).on('click', '#delete',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        swal({
            title: "Are you sure to delete ?",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) { 
                $('#deleted_form').submit();
            } else {
                swal("Your imaginary file is safe!");
            }
        });
    });

    // Show add payment modal with date 
    $(document).on('click', '#view_payroll', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
               $('#view_payroll_modal_content').html(data);
               $('.data_preloader').hide();
               $('#viewPayrollModal').modal('show');
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
            data:request,
            success:function(data){
                table.ajax.reload();
                toastr.success(data);
            }
        });
    });

     // Show sweet alert for delete
     $(document).on('click', '#delete_payment',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#payment_deleted_form').attr('action', url);
        swal({
            title: "Are you sure to delete ?",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) { 
                $('#payment_deleted_form').submit();
            } else {
                swal("Your imaginary file is safe!");
            }
        });
    });
        
    //data delete by ajax
    $(document).on('submit', '#payment_deleted_form',function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'post',
            data:request,
            success:function(data){
                toastr.success(data);
                $('#paymentViewModal').modal('hide');
                table.ajax.reload();
            }
        });
    });

    $(document).on('change', '#payment_method',function () {
        var value = $(this).val();
        $('.payment_method').hide();
        $('#'+value).show();
    });

    $(document).on('click', '.print_payroll',function (e) {
       e.preventDefault(); 
        var body = $('.payroll_print_area').html();
        var footer = $('.signature_area').html();
        $(body).printThis({
            debug: false,                   
            importCSS: true,                
            importStyle: true,          
            loadCSS: "{{asset('public/assets/css/print/payroll.print.css')}}",                      
            removeInline: true, 
            printDelay: 500,
            header : null,   
            footer : footer,      
        });
    });

    $(document).on('click', '#payment_details_print',function (e) {
       e.preventDefault(); 
        var body = $('.payroll_payment_print_area').html();
        var footer = $('.signature_area').html();
        $(body).printThis({
            debug: false,                   
            importCSS: true,                
            importStyle: true,          
            loadCSS: "{{asset('public/assets/css/print/payroll.print.css')}}",                      
            removeInline: true, 
            printDelay: 500,
            header : null,   
            footer : footer,      
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
