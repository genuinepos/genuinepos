<div class="row g-lg-3 g-1 sub-categories tab_contant">
    <div class="col-lg-4">
        <div class="card" id="add_sub_cate_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>Add SubCategory </h6>
                </div>
            </div>
            <div class="form-area px-3 pb-2">
                <form id="add_sub_category_form" action="{{ route('product.subcategories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mt-1">
                        <label><b>@lang('menu.name') :</b> <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control " id="name"
                            placeholder="Sub category name" />
                        <span class="error error_sub_name"></span>
                    </div>

                    <div class="form-group">
                        <label><b>Parent category : <span class="text-danger">*</span></b></label>
                        <select name="parent_category_id" class="form-control " id="parent_category"
                            required>
                            <option selected="" disabled="">Select Parent Category</option>
                            @foreach ($categories as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        <span class="error error_sub_parent_category_id"></span>
                    </div>

                    <div class="form-group mt-1">
                        <label><b>@lang('menu.description') :</b> </label>
                        <textarea name="description" class="form-control" cols="30" rows="3" placeholder="Description"></textarea>
                    </div>

                    <div class="form-group mt-2">
                        <label><b>Sub-Category photo :</b></label>
                        <input type="file" name="photo" class="form-control " id="photo"
                            accept=".jpg, .jpeg, .png, .gif">
                        <span class="error error_sub_photo"></span>
                    </div>

                    <div class="form-group mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                <button type="reset" class="btn btn-sm btn-danger">@lang('menu.reset')</button>
                                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card d-hide" id="edit_sub_cate_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>Edit SubCategory </h6>
                </div>
            </div>
            <div class="form-area px-3 pb-2" id="edit_sub_cate_form_body">
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>All SubCategory</h6>
                </div>
            </div>

         <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                </div>
                <div class="table-responsive">
                    <table class="display data_tbl2 data__table w-100">
                        <thead>
                            <tr>
                                <th>@lang('menu.serial')</th>
                                <th>Photo</th>
                                <th>@lang('menu.sub_category')</th>
                                <th>Parent Category</th>
                                <th>@lang('menu.description')</th>
                                <th>@lang('menu.action')</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
