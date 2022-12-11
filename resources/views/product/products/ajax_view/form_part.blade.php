@if ($type == 1)
    <div class="row mt-1">
        <div class="col-md-6">
            <div class="input-group">
                <label for="inputEmail3" class="col-4"><b>@lang('menu.unit_cost') :</b> <span class="text-danger">*</span></label>
                <div class="col-8">
                    <input type="number" step="any" name="product_cost" class="form-control"
                    autocomplete="off" id="product_cost" placeholder="Unit cost" value="0.00">
                    <span class="error error_product_cost"></span>
                </div>
            </div>
        </div>

        <div class="col-md-6">    
            <div class="input-group">
                <label for="inputEmail3" class="col-4"><b>Price Exc.Tax :</b> <span class="text-danger">*</span></label>
                <div class="col-8">
                    <input type="number" step="any" name="product_price" class="form-control" autocomplete="off" id="product_price" placeholder="@lang('menu.selling_price_exc_tax')" value="">
                <span class="error error_product_price"></span>    
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-6">                
            <div class="input-group">
                <label for="inputEmail3" class="col-4"><b>@lang('menu.unit_cost')(Inc.Tax) :</b> <span class="text-danger">*</span></label>
                <div class="col-8">
                    <input type="number" step="any" readonly name="product_cost_with_tax" class="form-control" autocomplete="off" id="product_cost_with_tax" placeholder="Unit cost Inc.Tax" value="0.00">
                    <span class="error error_product_cost_with_tax"></span>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="input-group">
                <label for="inputEmail3" class="col-4"><b>Profit Margin(%) :</b> <span class="text-danger">*</span></label>
                <div class="col-8">
                    <input type="text" name="profit" class="form-control" autocomplete="off" id="profit" value="{{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : 0 }}">
                </div>
            </div>
        </div>
    </div>

    @if (json_decode($generalSettings->product, true)['is_enable_price_tax'] == '1')
        <div class="row mt-1">
            <div class="col-md-6">
                <div class="input-group">
                    <label for="inputEmail3" class="col-4"><b>@lang('menu.tax') :</b> </label>
                    <div class="col-8">
                        <select class="form-control" name="tax_id" id="tax_id">
                            <option value="">@lang('menu.no_tax')</option>
                            @foreach ($taxes as $tax)
                                <option value="{{ $tax->id.'-'.$tax->tax_percent }}">{{ $tax->tax_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group">
                    <label for="inputEmail3" class="col-4"><b>@lang('menu.tax_type') :</b> </label>
                    <div class="col-8">
                        <select name="tax_type" class="form-control" id="tax_type">
                            <option value="1">@lang('menu.exclusive')</option>
                            <option value="2">@lang('menu.exclusive')</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row mt-1">                  
        <div class="col-md-6">
            <div class="input-group">
                <label for="inputEmail3" class="col-4"><b>Thumbnail Photo :</b> </label>
                <div class="col-8">
                    <input type="file" name="photo" class="form-control" id="photo">
                    <span class="error error_photo"></span>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="input-group">
                <div class="col-12">
                    <div class="row">
                        <p class="checkbox_input_wrap"> 
                        <input type="checkbox" name="is_variant" id="is_variant"> &nbsp; <b>This product has varient.</b> </p> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-1">                  
        <div class="dynamic_variant_create_area d-hide">
            <div class="row">
                <div class="col-md-12">
                    <div class="add_more_btn">
                        <a id="add_more_variant_btn" class="btn btn-sm btn-primary float-end" href="">Add More</a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive mt-1">
                        <table class="table modal-table table-sm">
                            <thead>
                                <tr class="text-center bg-primary variant_header">
                                    <th class="text-white text-start">Select Variant</th>
                                    <th class="text-white text-start">Varient code <i data-bs-toggle="tooltip" data-bs-placement="top" title="Also known as SKU. Variant code(SKU) must be unique." class="fas fa-info-circle tp"></i>
                                    </th>
                                    <th colspan="2" class="text-white text-start">Default Cost</th>
                                    <th class="text-white text-start">Profit(%)</th>
                                    <th class="text-white text-start">Default Price (Exc.Tax)</th>
                                    <th class="text-white text-start">Variant Image</th>
                                    <th><i class="fas fa-trash-alt text-white"></i></th>
                                </tr>
                            </thead>
                            <tbody class="dynamic_variant_body">
                                <tr>
                                    <td class="text-start">
                                        <select class="form-control form-control" name="" id="variants">
                                            <option value="">Create Variation</option>
                                            @foreach ($variants as $variant)
                                                <option value="{{ $variant->id }}">{{ $variant->bulk_variant_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="variant_combinations[]"
                                            id="variant_combination" class="form-control"
                                            placeholder="Variant Combination">
                                    </td>

                                    <td class="text-start">
                                        <input type="text" name="variant_codes[]" id="variant_code" class="form-control"
                                            placeholder="Variant Code">
                                    </td>

                                    <td class="text-start">
                                        <input type="number" name="variant_costings[]"
                                            class="form-control" placeholder="Cost" id="variant_costing">
                                    </td>

                                    <td class="text-start">
                                        <input type="number" name="variant_costings_with_tax[]"class="form-control" placeholder="Cost inc.tax" id="variant_costing_with_tax">
                                    </td>

                                    <td class="text-start">
                                        <input type="number" name="variant_profits[]" class="form-control" placeholder="Profit" value="0.00" id="variant_profit">
                                    </td>

                                    <td class="text-start">
                                        <input type="text" name="variant_prices_exc_tax[]"
                                            class="form-control" placeholder="Price inc.tax" id="variant_price_exc_tax">
                                    </td>

                                    <td class="text-start">
                                        <input type="file" name="variant_image[]" class="form-control" id="variant_image">
                                    </td>

                                    <td class="text-start">
                                        <a href="#" id="variant_remove_btn"
                                            class="btn btn-xs btn-sm btn-danger">X</a>
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
    <div class="row mt-1">
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
                            <ul class="variant_list_area">

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-10 offset-1 mt-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form_table_heading">
                                <p class="m-0 pb-1"><strong>Create combo product</strong></p>
                            </div>
                            <div class="table-responsive">
                                <table class="table modal-table table-sm">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.product')</th>
                                            <th>@lang('menu.quantity')</th>
                                            <th>Unit price</th>
                                            <th>@lang('menu.sub_total')</th>
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

    <div class="row">
        <div class="col-md-3 offset-3">
            <label><b>@lang('menu.x_margin'):</b></label>
            <input type="text" name="profit" class="form-control form-control-sm" id="profit"
                value="{{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : 0 }}">
        </div>

        <div class="col-md-3">
            <label><b>Default Price Exc.Tax :</b></label>
            <input type="text" name="combo_price" class="form-control form-control-sm" id="combo_price">
        </div>
    </div>
@endif
