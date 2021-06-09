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
                                            <a href="{{ route('hrm.attendance.shift') }}" class="text-primary"><i class="fas fa-network-wired"></i> <b>@lang('menu.shift')</b></a>
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
                                        <h6>Shifts</h6>
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
                                                    <th>Shift Name</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
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
                    <h6 class="modal-title" id="exampleModalLabel">Add Shift</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_shift_form" action="{{ route('hrm.shift.store') }}">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><b>Shift Name :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="shift_name" class="form-control" placeholder="Shift Name" required="" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-12">
                                <label><b>Start Time :</b> <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control" placeholder="start time" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-12">
                                <label><b>End Time :</b> <span class="text-danger">*</span></label>
                                <input type="time" name="endtime" class="form-control" placeholder="End Time"/>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn me-0 btn_blue float-end">Save</button>
                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
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
                    <h6 class="modal-title" id="exampleModalLabel">Edit Shift</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_shift_form" action="{{ route('hrm.shift.update') }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><b>Shift Name :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="shift_name" class="form-control" id="e_shift_name" placeholder="Shift Name" required="" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-12">
                                <label><b>Start Time :</b> <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control" id="e_start_time" placeholder="start time" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-12">
                                <label><b>End Time :</b> <span class="text-danger">*</span></label>
                                <input type="time" name="endtime" class="form-control"  id="e_endtime" placeholder="End Time"/>
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
    function getAllShift(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('hrm.shift.all') }}",
            type:'get',
            success:function(data){
                $('.table-responsive').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllShift();

     // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method 
    $(document).ready(function(){
        // Add department by ajax
        $('#add_shift_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            $('.submit_button').hide();
            var url = $(this).attr('action');
            var request = $(this).serialize();
          

            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('#add_shift_form')[0].reset();
                    $('.loading_button').hide();
                    getAllShift();
                    $('#addModal').modal('hide');
                }
            });
        });


        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            $('#edit_shift_form')[0].reset();
            $('.error').html('');
            var typeInfo = $(this).closest('tr').data('info');
            $('#id').val(typeInfo.id);
            $('#e_shift_name').val(typeInfo.shift_name);
            $('#e_start_time').val(typeInfo.start_time);
            $('#e_endtime').val(typeInfo.endtime);
            $('#editModal').modal('show');
        });

        // edit category by ajax
        $('#edit_shift_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            $('.submit_button').hide();
            var url = $(this).attr('action');
            var request = $(this).serialize();
           
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('.loading_button').hide();
                    $('#edit_shift_form')[0].reset();
                    getAllShift();
                    $('#editModal').modal('hide'); 
                }
            });
        });

        // Show sweet alert for delete
        // $(document).on('click', '#delete',function(e){
        //     e.preventDefault();
        //     var url = $(this).attr('href');
        //     $('#deleted_form').attr('action', url);
        //     swal({
        //         title: "Are you sure?",
        //         buttons: true,
        //         dangerMode: true,
        //     })
        //     .then((willDelete) => {
        //         if (willDelete) { 
        //             $('#deleted_form').submit();
        //         } else {
        //             swal("Your imaginary file is safe!");
        //         }
        //     });
        // });
        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);           
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#deleted_form').submit();
                            $('#recent_trans_preloader').show();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
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
                    getAllShift();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });
    });
</script>
@endpush
