@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
@endphp
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
                        <input required type="text" name="start_date" class="form-control" id="start_date" data-next="end_date" value="{{ date($dateFormat, strtotime($holiday->start_date)) }}" placeholder="{{ __('Start Date') }}" autocomplete="off">
                        <span class="error error_start_date"></span>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('End Date') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="end_date" class="form-control" id="end_date" data-next="allowed_branch_id" value="{{ date($dateFormat, strtotime($holiday->end_date)) }}" placeholder="{{ __('End Date') }}" autocomplete="off">
                        <span class="error error_end_date"></span>
                    </div>
                </div>

                {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                @if (auth()->user()->can('has_access_to_all_area') && ($generalSettings['subscription']->has_business == 1 || $generalSettings['subscription']->current_shop_count > 1))
                    <div class="form-group row mt-1">
                        <div class="col-md-12">
                            <label class="fw-bold">{{ __('Allowed Shop/Business') }} <span class="text-danger">*</span></label>
                            <input type="hidden" name="allowed_branch_count" value="allowed_branch_count">
                            <select class="form-control select2" name="allowed_branch_ids[]" id="allowed_branch_id" multiple>
                                @php
                                   $business = $holiday->allowedBranches()->where('branch_id', NUll)->first();
                                @endphp
                                <option value="NULL" @selected(isset($business))>{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
                                @foreach ($branches as $branch)
                                    <option
                                        @foreach ($holiday->allowedBranches as $allowedBranch)
                                            @selected($branch->id == $allowedBranch->branch_id)
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

@include('hrm.holidays.ajax_view.js_partials.edit_js')
