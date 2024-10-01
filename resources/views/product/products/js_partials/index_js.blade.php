<script>
    $('.loading_button').hide();

    var productTable = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: [3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: [3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
                'customize': function(doc) {
                    doc.styles.tableHeader.alignment = "left";
                    doc.defaultStyle.fontSize = 7; // Set your custom font size here
                    doc.styles.tableHeader.fontSize = 7; // Header font size

                    // var columnCount = doc.content[1].table.body[0].length;

                    // // Calculate the total for the 8th column (zero-based index: 8)
                    // var totalQuantity = 0;
                    // doc.content[1].table.body.forEach(function(row, index) {
                    //     // Skip the first row (header)
                    //     if (index > 0 && row[4] && typeof row[4].text === "string") {
                    //         // Extract the numeric part and remove any commas
                    //         var quantityText = row[4].text.split('/')[0].trim(); // Get the part before "/pieces"
                    //         quantityText = quantityText.replace(/,/g, ''); // Remove commas for parsing

                    //         // Ensure the value is numeric before adding to total
                    //         if (!isNaN(quantityText)) {
                    //             totalQuantity += parseFloat(quantityText);
                    //         }
                    //     }
                    // });

                    // // Define the footer row with the same number of columns
                    // var footerRow = [];

                    // // Loop through each column
                    // for (var i = 0; i < columnCount; i++) {
                    //     if (i === 4) {
                    //         // For the 8th column, add the calculated total value with "/Nos" suffix
                    //         footerRow.push({
                    //             text: totalQuantity.toFixed(2) + " /Nos",
                    //             alignment: 'left',
                    //             margin: [0, 10, 0, 0],
                    //             bold: true
                    //         });
                    //     } else {
                    //         // For other columns, add a placeholder (e.g., '---')
                    //         footerRow.push({
                    //             text: '---',
                    //             alignment: 'left',
                    //             margin: [0, 10, 0, 0]
                    //         });
                    //     }
                    // }

                    // // Push the footer row into the table content
                    // if (doc.content[1].table && doc.content[1].table.body) {
                    //     doc.content[1].table.body.push(footerRow);
                    // }

                    // // Add custom footer at the bottom of each page
                    // doc.footer = function(currentPage, pageCount) {
                    //     return {
                    //         columns: [{
                    //                 text: "Footer text left",
                    //                 alignment: "left"
                    //             },
                    //             {
                    //                 text: currentPage.toString() + ' of ' + pageCount,
                    //                 alignment: "right"
                    //             }
                    //         ],
                    //         margin: [10, 0] // Margin for the footer at the bottom of the page
                    //     };
                    // };
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: [3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            },
        ],
        "processing": true,
        "serverSide": true,
        // aaSorting: [
        //     [0, 'asc']
        // ],
        "language": {
            "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
        },
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('products.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.type = $('#product_type').val();
                d.category_id = $('#category_id').val();
                d.brand_id = $('#brand_id').val();
                d.unit_id = $('#unit_id').val();
                d.tax_ac_id = $('#tax_ac_id').val();
                d.status = $('#status').val();
                d.is_for_sale = $('#is_for_sale').val();
            }
        },
        columns: [{
                data: 'multiple_delete',
                name: 'products.name',
                orderable: false
            },
            {
                data: 'photo',
                name: 'name'
            },
            {
                data: 'action',
                name: 'name'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'access_branches',
                name: 'product_code'
            },
            {
                data: 'product_cost_with_tax',
                name: 'product_cost_with_tax',
                className: 'fw-bold'
            },
            {
                data: 'product_price',
                name: 'product_price',
                className: 'fw-bold'
            },
            {
                data: 'quantity',
                name: 'product_price',
                className: 'fw-bold'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'cate_name',
                name: 'categories.name'
            },
            {
                data: 'brand_name',
                name: 'brands.name'
            },
            {
                data: 'tax_name',
                name: 'brands.name'
            },
            {
                data: 'status',
                name: 'products.status'
            },
        ],
    });

    $(document).ready(function() {

        $(document).on('change', '.submit_able', function() {

            productTable.ajax.reload();
        });
    });

    $(document).on('ifChanged', '#is_for_sale', function() {

        productTable.ajax.reload();
    });

    $(document).on('change', '.all', function() {

        if ($(this).is(':CHECKED', true)) {

            $('.data_id').prop('checked', true);
        } else {

            $('.data_id').prop('checked', false);
        }
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

    //Check purchase and generate burcode
    $(document).on('click', '#check_pur_and_gan_bar_button', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                } else {

                    window.location = data;
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
            'content': 'Are you sure, you want to delete?',
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

                productTable.ajax.reload(null, false);
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

    // Show sweet alert for delete
    $(document).on('click', '#change_status', function(e) {
        e.preventDefault();
        // var url = $(this).attr('href');
        var url = $(this).data('url');

        $.confirm({
            'title': 'Changes Status',
            'message': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'Yes btn-danger',
                    'action': function() {
                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function(data) {

                                if (!$.isEmptyObject(data.errorMsg)) {
                                    toastr.error(data.errorMsg);
                                    return;
                                }
                                toastr.success(data);
                                productTable.ajax.reload();
                            }
                        });
                    }
                },
                'No': {
                    'class': 'no btn-modal-primary',
                    'action': function() {
                        // console.log('Confirmation canceled.');
                    }
                }
            }
        });
    });

    $(document).on('click', '.multipla_delete_btn', function(e) {
        e.preventDefault();

        $('#action').val('multiple_delete');

        $.confirm({
            'title': 'Confirmation',
            'content': 'Are you sure, you want to delete?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-modal-primary',
                    'action': function() {
                        $('#multiple_action_form').submit();
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

    $(document).on('click', '.multipla_deactive_btn', function(e) {
        e.preventDefault();

        $('#action').val('multipla_deactive');

        $.confirm({
            'title': 'Deactive Confirmation',
            'content': 'Are you sure to deactive selected all?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-danger',
                    'action': function() {
                        $('#multiple_action_form').submit();
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
    $(document).on('submit', '#multiple_action_form', function(e) {
        e.preventDefault();

        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'Attention');
                } else {

                    productTable.ajax.reload();
                    toastr.success(data, 'Attention');
                }
            }
        });
    });

    $(document).on('click', '#openingStock', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#addOrEditOpeningStock').html(data);
                $('#addOrEditOpeningStock').modal('show');

                // setTimeout(function() {

                //     $('#brand_name').focus();
                // }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });
</script>
