<script>
    var businessName = "{{ $generalSettings['business_or_shop__business_name'] . '(' . __('Business') . ')' }}";

    var suppliersReportTable = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary',
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary',
            },
        ],
        "processing": true,
        "serverSide": true,
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('reports.suppliers.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.supplier_account_id = $('#supplier_account_id').val();
            }
        },
        columns: [{
                data: 'name',
                name: 'accounts.name'
            },
            {
                data: 'phone',
                name: 'phone.name'
            },
            {
                data: 'opening_balance',
                name: 'opening_balance',
                className: 'text-end fw-bold'
            },

            {
                data: 'total_purchase',
                name: 'contacts.business_name',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_sale',
                name: 'contacts.business_name',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_return',
                name: 'contacts.business_name',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_paid',
                name: 'contacts.business_name',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_received',
                name: 'contacts.business_name',
                className: 'text-end fw-bold'
            },
            {
                data: 'current_balance',
                name: 'contacts.business_name',
                className: 'text-end fw-bold'
            },
        ],
        fnDrawCallback: function() {

            var opening_balance = sum_table_col($('.data_tbl'), 'opening_balance');
            var __opening_balance = opening_balance < 0 ? '(' + bdFormat(Math.abs(opening_balance)) + ')' : bdFormat(opening_balance);
            $('#opening_balance').text(__opening_balance);

            var total_purchase = sum_table_col($('.data_tbl'), 'total_purchase');
            $('#total_purchase').text(bdFormat(total_purchase));

            var total_sale = sum_table_col($('.data_tbl'), 'total_sale');
            $('#total_sale').text(bdFormat(total_sale));

            var total_return = sum_table_col($('.data_tbl'), 'total_return');
            var __total_returne = total_return < 0 ? '(' + bdFormat(Math.abs(total_return)) + ')' : bdFormat(total_return);
            $('#total_return').text(__total_returne);

            var total_paid = sum_table_col($('.data_tbl'), 'total_paid');
            $('#total_paid').text(bdFormat(total_paid));

            var total_received = sum_table_col($('.data_tbl'), 'total_received');
            $('#total_received').text(bdFormat(total_received));

            var current_balance = sum_table_col($('.data_tbl'), 'current_balance');
            var __current_balance = current_balance < 0 ? '(' + bdFormat(Math.abs(current_balance)) + ')' : bdFormat(current_balance);
            $('#current_balance').text(__current_balance);

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
        suppliersReportTable.ajax.reload();
    });

    $(document).on('click', '#printReport', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        var branch_id = $('#branch_id').val();
        var branch_name = $('#branch_id').find('option:selected').data('branch_name');
        var supplier_account_id = $('#supplier_account_id').val();
        var supplier_name = $('#supplier_account_id').find('option:selected').data('customer_name');

        var currentTitle = document.title;

        $.ajax({
            url: url,
            type: 'get',
            data: {
                branch_id,
                branch_name,
                supplier_account_id,
                supplier_name,
            },
            success: function(data) {

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 700,
                    header: null,
                });

                var tempElement = document.createElement('div');
                tempElement.innerHTML = data;
                var filename = tempElement.querySelector('#title');

                document.title = filename.innerHTML;

                setTimeout(function() {
                    document.title = currentTitle;
                }, 2000);
            },
            error: function(err) {

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
</script>
