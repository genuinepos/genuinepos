<div class="row mt-1 categories tab_contant">
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
                                placeholder="Category name" />
                        <span class="error error_name"></span>
                    </div>

                    <div class="form-group mt-1">
                        <label><b>Photo :</b> <small class="text-danger"><b>Photo size 400px * 400px.</b></small></label>
                        <input type="file" name="photo" class="form-control" id="photo">
                        <span class="error error_photo"></span>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
                            <button type="reset" class="c-btn btn_orange float-end">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card d-none" id="edit_cate_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>Edit Category </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2" id="edit_cate_form_body">

            </div>
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
                                <th class="text-black">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>