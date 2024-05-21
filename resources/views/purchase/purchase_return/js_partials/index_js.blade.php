<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Show session message by toster alert.
    @if (Session::has('successMsg'))
        toastr.success('{{ session('successMsg') }}');
    @endif

    var purchaseReturnsTable = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: 'Excel',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            },
            {
                extend: 'pdf',
                text: 'Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            },
            {
                extend: 'print',
                text: 'Print',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            },
        ],
        "processing": true,
        "serverSide": true,
        // aaSorting: [[0, 'asc']],
        "language": {
            "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
        },
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('purchase.returns.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.supplier_account_id = $('#supplier_account_id').val();
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
                data: 'voucher',
                name: 'voucher_no',
                className: 'fw-bold'
            },
            {
                data: 'parent_invoice_id',
                name: 'parent_invoice_id',
                className: 'fw-bold'
            },
            {
                data: 'supplier_name',
                name: 'suppliers.name'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'payment_status',
                name: 'parentBranch.name'
            },
            {
                data: 'total_qty',
                name: 'voucher_no',
                className: 'text-end fw-bold'
            },
            {
                data: 'net_total_amount',
                name: 'voucher_no',
                className: 'text-end fw-bold'
            },
            {
                data: 'return_discount',
                name: 'net_total_amount',
                className: 'text-end fw-bold'
            },
            {
                data: 'return_tax_amount',
                name: 'total_return_due',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_return_amount',
                name: 'total_return_due',
                className: 'text-end fw-bold'
            },
            {
                data: 'received',
                name: 'total_return_due',
                className: 'text-end fw-bold'
            },
            {
                data: 'due',
                name: 'total_return_due',
                className: 'text-end fw-bold'
            },
            {
                data: 'createdBy',
                name: 'createdBy.name',
                className: 'text-end fw-bold'
            },
        ],
        fnDrawCallback: function() {

            $('.data_preloader').hide();
        }
    });

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_form', function(e) {
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

    $(document).on('click', '#delete', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');
        $('#delete_form').attr('action', url);
        $.confirm({
            'title': 'Confirmation',
            'content': 'Are you sure, you want to delete?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-modal-primary',
                    'action': function() {
                        $('#delete_form').submit();
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
                } else {
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
