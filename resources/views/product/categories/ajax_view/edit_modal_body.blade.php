<form id="edit_category_form" action="{{ route('product.categories.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ $category->id }}">
    <div class="form-group">
        <label><b>@lang('menu.name') :</b> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control " id="e_name" placeholder="Category name" value="{{ $category->name }}"/>
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>@lang('menu.description') :</b> </label>
        <textarea name="description" class="form-control" cols="30" rows="3" placeholder="Description">{{ $category->description }}</textarea>
    </div>

    <div class="form-group mt-1">
        <label><b>Photo :</b> <small class="text-danger"><b>Photo size 400px * 400px.</b> </small></label>
        <input type="file" name="photo" class="form-control " accept=".jpg, .jpeg, .png, .gif">
        <span class="error error_e_photo"></span>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="button" class="btn btn-sm btn-danger" id="close_cate_form">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success" id="update_btn">@lang('menu.save_changes')</button>
            </div>
        </div>
    </div>
</form>
