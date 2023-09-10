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
                                <h5>@lang('menu.purchase_return')</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>

                    </div>

                    <div class="p-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-3">
                                    <div class="element-body">
                                        <form action="" method="get">
                                            <div class="form-group row">
                                                @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                                    <div class="col-md-2">
                                                        <label><strong>@lang('menu.business_location') </strong></label>
                                                        <select name="branch_id"
                                                            class="form-control select2" id="branch_id" autofocus>
                                                            <option value="">@lang('menu.all')</option>
                                                            <option value="NULL">{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    @php
                                                                        $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                        $areaName = $branch->area_name ? '('. $branch->area_name .')' : '';
                                                                        $branchCode = '-(' . $branch->branch_code.')';
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
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="section-header">
                                <div class="col-9">
                                    <h6>{{ __('All Purchase Returns') }}</h6>
                                </div>
                                @if(auth()->user()->can('purchase_add'))
                                    <div class="col-3 d-flex justify-content-end">
                                        <a href="{{ route('purchases.returns.supplier.return') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> @lang('menu.add_return')</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    {{-- <table class="display data_tbl data__table"> --}}
                                    <table class="display data_tbl modal-table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.action')</th>
                                                <th>@lang('menu.date')</th>
                                                <th>@lang('menu.return_invoice_id')</th>
                                                <th>@lang('menu.parent_purchase')</th>
                                                <th>@lang('menu.supplier_name')</th>
                                                <th>@lang('menu.location')</th>
                                                <th>@lang('menu.return_from')</th>
                                                <th>@lang('menu.payment_status')</th>
                                                <th>@lang('menu.return_amount')</th>
                                                <th>@lang('menu.payment_due')</th>
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

    <div id="purchase_return_details">

    </div>

    @if(auth()->user()->can('purchase_payment'))
    <!--Payment list modal-->
    <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.payment_list')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="payment_list_modal_body">

                </div>
            </div>
        </div>
    </div>
    <!--Payment list modal-->

    <!--Add Payment modal-->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">

    </div>
    <!--Add Payment modal-->

    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content payment_details_contant">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.payment_details') (<span
                            class="payment_invoice"></span>)</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <div class="payment_details_area">

                    </div>

                    <div class="row">
                        <div class="col-md-6 text-end">
                            <ul class="list-unstyled">
                                <li class="mt-1" id="payment_attachment"></li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-end">
                            <ul class="list-unstyled">
                                <li class="mt-1">
                                    <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">@lang('menu.close')</button>
                                    <button type="submit" id="print_payment" class="c-btn me-0 button-success">@lang('menu.print')</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
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

        var table = $('.data_tbl').DataTable({
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
                "url": "{{ route('purchases.returns.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.supplier_id = $('#supplier_id').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{
                "targets": [2, 3, 4, 5, 6, 9],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'voucher_no',name: 'voucher_no'},
                {data: 'parent_invoice_id',name: 'parent_invoice_id'},
                {data: 'supplier', name: 'supplier'},
                {data: 'location',name: 'branches.name'},
                {data: 'return_from',name: 'warehouses.name'},
                {data: 'payment_status',name: 'payment_status'},
                {data: 'total_return_amount',name: 'total_return_amount', className: 'text-end'},
                {data: 'total_return_due',name: 'total_return_due', className: 'text-end'},

            ],
        });

        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('.data_preloader').show();
            $.get(url, function(data) {
                $('#purchase_return_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
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
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                    }else{
                        table.ajax.reload();
                        toastr.error(data);
                    }
                }
            });
        });

        $(document).on('input', '.from_date', function () {
            table.ajax.reload();
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
