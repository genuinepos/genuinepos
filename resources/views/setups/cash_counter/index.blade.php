@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Cash Counter List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-4">
                    <h5>{{ __('Cash Counters') }}
                        <span>({{ __('Limit') }} -<span class="text-danger">{{ $count }}</span>/{{ $generalSettings['subscription']->features['cash_counter_count'] }})</span>
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
                                <form id="filter_form">
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>{{ location_label() }}</strong></label>
                                            <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                <option value="">{{ __('All') }}</option>
                                                <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
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
                                                <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-7">
                        <h6>{{ __('List of Cash Counters') }}</h6>
                    </div>

                    <div class="col-5 d-flex justify-content-end">
                        <a href="{{ route('cash.counters.create') }}" class="btn btn-sm btn-success" id="addCashCounter"><i class="fas fa-plus-square"></i> {{ __('Add Cash Counter') }}</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr class="bg-navey-blue">
                                    <th class="text-black">{{ __('Serial') }}</th>
                                    <th class="text-black">{{ __('Counter Name') }}</th>
                                    <th class="text-black">{{ __('Short Name') }}</th>
                                    <th class="text-black">{{ location_label() }}</th>
                                    <th class="text-black">{{ __('Action') }}</th>
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
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
            ],
            processing: true,
            serverSide: true,
            searchable: true,
            "ajax": {
                "url": "{{ route('cash.counters.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                }
            },
            "language": {
                "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
            },
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [50, 100, 500, 1000, -1],
                [50, 100, 500, 1000, "All"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'counter_name',
                    name: 'counter_name'
                },
                {
                    data: 'short_name',
                    name: 'short_name'
                },
                {
                    data: 'branch',
                    name: 'branch'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            cashCounterTable.ajax.reload();
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {

            $(document).on('click', '#addCashCounter', function(e) {
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

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg);
                            return;
                        }

                        toastr.error(data);
                        cashCounterTable.ajax.reload(null, false);
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
