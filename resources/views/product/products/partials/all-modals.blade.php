   <!-- Select modal  -->
   <div class="modal fade" id="VairantChildModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog select_variant_modal_dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Select variant Child</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="modal_variant_list_area">
                            <ul class="modal_variant_child">
                                <li class="modal_variant_child_list">
                                    <a class="select_variant_product" data-child="" href="#">X</a>
                                </li>

                                <li class="modal_variant_child_list">
                                    <a class="select_variant_product" data-child="" href="#">X</a>
                                </li>

                                <li class="modal_variant_child_list">
                                    <a class="select_variant_product" data-child="" href="#">X</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Select variant modal -->

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog double-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Add Unit</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>

            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_unit_form" action="{{ route('products.add.unit') }}">
                    <div class="form-group">
                        <label><b>@lang('menu.name') :</b></label> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control add_unit_input"
                            data-name="Unit name" id="add_unit_name" placeholder="Unit name" />
                        <span class="error error_add_unit_name"></span>
                    </div>

                    <div class="form-group mt-1">
                       <label><b>Unit Code :</b></label>  <span class="text-danger">*</span>
                        <input type="text" name="code" class="form-control add_unit_input"
                            data-name="Unit code" id="add_unit_code" placeholder="Unit code" />
                        <span class="error error_add_unit_code"></span>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Unit Modal End -->

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog double-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Add Category</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_category_form" action="{{ route('products.add.category') }}">
                    <div class="form-group">
                        <b>@lang('menu.name') :</b> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control add_cate_input"
                            data-name="Category name" id="add_cate_name" placeholder="Category name" />
                        <span class="error error_add_cate_name"></span>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Category Modal End -->

 <!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
 aria-hidden="true">
    <div class="modal-dialog double-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Add Brand</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_brand_form" action="{{ route('products.add.brand') }}">
                    <div class="form-group">
                        <b>@lang('menu.name') :</b> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control add_brand_input"
                            data-name="Brand name" id="add_brand_name" placeholder="Brand name" />
                        <span class="error error_add_brand_name"></span>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Brand Modal End -->

<!-- Add Warranty Modal -->
<div class="modal fade" id="addWarrantyModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
aria-hidden="true">
    <div class="modal-dialog double-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Add Warranty</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>

            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_warranty_form" action="{{ route('products.add.warranty') }}">
                    <div class="form-group">
                        <label><b>@lang('menu.name') :</b> </label> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control add_warranty_input" id="add_warranty_name" data-name="Warranty name" placeholder="Warranty name"/>
                        <span class="error error_add_warranty_name"></span>
                    </div>

                    <div class="row mt-1">
                        <div class="col-lg-4">
                            <label><b>@lang('menu.type') : </b> </label> <span class="text-danger">*</span>
                            <select name="type" class="form-control" id="type">
                                <option value="1">Warranty</option>
                                <option value="2">Guaranty</option>
                            </select>
                        </div>

                        <div class="col-lg-8">
                            <label><b>Duration :</b> </label> <span class="text-danger">*</span>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row g-0">
                                        <input type="number" step="any" name="duration" class="form-control w-50 add_warranty_input" data-name="Warranty duration" id="add_warranty_duration" placeholder="Warranty duration">
                                        <select name="duration_type" class="form-control w-50" id="duration_type">
                                            <option value="Months">Months</option>
                                            <option value="Days">Days</option>
                                            <option value="Years">Years</option>
                                        </select>
                                    </div>
                                    <span class="error error_add_warranty_duration"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-1">
                        <label><b>@lang('menu.description') :</b></label>
                        <textarea name="description" id="description" class="form-control" cols="10" rows="3" placeholder="Warranty description"></textarea>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Warranty Modal End -->
