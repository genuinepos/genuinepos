@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
@section('title', 'HRM Shifts - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-network-wired"></span>
                                <h6>{{ __('Shifts') }}</h6>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                                class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="form_element rounded m-0">
                            <div class="section-header">
                                <div class="col-6">
                                    <h6>{{ __('Shifts') }}</h6>
                                </div>

                                <div class="col-6 d-flex justify-content-end">
                                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i>@lang('menu.add')</a>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6></div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table shift_table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Shift Name') }}</th>
                                                <th>@lang('menu.start_time')</th>
                                                <th>@lang('menu.end_time')</th>
                                                <th>@lang('menu.action')</th>
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
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Shift') }}</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_shift_form" action="{{ route('hrm.shift.store') }}">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><b>{{ __('Shift Name') }} </b> <span class="text-danger">*</span></label>
                                <input type="text" name="shift_name" class="form-control" placeholder="{{ __('Shift Name') }}" required="" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-12">
                                <label><b>@lang('menu.start_time') </b> <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control" placeholder="@lang('menu.start_time')" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="form-group col-12">
                                <label><b>@lang('menu.end_time') </b> <span class="text-danger">*</span></label>
                                <input type="time" name="endtime" class="form-control" placeholder="@lang('menu.end_time')"/>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
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
    <div class="modal fade" id="editShiftModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Shift') }}</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_shift_modal_body">
                    <!--begin::Form-->

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

<script>
    var shift_table = $('.shift_table').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: 'Excel', messageTop: 'Asset types', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf', messageTop: 'Asset types', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print', messageTop: '<b>Asset types</b>', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        processing: true,
        serverSide: true,
        searchable: true,
        "lengthMenu" : [25, 100, 500, 1000, 2000],
        ajax: "{{ route('hrm.attendance.shift') }}",
        columns: [
            {data: 'shift_name',name: 'shift_name'},
            {data: 'start_time',name: 'start_time'},
            {data: 'endtime',name: 'endtime'},
            {data: 'action',name: 'action'},
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
                    shift_table.ajax.reload();
                    $('#addModal').modal('hide');
                }
            });
        });

        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#edit_shift_modal_body').html(data);
                    $('#editShiftModal').modal('show');
                }
            });
        });
        $(document).on('submit', '#edit_shift_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            // var request = $(this).serialize();
            console.log(new FormData(this));
            $.ajax({
                url: url,
                type: 'post',
                contentType: false,
                processData: false,
                cache: false,
                data: new FormData(this),
                success: function(data) {
                    console.log(data);
                    toastr.success(data);
                    $('.loading_button').hide();
                    $('#editShiftModal').modal('hide');
                    $('.error').html('');
                    shift_table.ajax.reload();
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_e_' + key + '').html(error[0]);
                    });
                }
            });
        });
        // edit category by ajax
        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
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
                    shift_table.ajax.reload();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });
    });
</script>
@endpush
