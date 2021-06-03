@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
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
                                    <ul >
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
                                                <a href="{{ route('hrm.leave') }}" class="text-primary"><i class="fas fa-level-down-alt"></i> <b>@lang('menu.leave')</b></a>
                                            </li>
                                        @endif

                                        <li>
                                            <a href="{{ route('hrm.attendance.shift') }}" class="text-dark text-muted"><i class="fas fa-network-wired"></i> <b>@lang('menu.shift')</b></a>
                                        </li>

                                        <li>
                                            <a href="{{ route('hrm.attendance') }}" class="text-dark text-muted"><i class="fas fa-paste"></i> <b>@lang('menu.attendance')</b></a>
                                        </li>

                                        <li>
                                            <a href="{{ route('hrm.allowance') }}" class="text-dark text-muted"><i class="fas fa-plus"></i> <b>@lang('menu.allowance_deduction')</b></a>
                                        </li>

                                        <li>
                                            <a href="{{ route('hrm.payroll.index') }}" class="text-dark text-muted"><i class="far fa-money-bill-alt"></i> <b>@lang('menu.payroll')</b></a>
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
                    <!-- =========================================top section button=================== -->

                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>Leaves</h6>
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
                                                    <th>Serial</th>
                                                    <th>Type</th>
                                                    <th>Max leave</th>
                                                    <th>Leave Count Interval</th>
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
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Leave</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_leave_form" action="{{ route('hrm.leave.store') }}">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label><b>Department :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="department_id" id="department_id">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label><b>Employee :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="employee_id" id="employee_id" required>
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->prefix.' '.$emp->name.' '.$emp->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-6">
                                <label><b>Leave Type :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="leave_id" required id="leave_id">
                                    <option value="">Select Leave Type</option>
                                    @foreach ($leavetypes as $lt)
                                        <option value="{{ $lt->id }}">{{ $lt->leave_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-6">
                                <label><b>Start Date :</b> <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" required="" class="form-control">
                            </div>

                            <div class="form-group col-6">
                              <label><b>End Date :</b> <span class="text-danger">*</span></label>
                              <input type="date" name="end_date" required class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-12">
                                <label><b>Reason :</b> </label>
                                <textarea type="text" name="reason" class="form-control" placeholder="Reason"></textarea>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Leave</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_designation_form" action="{{ route('hrm.leave.update') }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label><b>Department :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="department_id" id="e_department_id">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label><b>Employee :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="employee_id" id="e_employee_id" required="">
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->prefix.' '.$emp->name.' '.$emp->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-6">
                                <label><b>Leave Type :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="leave_id" required id="e_leave_id">
                                    <option value="">Select Leave Type</option>
                                    @foreach ($leavetypes as $lt)
                                        <option value="{{ $lt->id }}">{{ $lt->leave_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-6">
                                <label><b>Start Date :</b> <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="e_start_date" required="" class="form-control">
                            </div>

                            <div class="form-group col-6">
                              <label><b>End Date :</b> <span class="text-danger">*</span></label>
                              <input type="date" name="end_date" id="e_end_date" required="" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-12">
                                <label><b>Reason :</b> </label>
                                <textarea type="text" name="reason" id="e_reason" class="form-control" placeholder="Reason"></textarea>
                            </div>
                        </div>

                        <div class="form-group text-right mt-3">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="me-0 c-btn btn_blue float-end">Save Change</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

<script>
    // Get all category by ajax
    function getAllLeave(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('hrm.leave.all') }}",
            type:'get',
            success:function(data){
                $('.table-responsive').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllLeave();

    $('#department_id').on('change', function(e){
        e.preventDefault();
        var department_id = $(this).val();
        console.log(department_id);
        $.ajax({
            url:"{{ url('hrm/leave/department/employees/') }}"+"/"+department_id,
            type:'get',
            success:function(employees){
                $('#employee_id').empty();
                $('#employee_id').append('<option value="">Select Employee</option>');
                $.each(employees, function (key, emp) {
                    $('#employee_id').append('<option value="'+emp.id+'">'+ emp.prefix+' '+emp.name+' '+emp.last_name +'</option>');
                });
            }
        });
    });

    $('#e_department_id').on('change', function(e){
        e.preventDefault();
        var department_id = $(this).val();
        console.log(department_id);
        $.ajax({
            url:"{{ url('hrm/leave/department/employees/') }}"+"/"+department_id,
            type:'get',
            success:function(employees){
                $('#e_employee_id').empty();
                $('#e_employee_id').append('<option value="">Select Employee</option>');
                $.each(employees, function (key, emp) {
                    $('#e_employee_id').append('<option value="'+emp.id+'">'+ emp.prefix+' '+emp.name+' '+emp.last_name +'</option>');
                });
            }
        });
    });

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method 
    $(document).ready(function(){
        // Add department by ajax
        $('#add_leave_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data, 'Succeed');
                    getAllLeave();
                    $('#addModal').modal('hide');
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            $('.error').html('');
            var data = $(this).closest('tr').data('info');
            $('#id').val(data.id);
            $('#e_start_date').val(data.start_date);
            $('#e_end_date').val(data.end_date);
            $('#e_reason').val(data.reason);
            $('#e_employee_id').val(data.employee_id);
            $('#e_leave_id').val(data.leave_id);
            $('#e_department_id').val(data.admin_and_user.department_id);
            $('#editModal').modal('show');
        });

        // edit submit form by ajax
        $('#edit_designation_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
          
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    console.log(data);
                    toastr.success(data, 'Succeed');
                    $('.loading_button').hide();
                    getAllLeave();
                    $('#editModal').modal('hide'); 
                }
            });
        });

        // Show sweet alert for delete
        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            swal({
                title: "Are you sure?",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) { 
                    $('#deleted_form').submit();
                } else {
                    swal("Your imaginary file is safe!");
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
                    getAllLeave();
                    toastr.success(data, 'Succeed');
                    $('#deleted_form')[0].reset();
                }
            });
        });
    });
</script>
@endpush
