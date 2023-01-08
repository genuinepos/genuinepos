@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Bank List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">

            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-university"></span>
                    <h5>@lang('menu.bank')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">
            <div class="card">
                <div class="section-header">
                    <div class="col-6">
                        <h6>@lang('menu.all_bank')</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addModal" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> @lang('menu.add')</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table bank_table">
                            <thead>
                                <tr>
                                    <th class="text-start">@lang('menu.sl')</th>
                                    <th class="text-start">@lang('menu.bank_name')</th>
                                    <th class="text-start">@lang('menu.branch_name')</th>
                                    <th class="text-start">@lang('menu.address')</th>
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
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_bank')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_bank_form" action="{{ route('accounting.banks.store') }}">
                        <div class="form-group">
                            <label><b>@lang('menu.bank_name')</b> : <span class="text-danger">*</span></label>
                            <input required type="text" name="name" class="form-control form-control-sm add_input" data-name="Bank name" id="name" placeholder="@lang('menu.bank_name')" autofocus/>
                            <span class="error error_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>@lang('menu.branch_name')</b> : <span class="text-danger">*</span></label>
                            <input required type="text" name="branch_name" class="form-control form-control-sm add_input" data-name="Branch name" id="branch_name" placeholder="@lang('menu.branch_name')"/>
                            <span class="error error_branch_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>@lang('menu.bank_address')</b> :</label>
                            <textarea name="address" class="form-control form-control-sm"  id="address" cols="10" rows="3" placeholder="@lang('menu.bank_address')"></textarea>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i
                                        class="fas fa-spinner"></i><span> @lang('menu.loading')...</span>
                                    </button>
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                    <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
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
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_bank')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="editModalBody">
                    <!--begin::Form-->
                    
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Get all category by ajax

        var bank_table = $('.bank_table').DataTable({
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
            ajax: "{{ route('accounting.banks.index') }}",
            columns: [
                {data: 'DT_RowIndex',name: 'DT_RowIndex'},
                {data: 'name',name: 'name'},
                {data: 'branch_name',name: 'branch_name'},
                {data: 'address',name: 'address'},
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
            // Add bank by ajax
            $('#add_bank_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                $('.submit_button').prop('type', 'button');
                var url = $(this).attr('action');
                var request = $(this).serialize();
              
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        $('.error').html('');
                        toastr.success(data);
                        bank_table.ajax.reload();
                        $('#add_bank_form')[0].reset();
                        $('.loading_button').hide();
                        $('#addModal').modal('hide');
                        $('.submit_button').prop('type', 'sumbit');
                    },error: function(err) {
                        $('.error').html('');
                        $('.loading_button').hide();
                        $('.submit_button').prop('type', 'sumbit');

                        if (err.status == 0) {
                            toastr.error('Net Connetion Error.');
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
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#editModalBody').html(data);
                        $('#editModal').modal('show');
                        $('.data_preloader').hide();
                    }
                });
            });

            // edit bank by ajax
            

            $(document).on('click', '#delete',function(e){
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': 'Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_form').submit();}},
                        'No': {'class': 'no btn-modal-primary','action': function() { console.log('Deleted canceled.');}}
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
                        bank_table.ajax.reload();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });

    </script>
@endpush
