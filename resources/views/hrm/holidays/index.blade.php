@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Holidays - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Holidays') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>

        <div class="p-1">
            @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                    <div class="element-body">
                        <form id="filter_form" action="" method="get">
                            <div class="form-group row align-items-end">

                                    <div class="col-md-4">
                                        <label><strong>{{ __('Shop Acccess') }}</strong></label>
                                        <select name="branch_id" class="form-control submit_able select2" id="branch_id" autofocus>
                                            <option value="">{{ __('All') }}</option>
                                            <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __("Business") }})</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __('List Of Holidays') }}</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        <a href="{{ route('hrm.holidays.create') }}" class="btn btn-sm btn-primary" id="addHoliday"><i class="fas fa-plus-square"></i> {{ __("Add Holiday") }}</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6></div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>{{ __("Name") }}</th>
                                    <th>{{ __("Date") }}</th>
                                    <th>{{ __('Allowed Shop/Business') }}</th>
                                    <th>{{ __("Note") }}</th>
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

    <div class="modal fade" id="holidayAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var holidaysTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: 'Excel', messageTop: 'List Of Holidays', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: 'Pdf', messageTop: 'List Of Holidays', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: 'Print', messageTop: '<b>List Of Holidays</b>', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            processing: true,
            serverSide: true,
            searchable: true,
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('hrm.holidays.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                }
            }, columns: [
                {data: 'name', name: 'name'},
                {data: 'date',name: 'start_date'},
                {data: 'allowed_branches', name: 'end_date'},
                {data: 'note', name: 'note'},
                {data: 'action'},
            ], fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            holidaysTable.ajax.reload();
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        $(document).ready(function(){

            $(document).on('click', '#addHoliday', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#holidayAddOrEditModal').html(data);
                        $('#holidayAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#name').focus();
                        }, 500);
                    }, error: function(err) {

                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error') }}");
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
                $('.data_preloader').show();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#holidayAddOrEditModal').empty();
                        $('#holidayAddOrEditModal').html(data);
                        $('#holidayAddOrEditModal').modal('show');
                        $('.data_preloader').hide();
                        setTimeout(function() {

                            $('#name').focus().select();
                        }, 500);
                    },
                    error: function(err) {

                        $('.data_preloader').hide();
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

            $(document).on('click', '#delete',function(e){
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': 'Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes bg-primary',
                            'action': function() {
                                $('#deleted_form').submit();
                            }
                        },
                        'No': {
                            'class': 'no bg-danger',
                            'action': function() {
                                // alert('Deleted canceled.')
                            }
                        }
                    }
                });
            });

            $(document).on('submit', '#deleted_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $('#data_preloader').show();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        $('#data_preloader').hide();
                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg);
                            return;
                        }

                        toastr.error(data);
                        holidaysTable.ajax.reload();
                        $('#deleted_form')[0].reset();
                    },error: function(err) {

                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error.') }}");
                        }else if(err.status == 500){

                            toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        }
                    }
                });
            });
        });
    </script>
@endpush
