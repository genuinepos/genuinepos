@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-desktop"></span>
                                <h5>@lang('menu.customer_group')</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->

                    <div class="p-3">
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <div class="card" id="add_form">
                                    <div class="section-header">
                                        <div class="col-md-6">
                                            <h6>@lang('menu.add_customer_group')</h6>
                                        </div>
                                    </div>

                                    <div class="form-area px-3 pb-2">
                                        <form id="add_group_form" action="{{ route('contacts.customers.groups.store') }}" method="POST">
                                            <div class="form-group mt-2">
                                                <label><strong>@lang('menu.name') :</strong> <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control add_input"
                                                    data-name="Group name" id="name" placeholder="Group name" required/>
                                                <span class="error error_name"></span>
                                            </div>

                                            <div class="form-group mt-2">
                                                <label><strong>@lang('menu.calculation_percent') (%) :</strong></label>
                                                <input type="number" step="any" name="calculation_percent" class="form-control" step="any"
                                                    id="calculation_percent" placeholder="@lang('menu.calculation_percent')" autocomplete="off" />
                                                    <span class="error error_calculation_percent"></span>
                                            </div>

                                            <div class="form-group row mt-3">
                                                <div class="col-md-12 d-flex justify-content-end">
                                                    <div class="btn-loading">
                                                        <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner text-primary"></i><span> @lang('menu.loading')...</span></button>
                                                        <button type="reset" class="btn btn-sm btn-danger">@lang('menu.reset')</button>
                                                        <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="card d-hide" id="edit_form">

                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="section-header">
                                        <div class="col-md-6">
                                            <h6>@lang('menu.all_customer_group')</h6>
                                        </div>
                                    </div>

                                    <div class="widget_content">
                                        <div class="data_preloader">
                                            <h6><i class="fas fa-spinner"></i> @lang('menu.processing')...</h6>
                                        </div>
                                        <div class="table-responsive" id="data-list">
                                            <table class="display data_tbl data__table customerGroup_table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('menu.serial')</th>
                                                        <th>@lang('menu.name')</th>
                                                        <th>@lang('menu.calculation_percent')</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Get all customer group by ajax
        var customerGroup_table = $('.customerGroup_table').DataTable({
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
        ajax: "{{ route('contacts.customers.groups.index') }}",
        columns: [
            {data: 'DT_RowIndex',name: 'DT_RowIndex'},
            {data: 'group_name',name: 'group_name'},
            {data: 'calc_percentage',name: 'calc_percentage'},
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
            // Add Customer Group by ajax
            $('#add_group_form').on('submit', function(e) {
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
                        $('#add_group_form')[0].reset();
                        $('.loading_button').hide();
                        customerGroup_table.ajax.reload();
                        $('#addModal').modal('hide');
                    },
                    error: function(err) {
                            $('.loading_button').hide();
                            $('.error').html('');
                            $.each(err.responseJSON.errors, function(key, error) {
                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('.loading_button').hide();
                        $('#add_form').hide();
                        $('#edit_form').removeClass('d-hide');
                        $('#edit_form').html(data);
                        $('#edit_form').show();
                    }
                })
            });

            // Edit Customer by ajax
            $(document).on('click', '#delete',function(e){
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': 'Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_form').submit();}},
                        'No': {'class': 'no btn-modal-primary','action': function() {console.log('Deleted canceled.');}}
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
                        customerGroup_table.ajax.reload();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });

            $(document).on('click', '#close_form', function() {
                $('#add_form').show();
                $('#edit_form').hide();
                $('.error').html('');
            });
        });
    </script>
@endpush
