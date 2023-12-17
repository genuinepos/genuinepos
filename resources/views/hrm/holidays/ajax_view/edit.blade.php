<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __('Edit Holiday') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_holiday_form" action="{{ route('hrm.holidays.update', $holiday->id) }}">
                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="fw-bold">{{ __('Holiday Name') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="name" data-next="start_date" value="{{ $holiday->name }}" placeholder="{{ __('Holiday Name') }}">
                        <span class="error error_name"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="start_date" class="form-control" id="start_date" data-next="end_date" value="{{ $holiday->start_date }}" placeholder="{{ __('Start Date') }}" autocomplete="off">
                        <span class="error error_start_date"></span>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('End Date') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="end_date" class="form-control" id="end_date" data-next="allowed_branch_id" value="{{ $holiday->end_date }}" placeholder="{{ __('End Date') }}" autocomplete="off">
                        <span class="error error_end_date"></span>
                    </div>
                </div>

                @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                    <div class="form-group row mt-1">
                        <div class="col-md-12">
                            <label class="fw-bold">{{ __('Allowed Shop/Business') }} <span class="text-danger">*</span></label>
                            <input type="hidden" name="allowed_branch_count" value="allowed_branch_count">
                            <select class="form-control select2" name="allowed_branch_ids[]" id="allowed_branch_id" multiple>
                                @php
                                   $business = $holiday->allowedBranches()->where('branch_id', NUll)->first();
                                @endphp
                                <option value="NULL" {{ isset($business) ? 'SELECTED' : '' }}>{{ $generalSettings['business__business_name'] }}({{ __('Business') }})</option>
                                @foreach ($branches as $branch)
                                    <option
                                        @foreach ($holiday->allowedBranches as $allowedBranch)
                                            {{ $branch->id == $allowedBranch->branch_id ? 'SELECTED' : '' }}
                                        @endforeach
                                        value="{{ $branch->id }}"
                                    >
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="error error_allowed_branch_ids"></span>
                        </div>
                    </div>
                @endif

                <div class="form-group mt-1">
                    <div class="col-md-12">
                        <label class="fw-bold">{{ __('Note') }}</label>
                        <input name="note" class="form-control" id="note" data-next="holiday_save_btn" value="{{ $holiday->note }}" placeholder="{{ __('Note') }}">
                    </div>
                </div>

                <div class="form-group d-flex justify-content-end mt-3">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button holiday_loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        <button type="submit" id="holiday_save_btn" class="btn btn-sm btn-success holiday_submit_button">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#allowed_branch_id').select2();

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.holiday_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.holiday_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_holiday_form').on('submit', function(e) {
        e.preventDefault();

        $('.holiday_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.holiday_loading_button').hide();
                $('.error').html('');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);
                $('#holidayAddOrEditModal').modal('hide');
                holidaysTable.ajax.reload();
            },
            error: function(err) {

                $('.holiday_loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    function litepicker(idName) {

        var dateFormat = "{{ $generalSettings['business__date_format'] }}";
        var _expectedDateFormat = '' ;
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
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
            format: _expectedDateFormat
        });
    }

    litepicker('start_date');
    litepicker('end_date');
</script>
