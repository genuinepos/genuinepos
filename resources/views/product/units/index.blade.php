@extends('layout.master')
@push('stylesheets')
@endpush

@section('title', 'Units -')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Units') }}</h5>
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
                                <h6>{{ __('List Of Units') }}</h6>
                            </div>

                            <div class="col-6 d-flex justify-content-end">
                                @if (auth()->user()->can('product_unit_add'))
                                    <a href="{{ route('units.create', 1) }}" class="btn btn-sm btn-primary" id="addUnit"><i class="fas fa-plus-square"></i> {{ __('Add Unit') }}</a>
                                @endif
                            </div>
                        </div>

                        <div class="widget_content">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                            </div>

                            <div class="table-responsive" id="data-list">
                                <table class="display data_tbl data__table unit_table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Unit ID') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Short Name') }}</th>
                                            <th>{{ __('Base Unit') }}</th>
                                            <th>{{ __('Multiplier Details') }}</th>
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

    <div class="modal fade" id="unitAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
@endsection
@push('scripts')
    <script>
        // Get all units by ajax
        var unitsTable = $('.data_tbl').DataTable({
            processing: true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
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
            serverSide: true,
            "language": {
                "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
            },
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            ajax: "{{ route('units.index') }}",
            columns: [
                // {
                //     data: 'DT_RowIndex',
                //     name: 'baseUnit.code_name'
                // },
                {
                    data: 'code',
                    name: 'units.code'
                },
                {
                    data: 'name',
                    name: 'units.name'
                },
                {
                    data: 'code_name',
                    name: 'units.code_name'
                },
                {
                    data: 'base_unit_name',
                    name: 'baseUnit.name'
                },
                {
                    data: 'multiplierUnitDetails',
                    name: 'baseUnit.code_name',
                    className: 'fw-bold'
                },
                {
                    data: 'action'
                },
            ],
        });

        // insert branch by ajax
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {

            $(document).on('click', '#addUnit', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#unitAddOrEditModal').html(data);
                        $('#unitAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#unit_name').focus();
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

                        $('#unitAddOrEditModal').empty();
                        $('#unitAddOrEditModal').html(data);
                        $('#unitAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#unit_name').focus().select();
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
                    'content': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $('#deleted_form').submit();
                            }
                        },
                        'No': {
                            'class': 'no btn-modal-primary',
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
                    type: 'delete',
                    data: request,
                    success: function(data) {

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg);
                            return;
                        }

                        unitsTable.ajax.reload(null, false);
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
