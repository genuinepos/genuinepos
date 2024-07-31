<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Show session message by toster alert.
    @if (Session::has('successMsg'))
        toastr.success('{{ session('successMsg') }}');
    @endif

    $(document).ready(function() {
        function formatState(state) {
            if (!state.id) {
                return state.text; // optgroup
            }

            var icon = $(state.element).data('icon');
            var color = $(state.element).data('color');

            var $state = $(
                '<span><i class="' + icon + '" style="color:' + color + '"></i> ' + state.text + '</span>'
            );
            return $state;
        };

        $("#status_id").select2({
            templateResult: formatState,
            templateSelection: formatState
        });
    });

    var jobCardsTable = $('#job-cards-table').DataTable({
        "processing": true,
        "serverSide": true,
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> ' + "{{ __('Excel') }}" + '',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> ' + "{{ __('Pdf') }}" + '',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> ' + "{{ __('Print') }}" + '',
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
            "url": "{{ route('services.job.cards.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.customer_account_id = $('#customer_account_id').val();
                d.service_type = $('#service_type').val();
                d.status_id = $('#status_id').val();
                d.brand_id = $('#brand_id').val();
                d.device_id = $('#device_id').val();
                d.device_model_id = $('#device_model_id').val();
                d.user_id = $('#user_id').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [{
                data: 'action'
            },
            {
                data: 'job_no',
                name: 'service_job_cards.job_no',
                className: 'fw-bold'
            },
            {
                data: 'service_type',
                name: 'service_job_cards.service_type'
            },
            {
                data: 'customer',
                name: 'customers.name'
            },
            {
                data: 'date',
                name: 'service_job_cards.date_ts'
            },
            {
                data: 'delivery_date',
                name: 'service_job_cards.delivery_date_ts'
            },
            {
                data: 'due_date',
                name: 'service_job_cards.due_date_ts'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'quotation_no',
                name: 'quotations.quotation_id',
                className: 'fw-bold'
            },
            {
                data: 'invoice_id',
                name: 'sales.invoice_id',
                className: 'fw-bold'
            },
            {
                data: 'status',
                name: 'service_status.name',
                className: 'text-start'
            },
            {
                data: 'device_name',
                name: 'service_devices.name',
            },
            {
                data: 'device_model_name',
                name: 'service_device_models.name',
            },
            {
                data: 'serial_no',
                name: 'service_job_cards.serial_no',
            },
            {
                data: 'total_cost',
                name: 'service_job_cards.total_cost',
            },
            {
                data: 'created_by',
                name: 'created_by.name',
                className: 'text-end fw-bold'
            },
        ],
        fnDrawCallback: function() {

            var total_cost = sum_table_col($('.data_tbl'), 'total_cost');
            $('#total_cost').text(bdFormat(total_cost));

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
        jobCardsTable.ajax.reload();
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

    @if (auth()->user()->can('job_cards_change_status'))
        $(document).on('click', '#changeStatus', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#changeStatusModal').empty();
                    $('#changeStatusModal').html(data);
                    $('#changeStatusModal').modal('show');

                    setTimeout(function() {

                        $('#job_card_status_id').focus();
                    }, 1000);
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
    @endif

    @if (auth()->user()->can('job_cards_delete'))
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

                    toastr.error(data);
                    jobCardsTable.ajax.reload(null, false);
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
    @endif

    $(document).on('click', '#generateLabel', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');
        // var url = event.getAttribute('href');
        // var filename = event.getAttribute('data-filename');
        // var print_page_size = $('#print_page_size').val();
        // var currentTitle = document.title;

        $.ajax({
            url: url,
            type: 'get',
            // data: {
            //     print_page_size
            // },
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 1000,
                    header: null,
                    footer: null,
                });

                // document.title = filename;

                // setTimeout(function() {
                //     document.title = currentTitle;
                // }, 2000);
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
