<form id="edit_cash_counter_form" action="{{ route('settings.cash.counter.update', $cc->id) }}" method="POST"
    enctype="multipart/form-data">
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('menu.counter_name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="counter_name" class="form-control" id="e_counter_name"
                placeholder="@lang('menu.counter_name')" value="{{ $cc->counter_name }}"/>
            <span class="error error_e_counter_name"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <label for=""><b>@lang('menu.short_name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="short_name" class="form-control" id="e_short_name" placeholder="@lang('menu.short_name')" value="{{ $cc->short_name }}"/>
            <span class="error error_e_short_name"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success">@lang('menu.update')</button>
            </div>
        </div>
    </div>
</form>
