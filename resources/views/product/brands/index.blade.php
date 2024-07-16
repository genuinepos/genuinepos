@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Brands - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Brands') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="row g-lg-3 g-1">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>{{ __('List of Brands') }}</h6>
                            </div>

                            <div class="col-6 d-flex justify-content-end">
                                @if (auth()->user()->can('product_brand_add'))
                                    <a href="{{ route('brands.create') }}" class="btn btn-sm btn-success" id="addBrand"><i class="fas fa-plus-square"></i> {{ __('Add Brand') }}</a>
                                @endif
                            </div>
                        </div>

                        <div class="widget_content">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                            </div>
                            <div class="table-responsive" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Brand ID') }}</th>
                                            <th>{{ __('Photo') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Action') }}</th>
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

    <div class="modal fade" id="brandAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
@endsection
@push('scripts')
    <script>
        // Get all brands by ajax
        var brandsTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                //{extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {
                    extend: 'pdf',
                    text: 'Pdf',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
            ],
            "language": {
                "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
            },
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            processing: true,
            serverSide: true,
            searchable: true,
            ajax: "{{ route('brands.index') }}",
            columns: [
                // {data: 'DT_RowIndex',name: 'DT_RowIndex'},
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'photo',
                    name: 'photo'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'action',
                    name: 'action'
                },
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
                    },
                    error: function(err) {

                        if (err.status == 0) {

                            toastr.error("{{ __('Net connetion error.') }}");
                            return;
                        } else if (err.status == 500) {

                            toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                            return;
                        }
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#editBrand', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                $('.data_preloader').show();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#brandAddOrEditModal').empty();
                        $('#brandAddOrEditModal').html(data);
                        $('#brandAddOrEditModal').modal('show');
                        $('.data_preloader').hide();

                        setTimeout(function() {

                            $('#brand_name').focus().select();
                        }, 500);
                    },
                    error: function(err) {

                        $('.data_preloader').hide();
                        if (err.status == 0) {

                            toastr.error("{{ __('Net connetion error.') }}");
                            return;
                        } else if (err.status == 500) {

                            toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                            return;
                        }
                    }
                });
            });

            $(document).on('click', '#deleteBrand', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': '@lang('brand.delete_alert')',
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
                                console.log('Deleted canceled.');
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

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg);
                            return;
                        }

                        brandsTable.ajax.reload(null, false);
                        toastr.error(data);
                    },
                    error: function(err) {

                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error.') }}");
                            return;
                        } else if (err.status == 500) {

                            toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                            return;
                        }

                        toastr.error(err.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endpush
