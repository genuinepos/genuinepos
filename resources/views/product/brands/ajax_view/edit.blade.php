 <!--begin::Form-->
<form id="edit_brand_form" action="{{ route('product.brands.update') }}">
    <input type="hidden" name="id" id="id" value="{{$data->id}}">
    <div class="form-group mt-2">
        @lang('brand.name') : <span class="text-danger">*</span>
        <input type="text" name="name" class="form-control edit_input" value="{{$data->name}}" id="e_name" placeholder="Brand Name"/>
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group editable_brand_img_field mt-2">
        @lang('brand.brand_photo') :
        <input type="file" name="photo" class="form-control dropify" data-max-file-size="2M" id="e_photo" accept=".jpg, .jpeg, .png, .gif">
        <span class="error error_e_photo"></span>
    </div>

	<div class="form-group mt-3">
		<div class="col-md-12">
			<button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
			<button type="submit" class="c-btn btn_blue float-end">@lang('brand.update')</button>
			<button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
		</div>
	</div>
</form>