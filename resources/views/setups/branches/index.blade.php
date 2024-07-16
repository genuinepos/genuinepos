@extends('layout.master')
@push('stylesheets')
    <style>
        .dropify-wrapper {
            height: 100px !important;
        }
    </style>
    <link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('title', 'Shop List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-4">
                    <h5>{{ __('Shops') }}
                        <span>({{ __('Limit') }} -<span class="text-danger">{{ $currentCreatedBranchCount }}</span>/{{ $generalSettings['subscription']->current_shop_count }})</span>
                    </h5>
                </div>

                <div class="col-md-4 text-start">
                    <p class="fw-bold"></p>
                </div>
                <div class="col-md-4">

                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                        <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <div class="section-header">
                    <div class="col-md-6">
                        <h6>{{ __('Shop List') }}</h6>
                    </div>

                    @if (auth()->user()->can('branches_create') && $currentCreatedBranchCount < $generalSettings['subscription']->current_shop_count)
                        <div class="col-md-6 d-flex justify-content-end">
                            <a id="addBtn" href="{{ route('branches.create') }}" class="btn btn-sm btn-success">
                                <i class="fas fa-plus-square"></i> {{ __('Add New Shop') }}
                            </a>
                        </div>
                    @endif
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>{{ __('Shop Name') }}</th>
                                    <th>{{ __('Shop Id') }}</th>
                                    <th>{{ __('Parent Shop') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Shop Logo') }}</th>
                                    <th>{{ __('Expire Date') }}</th>
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

    <div class="modal fade" id="branchAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/custom/dropify/js/dropify.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var branchTable = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
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
            "language": {
                "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
            },
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [50, 100, 500, 1000, -1],
                [50, 100, 500, 1000, "All"]
            ],
            ajax: "{{ route('branches.index') }}",
            columns: [{
                    data: 'branchName',
                    name: 'branches.name'
                },
                {
                    data: 'branch_code',
                    name: 'branches.branch_code'
                },
                {
                    data: 'parent_branch_name',
                    name: 'parentBranch.name',
                    className: 'fw-bold'
                },
                {
                    data: 'phone',
                    name: 'branches.phone'
                },
                {
                    data: 'address',
                    name: 'branches.city'
                },
                {
                    data: 'logo',
                    name: 'branches.state'
                },
                {
                    data: 'expire_date',
                    name: 'branches.name'
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

            $(document).on('click', '#addBtn', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#branchAddOrEditModal').html(data);
                        $('#branchAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#branch_type').focus();
                        }, 500);
                    },
                    error: function(err) {

                        if (err.status == 0) {

                            toastr.error('Net Connetion Error.');
                        } else {

                            toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
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

                        $('#branchAddOrEditModal').html(data);
                        $('#branchAddOrEditModal').modal('show');

                        setTimeout(function() {

                            if ($('#branch_name').val() != undefined) {

                                $('#branch_name').focus();
                            } else {

                                $('#branch_area_name').focus();
                            }

                        }, 500);
                    },
                    error: function(err) {

                        if (err.status == 0) {

                            toastr.error('{{ __('Net Connetion Error.') }}');
                        } else {

                            toastr.error('{{ __('Server Error. Please contact to the support team.') }}');
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
                    type: 'delete',
                    data: request,
                    success: function(data) {

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg);
                            return;
                        }

                        toastr.error(data);
                        branchTable.ajax.reload(false, null);
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
