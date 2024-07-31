<script src="{{ asset('assets/plugins/custom/dropify/js/dropify.min.js') }}"></script>
<script>
    $('#photo').dropify({
        messages: {
            'default': "{{ __('Drag and drop a file here or click') }}",
            'replace': "{{ __('Drag and drop or click to replace') }}",
            'remove': "{{ __('Remove') }}",
            'error': "{{ __('Ooops, something wrong happended.') }}"
        }
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.submit_button', function() {

        var value = $(this).val();
        $('#action').val(value);

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    // Add user by ajax
    $(document).on('submit', '#update_user_form', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.loading_button').hide();
                $('.error').html('');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                toastr.success(data);
                window.location = "{{ url()->previous() }}";
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('change', '#user_type', function() {

        changeUserType();
    });

    function changeUserType() {
        var userType = $('#user_type').val() ? $('#user_type').val() : 1;
        if (userType == 1 || userType == 3) {

            $('#allow_login').val(1);
            $('.role_permission_area').show();
            changeAllowLoginField();
        } else {

            $('#allow_login').val(0);
            $('.role_permission_area').hide();
            changeAllowLoginField();
        }
    }

    $(document).on('change', '#allow_login', function() {

        changeAllowLoginField();
    });

    function changeAllowLoginField() {

        $('#auth_fields_area').show();
        $('#role_id').prop('required', true);
        $('#username').prop('required', true);

        if ($('#allow_login').val() == 0) {

            $('#auth_fields_area').hide();
            $('#role_id').prop('required', false);
            $('#username').prop('required', false);
        }
    }

    $(document).on('change', '#branch_id', function(e) {

        currentUserAndEmployeeCount();
    });

     function currentUserAndEmployeeCount() {

        var branchId = $('#branch_id').val();

        var url = "{{ route('users.current.user.and.employee.count', [':branchId']) }}";
        var route = url.replace(':branchId', branchId);

        $.ajax({
            url: route,
            type: 'get',
            success: function(data) {

                $('#current_user_count').html(data.current_user_count);
                $('#current_employee_count').html(data.current_employee_count);
            }, error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                }
            }
        });
    }
    currentUserAndEmployeeCount();

    document.onkeyup = function() {
        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.ctrlKey && e.which == 13) {

            $('#save_changes_btn').click();
            return false;
        }
    }

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            if ($(this).attr('id') == 'allow_login' && $('#allow_login').val() == 0) {

                $('#sales_commission_percent').focus().select();
                return;
            }

            if (nextId == 'username' && $('#username').val()) {

                $('#role_id').focus();
                return;
            }

             if (nextId == 'allow_login' && $('#allow_login').val() == 0) {

                $('#sales_commission_percent').focus().select();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            if (nextId == 'emp_id' && $('#emp_id').val() == undefined) {

                $('#save_btn').focus();
                return;
            }

            if (nextId == 'branch_id' && $('#branch_id').val() == undefined) {

                $('#allow_login').focus();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change', '#role_id', function(e) {
        var hasAccassToAllArea = $(this).find(':selected').data('has_accass_to_all_area');
        $('#branch_id').prop('required', true);
        $('#roleMsg').html('');
        if (hasAccassToAllArea == 1) {

            $('#roleMsg').html('Selected Role Has Access to All Store/Place');
            $('#branch_id').prop('required', false);
        }
    });

    $('#prefix').focus().select();
</script>

<script>
    $(document).on('click', '#addShift', function(e) {
        e.preventDefault();

        var url = "{{ route('hrm.shifts.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#shiftAddOrEditModal').html(data);
                $('#shiftAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#shift_name').focus();
                }, 500);
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
    });

    $(document).on('click', '#addDepartment', function(e) {
        e.preventDefault();

        var url = "{{ route('hrm.departments.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#departmentAddOrEditModal').html(data);
                $('#departmentAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#department_name').focus();
                }, 500);
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
    });

    $(document).on('click', '#addDesignation', function(e) {
        e.preventDefault();

        var url = "{{ route('hrm.designations.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#designationAddOrEditModal').html(data);
                $('#designationAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#designation_name').focus();
                }, 500);
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
    });
</script>
