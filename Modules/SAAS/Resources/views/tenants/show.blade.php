<x-saas::admin-layout title="Customer Details">
    @push('css')
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}"/>
        <style>
            .main-content .digi-dataTable thead th {
                font-weight: 400;
                padding: 4px 22px 5px 4px;
                background-position-x: calc(100% - 10px);
                background-size: 9px;
                font-size: 11px;
            }

            .main-content .digi-dataTable tr td {
                vertical-align: middle;
                padding: 6px 5px 4px 3px;
                font-size: 11px;
            }

            table.dataTable tfoot th, table.dataTable tfoot td {
                padding: 4px 17px 4px 4px;
                border-top: 1px solid #111;
                font-size: 11px;
            }

            table td .btn-sm {
                padding: 1px 6px!important;
                font-size: 11px!important;
            }

            span.selection {
                width: 100%;
            }

            .details_table th{
                font-size: 11px!important;
                font-weight: 600;
            }

            .details_table td{
                font-size: 11px!important;
            }
        </style>
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Customer Details') }}</h5>
        </div>

        <div class="panel-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <table class="table table-sm details_table">
                        <tr>
                            <th>{{ __('Customer Name') }}</th>
                            <td class="text-start">: {{ $tenant?->user?->name }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('Email') }}</th>
                            <td class="text-start">: {{ $tenant?->user?->email }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('Phone') }}</th>
                            <td class="text-start">: {{ $tenant?->user?->phone }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-4">
                    <table class="table table-sm details_table">
                        <tr>
                            <th>{{ __('Company Name') }}</th>
                            <td class="text-start">: {{ $tenant->name }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('Subdomain') }}</th>
                            <td class="text-start">: {{ $tenant?->id }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('App Url') }}</th>
                            <td class="text-start">: {{ \Modules\SAAS\Utils\UrlGenerator::generateFullUrlFromDomain($tenant->id) }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-4">
                    <table class="table table-sm details_table">
                        <tr>
                            <th>{{ __('Current Plan') }}</th>
                            <td class="text-start">: {{ $tenant?->user?->userSubscription?->plan?->name }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('Plan Start Date') }}</th>
                            <td class="text-start">: {{ $tenant?->user?->userSubscription?->initial_plan_start_date }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('Store Count') }}</th>
                            <td class="text-start">: {{ $tenant?->user?->userSubscription?->current_shop_count }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('Has Company') }}</th>
                            <td class="text-start">:
                                @if ($tenant?->user?->userSubscription?->has_business == 1)
                                    <span class="text-success fw-bold">{{ __("Yes") }}</span>
                                @else
                                    <span class="text-danger fw-bold">{{ __("No") }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel-header">
            <h5>{{ __('Transactions') }}</h5>
        </div>

        <form id="filter_form" action="" class="px-4 mt-3">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label>{{ __('From Date') }}</label>
                    <input type="text" name="from_date" class="form-control form-control-sm" id="from_date" autocomplete="off">
                </div>

                <div class="col-md-3">
                    <label>{{ __('To Date') }}</label>
                    <input type="text" name="to_date" class="form-control form-control-sm" id="to_date" autocomplete="off">
                </div>

                <div class="col-md-3">
                    <button class="btn btn-sm btn-info text-white">Filter</button>
                </div>
            </div>
        </form>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-dashed table-hover digi-dataTable all-product-table table-striped" id="transactionsTable">
                            <thead>
                                <tr>
                                    <th class="text-start">{{ __('Action') }}</th>
                                    <th class="text-start">{{ __('Date') }}</th>
                                    <th class="text-start">{{ __('Customer') }}</th>
                                    <th class="text-start">{{ __('Transaction Type') }}</th>
                                    <th class="text-start">{{ __('Payment Status') }}</th>
                                    <th class="text-start">{{ __('Payment Date') }}</th>
                                    <th class="text-start">{{ __('Gateway') }}</th>
                                    <th class="text-start">{{ __('Trans. ID') }}</th>
                                    <th class="text-start">{{ __('Net Total') }}</th>
                                    <th class="text-start">{{ __('Discount') }}</th>
                                    <th class="text-start">{{ __('Total Payable') }}</th>
                                    <th class="text-start">{{ __('Paid') }}</th>
                                    <th class="text-start">{{ __('Due') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="bg-secondary">
                                    <th colspan="8" class="text-white text-end">{{ __('Total') }} : </th>
                                    <th class="text-white text-start" id="net_total"></th>
                                    <th class="text-white text-start" id="discount"></th>
                                    <th class="text-white text-start" id="total_payable_amount"></th>
                                    <th class="text-white text-start" id="paid"></th>
                                    <th class="text-white text-start" id="due"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
        <script src="{{ asset('backend/js/number-bdt-formater.js') }}"></script>
        <script>
            $(function() {
                $('.select2').select2();
                $("#from_date").datepicker({
                    dateFormat: 'yy-mm-dd'
                });

                $("#to_date").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            });

            var transactionsTable = $("#transactionsTable").DataTable({
                processing: true,
                serverSide: true,
                searchable: true,
                "pageLength": 100,
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                "ajax": {
                    "url": "{{ route('saas.tenants.user.subscription.transaction.index', ['userId' => $tenant?->user?->id ? $tenant?->user?->id : 0]) }}",
                    "data": function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                columns: [
                    { data: 'action', className: 'text-start' },
                    { data: 'created_at', name: 'user_subscription_transactions.created_at', className: 'text-start' },
                    { data: 'user', name: 'users.name', className: 'text-start' },
                    { data: 'transaction_type', name: 'tenants.id', className: 'text-start fw-bold' },
                    { data: 'payment_status', name: 'user_subscription_transactions.payment_status', className: 'text-start' },
                    { data: 'payment_date', name: 'user_subscription_transactions.payment_date', className: 'text-start' },
                    { data: 'payment_method_name', name: 'user_subscription_transactions.payment_method_name', className: 'text-start' },
                    { data: 'payment_trans_id', name: 'user_subscription_transactions.payment_trans_id', className: 'text-start' },
                    { data: 'net_total', name: 'user_subscription_transactions.net_total', className: 'text-start fw-bold' },
                    { data: 'discount', name: 'user_subscription_transactions.discount', className: 'text-start fw-bold' },
                    { data: 'total_payable_amount', name: 'user_subscription_transactions.total_payable_amount', className: 'text-start fw-bold' },
                    { data: 'paid', name: 'user_subscription_transactions.paid', className: 'text-start text-success fw-bold' },
                    { data: 'due', name: 'user_subscription_transactions.due', className: 'text-start text-danger fw-bold' }
                ],
                fnDrawCallback: function() {

                    var net_total = sum_table_col($('.digi-dataTable'), 'net_total');
                    $('#net_total').text(bdFormat(net_total));

                    var discount = sum_table_col($('.digi-dataTable'), 'discount');
                    $('#discount').text(bdFormat(discount));

                    var total_payable_amount = sum_table_col($('.digi-dataTable'), 'total_payable_amount');
                    $('#total_payable_amount').text(bdFormat(total_payable_amount));

                    var paid = sum_table_col($('.digi-dataTable'), 'paid');
                    $('#paid').text(bdFormat(paid));

                    var due = sum_table_col($('.digi-dataTable'), 'due');
                    $('#due').text(bdFormat(due));
                }
            });

            function sum_table_col(table, class_name) {
                var sum = 0;
                table.find('tbody').find('tr').each(function() {
                    if (parseFloat($(this).find('.' + class_name).data('value'))) {
                        sum += parseFloat(
                            $(this).find('.' + class_name).data('value')
                        );
                    }
                });
                return sum;
            }

            //Submit filter form by select input changing
            $(document).on('submit', '#filter_form', function(e) {
                e.preventDefault();
                transactionsTable.ajax.reload();
            });
        </script>
    @endpush
</x-saas::admin-layout>
