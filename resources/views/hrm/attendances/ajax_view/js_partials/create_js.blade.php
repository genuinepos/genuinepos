<script>
    $('#user_id').select2();

    function getUsers(e) {

        var department_id = $(e).val();

        var url = "{{ route('hrm.departments.users', ':department_id') }}";
        var route = url.replace(':department_id', department_id);

        $.ajax({
            url: route,
            type: 'get',
            success: function(users) {

                $('#user_id').empty();
                $('#user_id').append('<option value="">' + "{{ __('Select Employee') }}" + '</option>');

                $.each(users, function(key, user) {

                    var prefix = user.prefix != null ? user.prefix : '';
                    var name = user.name != null ? ' ' + user.name : '';
                    var last_name = user.last_name != null ? ' ' + user.last_name : '';
                    var emp_id = user.emp_id != null ? '(' + user.emp_id + ')' : '';

                    var __name = prefix + name + last_name + emp_id;

                    $('#user_id').append('<option value="' + user.id + '">' + __name + '</option>');
                });
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    }

    function getAttendanceRow(e) {

        var user_id = $(e).val();

        var count = 0;

        $('.attendance_table table').find('tr').each(function() {
            if ($(this).data('user_id') == user_id) {
                count++;
            }
        });

        if (user_id && count == 0) {

            $('#attendance_row_loader').show();

            var url = "{{ route('hrm.attendances.row', ':user_id') }}";
            var route = url.replace(':user_id', user_id);

            $.ajax({
                url: route,
                type: 'get',
                success: function(data) {

                    $('#table_data').append(data);
                    $('#attendance_row_loader').hide();
                    executeDatePicker();
                },
                error: function(err) {

                    $('#attendance_row_loader').hide();

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        }
    }

    var isAllowSubmit = true;
    $(document).on('click', '.attendance_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_attendance_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                toastr.success(data);

                attendancesTable.ajax.reload();
                $('#attendanceAddOrEditModal').modal('hide');
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    function deleteRow(e) {

        $(e).closest('tr').remove();
    }

    function litepicker(idName) {

        new Litepicker({
            singleMode: true,
            element: document.getElementById(idName),
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
    }

    function executeDatePicker() {

        var clockOutDates = document.querySelectorAll('.clock_out_date');

        clockOutDates.forEach(function(clockOutDate) {

            var idName = clockOutDate.getAttribute('id');
            litepicker(idName);
        });
    }
</script>
