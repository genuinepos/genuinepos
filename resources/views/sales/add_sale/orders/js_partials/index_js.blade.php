<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Show session message by toster alert.
    @if (Session::has('successMsg'))
        toastr.success('{{ session('successMsg') }}');
    @endif

    var table = $('.data_tbl').DataTable({
        "processing": true,
        "serverSide": true,
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            },
        ],
        "language": {
            "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
        },
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('sale.orders.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.customer_account_id = $('#customer_account_id').val();
                d.payment_status = $('#payment_status').val();
                d.user_id = $('#user_id').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [{
                data: 'action'
            },
            {
                data: 'date',
                name: 'date'
            },
            {
                data: 'order_id',
                name: 'sales.order_id',
                className: 'fw-bold'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'customer_name',
                name: 'customers.name'
            },
            {
                data: 'payment_status',
                name: 'created_by.name',
                className: 'text-start'
            },
            {
                data: 'delivery_status',
                name: 'created_by.name',
                className: 'text-start'
            },
            {
                data: 'total_item',
                name: 'sales.quotation_id',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_ordered_qty',
                name: 'total_ordered_qty',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_delivered_qty',
                name: 'total_delivered_qty',
                className: 'text-end text-success fw-bold'
            },
            {
                data: 'total_left_qty',
                name: 'total_left_qty',
                className: 'text-end text-danger fw-bold'
            },
            {
                data: 'total_invoice_amount',
                name: 'total_invoice_amount',
                className: 'text-end fw-bold'
            },
            {
                data: 'received_amount',
                name: 'paid',
                className: 'text-end fw-bold'
            },
            {
                data: 'due',
                name: 'due',
                className: 'text-end fw-bold'
            },
            {
                data: 'created_by',
                name: 'created_by.name',
                className: 'text-end fw-bold'
            },

        ],
        fnDrawCallback: function() {
            var total_item = sum_table_col($('.data_tbl'), 'total_item');
            $('#total_item').text(bdFormat(total_item));

            var total_ordered_qty = sum_table_col($('.data_tbl'), 'total_ordered_qty');
            $('#total_ordered_qty').text(bdFormat(total_ordered_qty));

            var total_delivered_qty = sum_table_col($('.data_tbl'), 'total_delivered_qty');
            $('#total_delivered_qty').text(bdFormat(total_delivered_qty));

            var total_left_qty = sum_table_col($('.data_tbl'), 'total_left_qty');
            $('#total_left_qty').text(bdFormat(total_left_qty));

            var total_invoice_amount = sum_table_col($('.data_tbl'), 'total_invoice_amount');
            $('#total_invoice_amount').text(bdFormat(total_invoice_amount));

            var received_amount = sum_table_col($('.data_tbl'), 'received_amount');
            $('#received_amount').text(bdFormat(received_amount));

            var due = sum_table_col($('.data_tbl'), 'due');
            $('#due').text(due < 0 ? '(' + bdFormat(Math.abs(due)) + ')' : bdFormat(Math.abs(due)));

            $('.data_preloader').hide();
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
        $('.data_preloader').show();
        table.ajax.reload();
    });

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

                table.ajax.reload(null, false);
                toastr.error(data);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);
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
</script>
