<form id="edit_shift_form" action="{{ route('hrm.shift.update', $type->id) }}" method="POST">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ $type->id }}">
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>{{ __('Shift Name') }} </b> <span class="text-danger">*</span></label>
            <input type="text" name="shift_name" class="form-control" id="e_shift_name" placeholder="{{ __('Shift Name') }}" required="" value="{{ $type->shift_name }}" />
        </div>
    </div>
    <div class="form-group row mt-1">
        <div class="form-group col-12">
            <label><b>@lang('menu.start_time') </b> <span class="text-danger">*</span></label>
            <input type="time" name="start_time" class="form-control" id="e_start_time" placeholder="@lang('menu.start_time')" value="{{ $type->start_time }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="form-group col-12">
            <label><b>@lang('menu.end_time') </b> <span class="text-danger">*</span></label>
            <input type="time" name="endtime" class="form-control"  id="e_endtime" placeholder="@lang('menu.end_time')" value="{{ $type->endtime }}" />
        </div>
    </div>

    <div class="form-group d-flex justify-content-end mt-3">
        <div class="btn-loading">
            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
            <button type="submit" class="btn btn-sm btn-success">@lang('menu.save_change')</button>
        </div>
    </div>
</form>
