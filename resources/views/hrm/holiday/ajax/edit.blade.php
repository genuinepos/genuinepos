<form id="edit_holiday_form" action="{{ route('hrm.holidays.update') }}">
    <input type="hidden" name="id" id="id" value="{{ $holiday->id }}">
    <div class="form-group ">
        <label><b>Holiday Name :</b> <span class="text-danger">*</span></label>
        <input type="text" name="holiday_name" required class="form-control" placeholder="Holiday Name" value="{{ $holiday->holiday_name }}">
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>Start Date :</b> <span class="text-danger">*</span></label>
            <input type="date" name="start_date" required class="form-control" value="{{ $holiday->start_date }}">
        </div>

        <div class="col-md-6">
            <label><b>End Date :</b> <span class="text-danger">*</span></label>
            <input type="date" name="end_date" required class="form-control" value="{{ $holiday->end_date }}">
        </div>
    </div>

    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
        <div class="form-group mt-1">
            <label><b>Allowed Branch</b> <span class="text-danger">*</span></label>
            <select class="form-control" name="branch_id">
                <option {{ $holiday->is_all == 1 ? 'SELECTED' : '' }} value="All"> All </option>
                <option {{ !$holiday->branch_id ? 'SELECTED' : '' }} value=""> {{json_decode($generalSettings->business, true)['shop_name']}}  (<b>Head Office</b>) </option>
                @foreach($branches as $row)
                    <option {{ $row->id == $holiday->branch_id ? 'SELECTED' : '' }} value="{{ $row->id }}"> {{ $row->name.'/'.$row->branch_code }}</option>
                @endforeach
            </select>
        </div>
    @endif
    
    <div class="form-group mt-1">
        <label><b>Note :</b> </label>
        <textarea name="notes" class="form-control" cols="10" rows="3" placeholder="Note">{{ $holiday->notes }}</textarea>
    </div>

    <div class="form-group mt-3">
        <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')...</b></button>
        <button type="submit" class="c-btn me-0 button-success float-end">Save Change</button>
        <button type="reset" data-bs-dismiss="modal"
            class="c-btn btn_orange float-end">@lang('menu.close')</button>
    </div>
</form>