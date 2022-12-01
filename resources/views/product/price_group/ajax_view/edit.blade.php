<form id="edit_price_group_form" action="{{ route('product.selling.price.groups.update', $pg->id) }}" method="POST">
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('menu.name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="e_name" placeholder="Name" value="{{ $pg->name }}"/>
            <span class="error error_e_name"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>@lang('menu.description') :</b></label>
            <textarea name="description" class="form-control" cols="10" rows="3" placeholder="Price Group Description">{{ $pg->description }}</textarea>
            <span class="error error_photo"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success submit_button">Save Change</button>
            </div>
        </div>
    </div>
</form>