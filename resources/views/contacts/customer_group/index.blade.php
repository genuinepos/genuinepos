@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Customer Groups - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-basket"></span>
                                <h5>{{ __("Customer Groups") }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <form id="filter_form">
                                            <div class="form-group row">
                                                @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                                    <div class="col-md-4">
                                                        <label><strong>{{ __("Shop") }}</strong></label>
                                                        <select name="branch_id" class="form-control submit_able select2" id="branch_id" autofocus>
                                                            <option value="">{{ __("All") }}</option>
                                                            <option value="NULL">{{ $generalSettings['business__business_name'] }}({{ __("Business") }})</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">{{ $branch->name.'/'.$branch->branch_code }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="submit" class="btn text-white btn-sm btn-info float-start m-0">
                                                            <i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}
                                                        </button>
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
                                <div class="col-10">
                                    <h6>{{ __('List Of Customer Groups') }}</h6>
                                </div>
                                @if(auth()->user()->can('customer_group'))
                                    <div class="col-2 d-flex justify-content-end">
                                        <a href="{{ route('contacts.customers.groups.create') }}" class="btn btn-sm btn-primary" id="addCustomerGroup"><i class="fas fa-plus-square"></i> {{ __("Add Customer Group") }}</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>{{ __("Serial") }}</th>
                                                <th>{{ __("Shop") }}</th>
                                                <th>{{ __("Name") }}</th>
                                                <th>{{ __("Price Calculation type") }}</th>
                                                <th>{{ __("Price Calculation Percent") }}</th>
                                                <th>{{ __("Price Group") }}</th>
                                                <th>{{ __("Action") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                            <form id="delete_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="customerGroupAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))

            toastr.success('{{ session('successMsg')[0] }}');
        @endif

        var customerGroupsTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary', exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary', exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            //aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('contacts.customers.groups.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex',name: 'DT_RowIndex' },
                { data: 'branch',name: 'branches.name' },
                { data: 'name',name: 'customer_groups.name' },
                { data: 'price_calculation_type',name: 'customer_groups.price_calculation_type', },
                { data: 'calculation_percentage',name: 'customer_groups.calculation_percentage', className: 'fw-bold' },
                { data: 'price_group_name',name: 'price_groups.name' },
                { data: 'action' },
            ],fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function (e) {
            e.preventDefault();

            $('.data_preloader').show();
            customerGroupsTable.ajax.reload();
        });

        // call jquery method
        $(document).on('click', '#addCustomerGroup', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#customerGroupAddOrEditModal').html(data);
                    $('#customerGroupAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#cus_group_name').focus();
                    }, 500);
                }, error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $('.data_preloader').show();
            $.ajax({
                url: url
                , type: 'get'
                , success: function(data) {

                    $('#customerGroupAddOrEditModal').empty();
                    $('#customerGroupAddOrEditModal').html(data);
                    $('#customerGroupAddOrEditModal').modal('show');
                    $('.data_preloader').hide();
                    setTimeout(function() {

                        $('#cus_group_name').focus().select();
                    }, 500);
                } , error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#delete', function(e){

            e.preventDefault();

            var url = $(this).attr('href');
            $('#delete_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() { $('#delete_form').submit(); }},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#delete_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    customerGroupsTable.ajax.reload();
                    toastr.error(data);
                },error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    }else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    </script>
@endpush
