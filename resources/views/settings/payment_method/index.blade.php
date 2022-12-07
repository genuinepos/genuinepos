@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Payment Methods - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-glass-whiskey"></span>
                    <h5>@lang('menu.payment_method')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i>@lang('menu.back')
                </a>
            </div>

            <div class="p-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card" id="add_form">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>Add Payment Method</h6>
                                </div>
                            </div>

                            <form id="add_payment_method_form" class="p-2" action="{{ route('settings.payment.method.store') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label><b>Method Name :</b> <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Payment Method Name" />
                                        <span class="error error_name"></span>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="btn-loading">
                                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                            <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card" id="edit_form" style="display: none;">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>Edit Payment Method</h6>
                                </div>
                            </div>

                            <div class="form-area" id="edit_form_body"></div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>All Payment Methods</h6>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.serial')</th>
                                                <th>Payment Method Name</th>
                                                <th>Actions</th>
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
@endsection
@push('scripts')
<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: 'Excel', messageTop: 'Payment Card types', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf', messageTop: 'Payment Card types', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print', messageTop: '<b>Payment Card types</b>', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('settings.payment.method.index') }}",
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
            {data: 'name',name: 'name'},
            {data: 'action',name: 'action'},
        ],
    });

    $(document).on('submit', '#add_payment_method_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $('.submit_button').prop('type', 'button');
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('#add_payment_method_form')[0].reset();
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');
                $('#add_form').show();
                $('#edit_form').hide();
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
                $('#edit_form_body').html(data);
                $('#add_form').hide();
                $('#edit_form').show();
            }
        });
    });

    $(document).on('submit', '#edit_payment_method_form', function(e) {
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
                $('.error').html('');
                $('#add_form').show();
                $('#edit_form').hide();
                table.ajax.reload();
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
                    'class': 'yes btn-danger',
                    'action': function() {$('#deleted_form').submit();}
                },
                'No': {'class': 'no btn-modal-primary','action': function() {console.log('Deleted canceled.');}
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
</script>

@endpush
