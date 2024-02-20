@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Invoice Layouts - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __("Invoice Layouts") }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>

        <div class="p-1">
            @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <form id="filter_form" action="" method="get" class="px-2">
                                    <div class="form-group row">
                                        @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                            <div class="col-md-4">
                                                <label><strong>{{ __('Shop/Business') }}</strong></label>
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
                                        @endif

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

            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-7">
                        <h6>{{ __('List of Invoice Layouts') }}</h6>
                    </div>

                    <div class="col-5 d-flex justify-content-end">
                        @if (auth()->user()->can('invoice_layouts_add'))

                            <a href="{{ route('invoices.layouts.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> {{ __("Add") }}</a>
                        @endif
                    </div>
                </div>

                <div class="widget_content">
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>{{ __("Serial") }}</th>
                                    <th>{{ __("Layout Name") }}</th>
                                    <th>{{ __("Shop/Business") }}</th>
                                    <th>{{ __('Is Header Less') }}</th>
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
@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
            processing: true,
            serverSide: true,
            // aaSorting: [[3, 'asc']],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"] ],
            "ajax": {
                "url": "{{ route('invoices.layouts.index') }}",
                "data": function(d) { d.branch_id = $('#branch_id').val(); }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'name', name: 'name' },
                { data: 'branch', name: 'branches.name' },
                { data: 'is_header_less', name: 'is_header_less' },
                { data: 'action', name: 'action' },
            ]
        });

        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': "{{ __('Confirmation') }}",
                'content': "{{ __('Are you sure?') }}",
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    }, 'No': {
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
                    table.ajax.reload();
                    $('#deleted_form')[0].reset();
                }, error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    </script>
@endpush
