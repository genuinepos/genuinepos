<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var vatTaxInputTable = $('#vat-tax-input-table').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": true,
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary'
            },
        ],
        "lengthMenu": [
            [50, 100, 500, 1000, -1],
            [50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('reports.vat.tax.input.table') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.tax_account_id = $('#tax_account_id').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [{
                data: 'date',
                name: 'account_ledgers.date'
            },
            {
                data: 'particulars',
                name: 'purchase.supplier.name'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'voucher_type',
                name: 'purchaseProduct.purchase.supplier.name'
            },
            {
                data: 'voucher_no',
                name: 'purchase.invoice_id'
            },
            {
                data: 'account_name',
                name: 'accounts.name'
            },
            {
                data: 'input_amount',
                name: 'purchaseProduct.purchase.supplier.name',
                className: 'text-end'
            },
            {
                data: 'on_amount',
                name: 'salesReturn.customer.name',
                className: 'text-end'
            },
        ],
        fnDrawCallback: function() {

            $('.data_preloader').hide();
        }
    });

    vatTaxInputTable.buttons().container().appendTo('#exportButtonsContainer');

    var vatTaxOutputTable = $('#vat-tax-output-table').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": true,
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary'
            },
        ],
        "lengthMenu": [
            [50, 100, 500, 1000, -1],
            [50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('reports.vat.tax.output.table') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.tax_account_id = $('#tax_account_id').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [{
                data: 'date',
                name: 'account_ledgers.date'
            },
            {
                data: 'particulars',
                name: 'sale.customer.name'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'voucher_type',
                name: 'saleProduct.sale.customer.name'
            },
            {
                data: 'voucher_no',
                name: 'sale.invoice_id'
            },
            {
                data: 'account_name',
                name: 'accounts.name'
            },
            {
                data: 'output_amount',
                name: 'saleProduct.sale.customer.name',
                className: 'text-end'
            },
            {
                data: 'on_amount',
                name: 'purchaseReturn.supplier.name',
                className: 'text-end'
            },
        ],
        fnDrawCallback: function() {

            $('.data_preloader').hide();
        }
    });

    vatTaxOutputTable.buttons().container().appendTo('#exportButtonsContainer');

    var filterObj = {
        branch_id: $('#branch_id').val() != '' || $('#branch_id').val() != undefined ? $('#branch_id').val() : null,
        tax_account_id: $('#tax_account_id').val() != '' || $('#tax_account_id').val() != undefined ? $('#tax_account_id').val() : null,
        from_date: $('#from_date').val() != '' || $('#from_date').val() != undefined ? $('#from_date').val() : null,
        to_date: $('#to_date').val() != '' || $('#to_date').val() != undefined ? $('#to_date').val() : null,
    };

    // Submit filter form by select input changing
    $(document).on('submit', '#filter_form', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        vatTaxInputTable.ajax.reload(null, false);
        vatTaxOutputTable.ajax.reload(null, false);

        filterObj = {
            branch_id: $('#branch_id').val() != '' || $('#branch_id').val() != undefined ? $('#branch_id').val() : null,
            tax_account_id: $('#tax_account_id').val() != '' || $('#tax_account_id').val() != undefined ? $('#tax_account_id').val() : null,
            from_date: $('#from_date').val() != '' || $('#from_date').val() != undefined ? $('#from_date').val() : null,
            to_date: $('#to_date').val() != '' || $('#to_date').val() != undefined ? $('#to_date').val() : null,
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
                } else {

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
        var tax_account_name = $('#tax_account_id').find('option:selected').data('tax_account_name');
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
            },
            success: function(data) {

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

                    toastr.error("{{ __('Net Connection Error.') }}");
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
