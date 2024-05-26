<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    var contactTable = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                }
            },
        ],
        "processing": true,
        "serverSide": true,
        aaSorting: [
            [0, 'asc']
        ],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('contacts.manage.customer.index', \App\Enums\ContactType::Customer->value) }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
            }
        },
        columns: [{
                data: 'action',
                name: 'action'
            },
            {
                data: 'contact_id',
                name: 'contacts.contact_id'
            },
            {
                data: 'name',
                name: 'contacts.name'
            },
            {
                data: 'phone',
                name: 'contacts.phone'
            },
            // {data: 'group_name', name: 'customer_groups.group_name'},
            {
                data: 'credit_limit',
                name: 'contacts.credit_limit'
            },
            {
                data: 'opening_balance',
                name: 'opening_balance',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_sale',
                name: 'contacts.business_name',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_purchase',
                name: 'contacts.business_name',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_return',
                name: 'contacts.business_name',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_received',
                name: 'contacts.business_name',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_paid',
                name: 'contacts.business_name',
                className: 'text-end fw-bold'
            },
            {
                data: 'current_balance',
                name: 'contacts.business_name',
                className: 'text-end fw-bold'
            },
            {
                data: 'status',
                name: 'status',
                name: 'contacts.business_name',
            },
        ],
        fnDrawCallback: function() {

            var opening_balance = sum_table_col($('.data_tbl'), 'opening_balance');
            $('#opening_balance').text(bdFormat(opening_balance));

            var total_sale = sum_table_col($('.data_tbl'), 'total_sale');
            $('#total_sale').text(bdFormat(total_sale));

            var total_purchase = sum_table_col($('.data_tbl'), 'total_purchase');
            $('#total_purchase').text(bdFormat(total_purchase));

            var total_return = sum_table_col($('.data_tbl'), 'total_return');
            $('#total_return').text(bdFormat(total_return));

            var total_received = sum_table_col($('.data_tbl'), 'total_received');
            $('#total_received').text(bdFormat(total_received));

            var total_paid = sum_table_col($('.data_tbl'), 'total_paid');
            $('#total_paid').text(bdFormat(total_paid));

            var current_balance = sum_table_col($('.data_tbl'), 'current_balance');
            $('#current_balance').text(bdFormat(current_balance));

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
        contactTable.ajax.reload();
    });

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function() {
        // Add category by ajax
        $('#addContact').on('click', function(e) {

            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#addOrEditContactModal').html(data);
                    $('#addOrEditContactModal').modal('show');

                    setTimeout(function() {

                        $('#contact_name').focus();
                    }, 500);

                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#editContact', function(e) {

            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#addOrEditContactModal').html(data);
                    $('#addOrEditContactModal').modal('show');

                    setTimeout(function() {

                        $('#contact_name').focus().select();
                    }, 500);

                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#deleteContact', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#delete_contact_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#delete_contact_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {
                            console.log('Deleted canceled.');
                        }
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#delete_contact_form', function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                async: false,
                data: request,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    contactTable.ajax.reload();
                    toastr.error(data);
                    $('#delete_contact_form')[0].reset();
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#money_receipts', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#moneyReceiptListModal').html(data);
                    $('#moneyReceiptListModal').modal('show');
                    $('.data_preloader').hide();
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#delete_money_receipt_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                async: false,
                data: request,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.error(data);
                    $('#delete_money_receipt_form')[0].reset();

                    if (deleteAbleMoneryReceiptVoucherTr) {

                        deleteAbleMoneryReceiptVoucherTr.remove();
                    }
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        // Show sweet alert for delete
        $(document).on('click', '#change_status', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            $.confirm({
                'title': 'Changes Status Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $.ajax({
                                url: url,
                                type: 'post',
                                success: function(data) {
                                    toastr.success(data);
                                    contactTable.ajax.reload();
                                }
                            });
                        }
                    },

                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {
                            console.log('Confirmation canceled.');
                        }
                    }
                }
            });
        });
    });

    //Print supplier report
    $(document).on('click', '#printReport', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        var branch_id = $('#branch_id').val();
        var branch_name = $('#branch_id').find('option:selected').data('branch_name');
        var customer_account_id = $('#customer_account_id').val();
        var customer_name = $('#customer_account_id').find('option:selected').data('customer_name');

        var currentTitle = document.title;

        $.ajax({
            url: url,
            type: 'get',
            data: {
                branch_id,
                branch_name,
                customer_account_id,
                customer_name,
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
            }
        });
    });

    document.onkeyup = function() {

        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.ctrlKey && e.which == 13) {

            $('#addModal').modal('show');
            setTimeout(function() {

                $('#name').focus();
            }, 500);
            //return false;
        }
    }
</script>
