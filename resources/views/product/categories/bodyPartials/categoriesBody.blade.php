<div class="row g-3 categories tab_contant">
    <div class="col-md-4">
        <div class="card" id="add_cate_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>Add Category </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2">
                <form id="add_category_form" action="{{ route('product.categories.store') }}" method="POST"
                enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label><b>Name :</b> <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name"
                                placeholder="Category name"/>
                        <span class="error error_name"></span>
                    </div>

                    <div class="form-group mt-1">
                        <label><b>Description :</b> </label>
                        <textarea name="description" class="form-control" cols="30" rows="3" placeholder="Description"></textarea>
                    </div>

                    <div class="form-group mt-1">
                        <label><b>Photo :</b> <small class="text-danger"><b>Photo size 400px * 400px.</b></small></label>
                        <input type="file" name="photo" class="form-control" id="photo">
                        <span class="error error_photo"></span>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><b> Loading...</b></button>
                                <button type="reset" class="btn btn-sm btn-danger">Reset</button>
                                <button type="submit" class="btn btn-sm btn-success submit_button">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card d-hide" id="edit_cate_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>Edit Category </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2" id="edit_cate_form_body"></div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>All Category</h6>
                </div>
            </div>

            <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                </div>
                <div class="table-responsive" id="data-list">
                    <table class="display data_tbl data__table">
                        <thead>
                            <tr class="bg-navey-blue">
                                <th class="text-black">Serial</th>
                                <th class="text-black">Photo</th>
                                <th class="text-black">Name</th>
                                <th class="text-black">Description</th>
                                <th class="text-black">@lang('menu.action')</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
