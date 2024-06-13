@php
    $generalSettings = config('generalSettings');
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Attendance') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_attendance_form" action="{{ route('hrm.attendances.update', $attendance->id) }}" method="POST">
                @csrf
                <label class="fw-bold"><b>{{ __('Employee') }} : </b> {{ $attendance->prefix . ' ' . $attendance->name . ' ' . $attendance->last_name }} </label>
                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('Clock In Date') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="clock_in_date" class="form-control" id="attendance_clock_in_date" data-next="attendance_clock_in" value="{{ $attendance->clock_in_date ? date($dateFormat, strtotime($attendance->clock_in_date)) : date($dateFormat) }}" placeholder="{{ __('Clock In Date') }}">
                        <span class="error error_attendance_clock_in_date"></span>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('Clock In Time') }} <span class="text-danger">*</span></label>
                        <input type="time" name="clock_in" class="form-control" id="attendance_clock_in" data-next="attendance_clock_out_date" value="{{ $attendance->clock_in }}">
                        <span class="error error_attendance_clock_in"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('Clock Out Date') }} <span class="text-danger">*</span></label>
                        <input type="text" name="clock_out_date" class="form-control" id="attendance_clock_out_date" data-next="attendance_clock_out" value="{{ $attendance->clock_out_date ? date($dateFormat, strtotime($attendance->clock_out_date)) : date($dateFormat) }}" placeholder="{{ __('Clock Out Date') }}">
                        <span class="error error_attendance_clock_out_date"></span>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('Clock Out Time') }}</label>
                        <input type="time" name="clock_out" class="form-control" id="attendance_clock_out" data-next="attendance_shift_id" value="{{ $attendance->clock_out }}">
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('Shift') }}</label>
                        <select name="shift_id" class="form-control" id="attendance_shift_id" data-next="attendance_clock_in_note">
                            <option value="">{{ __('Select Shift') }}</option>
                            @foreach ($shifts as $shift)
                                <option {{ $attendance->shift_id == $shift->id ? 'SELECTED' : '' }} value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-12">
                        <label class="fw-bold">{{ __('Clock In Note') }}</label>
                        <input name="clock_in_note" class="form-control" id="attendance_clock_in_note" data-next="attendance_clock_out_note" value="{{ $attendance->clock_in_note }}" placeholder="{{ __('Clock In Note') }}">
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-12">
                        <label class="fw-bold">{{ __('Clock Out Note') }}</label>
                        <input name="clock_out_note" class="form-control" id="attendance_clock_out_note" data-next="save_changes" value="{{ $attendance->clock_out_note }}" placeholder="{{ __('Clock Out Note') }}">
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button attendance_loading_button d-hide">
                                <i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span>
                            </button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="save_changes" class="btn btn-sm btn-success attendance_submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('hrm.attendances.ajax_view.js_partials.edit_js')
