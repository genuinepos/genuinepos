@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Warrantites - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Warranties/Guaranties') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                </a>
            </div>
        </div>

        <div class="p-1">
            <div class="row g-lg-3 g-1">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>{{ __('List of Warranties/Guaranties') }}</h6>
                            </div>

                            <div class="col-6 d-flex justify-content-end">
                                @if (auth()->user()->can('product_warranty_add'))
                                    <a href="{{ route('warranties.create') }}" class="btn btn-sm btn-primary" id="addWarranty"><i class="fas fa-plus-square"></i> {{ __('Add Warranty') }}</a>
                                @endif
                            </div>
                        </div>
                        <!--begin: Datatable-->
                        <div class="widget_content">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                            </div>
                            <div class="table-responsive" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Warranty ID') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Duration') }}</th>
                                            <th>{{ __('Desctiption') }}</th>
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

    <div class="modal fade" id="warrantyAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
@endsection
@push('scripts')
    <script>
        var warrantiesTable = $('.data_tbl').DataTable({
            processing: true,
            serverSide: true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    'title': 'List Of Warranties/Guaranties',
                    text: 'Excel',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    'title': 'List Of Warranties/Guaranties',
                    text: 'Pdf',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    'title': 'List Of Warranties/Guaranties',
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
            ajax: "{{ route('warranties.index') }}",
            columns: [
                // {
                //     data: 'DT_RowIndex',
                //     name: 'DT_RowIndex'
                // },
                {
                    data: 'code',
                    name: 'warranties.code'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'duration',
                    name: 'duration'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
        });

        warrantiesTable.buttons().container().appendTo('#exportButtonsContainer');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {

            $(document).on('click', '#addWarranty', function(e) {

                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#warrantyAddOrEditModal').html(data);
                        $('#warrantyAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#warranty_name').focus();
                        }, 500);
                    },
                    error: function(err) {

                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error.') }}");
                            return;
                        } else if (err.status == 500) {

                            toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                            return;
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
                    success: function(data) {

                        $('#warrantyAddOrEditModal').empty();
                        $('#warrantyAddOrEditModal').html(data);
                        $('#warrantyAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#warranty_name').focus().select();
                        }, 500);
                    },
                    error: function(err) {

                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error.') }}");
                            return;
                        } else if (err.status == 500) {

                            toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                            return;
                        }
                    }
                });
            });

            $(document).on('click', '#delete', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);

                $.confirm({
                    'title': 'Confirmation',
                    'content': 'Are you sure, you want to delete?',
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

                        toastr.error(data);
                        warrantiesTable.ajax.reload(null, false);
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
