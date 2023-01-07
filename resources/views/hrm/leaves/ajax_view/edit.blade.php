<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_leave')</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_leave_form" action="{{ route('hrm.leaves.update', $leave->id) }}">
                <div class="form-group row">
                    <div class="col-md-6">
                        <label><b>@lang('menu.department') :</b></label>
                        <select class="form-control" name="department_id" id="e_department_id">
                            <option value="all"> @lang('menu.all') </option>
                            @foreach ($departments as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label><b>{{ __('Employee') }} :</b> <span class="text-danger">*</span></label>
                        <select class="form-control" name="employee_id" id="e_employee_id" required>
                            <option value="">{{ __('Select Employee') }}</option>
                            @foreach ($employees as $emp)
                                <option {{ $leave->employee_id == $emp->id ? 'SELECTED' : '' }} value="{{ $emp->id }}">
                                    {{ $emp->prefix . ' ' . $emp->name . ' ' . $emp->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="error error_e_employee_id"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="form-group col-6">
                        <label><b>@lang('menu.leave_type') :</b> <span class="text-danger">*</span></label>
                        <select required class="form-control" name="leave_type_id" id="e_leave_type_id">
                            <option value="">{{ __('Select Leave Type') }}</option>
                            @foreach ($leaveTypes as $lt)
                                <option {{ $leave->leave_type_id == $lt->id ? 'SELECTED' : '' }} value="{{ $lt->id }}">{{ $lt->leave_type }}</option>
                            @endforeach
                        </select>
                        <span class="error error_e_leave_type_id"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="form-group col-6">
                        <label><b>@lang('menu.start_date') :</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="start_date" class="form-control" id="e_start_date" value="{{ $leave->start_date }}" autocomplete="off" placeholder="@lang('menu.start_date')">
                        <span class="error error_e_start_date"></span>
                    </div>

                    <div class="form-group col-6">
                      <label><b>@lang('menu.end_date') :</b> <span class="text-danger">*</span></label>
                      <input required type="text" name="end_date" class="form-control" id="e_end_date" value="{{ $leave->end_date }}" autocomplete="off" placeholder="@lang('menu.end_date')">
                      <span class="error error_e_end_date"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="form-group col-12">
                        <label><b>@lang('menu.reason') :</b> </label>
                        <textarea type="text" name="reason" class="form-control" placeholder="@lang('menu.reason')">{{ $leave->reason }}</textarea>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide">
                                <i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span>
                            </button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                            <button type="submit" class="btn btn-sm btn-success">@lang('menu.save_changes')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#e_department_id').on('change', function(e){
        e.preventDefault();

        var department_id = $(this).val();

        $.ajax({
            url:"{{ url('hrm/leaves/department/employees/') }}"+"/"+department_id,
            type:'get',
            success:function(employees){

                $('#e_employee_id').empty();
                $('#e_employee_id').append('<option value="">Select Employee</option>');

                $.each(employees, function (key, emp) {

                    emp.prefix = emp.prefix || '';
                    emp.name = emp.name || '';
                    emp.last_name = emp.last_name || '';
                    $('#e_employee_id').append('<option value="'+emp.id+'">'+ emp.prefix+' '+emp.name+' '+emp.last_name +'</option>');
                });
            }
        });
    });

    $('#edit_leave_form').on('submit', function(e){
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data) {

                toastr.success(data);
                table.ajax.reload();
                $('#editModal').modal('hide');
                $('.loading_button').hide();
            },error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');
                $('.submit_button').prop('type', 'submit');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }else if (err.status == 500){

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });

    var dateFormat = "{{ $generalSettings['business']['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_start_date'),
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
        tooltipNumber : (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_end_date'),
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
        tooltipNumber : (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });
</script>