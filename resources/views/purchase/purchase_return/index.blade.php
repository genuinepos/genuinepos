@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Purchase Return List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-undo-alt"></span>
                                <h5>{{ __("Purchase Returns") }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Bank") }}</a>
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
                                                    <div class="col-md-3">
                                                        <label><strong>{{ __("Shop/Business") }}</strong></label>
                                                        <select name="branch_id"
                                                            class="form-control select2" id="branch_id" autofocus>
                                                            <option value="">@lang('menu.all')</option>
                                                            <option value="NULL">{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    @php
                                                                        $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                        $areaName = $branch->area_name ? '('. $branch->area_name .')' : '';
                                                                        $branchCode = '-' . $branch->branch_code;
                                                                    @endphp
                                                                    {{  $branchName.$areaName.$branchCode }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>{{ __("Supplier") }}</strong></label>
                                                    <select name="supplier_account_id" class="form-control select2" id="supplier_account_id" autofocus>
                                                        <option value="">@lang('menu.all')</option>
                                                        @foreach ($supplierAccounts as $supplierAccount)
                                                            <option data-supplier_account_name="{{ $supplierAccount->name.'/'.$supplierAccount->phone }}" value="{{ $supplierAccount->id }}">{{ $supplierAccount->name.'/'.$supplierAccount->phone }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __("From Date") }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control from_date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __("To Date") }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

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
                                <div class="col-9">
                                    <h6>{{ __('List Of Purchase Returns') }}</h6>
                                </div>
                                @if(auth()->user()->can('purchase_add'))
                                    <div class="col-3 d-flex justify-content-end">
                                        <a href="{{ route('purchase.returns.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> {{ __("Add New") }}</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    {{-- <table class="display data_tbl data__table"> --}}
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="fw-bold">{{ __("Action") }}</th>
                                                <th>{{ __("Date") }}</th>
                                                <th>{{ __("Voucher No") }}</th>
                                                <th>{{ __("Parent Purchase") }}</th>
                                                <th>{{ __("Supplier") }}</th>
                                                <th>{{ __("Branch") }}</th>
                                                <th>{{ __("Payment Status") }}</th>
                                                <th>{{ __("Total Qty") }}</th>
                                                <th>{{ __("Net Total Amount") }}</th>
                                                <th>{{ __("Return Discount") }}</th>
                                                <th>{{ __("Return Tax") }}</th>
                                                <th>{{ __("Total Returned Amount") }}</th>
                                                <th>{{ __("Received Amount") }}</th>
                                                <th>{{ __("Due") }}</th>
                                                <th>{{ __("Created By") }}</th>
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

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('assets/plugins/custom/moment/moment.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/plugins/custom/print_this/printThis.js') }}"></script>
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        var purchaseReturnsTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            // aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('purchase.returns.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.supplier_account_id = $('#supplier_account_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'voucher',name: 'voucher_no', className: 'fw-bold'},
                {data: 'parent_invoice_id',name: 'parent_invoice_id', className: 'fw-bold'},
                {data: 'supplier_name', name: 'suppliers.name'},
                {data: 'branch',name: 'branches.name'},
                {data: 'payment_status', name: 'parentBranch.name'},
                {data: 'total_qty', name: 'voucher_no', className: 'text-end fw-bold'},
                {data: 'net_total_amount', name: 'voucher_no', className: 'text-end fw-bold'},
                {data: 'return_discount', name: 'net_total_amount', className: 'text-end fw-bold'},
                {data: 'return_tax_amount', name: 'total_return_due', className: 'text-end fw-bold'},
                {data: 'total_return_amount', name: 'total_return_due', className: 'text-end fw-bold'},
                {data: 'received', name: 'total_return_due', className: 'text-end fw-bold'},
                {data: 'due', name: 'total_return_due', className: 'text-end fw-bold'},
                {data: 'createdBy', name: 'createdBy.name', className: 'text-end fw-bold'},
            ],fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function (e) {
            e.preventDefault();

            $('.data_preloader').show();
            purchaseReturnsTable.ajax.reload();
        });

        // Show details modal with data
        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#details').html(data);
                    $('#detailsModal').modal('show');
                    $('.data_preloader').hide();
                },error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    }else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        $(document).on('click', '#modalDetailsPrintBtn', function(e) {
            e.preventDefault();

            var body = $('.print_modal_details').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
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
        $(document).on('submit', '#delete_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                    }else{
                        purchaseReturnsTable.ajax.reload();
                        toastr.error(data);
                    }
                }
            });
        });
    </script>

    <script type="text/javascript">
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
            format: 'DD-MM-YYYY'
        })

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
            format: 'DD-MM-YYYY'
        })
    </script>
@endpush
