<div class="modal-dialog five-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Attendances') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_attendance_form" action="{{ route('hrm.attendances.store') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6">
                        <label><b>{{ __('Department') }}</b></label>
                        <select onchange="getUsers(this); return false;" class="form-control" id="department_id">
                            <option value="all"> {{ __('All') }} </option>
                            @foreach ($departments as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label><b>{{ __('Employee') }} </b></label>
                        <select onchange="getAttendanceRow(this); return false;" class="form-control" id="user_id">
                            <option disabled selected> {{ __('Select Employee') }} </option>
                            @foreach ($users as $user)
                                @php
                                    $empId = $user->emp_id ? '(' . $user->emp_id . ')' : '';
                                @endphp
                                <option value="{{ $user->id }}">{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . $empId }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="attendance_table mt-2">
                    <div class="data_preloader d-hide" id="attendance_row_loader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>
                    <table class="table display modal-table table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Clock In Date') }}</th>
                                <th>{{ __('Clock In Time') }}</th>
                                <th>{{ __('Clock Out Date') }}</th>
                                <th>{{ __('Clock Out Time') }}</th>
                                <th>{{ __('Shift') }}</th>
                                <th>{{ __('Clock In Note') }}</th>
                                <th>{{ __('Clock Out Note') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="table_data"></tbody>
                    </table>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-sm btn-success attendance_submit_button">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#user_id').select2();

    function getUsers(e) {

        var department_id = $(e).val();

        var url = "{{ route('hrm.department.users', ':department_id') }}";
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
                    var emp_id = user.last_name != null ? '(' + user.emp_id + ')' : '';

                    var __name = prefix + name + last_name + emp_id;

                    $('#user_id').append('<option value="' + user.id + '">' + __name + '</option>');
                });
            }, error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error') }}");
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
                }, error: function(err) {

                    $('#attendance_row_loader').hide();

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error') }}");
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
            }, error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
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
