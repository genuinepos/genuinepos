@if ($type == 1)
    <div class="general_product_and_pricing_area">
        <div class="form-group row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Default Unit Cost</th>
                                <th>Profit(%)</th>
                                <th>Default Unit Price</th>
                                <th>Photo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label><b>Unit Cost :</b> <span class="text-danger">*</span></label>
                                            <input type="text" name="product_cost" class="form-control form-control-sm"
                                                autocomplete="off" id="product_cost">
                                            <span class="error error_product_cost"></span>
                                        </div>

                                        <div class="col-md-6">
                                            <label><b>Unit Cost (Inc.Tax) :</b><span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="product_cost_with_tax"
                                                class="form-control form-control-sm" autocomplete="off"
                                                id="product_cost_with_tax">
                                            <span class="error error_product_cost_with_tax"></span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <br>
                                    <input type="text" name="profit" class="form-control form-control-sm mt-2"
                                        autocomplete="off" id="profit"
                                        value="{{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : 0 }}">
                                </td>

                                <td>
                                    <label><b>Price Exc.Tax :</b><span class="text-danger">*</span></label>
                                    <input type="text" name="product_price" class="form-control form-control-sm"
                                        autocomplete="off" id="product_price">
                                    <span class="error error_product_price"></span>
                                </td>

                                <td>
                                    <label><b>Photo :</b> <span class="text-danger">*</span></label>
                                    <input type="file" name="photo" class="form-control form-control-sm" id="photo">
                                    <span class="error error_photo"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="form-group row">
            &nbsp;&nbsp;&nbsp;&nbsp;<h6 class="checkbox_input_wrap"> <input type="checkbox" name="is_variant"
                    class="form-control" autocomplete="off" id="is_variant"> &nbsp; This product has varient. </h6>
        </div>

        <div class="dynamic_variant_create_area d-none">
            <div class="row">
                <div class="col-md-12">
                    <div class="add_more_btn">
                        <a id="add_more_variant_btn" class="btn btn-sm btn-primary float-right mb-1" href="">Add</a>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table variant_table table-sm table-hover form_table">
                            <thead>
                                <tr class="text-center bg-primary variant_header">
                                    <th>Variant Combination</th>
                                    <th>
                                        Varient code (SKU)
                                        <i data-toggle="tooltip" data-placement="top"
                                            title="You can customize the variant code"
                                            class="fas fa-info-circle tp text-light"></i>
                                    </th>
                                    <th>Default Cost</th>
                                    <th>Profit(%)</th>
                                    <th>Default Price (Exc.Tax)</th>
                                    <th>Image</th>
                                    <th><i class="fas fa-trash-alt text-white"></i></th>
                                </tr>
                            </thead>
                            <tbody class="dynamic_variant_body">
                                <tr>
                                    <td>
                                        <select class="form-control form-control form-control-sm" name="" id="variants">
                                            <option value="">Create Variation</option>
                                            @foreach ($variants as $variant)
                                                <option value="{{ $variant->id }}">{{ $variant->bulk_variant_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="variant_combinations[]" id="variant_combination"
                                            class="form-control form-control-sm" placeholder="Variant Combination">
                                    </td>

                                    <td>
                                        <input type="text" name="variant_codes[]" id="variant_code"
                                            class="form-control form-control form-control-sm mt-3"
                                            placeholder="Variant Code">
                                    </td>

                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="number" name="variant_costings[]"
                                                class="form-control form-control form-control-sm mt-3" placeholder="Cost"
                                                id="variant_costing" step="any">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="number" name="variant_costings_with_tax[]"
                                                class="form-control form-control form-control-sm mt-3"
                                                placeholder="Cost inc.tax" id="variant_costing_with_tax" step="any">
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <input type="number" name="variant_profits[]"
                                            class="form-control form-control form-control-sm mt-3" placeholder="Profit"
                                            value="0.00" id="variant_profit" step="any">
                                    </td>

                                    <td>
                                        <input type="number" step="any" name="variant_prices_exc_tax[]"
                                            class="form-control form-control form-control-sm mt-3"
                                            placeholder="Price inc.tax" id="variant_price_exc_tax">
                                    </td>

                                    <td>
                                        <input type="file" name="variant_image[]"
                                            class="form-control form-control form-control-sm mt-3 " id="variant_image">
                                    </td>

                                    <td>
                                        <a href="#" id="variant_remove_btn"
                                            class="btn btn-xs btn-sm btn-danger mt-3">X</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="combo_product_and_pricing_area">
        <div class="form-group row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-8 offset-2">
                        <div class="add_combo_product_input">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                </div>
                                <input type="text" name="search_product" class="form-control form-control-sm"
                                    autocomplete="off" id="search_product"
                                    placeholder="Product search/scan by product code">
                            </div>

                            <div class="select_area">
                                <div class="remove_select_area_btn">X</div>
                                <ul class="variant_list_area">

                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-10 offset-1 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_table_heading">
                                    <p class="m-0 pb-1"><b>Create combo product</b></p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover form_table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Unit price</th>
                                                <th>SubTotal</th>
                                                <th><i class="fas fa-trash-alt"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody id="combo_products">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-center">Net Total Amount :</th>
                                                <th>
                                                    {{ json_decode($generalSettings->business, true)['currency']}} <span class="span_total_combo_price">0.00</span>

                                                    <input type="hidden" name="total_combo_price"
                                                        id="total_combo_price"/>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3 offset-3">
                <label><b>x Margin :</b></label>
                <input type="text" name="profit" class="form-control form-control-sm" id="profit"
                    value="{{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : 0 }}">
            </div>

            <div class="col-md-3">
                <label><b>Default Price Exc.Tax :</b></label>
                <input type="text" name="combo_price" class="form-control form-control-sm" id="combo_price">
            </div>
        </div>
    </div>
@endif
