@extends('layout.master')
@push('stylesheets')@endpush
@section('title', 'Brands - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-band-aid"></span>
                    <h5>@lang('menu.brands')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-1">
            <div class="row g-lg-3 g-1">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>{{ __("List Of Brands") }}</h6>
                            </div>

                            <div class="col-6 d-flex justify-content-end">
                                @if (auth()->user()->can('brand'))
                                    <a href="{{ route('brands.create') }}" class="btn btn-sm btn-primary" id="addBrand"><i class="fas fa-plus-square"></i> {{ __("Add Brand") }}</a>
                                @endif
                            </div>
                        </div>

                        <div class="widget_content">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                            </div>
                            <div class="table-responsive" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th>{{ __("Serial") }}</th>
                                            <th>{{ __("Photo") }}</th>
                                            <th>{{ __("Name") }}</th>
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
        </div>
    </div>

    <div class="modal fade" id="brandAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
    <script>
        // Get all brands by ajax
        var brandsTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                //{extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: 'Pdf', className: 'btn btn-primary', exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: 'Print', className: 'btn btn-primary', exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            processing: true,
            serverSide: true,
            searchable: true,
            ajax: "{{ route('brands.index') }}",
            columnDefs: [{
                "targets": [0, 1, 3],
                "orderable": false,
                "searchable": false
            }],
            columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
                {data: 'photo',name: 'photo'},
                {data: 'name',name: 'name'},
                {data: 'action',name: 'action'},
            ]
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {

            $(document).on('click', '#addBrand', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#brandAddOrEditModal').html(data);
                        $('#brandAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#brand_name').focus();
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
                    url: url
                    , type: 'get'
                    , success: function(data) {

                        $('#brandAddOrEditModal').empty();
                        $('#brandAddOrEditModal').html(data);
                        $('#brandAddOrEditModal').modal('show');
                        $('.data_preloader').hide();
                        setTimeout(function() {

                            $('#brand_name').focus().select();
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
                    'title': '@lang("brand.delete_alert")',
                    'content': 'Are you sure?',
                    'buttons': {
                        'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}
                        },'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
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

                        brandsTable.ajax.reload();
                        toastr.error(data);
                    }
                });
            });
        });
    </script>
@endpush
