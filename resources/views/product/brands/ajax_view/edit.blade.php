 <!--begin::Form-->
<form id="edit_brand_form" action="{{ route('product.brands.update') }}">
    <input type="hidden" name="id" id="id" value="{{$data->id}}">
    <div class="form-group mt-2">
        <label><b>@lang('brand.name')</b> : <span class="text-danger">*</span></label> 
        <input type="text" name="name" class="form-control edit_input" value="{{$data->name}}" id="e_name" placeholder="Brand Name"/>
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>@lang('brand.brand_photo') :</b> </label> 
        <input type="file" name="photo" class="form-control" data-max-file-size="2M" id="e_photo" accept=".jpg, .jpeg, .png, .gif">
        <span class="error error_e_photo"></span>
    </div>

	<div class="form-group row mt-2">
		<div class="col-md-12">
			<button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
			<button type="submit" class="c-btn btn_blue float-end me-0">@lang('brand.update')</button>
			<button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end" id="close_form">Close</button>
		</div>
	</div>
</form>