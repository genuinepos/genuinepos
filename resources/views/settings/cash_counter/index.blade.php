@extends('layout.master')
@push('stylesheets')@endpush
@section('title', 'All Cash Counter - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-cubes"></span>
                    <h5>Cash Counters</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button"><i
                        class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
            </div>
        </div>

        <div class="p-3">
            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-md-6">
                        <h6>All Cash Counter</h6>
                    </div>

                    <div class="col-md-6 d-flex justify-content-end">
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> Add</a>
                    </div>

                </div>

                <div class="widget_content">
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr class="bg-navey-blue">
                                    <th class="text-black">Serial</th>
                                    <th class="text-black">Counter Name</th>
                                    <th class="text-black">Short Name</th>
                                    <th class="text-black">Branch</th>
                                    <th class="text-black">@lang('menu.action')</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Cash Counter</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_cash_counter_form" action="{{ route('settings.payment.cash.counter.store') }}" method="POST"
                        enctype="multipart/form-data">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><b>Counter Name :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="counter_name" class="form-control" id="counter_name"
                                    placeholder="Cash Counter name"/>
                                <span class="error error_counter_name"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <label for=""><b>Short Name :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="short_name" class="form-control" id="short_name" placeholder="Short Name">
                                <span class="error error_short_name"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                    <button type="submit" class="btn btn-sm btn-success submit_button">Save</button>
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
                    <h6 class="modal-title" id="exampleModalLabel">Edit Cash Counter</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body">
                    <!--begin::Form-->

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            processing: true,
            serverSide: true,
            searchable: true,
            ajax: "{{ route('settings.cash.counter.index') }}",
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
                {data: 'counter_name',name: 'counter_name'},
                {data: 'short_name',name: 'short_name'},
                {data: 'branch',name: 'branch'},
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
        $(document).ready(function() {
            // Add category by ajax
            $(document).on('submit', '#add_cash_counter_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                $('.submit_button').prop('type', 'button');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        $('.error').html('');
                        $('.loading_button').hide();
                        $('.submit_button').prop('type', 'submit');
                        if (!$.isEmptyObject(data.errorMsg)) {
                            toastr.error(data.errorMsg);
                            return;
                        }

                        $('#addModal').modal('hide');
                        toastr.success(data);
                        $('#add_cash_counter_form')[0].reset();
                        table.ajax.reload();
                    },
                    error: function(err) {

                        $('.loading_button').hide();
                        $('.error').html('');

                        $.each(err.responseJSON.errors, function(key, error) {

                            $('.error_' + key + '').html(error[0]);
                        });

                        $('.submit_button').prop('type', 'submit');
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#edit_modal_body').html(data);
                        $('#editModal').modal('show');
                    }
                });
            });

            // edit Cash counter by ajax
            $(document).on('submit', '#edit_cash_counter_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#editModal').modal('hide');
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
            $(document).on('submit', '#deleted_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    async: false,
                    data: request,
                    success: function(data) {
                        toastr.error(data);
                        table.ajax.reload();
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });
    </script>
@endpush
