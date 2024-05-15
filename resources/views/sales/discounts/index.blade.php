@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Discounts - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Discounts') }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-6">
                                    <h6>{{ __('List Of Discounts') }}</h6>
                                </div>

                                <div class="col-6 d-flex justify-content-end">
                                    <a href="{{ route('sales.discounts.create') }}" class="btn btn-sm btn-primary" id="addBtn"><i class="fas fa-plus-square"></i> {{ __('Add Discount') }}</a>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner"></i> {{ __('Processing') }}...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr class="text-start">
                                                <th>{{ __('Discount Name') }}</th>
                                                <th>{{ __('Created From') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Start At') }}</th>
                                                <th>{{ __('End At') }}</th>
                                                <th>{{ __('Discount Type') }}</th>
                                                <th>{{ __('Discount Amount') }}</th>
                                                <th>{{ __('Priority') }}</th>
                                                <th>{{ __('Brand.') }}</th>
                                                <th>{{ __('Category') }}</th>
                                                <th>{{ __('Applicable Products') }}</th>
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
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addOrEditModal" role="dialog" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Pdf',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-file-pdf"></i> Print',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
            ],
            "processing": true,
            "serverSide": true,
            // aaSorting: [[0, 'asc']],
            ajax: "{{ route('sales.discounts.index') }}",
            "language": {
                "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
            },
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            columns: [

                {
                    data: 'name',
                    name: 'discounts.name'
                },
                {
                    data: 'branch',
                    name: 'branches.name'
                },
                {
                    data: 'status',
                    name: 'discounts.status',
                    className: 'text-start'
                },
                {
                    data: 'start_at',
                    name: 'discounts.start_at'
                },
                {
                    data: 'end_at',
                    name: 'discounts.end_at'
                },
                {
                    data: 'discount_type',
                    name: 'discounts.discount_type'
                },
                {
                    data: 'discount_amount',
                    name: 'discounts.discount_amount'
                },
                {
                    data: 'priority',
                    name: 'discounts.priority'
                },
                {
                    data: 'brand_name',
                    name: 'brands.name'
                },
                {
                    data: 'category_name',
                    name: 'categories.name'
                },
                {
                    data: 'products',
                    name: 'brands.name'
                },
                {
                    data: 'action'
                },
            ]
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '#addBtn', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#addOrEditModal').html(data);
                    $('#addOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#discount_name').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#edit', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#addOrEditModal').empty();
                    $('#addOrEditModal').html(data);
                    $('#addOrEditModal').modal('show');
                    $('.data_preloader').hide();
                    setTimeout(function() {

                        $('#discount_name').focus().select();
                    }, 500);
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
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
                'message': 'Are you sure?',
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
                type: 'post',
                async: false,
                data: request,
                success: function(data) {

                    table.ajax.reload();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });

        // Show sweet alert for delete
        $(document).on('click', '#change_status', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            $.confirm({
                'title': 'Changes Status Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $.ajax({
                                url: url,
                                type: 'get',
                                success: function(data) {
                                    toastr.success(data);
                                    table.ajax.reload();
                                }
                            });
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {
                            console.log('Confirmation canceled.');
                        }
                    }
                }
            });
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#addBtn').click();
                //return false;
            }
        }
    </script>
@endpush
