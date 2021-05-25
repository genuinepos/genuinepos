@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_user_form" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0 mt-4">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5>Add Product</h5>
                                                </div>

                                                <div class="col-md-6">
                                                    <a href="{{ url()->previous() }}"
                                                        class="btn text-white btn-sm btn-info float-end"><i
                                                            class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><span class="text-danger">*
                                                        </span><b>Product Name :</b> </label>

                                                    <div class="col-8">
                                                        <input type="text" name="name" class="form-control" id="name" placeholder="Product Name">
                                                        <span class="error error_name"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><span class="text-danger">*</span><b> P.Code(SKU) :</b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input type="password" name="code" class="form-control"
                                                            autocomplete="off" placeholder="Product Code/SKU">
                                                            <div class="input-group-prepend code_generate_btn">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-sync-alt input_i"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><span class="text-danger">*
                                                    </span><b> Unit :</b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <select class="form-control product_unit" name="unit_id" id="unit_id">
                                                                <option value="">Select Unit</option>
                                                            </select>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button" data-toggle="modal"
                                                                    data-target="#addUnitModal"><i class="fas fa-plus-square input_i"></i></span>
                                                            </div>
                                                        </div>
                                                        <span class="error error_unit_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><span class="text-danger">*</span> <b>Barcode Type :</b> </label>
                                                    <div class="col-8">
                                                        <select class="form-control" name="barcode_type" id="barcode_type">
                                                            <option value="CODE128">Code 128 (C128)</option>
                                                            <option value="CODE39">Code 39 (C39)</option>
                                                            <option value="EAN13">EAN-13</option>
                                                            <option value="UPC">UPC</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b> Category :</b> </label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <select class="form-control category" name="category_id"
                                                                    id="category_id">
                                                                    <option value="">Select Category</option>
                                                                </select>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" data-toggle="modal"
                                                                        data-target="#addCategoryModal"><i
                                                                            class="fas fa-plus-square input_i"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1' && json_decode($generalSettings->product, true)['is_enable_sub_categories'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><span class="text-danger">*</span> <b>Sub-category :</b> </label>
                                                        <div class="col-8">
                                                            <select class="form-control" name="child_category_id" id="child_category_id">
                                                                <option value="">Select child category first</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row mt-1">
                                            @if (json_decode($generalSettings->product, true)['is_enable_brands'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b> Brand :</b> </label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <select class="form-control" data-live-search="true" name="brand_id" id="brand_id">
                                                                    <option value="">Select Brand</option>
                                                                </select>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" data-toggle="modal" data-target="#addBrandModal"><i
                                                                            class="fas fa-plus-square input_i"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                           
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><span class="text-danger">*</span> <b>Alert Quentity :</b> </label>
                                                    <div class="col-8">
                                                        <input type="number" name="alert_quantity" class="form-control" autocomplete="off" id="alert_quantity" value="0">
                                                        <span class="error error_alert_quantity"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b> Expired Date :</b> </label>
                                                    <div class="col-8">
                                                        <input type="date" name="expired_date" class="form-control date-picker" autocomplete="off" placeholder="dd-mm-yyyy">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if (json_decode($generalSettings->product, true)['is_enable_warranty'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b> Warranty :</b> </label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <select class="form-control" name="warranty_id" id="warranty_id">
                                                                    <option value="">Select Warranty</option>
                                                                </select>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" data-bs-toggle="modal" data-bs-target="#addWarrantyModal"><i class="fas fa-plus-square input_i"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"> <b>Condition :</b> </label>
                                                    <div class="col-8">
                                                        <select class="form-control" name="product_condition"
                                                        id="product_condition">
                                                        <option value="New">New</option>
                                                        <option value="Used">Used</option>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="element-body">
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-2"><span
                                                            class="text-danger">*</span> <b>Description :</b>  </label>
                                                    <div class="col-10">
                                                        <textarea name="filed05" id="txt_editor" cols="50" rows="5" tabindex="4" style="display: none; width: 50%; height: 160px;"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="submit-area py-3 mb-4">
                                    <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                    <button class="btn btn-sm btn-primary submit_button float-end">Save</button>
                                </div>
                            </div>
                        </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')

@endpush
