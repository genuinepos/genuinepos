@extends('layout.master')
@push('stylesheets')

@endpush
@section('title', 'Price Groups - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __("Selling Price Groups") }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __('All Selling Price Group') }}</h6>
                    </div>

                    @if (auth()->user()->can('selling_price_group_index'))
                        <div class="col-6 d-flex justify-content-end">
                            <a href="{{ route('selling.price.groups.create') }}" class="btn btn-sm btn-primary" id="addPriceGroup"><i class="fas fa-plus-square"></i>{{ __("Add Price Group") }}</a>
                        </div>
                    @endif
                </div>

                <div class="widget_content">
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __("S/L") }}</th>
                                    <th>{{ __("Price Group Name") }}</th>
                                    <th>{{ __("Description") }}</th>
                                    <th>{{ __("Status") }}</th>
                                    <th>{{ __("Action") }}</th>
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
    <div class="modal fade" id="priceGroupAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
    <script>
        var priceGroupsTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-primary', exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> Pdf', className: 'btn btn-primary', exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print', text: '<i class="fas fa-print"></i> Print', className: 'btn btn-primary', exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            processing: true,
            serverSide: true,
            aaSorting: [[0, 'desc']],
            ajax: "{{ route('selling.price.groups.index') }}",
            columns: [
                {data: 'DT_RowIndex',name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'},
            ]
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        $(document).on('click', '#addPriceGroup', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#priceGroupAddOrEditModal').html(data);
                    $('#priceGroupAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#price_group_name').focus();
                    }, 500);
                }, error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#priceGroupAddOrEditModal').empty();
                    $('#priceGroupAddOrEditModal').html(data);
                    $('#priceGroupAddOrEditModal').modal('show');
                    $('.data_preloader').hide();
                    setTimeout(function() {

                        $('#price_group_name').focus().select();
                    }, 500);
                }
                , error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
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
                    'Yes': {'class': 'yes btn-modal-primary','action': function() { $('#deleted_form').submit(); }},
                    'No': {'class': 'no btn-danger','action': function() { console.log('Deleted canceled.'); }}
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
                    priceGroupsTable.ajax.reload(null, false);
                    $('#deleted_form')[0].reset();
                },error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    }else{

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });


         $(document).on('click', '#change_status', function(e) {
            e.preventDefault();
            var url = $(this).data('url');

            $.confirm({
                'title': 'Changes Status',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'Yes btn-danger',
                        'action': function() {
                            $.ajax({
                                url: url,
                                type: 'GET',
                                success: function(data) {

                                    if (!$.isEmptyObject(data.errorMsg)) {

                                        toastr.error(data.errorMsg);
                                        return;
                                    }

                                    toastr.success(data);
                                    priceGroupsTable.ajax.reload(null, false);
                                }
                            });
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {
                            // console.log('Confirmation canceled.');
                        }
                    }
                }
            });
        });
    </script>
@endpush
