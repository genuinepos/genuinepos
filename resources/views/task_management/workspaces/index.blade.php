@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li { display: inline-block;margin-right: 3px; }
        .top-menu-area a { border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px; }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/image-previewer/jquery.magnify.min.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('title', 'Project Management - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __("Project Management") }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}
                </a>
            </div>
        </div>

        <div class="p-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form action="" method="get">
                                <div class="form-group row">
                                    @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                        <div class="col-md-2">
                                            <label><strong>{{ __('Shop/Business') }}</strong></label>
                                            <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                <option value="">{{ __('All') }}</option>
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
                                        <label><strong>{{ __("Priority") }}</strong></label>
                                        <select name="priority" class="form-control submit_able select2" id="priority" autofocus>
                                            <option value="">{{ __("All") }}</option>
                                            @foreach (\App\Enums\TaskPriority::cases() as $item)
                                                <option value="{{ $item->value }}">{{ $item->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>{{ __("Status") }}</strong></label>
                                        <select name="status" class="form-control select2" id="status" autofocus>
                                            <option value="">{{ __("All") }}</option>
                                            @foreach (\App\Enums\TaskStatus::cases() as $item)
                                                <option value="{{ $item->value }}">{{ $item->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>{{ __("From Date") }}</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>{{ __("To Date") }}</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong></strong></label>
                                        <div class="input-group">
                                            <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __('List of Projects') }} </h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        <a href="{{ route('workspaces.create') }}" class="btn btn-sm btn-primary" id="addBtn"><i class="fas fa-plus-square"></i> {{ __("Add") }}</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6></div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>{{ __("Entry Date") }}</th>
                                    <th>{{ __("Name") }}</th>
                                    <th>{{ __("Project ID") }}</th>
                                    <th>{{ __("Shop/Business") }}</th>
                                    <th>{{ __("Priority") }}</th>
                                    <th>{{ __("Status") }}</th>
                                    <th>{{ __("Start Date") }}</th>
                                    <th>{{ __("End Date") }}</th>
                                    <th>{{ __('Estimated Hour') }}</th>
                                    <th>{{ __("Assigned By") }}</th>
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

    <div class="modal fade" id="workspaceAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop"></div>

    <div class="modal fade" id="docsModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
      aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/custom/image-previewer/jquery.magnify.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var workspacesTable = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            // aaSorting: [[0, 'desc']],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('workspaces.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.priority = $('#priority').val();
                    d.status = $('#status').val();
                    d.date_range = $('#date_range').val();
                }
            },
            // columnDefs: [{"targets": [10], "orderable": false, "searchable": false}],
            columns: [
                {data: 'date', name: 'workspaces.created_at'},
                {data: 'name', name: 'workspaces.name'},
                {data: 'workspace_no', name: 'workspaces.workspace_no'},
                {data: 'from', name: 'branches.name'},
                {data: 'priority', name: 'workspaces.priority'},
                {data: 'status', name: 'workspaces.status'},
                {data: 'start_date', name: 'workspaces.start_date'},
                {data: 'end_date', name: 'workspaces.end_date'},
                {data: 'estimated_hours', name: 'workspaces.estimated_hours'},
                {data: 'assigned_by', name: 'users.name'},
                {data: 'action'},
            ],
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            workspacesTable.ajax.reload();
        });

        $(document).on('click', '#addBtn', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#workspaceAddOrEditModal').html(data);
                    $('#workspaceAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#workspace_name').focus();
                    }, 500);
                }, error: function(err) {

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

                    $('#workspaceAddOrEditModal').html(data);
                    $('#workspaceAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#workspace_name').select().focus();
                    }, 500);
                }, error: function(err) {

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

        // //Show payment view modal with data
        $(document).on('click', '#view', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            // $.ajax({
            //     url:url,
            //     type:'get',
            //     success:function(date){

            //     }
            // });
        });

        $(document).on('click', '#attachments', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('.data_preloader').hide();
                    $('#docsModal').html(data);
                    $('#docsModal').modal('show');
                }
            });
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {'class': 'yes bg-primary','action': function() {$('#deleted_form').submit();}},
                    'No': {'class': 'no bg-danger','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    workspacesTable.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        $(document).on('click', '#attachmentDelete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var tr = $(this).closest('tr');
            $('#deleted_attachment_form').attr('action', url);
            $.confirm({
                'title': "{{ __('Confirmation') }}",
                'message': "{{ __('Are you sure?') }}",
                'buttons': {
                    'Yes': {'class': 'yes bg-primary','action': function() { $('#deleted_attachment_form').submit(); tr.remove(); }},
                    'No': {'class': 'no bg-danger','action': function() { console.log('Deleted canceled.'); }}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_attachment_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){

                    toastr.error(data);
                }
            });
        });
    </script>

    <script type="text/javascript">
        $('.select2').select2();
        $('[data-magnify=gallery]').magnify();
    </script>

    <script>
        var dateFormat = "{{ $generalSettings['business_or_shop__date_format'] }}";
        var _expectedDateFormat = '' ;
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
        new Litepicker({
            singleMode: true,
            element: document.getElementById('from_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: _expectedDateFormat,
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('to_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: _expectedDateFormat,
        });
    </script>
@endpush
