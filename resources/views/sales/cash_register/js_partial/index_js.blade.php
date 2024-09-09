<script>
    var cashRegistersTable = $('#cash-registers-table').DataTable({
        "processing": true,
        "serverSide": true,
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> ' + "{{ __('Excel') }}" + '',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> ' + "{{ __('Pdf') }}" + '',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> ' + "{{ __('Print') }}" + '',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
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
            "url": "{{ route('cash.register.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.status = $('#status').val();
                d.user_id = $('#user_id').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [{
                data: 'cash_counter',
                name: 'cash_counters.counter_name'
            },
            {
                data: 'user',
                name: 'users.name',
                className: 'fw-bold'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'opened_at',
                name: 'parentBranch.name'
            },
            {
                data: 'closed_at',
                name: 'users.last_name',
            },
            {
                data: 'opening_cash',
                name: 'cash_counters.short_name',
                className: 'text-end fw-bold'
            },
            {
                data: 'closing_cash',
                name: 'cash_registers.closing_cash',
                className: 'text-end fw-bold'
            },
            {
                data: 'status',
                name: 'cash_registers.status',
            },
            {
                data: 'action'
            }
        ],
        fnDrawCallback: function() {

            $('.data_preloader').hide();
        }
    });

    $(document).on('submit', '#filter_form', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        cashRegistersTable.ajax.reload();
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

    $(document).on('click', '#cashRegisterDetailsBtn', function(e) {

        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#cashRegisterDetailsAndCloseModal').empty();
                $('#cashRegisterDetailsAndCloseModal').html(data);
                $('#cashRegisterDetailsAndCloseModal').modal('show');
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);
            }
        });
    });

    $(document).on('click', '#closeCashRegisterBtn', function(e) {

        e.preventDefault();
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#cashRegisterDetailsAndCloseModal').empty();
                $('#cashRegisterDetailsAndCloseModal').html(data);
                $('#cashRegisterDetailsAndCloseModal').modal('show');

                setTimeout(function() {

                    $('#closing_note').focus().select();
                }, 500);
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
