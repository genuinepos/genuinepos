@extends('layout.master')
@push('stylesheets')@endpush
@section('title', 'All Cash Counter - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-cubes"></span>
                    <h5>@lang('menu.cash_counter')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                        class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">
            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-7">
                        <h6>{{ __('All Cash Counter') }}</h6>
                    </div>

                    <div class="col-5 d-flex justify-content-end">
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i>@lang('menu.add')</a>
                    </div>

                </div>

                <div class="widget_content">
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr class="bg-navey-blue">
                                    <th class="text-black">@lang('menu.serial')</th>
                                    <th class="text-black">@lang('menu.counter_name')</th>
                                    <th class="text-black">@lang('menu.short_name')</th>
                                    <th class="text-black">@lang('menu.branch')</th>
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
    <div class="modal fade" id="cashCounterAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>

@endsection
@push('scripts')
    <script>
        var cashCounterTable = $('.data_tbl').DataTable({
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

            $(document).on('click', '#addBtn', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    cache: false,
                    async: false,
                    success: function(data) {

                        $('#cashCounterAddOrEditModal .modal-dialog').remove();
                        $('#cashCounterAddOrEditModal').html(data);
                        $('#cashCounterAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#cash_counter_name').focus();
                        }, 500);

                        $('.data_preloader').hide();

                    },
                    error: function(err) {

                        $('.data_preloader').hide();
                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                        } else {

                            toastr.error('Server Error. Please contact to the support team.');
                        }
                    }
                });
            });

            $(document).on('click', '#edit', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    cache: false,
                    async: false,
                    success: function(data) {

                        $('#cashCounterAddOrEditModal .modal-dialog').remove();
                        $('#cashCounterAddOrEditModal').html(data);
                        $('#cashCounterAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#cash_counter_name').focus().select();
                        }, 500);

                        $('.data_preloader').hide();

                    },
                    error: function(err) {

                        $('.data_preloader').hide();
                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                        } else {

                            toastr.error('Server Error. Please contact to the support team.');
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
                        cashCounterTable.ajax.reload();
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });
    </script>
@endpush