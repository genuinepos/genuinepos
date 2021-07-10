<form id="edit_category_form" action="{{ route('product.categories.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ $category->id }}">
    <div class="form-group">
        <label><b>Name :</b> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control " id="e_name" placeholder="Category name" value="{{ $category->name }}"/>
        <span class="error error_e_name"></span>
    </div>
   
    <div class="form-group mt-1">
        <label><b>Photo :</b> <small class="text-danger"><b>Photo size 400px * 400px.</b> </small></label> 
        <input type="file" name="photo" class="form-control " accept=".jpg, .jpeg, .png, .gif">
        <span class="error error_e_photo"></span>
    </div>
  
    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn btn_blue me-0 float-end">Save Change</button>
            <button type="button" class="c-btn btn_orange float-end" id="close_form">Close</button>
        </div>
    </div>
</form>