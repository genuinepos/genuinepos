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
                    <h5>{{ __("Banks") }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">
            <div class="card">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __("List Of Banks") }}</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        <a href="{{ route('banks.create') }}" class="btn btn-sm btn-primary" id="addBankBtn"><i class="fas fa-plus-square"></i> {{ __("Add") }}</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table bank_table">
                            <thead>
                                <tr>
                                    <th class="text-start">@lang('menu.sl')</th>
                                    <th class="text-start">@lang('menu.name')</th>
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
    <div class="modal fade" id="bankAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>
@endsection
@push('scripts')
    <script>
        var bankTable = $('.bank_table').DataTable({
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
            ajax: "{{ route('banks.index') }}",
            columns: [
                {data: 'DT_RowIndex',name: 'DT_RowIndex'},
                {data: 'name',name: 'name'},
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
            $(document).on('click', '#addBankBtn', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#bankAddOrEditModal').html(data);
                        $('#bankAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#bank_name').focus();
                        }, 500);
                    }
                })
            });

            $(document).on('click', '#editBank', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#bankAddOrEditModal').html(data);
                        $('#bankAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#bank_name').focus().select();
                        }, 500);
                    }
                })
            });

            $(document).on('click', '#deleteBank',function(e){
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

                        bankTable.ajax.reload(false, null);
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });

    </script>
@endpush
