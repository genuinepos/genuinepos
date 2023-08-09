 <!--begin::Form-->
<form id="edit_brand_form" action="{{ route('product.brands.update') }}">
    <input type="hidden" name="id" id="id" value="{{$data->id}}">
    <div class="form-group">
        <label><b>@lang('brand.name')</b> : <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control edit_input" value="{{$data->name}}" id="e_name" placeholder="Brand Name"/>
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>@lang('brand.brand_photo') </b> </label>
        <input type="file" name="photo" class="form-control" data-max-file-size="2M" id="e_photo" accept=".jpg, .jpeg, .png, .gif">
        <span class="error error_e_photo"></span>
    </div>

	<div class="form-group row mt-2">
		<div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger" id="close_form">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success">@lang('brand.update')</button>
            </div>
		</div>
	</div>
</form>
