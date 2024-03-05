@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
@endpush
@section('title', 'Warehouses - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-4">
                    <h5>{{ __('Warehouses') }}
                        <span>({{ __("Limit") }} -<span class="text-danger">{{ $count }}</span>/{{ $generalSettings['subscription']->features['warehouse_count'] }})</span>
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
            {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
            @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <form id="filter_form" action="" method="get" class="px-2">
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>{{ __('Created From') }}</strong></label>
                                            <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                <option value="">{{ __("All") }}</option>
                                                <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        @php
                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                            $branchCode = '-' . $branch->branch_code;
                                                        @endphp
                                                        {{ $branchName . $areaName . $branchCode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong></strong></label>
                                            <div class="input-group">
                                                <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>{{ __('List of Warehouses') }}</h6>
                            </div>

                            <div class="col-6 d-flex justify-content-end">
                                @if (auth()->user()->can('warehouses_index'))
                                    <a href="{{ route('warehouses.create') }}" class="btn btn-sm btn-primary" id="addWarehouse"><i class="fas fa-plus-square"></i> {{ __('Add') }}</a>
                                @endif
                            </div>
                        </div>

                        <div class="widget_content">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">{{ __('S/L') }}</th>
                                            <th class="text-start">{{ __('Name') }}</th>
                                            <th class="text-start">{{ __('Shop/Business') }}</th>
                                            <th class="text-start">{{ __('Code') }}</th>
                                            <th class="text-start">{{ __('Phone') }}</th>
                                            <th class="text-start">{{ __('Address') }}</th>
                                            <th class="text-start">{{ __('Action') }}</th>
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

    <div class="modal fade" id="warehouseAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>
@endsection
@push('scripts')
    <script>
        $('.select2').select2();

        var warehouseTable = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [
                //{extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                { extend: 'pdf', text: 'Pdf', className: 'btn btn-primary', exportOptions: { columns: 'th:not(:last-child)' } },
                { extend: 'print', text: 'Print', className: 'btn btn-primary', exportOptions: { columns: 'th:not(:last-child)' } },
            ],
            "lengthMenu": [ [50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"] ],
            "ajax": {
                "url": "{{ route('warehouses.index') }}",
                "data": function(d) { d.branch_id = $('#branch_id').val(); }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'name', name: 'warehouses.warehouse_name' },
                { data: 'branch', name: 'branches.name' },
                { data: 'code', name: 'warehouses.warehouse_code' },
                { data: 'phone', name: 'warehouses.phone' },
                { data: 'address', name: 'warehouses.address' },
                { data: 'action' },
            ],
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            warehouseTable.ajax.reload();
        });

        // Setup CSRF Token for ajax request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {

            $(document).on('click', '#addWarehouse', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#warehouseAddOrEditModal .modal-dialog').remove();
                        $('#warehouseAddOrEditModal').html(data);
                        $('#warehouseAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#warehouse_name').focus();
                        }, 500);

                        $('.data_preloader').hide();

                    }, error: function(err) {

                        $('.data_preloader').hide();
                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error.') }}");
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

                        $('#warehouseAddOrEditModal .modal-dialog').remove();
                        $('#warehouseAddOrEditModal').html(data);
                        $('#warehouseAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#warehouse_name').focus().select();
                        }, 500);

                        $('.data_preloader').hide();
                    },
                    error: function(err) {

                        $('.data_preloader').hide();
                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error.') }}");
                        } else {

                            toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        }
                    }
                });
            });

            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var id = $(this).data('id');
                $('#deleted_form').attr('action', url);
                $('#deleteId').val(id);
                $.confirm({
                    'title': "{{ __('Confirmation') }}",
                    'content': "{{ __('Are you sure?') }}",
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

                        toastr.error(data);
                        warehouseTable.ajax.reload();
                    }
                });
            });
        });
    </script>
@endpush
