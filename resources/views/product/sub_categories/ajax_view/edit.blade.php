 <!--begin::Form-->
<form id="edit_sub_category_form" action="{{ route('product.subcategories.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ $data->id }}">
    <div class="form-group">
        <b>Name :</b> <span class="text-danger">*</span>
        <input type="text" name="name" class="form-control form-control-sm" value="{{ $data->name }}" id="e_name" placeholder="Sub category name"/>
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-2">
        <b>Parent category :</b> 
        <select name="parent_category_id" class="form-control form-control-sm" id="edit_parent_category">
        	@foreach($category as $row)
             <option value="{{ $row->id }}" @if($data->parent_category_id==$row->id) selected @endif>{{ $row->name }}</option>
            @endforeach
        </select>
        <span class="error error_e_parent_category_id"></span>
    </div>

    <div class="form-group editable_cate_img_field mt-2">
        <b>Sub Category photo :</b>
        <input type="file" name="photo" class="form-control" id="e_photo" accept=".jpg, .jpeg, .png, .gif">
        <span class="error error_e_photo"></span>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn btn_blue float-end">Save</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
        </div>
    </div>
</form>
