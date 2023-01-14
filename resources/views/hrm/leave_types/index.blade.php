@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border-radius: 3px;font-size: 11px;}
    </style>
@endpush
@section('title', 'HRM Leaves Types - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-th-large"></span>
                    <h6>{{ __('Leave Types') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                    class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">
            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __('Leave Types') }}</h6>
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
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Leave Type') }}</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_leave_type_form" action="{{ route('hrm.leave.type.store') }}">
                        <div class="form-group">
                            <label><b>@lang('menu.leave_type') :</b> <span class="text-danger">*</span></label>
                            <input required type="text" name="leave_type" class="form-control add_input" data-name="leave type" id="leave_type" placeholder="Leave Type" required="" />
                            <span class="error error_leave_type"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>{{ __('Max leave count') }} :</b> <span class="text-danger">*</span></label>
                            <input required type="text" name="max_leave_count" class="form-control add_input" data-name="max leave count" id="max_leave_count" placeholder="{{ __('Max leave count') }}t"  />
                            <span class="error error_max_leave_count"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>{{ __('Leave Count Interval') }} :</b> </label>
                            <select name="leave_count_interval" class="form-control">
                            	<option value="0">@lang('menu.none')</option>
                            	<option value="1">{{ __('Current Month') }}</option>
                            	<option value="2">{{ __('Current Financial Year') }}</option>
                            </select>
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
<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',className: 'btn btn-primary',autoPrint: true,exportOptions: {columns: ':visible'}}
        ],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('hrm.leave.type.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'leave_type',name: 'leave_type'},
            {data: 'max_leave_count',name: 'max_leave_count'},
            {data: 'leave_count_interval',name: 'leave_count_interval'},
            {data: 'action'},
        ],
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
        $('#add_leave_type_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            $('.submit_button').prop('type', 'submit');
            var request = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    
                    table.ajax.reload();
                    toastr.success(data);
                    $('#add_leave_type_form')[0].reset();
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    $('#addModal').modal('hide');
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
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#editModal').html(data);
                    $('#editModal').modal('show');
                    $('.data_preloader').hide();
                },error:function(err){

                    $('.data_preloader').hide();

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
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
                'title': 'Confirmation',
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
                    table.ajax.reload();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });
    });
</script>
@endpush
