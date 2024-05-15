@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .widget_content p { padding: 0px 0px; }
    </style>
@endpush
@section('title', 'Vat/Tax Report - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Vat/Tax Report') }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <form id="filter_form">
                                            <div class="form-group row align-items-end">
                                                {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                                @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                                    <div class="col-md-2">
                                                        <label><strong>{{ __('Shop/Business') }}</strong></label>
                                                        <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                            <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                            <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
                                                            @foreach ($branches as $branch)
                                                                @php
                                                                    $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                    $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                    $branchCode = '-' . $branch->branch_code;
                                                                @endphp
                                                                <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                                                    {{ $branchName . $areaName . $branchCode }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('Tax Ledger A/c') }}</strong></label>
                                                    <select name="tax_account_id" id="tax_account_id" class="form-control select2">
                                                        <option data-tax_account_name="All" value="">{{ __('All') }}</option>
                                                        @foreach ($taxAccounts as $taxAccount)
                                                            <option data-tax_account_name="{{ $taxAccount->name }}" value="{{ $taxAccount->id }}">
                                                                {{ $taxAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- <div class="col-md-2">
                                                    <label><strong>{{ __('Customer/Supplier') }}</strong></label>
                                                    <select name="contact_account_id" class="form-control select2" id="contact_account_id" autofocus>
                                                        <option data-contact_account_name="{{ __("All") }}" value="">{{ __('All') }}</option>
                                                        @foreach ($contacts as $contact)
                                                            <option data-contact_account_name="{{ $contact->name . '/' . $contact->phone }}" value="{{ $contact->id }}">{{ $contact->name . '/' . $contact->phone }}</option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('From Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('To Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="row align-items-end">
                                                        <div class="col-6">
                                                            <div class="input-group">
                                                                <button type="submit" id="filter_button" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <a href="#" class="btn btn-sm btn-primary float-end m-0" id="printReport"><i class="fas fa-print "></i> {{ __('Print') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6>{{ __("Overall") }}({{ __("Output Vat/Tax - Input Vat/Tax") }})</h6>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="text-muted">({{ __("Output - Input") }}) :
                                            (<span id="span_total_output_tax"></span> - <span id="span_total_input_tax"></span>) = <span id="net_amount"></span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-1">
                            <div class="tab_list_area p-1">
                                <div class="btn-group">
                                    <a id="tab_btn" data-show="input_tax" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                        <i class="fas fa-long-arrow-alt-down"></i> {{ __('Input Vat/Tax') }}
                                    </a>

                                    <a id="tab_btn" data-show="output_tax" class="btn btn-sm btn-primary tab_btn" href="#">
                                        <i class="fas fa-long-arrow-alt-up"></i> {{ __('Output Vat/Tax') }}
                                    </a>
                                </div>
                            </div>

                            <div class="tab_contant input_tax">
                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                                    </div>
                                    <div class="table-responsive" id="data-list">
                                        <table id="vat-tax-input-table" class="display data_tbl data__table w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">{{ __('Date') }}</th>
                                                    <th class="text-start">{{ __('Particulars') }}</th>
                                                    <th class="text-start">{{ __('Shop/Business') }}</th>
                                                    <th class="text-start">{{ __('Voucher Type') }}</th>
                                                    <th class="text-start">{{ __('Voucher No') }}</th>
                                                    <th class="text-start">{{ __('Tax Ledger A/c') }}</th>
                                                    <th class="text-start">{{ __('Input Tax Amount') }}</th>
                                                    <th class="text-start">{{ __('On Amount') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="6" class="text-white" style="text-align: right!important;"> {{ __('Total') }} : </th>
                                                    <th id="table_total_input_tax" class="text-white"></th>
                                                    <th class="text-white" style="text-align: right!important;">---</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab_contant output_tax d-hide">
                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                                    </div>
                                    <div class="table-responsive" id="data-list">
                                        <table id="vat-tax-output-table" class="display data_tbl data__table w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">{{ __('Date') }}</th>
                                                    <th class="text-start">{{ __('Particulars') }}</th>
                                                    <th class="text-start">{{ __('Shop/Business') }}</th>
                                                    <th class="text-start">{{ __('Voucher Type') }}</th>
                                                    <th class="text-start">{{ __('Voucher No') }}</th>
                                                    <th class="text-start">{{ __('Tax Ledger A/c') }}</th>
                                                    <th class="text-start">{{ __('Output Tax Amount') }}</th>
                                                    <th class="text-start">{{ __('On Amount') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="6" class="text-white" style="text-align: right!important;"> {{ __('Total') }} : </th>
                                                    <th id="table_total_output_tax" class="text-white"></th>
                                                    <th class="text-white" style="text-align: right!important;">---</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var vatTaxInputTable = $('#vat-tax-input-table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            dom: "lBfrtip",
            buttons: [
                { extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-primary' },
                { extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> Pdf', className: 'btn btn-primary'},
            ],
            "lengthMenu": [
                [50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.vat.tax.input.table') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.tax_account_id = $('#tax_account_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            }, columns: [
                { data: 'date', name: 'account_ledgers.date' },
                { data: 'particulars', name: 'purchase.supplier.name' },
                { data: 'branch', name: 'branches.name' },
                { data: 'voucher_type', name: 'purchaseProduct.purchase.supplier.name' },
                { data: 'voucher_no', name: 'purchase.invoice_id' },
                { data: 'account_name', name: 'accounts.name' },
                { data: 'input_amount', name: 'purchaseProduct.purchase.supplier.name', className: 'text-end' },
                { data: 'on_amount', name: 'salesReturn.customer.name', className: 'text-end' },
            ], fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        vatTaxInputTable.buttons().container().appendTo('#exportButtonsContainer');

        var vatTaxOutputTable = $('#vat-tax-output-table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            dom: "lBfrtip",
            buttons: [
                { extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-primary' },
                { extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> Pdf', className: 'btn btn-primary' },
            ],
            "lengthMenu": [
                 [50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.vat.tax.output.table') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.tax_account_id = $('#tax_account_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            }, columns: [
                { data: 'date', name: 'account_ledgers.date' },
                { data: 'particulars', name: 'sale.customer.name' },
                { data: 'branch', name: 'branches.name' },
                { data: 'voucher_type', name: 'saleProduct.sale.customer.name' },
                { data: 'voucher_no', name: 'sale.invoice_id' },
                { data: 'account_name', name: 'accounts.name' },
                { data: 'output_amount', name: 'saleProduct.sale.customer.name', className: 'text-end' },
                { data: 'on_amount', name: 'purchaseReturn.supplier.name', className: 'text-end' },
            ], fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        vatTaxOutputTable.buttons().container().appendTo('#exportButtonsContainer');

        var filterObj = {
             branch_id : $('#branch_id').val() != '' || $('#branch_id').val() != undefined ? $('#branch_id').val() : null,
             tax_account_id : $('#tax_account_id').val() != '' || $('#tax_account_id').val() != undefined ? $('#tax_account_id').val() : null,
             from_date : $('#from_date').val() != '' || $('#from_date').val() != undefined ? $('#from_date').val() : null,
             to_date : $('#to_date').val() != '' || $('#to_date').val() != undefined ? $('#to_date').val() : null,
        };

        // Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            vatTaxInputTable.ajax.reload(null, false);
            vatTaxOutputTable.ajax.reload(null, false);

            filterObj = {
                branch_id : $('#branch_id').val() != '' || $('#branch_id').val() != undefined ? $('#branch_id').val() : null,
                tax_account_id : $('#tax_account_id').val() != '' || $('#tax_account_id').val() != undefined ? $('#tax_account_id').val() : null,
                from_date : $('#from_date').val() != '' || $('#from_date').val() != undefined ? $('#from_date').val() : null,
                to_date : $('#to_date').val() != '' || $('#to_date').val() != undefined ? $('#to_date').val() : null,
            };

            VatTaxAmounts(filterObj);
        });

        function VatTaxAmounts(filterObj) {

            var url = "{{ route('reports.vat.tax.amounts') }}";

            $.ajax({
                url: url,
                type: 'get',
                data: filterObj,
                success: function(data) {

                    $('#net_amount').removeClass('text-success');
                    $('#net_amount').removeClass('text-danger');
                    $('#table_total_output_tax').html(bdFormat(data.totalOutputTaxAmount));
                    $('#table_total_input_tax').html(bdFormat(data.totalInputTaxAmount));
                    $('#span_total_output_tax').html(bdFormat(data.totalOutputTaxAmount));
                    $('#span_total_input_tax').html(bdFormat(data.totalInputTaxAmount));
                    $('#net_amount').html(bdFormat(data.netAmount));

                    if (parseFloat(data.netAmount) < 0) {

                        $('#net_amount').addClass('text-danger');
                    }else {

                        $('#net_amount').addClass('text-success');
                    }
                }
            });
        }

        VatTaxAmounts(filterObj);

        //Print account ledger
        $(document).on('click', '#printReport', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.vat.tax.print') }}";

            var branch_id = $('#branch_id').val();
            var branch_name = $('#branch_id').find('option:selected').data('branch_name');
            var tax_account_id = $('#tax_account_id').val();
            var tax_account_name = $('#tax_account_id').find('option:selected').data('tax_account_name');;
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            var currentTitle = document.title;

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    branch_name,
                    tax_account_id,
                    tax_account_name,
                    from_date,
                    to_date,
                }, success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: "",
                        pageTitle: "",
                        // footer: 'Footer Text',
                    });

                    var tempElement = document.createElement('div');
                    tempElement.innerHTML = data;
                    var filename = tempElement.querySelector('#title');
                    console.log(filename.innerHTML);
                    document.title = filename.innerHTML;

                    setTimeout(function() {
                        document.title = currentTitle;
                    }, 2000);
                }
            });
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
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
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
            format: 'DD-MM-YYYY',
        });

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();

            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').hide();
            var show_content = $(this).data('show');
            $('.' + show_content).show();
            $(this).addClass('tab_active');
        });
    </script>
@endpush
