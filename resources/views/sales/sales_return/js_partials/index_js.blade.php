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
            "url": "{{ route('sales.returns.index') }}",
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
                data: 'voucher_no',
                name: 'sale_returns.invoice_id',
                className: 'fw-bold'
            },
            {
                data: 'invoice_id',
                name: 'sales.invoice_id',
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
                data: 'total_qty',
                name: 'total_qty',
                className: 'text-end fw-bold'
            },
            {
                data: 'net_total_amount',
                name: 'net_total_amount',
                className: 'text-end fw-bold'
            },
            {
                data: 'return_discount_amount',
                name: 'return_discount_amount',
                className: 'text-end fw-bold'
            },
            {
                data: 'return_tax_amount',
                name: 'return_tax_amount',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_return_amount',
                name: 'total_return_amount',
                className: 'text-end fw-bold'
            },
            {
                data: 'paid',
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
            var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
            $('#total_qty').text(bdFormat(total_qty));

            var net_total_amount = sum_table_col($('.data_tbl'), 'net_total_amount');
            $('#net_total_amount').text(bdFormat(net_total_amount));

            var return_discount_amount = sum_table_col($('.data_tbl'), 'return_discount_amount');
            $('#return_discount_amount').text(bdFormat(return_discount_amount));

            var return_tax_amount = sum_table_col($('.data_tbl'), 'return_tax_amount');
            $('#return_tax_amount').text(bdFormat(return_tax_amount));

            var total_return_amount = sum_table_col($('.data_tbl'), 'total_return_amount');
            $('#total_return_amount').text(bdFormat(total_return_amount));

            var paid = sum_table_col($('.data_tbl'), 'paid');
            $('#paid').text(bdFormat(paid));

            var due = sum_table_col($('.data_tbl'), 'due');
            $('#due').text(bdFormat(due));

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
