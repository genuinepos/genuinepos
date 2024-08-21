<script>
    var deviceModelsTable = $('#device-models-table').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: 'Excel',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                text: 'Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: 'Print',
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
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('services.settings.device.models.table') }}",
        columns: [
            // {data: 'DT_RowIndex'},
            {
                data: 'name',
                name: 'service_device_models.name'
            },
            {
                data: 'brand_name',
                name: 'brands.name'
            },
            {
                data: 'device_name',
                name: 'service_devices.name'
            },
            {
                data: 'service_checklist',
                name: 'service_device_models.service_checklist'
            },
            {
                data: 'created_by',
                name: 'users.name'
            },
            {
                data: 'action',
                name: 'action'
            },
        ]
    });

    $(document).on('click', '#addDeviceModel', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#deviceModelAddOrEditModal').html(data);
                $('#deviceModelAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#device_model_name').focus();
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

                toastr.error(err.responseJSON.message);
            }
        });
    });

    $(document).on('click', '#editDeviceModel', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#deviceModelAddOrEditModal').html(data);
                $('#deviceModelAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#device_model_name').focus().select();
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

                toastr.error(err.responseJSON.message);
            }
        });
    });

    $(document).on('click', '#deleteDeviceModel', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#delete_device_model_form').attr('action', url);
        $.confirm({
            'title': "{{ __('Are you sure to delete?') }}",
            'content': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-modal-primary',
                    'action': function() {
                        $('#delete_device_model_form').submit();
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
    $(document).on('submit', '#delete_device_model_form', function(e) {
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

                deviceModelsTable.ajax.reload(null, false);
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
