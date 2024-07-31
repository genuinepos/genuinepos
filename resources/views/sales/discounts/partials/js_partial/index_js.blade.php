<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-file-pdf"></i> Print',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
        ],
        "processing": true,
        "serverSide": true,
        // aaSorting: [[0, 'asc']],
        ajax: "{{ route('sales.discounts.index') }}",
        "language": {
            "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
        },
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        columns: [

            {
                data: 'name',
                name: 'discounts.name'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'status',
                name: 'discounts.status',
                className: 'text-start'
            },
            {
                data: 'start_at',
                name: 'discounts.start_at'
            },
            {
                data: 'end_at',
                name: 'discounts.end_at'
            },
            {
                data: 'discount_type',
                name: 'discounts.discount_type'
            },
            {
                data: 'discount_amount',
                name: 'discounts.discount_amount'
            },
            {
                data: 'priority',
                name: 'discounts.priority'
            },
            {
                data: 'brand_name',
                name: 'brands.name'
            },
            {
                data: 'category_name',
                name: 'categories.name'
            },
            {
                data: 'products',
                name: 'brands.name'
            },
            {
                data: 'action'
            },
        ]
    });

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '#addBtn', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#addOrEditModal').html(data);
                $('#addOrEditModal').modal('show');

                setTimeout(function() {

                    $('#discount_name').focus();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

    $(document).on('click', '#edit', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $('.data_preloader').show();
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#addOrEditModal').empty();
                $('#addOrEditModal').html(data);
                $('#addOrEditModal').modal('show');
                $('.data_preloader').hide();
                setTimeout(function() {

                    $('#discount_name').focus().select();
                }, 500);
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
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
            'message': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-danger',
                    'action': function() {
                        $('#deleted_form').submit();
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
    $(document).on('submit', '#deleted_form', function(e) {
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
                            type: 'get',
                            success: function(data) {
                                toastr.success(data);
                                table.ajax.reload();
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

    document.onkeyup = function() {

        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.ctrlKey && e.which == 13) {

            $('#addBtn').click();
            //return false;
        }
    }
</script>
