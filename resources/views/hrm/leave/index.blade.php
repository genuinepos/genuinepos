@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
    <link rel="stylesheet" href="{{ asset('backend/asset/css/bootstrap-datepicker.min.css') }}">
@endpush
@section('title', 'HRM Leaves - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-level-down-alt"></span>
                    <h6>@lang('menu.leaves')</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                    class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">
            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-6">
                        <h6>@lang('menu.leaves')</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i>@lang('menu.add')</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6></div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>@lang('menu.serial')</th>
                                    <th>@lang('menu.type')</th>
                                    <th>{{ __('Max leave') }}</th>
                                    <th>{{ __('Leave Count Interval') }}</th>
                                    <th>@lang('menu.action')</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_leave')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_leave_form" action="{{ route('hrm.leave.store') }}">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label><b>@lang('menu.department') :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="department_id" id="department_id">
                                    <option value="">@lang('menu.select_department')</option>
                                    @foreach ($departments as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label><b>{{ __('Employee') }} :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="employee_id" id="employee_id" required>
                                    <option value="">{{ __('Select Employee') }}</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->prefix.' '.$emp->name.' '.$emp->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-6">
                                <label><b>@lang('menu.leave_type') :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="leave_id" required id="leave_id">
                                    <option value="">{{ __('Select Leave Type') }}</option>
                                    @foreach ($leavetypes as $lt)
                                        <option value="{{ $lt->id }}">{{ $lt->leave_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-6">
                                <label><b>@lang('menu.start_date') :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="start_date" required class="form-control datepicker" autocomplete="off" placeholder="@lang('menu.start_date')">
                            </div>

                            <div class="form-group col-6">
                              <label><b>@lang('menu.end_date') :</b> <span class="text-danger">*</span></label>
                              <input type="text" name="end_date" required class="form-control datepicker" autocomplete="off" placeholder="@lang('menu.end_date')">
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-12">
                                <label><b>@lang('menu.reason') :</b> </label>
                                <textarea type="text" name="reason" class="form-control" placeholder="Reason"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide">
                                        <i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span>
                                    </button>
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                    <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
                                </div>
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
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Leave') }}</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_designation_form" action="{{ route('hrm.leave.update') }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label><b>@lang('menu.department') :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="department_id" id="e_department_id">
                                    <option value="">@lang('menu.select_department')</option>
                                    @foreach ($departments as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label><b>{{ __('Employee') }} :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="employee_id" id="e_employee_id" required="">
                                    <option value="">{{ __('Select Employee') }}</option>
                                    {{-- @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->prefix.' '.$emp->name.' '.$emp->last_name }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-6">
                                <label><b>@lang('menu.leave_type') :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="leave_id" required id="e_leave_id">
                                    <option value="">{{ __('Select Leave Type') }}</option>
                                    @foreach ($leavetypes as $lt)
                                        <option value="{{ $lt->id }}">{{ $lt->leave_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-6">
                                <label><b>@lang('menu.start_date') :</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="start_date" id="e_start_date" class="form-control datepicker" placeholder="@lang('menu.start_date')" autocomplete="off">
                            </div>

                            <div class="form-group col-6">
                              <label><b>@lang('menu.end_date') :</b> <span class="text-danger">*</span></label>
                              <input required type="text" name="end_date" id="e_end_date" class="form-control datepicker" placeholder="@lang('menu.end_date')" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-12">
                                <label><b>@lang('menu.reason') :</b> </label>
                                <textarea type="text" name="reason" id="e_reason" class="form-control" placeholder="Reason"></textarea>
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-end mt-3">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide">
                                    <i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                <button type="submit" class="btn btn-sm btn-success">@lang('menu.save_change')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{ asset('backend/asset/js/bootstrap-date-picker.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
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
        $.ajax({
            url:"{{ url('hrm/leave/department/employees/') }}"+"/"+department_id,
            type:'get',
            success:function(employees){
                $('#employee_id').empty();
                $('#employee_id').append('<option value="">Select Employee</option>');
                $.each(employees, function (key, emp) {
                    emp.prefix = emp.prefix || '';
                    emp.name = emp.name || '';
                    emp.last_name = emp.last_name || '';
                    // console.log(emp);
                    $('#employee_id').append('<option value="'+emp.id+'">'+ emp.prefix+' '+emp.name+' '+emp.last_name +'</option>');
                });
            }
        });
    });

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
                    toastr.success(data);
                    getAllLeave();
                    $('#addModal').modal('hide');
                    $('.loading_button').hide();
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
            $('#e_department_id').val(data.users.department_id);
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
                    // console.log(data);
                    toastr.success(data);
                    $('.loading_button').hide();
                    getAllLeave();
                    $('#editModal').modal('hide');
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
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });
    });

    var dateFormat = "{{ $generalSettings['business']['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'dd');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'mm');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'yyyy');
    $('.datepicker').datepicker({format: _expectedDateFormat});
</script>
@endpush
