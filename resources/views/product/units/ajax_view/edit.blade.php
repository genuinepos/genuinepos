<div class="section-header">
    <div class="col-md-6">
        <h6>Edit Unit</h6>
    </div>
</div>

<form id="edit_unit_form" class="p-2" action="{{ route('product.units.update', $units->id) }}">
    <input type="hidden" name="id" id="id">
    <div class="form-group">
        <label><b>@lang('menu.unit_name') :</b> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" data-name="Name" id="e_name" placeholder="@lang('menu.unit_name')" value="{{ $units->name }}"/>
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>@lang('menu.short_name') :</b> <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control" data-name="Code name" id="e_code" placeholder="@lang('menu.short_name')" value="{{ $units->code_name }}"/>
        <span class="error error_e_code"></span>
    </div>

    <div class="form-group d-flex justify-content-end mt-3">
        <div class="btn-loading">
            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
            <button type="button" id="close_form" class="btn btn-sm btn-danger">@lang('menu.close')</button>
            <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
        </div>
    </div>
</form>
