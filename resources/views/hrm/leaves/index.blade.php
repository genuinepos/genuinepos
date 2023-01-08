@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                                    <th class="text-start">@lang('menu.leave_no')</th>
                                    <th class="text-start">@lang('menu.type')</th>
                                    <th class="text-start">{{ __('Employee') }}</th>
                                    <th class="text-start">@lang('menu.start_date')</th>
                                    <th class="text-start">@lang('menu.end_date')</th>
                                    <th class="text-start">@lang('menu.reason')</th>
                                    <th class="text-start">@lang('menu.status')</th>
                                    <th class="text-start">@lang('menu.action')</th>
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
                    <form id="add_leave_form" action="{{ route('hrm.leaves.store') }}">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label><b>@lang('menu.department') :</b></label>
                                <select class="form-control" name="department_id" id="department_id">
                                    <option value="all"> @lang('menu.all') </option>
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
                                <span class="error error_employee_id"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-6">
                                <label><b>@lang('menu.leave_type') :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="leave_type_id" required id="leave_id">
                                    <option value="">{{ __('Select Leave Type') }}</option>
                                    @foreach ($leaveTypes as $lt)
                                        <option value="{{ $lt->id }}">{{ $lt->leave_type }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_employee_id"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-6">
                                <label><b>@lang('menu.start_date') :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="start_date" required class="form-control" id="start_date" autocomplete="off" placeholder="@lang('menu.start_date')">
                            </div>

                            <div class="form-group col-6">
                              <label><b>@lang('menu.end_date') :</b> <span class="text-danger">*</span></label>
                              <input type="text" name="end_date" required class="form-control" id="end_date" autocomplete="off" placeholder="@lang('menu.end_date')">
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-12">
                                <label><b>@lang('menu.reason') :</b> </label>
                                <textarea type="text" name="reason" class="form-control" placeholder="@lang('menu.reason')"></textarea>
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
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',className: 'btn btn-primary',autoPrint: true,exportOptions: {columns: ':visible'}}
        ],
        "pageLength": parseInt("{{ $generalSettings['system']['datatable_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('hrm.leaves.index') }}",
        columns: [
            {data: 'leave_no',name: 'hrm_leaves.leave_no'},
            {data: 'leave_type',name: 'hrm_leavetypes.leave_type'},
            {data: 'employee',name: 'users.name'},
            {data: 'start_date',name: 'hrm_leaves.start_date'},
            {data: 'end_date',name: 'hrm_leaves.end_date'},
            {data: 'reason',name: 'hrm_leaves.reason'},
            {data: 'status',name: 'users.last_name'},
            {data: 'action'},
        ],
    });

    $('#department_id').on('change', function(e){
        e.preventDefault();

        var department_id = $(this).val();

        $.ajax({
            url:"{{ url('hrm/leaves/department/employees/') }}"+"/"+department_id,
            type:'get',
            success:function(employees){

                $('#employee_id').empty();
                $('#employee_id').append('<option value="">Select Employee</option>');

                $.each(employees, function (key, emp) {

                    emp.prefix = emp.prefix || '';
                    emp.name = emp.name || '';
                    emp.last_name = emp.last_name || '';
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
            $('.submit_button').prop('type', 'button');
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){

                    toastr.success(data);
                    table.ajax.reload();
                    $('#addModal').modal('hide');
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                },error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');
                    $('.submit_button').prop('type', 'submit');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();

            $('.error').html('');

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url : url,
                type : 'get',
                success: function(data) {

                    $('#editModal').html(data);
                    $('#editModal').modal('show');
                    $('.data_preloader').hide();
                },error:function(err){

                    $('.data_preloader').hide();

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error.');
                    }else{

                        toastr.error('Server Error, Please contact to the support team.');
                    }
                }
            });
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();

            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);

            $.confirm({
                'title': '@lang("menu.confirmation")',
                'message' : '@lang("menu.delete_permission_msg")',
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
                async : false,
                data:request,
                success:function(data){

                    table.ajax.reload();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });
    });

    var dateFormat = "{{ $generalSettings['business']['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('start_date'),
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
        tooltipNumber : (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('end_date'),
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
        tooltipNumber : (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });
</script>
@endpush
