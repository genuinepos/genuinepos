<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
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

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();

        $('.tab_btn').removeClass('tab_active');
        $('.tab_contant').hide();
        var show_content = $(this).data('show');
        $('.' + show_content).show();
        $(this).addClass('tab_active');
    });
</script>

<script type="text/javascript">
    function litepicker(idName) {

        new Litepicker({
            singleMode: true,
            element: document.getElementById(idName),
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
    }

    litepicker('ledger_from_date');
    litepicker('ledger_to_date');

    @if (auth()->user()->can('view_add_sale') || auth()->user()->can('pos_all') || auth()->user()->can('service_invoices_index'))
        litepicker('sales_from_date');
        litepicker('sales_to_date');
    @endif

    @if (auth()->user()->can('sales_orders_index'))
        litepicker('sales_order_from_date');
        litepicker('sales_order_to_date');
    @endif

    @if (auth()->user()->can('purchase_all'))
        litepicker('purchases_from_date');
        litepicker('purchases_to_date');
    @endif

    @if (auth()->user()->can('purchase_order_index'))
        litepicker('purchase_orders_from_date');
        litepicker('purchase_orders_to_date');
    @endif

    @if (auth()->user()->can('receipts_index'))
        litepicker('receipts_from_date');
        litepicker('receipts_to_date');
    @endif

    @if (auth()->user()->can('payments_index'))
        litepicker('payments_from_date');
        litepicker('payments_to_date');
    @endif
</script>

<script>
    @if (auth()->user()->can('customer_ledger') || auth()->user()->can('view_add_sale') || auth()->user()->can('pos_all') || auth()->user()->can('service_invoices_index') || auth()->user()->can('sales_orders_index') || auth()->user()->can('purchase_all') || auth()->user()->can('purchase_order_index') || auth()->user()->can('receipts_index') || auth()->user()->can('payments_index'))

        function getAccountClosingBalance(filterObj, parentDiv, changeLedgerTableCurrentTotal = false) {

            var url = "{{ route('accounts.balance', $contact?->account?->id) }}";

            $.ajax({
                url: url,
                type: 'get',
                data: filterObj,
                success: function(data) {

                    if (parentDiv) {

                        $('#' + parentDiv + ' .opening_balance').html(data.opening_balance_in_flat_amount_string);
                        $('#' + parentDiv + ' .total_sale').html(data.total_sale_string);
                        $('#' + parentDiv + ' .total_purchase').html(data.total_purchase_string);
                        $('#' + parentDiv + ' .total_return').html(data.total_return_string);
                        $('#' + parentDiv + ' .total_received').html(data.total_received_string);
                        $('#' + parentDiv + ' .total_paid').html(data.total_paid_string);
                        $('#' + parentDiv + ' .closing_balance').html(data.closing_balance_in_flat_amount_string);
                    } else {

                        $('.opening_balance').html(data.opening_balance_in_flat_amount_string);
                        $('.total_sale').html(data.total_sale_string);
                        $('.total_purchase').html(data.total_purchase_string);
                        $('.total_return').html(data.total_return_string);
                        $('.total_received').html(data.total_received_string);
                        $('.total_paid').html(data.total_paid_string);
                        $('.closing_balance').html(data.closing_balance_in_flat_amount_string);
                    }

                    if (changeLedgerTableCurrentTotal == true) {

                        $('#ledger_table_total_debit').html(data.all_total_debit_string);
                        $('#ledger_table_total_credit').html(data.all_total_debit_string);
                        $('#ledger_table_current_balance').html(data.closing_balance_string);
                    }

                }
            });
        }

        var filterObj = {
            branch_id: null,
            from_date: null,
            to_date: null,
        };
        getAccountClosingBalance(filterObj, '', true);
    @endif
</script>

<script>
    @if (auth()->user()->can('shipment_access') && $generalSettings['subscription']->features['sales'] == \App\Enums\BooleanType::True->value)
        $(document).on('click', '#editShipmentDetails', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#editShipmentDetailsModal').html(data);
                    $('#editShipmentDetailsModal').modal('show');
                    $('.data_preloader').hide();

                    setTimeout(function() {

                        $('#shipment_shipment_address').focus().select();
                    }, 500);
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    @endif

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

                    toastr.error("{{ __('Net Connection Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    });
</script>

<script>
    $.ajaxSetup({
        cache: false
    });

    function reloadAllAccountSummaryArea() {

        var summeryReloaderDatas = [
            @if (auth()->user()->can('customer_ledger'))
                {
                    filterDatePrefix: 'ledger_',
                    filterSummerParentDiv: 'for_ledger',
                    changeLedgerTableCurrentTotal: true,
                },
            @endif

            @if (auth()->user()->can('view_add_sale') || auth()->user()->can('pos_all') || auth()->user()->can('service_invoices_index'))
                {
                    filterDatePrefix: 'sales_',
                    filterSummerParentDiv: 'for_sales',
                    changeLedgerTableCurrentTotal: false,
                },
            @endif

            @if (auth()->user()->can('sales_orders_index'))
                {
                    filterDatePrefix: 'sales_order_',
                    filterSummerParentDiv: 'for_sales_order',
                    changeLedgerTableCurrentTotal: false,
                },
            @endif

            @if (auth()->user()->can('purchase_all'))
                {
                    filterDatePrefix: 'purchases_',
                    filterSummerParentDiv: 'for_purchases',
                    changeLedgerTableCurrentTotal: false,
                },
            @endif

            @if (auth()->user()->can('purchase_order_index'))
                {
                    filterDatePrefix: 'purchases_orders_',
                    filterSummerParentDiv: 'for_purchase_orders',
                    changeLedgerTableCurrentTotal: false,
                },
            @endif

            @if (auth()->user()->can('receipts_index'))
                {
                    filterDatePrefix: 'receipts_',
                    filterSummerParentDiv: 'for_receipts',
                    changeLedgerTableCurrentTotal: false,
                },
            @endif

            @if (auth()->user()->can('payments_index'))
                {
                    filterDatePrefix: 'payments_',
                    filterSummerParentDiv: 'for_payments',
                    changeLedgerTableCurrentTotal: false,
                },
            @endif
        ];

        summeryReloaderDatas.forEach(function(element) {
            var filterObj = {
                branch_id: $('#' + element.filterDatePrefix + 'branch_id').val(),
                from_date: $('#' + element.filterDatePrefix + 'from_date').val(),
                to_date: $('#' + element.filterDatePrefix + 'to_date').val(),
            };

            getAccountClosingBalance(filterObj, element.filterSummerParentDiv, element.changeLedgerTableCurrentTotal);
        });
    }

    @if (auth()->user()->can('delete_add_sale') || auth()->user()->can('pos_delete') || auth()->user()->can('purchase_delete') || auth()->user()->can('purchase_order_delete') || auth()->user()->can('payments_delete') || auth()->user()->can('receipts_delete'))

        var tableId = '';
        $(document).on('click', '#delete', function(e) {

            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            tableId = $(this).closest('tr').closest('table').attr('id');
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
                type: 'post',
                data: request,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.error(data);
                    $('.common-reloader').DataTable().ajax.reload();

                    reloadAllAccountSummaryArea();
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    @endif
</script>
