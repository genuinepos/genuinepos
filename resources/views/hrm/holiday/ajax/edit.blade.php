<form id="edit_holiday_form" action="{{ route('hrm.holidays.update') }}">
    <input type="hidden" name="id" id="id" value="{{ $holiday->id }}">
    <div class="form-group ">
        <label><b>{{ __('Holiday Name') }} </b> <span class="text-danger">*</span></label>
        <input type="text" name="holiday_name" required class="form-control" placeholder="{{ __('Holiday Name') }}" value="{{ $holiday->holiday_name }}">
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('menu.start_date') </b> <span class="text-danger">*</span></label>
            <input type="date" name="start_date" required class="form-control" value="{{ $holiday->start_date }}">
        </div>

        <div class="col-md-6">
            <label><b>@lang('menu.end_date') </b> <span class="text-danger">*</span></label>
            <input type="date" name="end_date" required class="form-control" value="{{ $holiday->end_date }}">
        </div>
    </div>

    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
        <div class="form-group mt-1">
            <label><b>{{ __('Allowed Branch') }}</b> <span class="text-danger">*</span></label>
            <select class="form-control" name="branch_id">
                <option {{ $holiday->is_all == 1 ? 'SELECTED' : '' }} value="All"> @lang('menu.all') </option>
                <option {{ !$holiday->branch_id ? 'SELECTED' : '' }} value=""> {{$generalSettings['business__shop_name']}}  (<b>@lang('menu.head_office')</b>) </option>
                @foreach($branches as $row)
                    <option {{ $row->id == $holiday->branch_id ? 'SELECTED' : '' }} value="{{ $row->id }}"> {{ $row->name.'/'.$row->branch_code }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="form-group mt-1">
        <label><b>@lang('menu.note') </b> </label>
        <textarea name="notes" class="form-control" cols="10" rows="3" placeholder="Note">{{ $holiday->notes }}</textarea>
    </div>

    <div class="form-group d-flex justify-content-end mt-3">
        <div class="btn-loading">
            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')......</span></button>
            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
            <button type="submit" class="btn btn-sm btn-success">@lang('menu.save_change')</button>
        </div>
    </div>
</form>
