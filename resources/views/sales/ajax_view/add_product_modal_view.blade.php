<form id="add_product_form" action="{{ route('sales.add.product') }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-3">
            <label><b>Product Name :</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="sale_code" placeholder="Product Name"/>
            <span class="error error_sale_name"></span>
        </div>

        <div class="col-md-3">
            <label><b>Product Code (SKU) :</b></label>
            <input type="text" name="product_code" class="form-control" placeholder="Product code"/>
        </div>

        <div class="col-md-3">
            <label><b>Barcode Type :</b></label>
            <select class="form-control" name="barcode_type" id="sale_barcode_type">
                <option value="CODE128">Code 128 (C128)</option>
                <option value="CODE39">Code 39 (C39)</option>
                <option value="EAN13">EAN-13</option>
                <option value="UPC">UPC</option>
            </select>
        </div>

        <div class="col-md-3">
            <label><b> Unit :</b> <span class="text-danger">*</span></label>
            <select class="form-control product_unit" name="unit_id" id="sale_unit_id">
                <option value="">Select Unit</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                @endforeach
            </select>
            <span class="error error_sale_unit_id"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1')
            <div class="col-md-3">
                <label><b>Category :</b> </label>
                <select class="form-control category" name="category_id" id="sale_category_id">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1' && json_decode($generalSettings->product, true)['is_enable_sub_categories'] == '1')
            <div class="col-md-3 parent_category">
                <label><b>Child category :</b></label>
                <select class="form-control" name="child_category_id"
                    id="sale_child_category_id">
                    <option value="">Select category first</option>
                </select>
            </div>
        @endif

        @if (json_decode($generalSettings->product, true)['is_enable_brands'] == '1')
            <div class="col-md-3">
                <label><b>Brand :</b></label>
                <select class="form-control" data-live-search="true" name="brand_id"
                    id="sale_brand_id">
                    <option value="">Select Brand</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if (json_decode($generalSettings->product, true)['is_enable_warranty'] == '1')
            <div class="col-md-3">
                <label><b>Warranty :</b></label>
                <select class="form-control" name="warranty_id" id="sale_warranty_id">
                    <option value="">Select Warranty</option>
                    @foreach ($warranties as $warranty)
                        <option value="{{ $warranty->id }}">{{ $warranty->name }} ({{$warranty->type == 1 ? 'Warranty' : 'Guaranty'}})</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-8">
            <label><b>@lang('menu.description') :</b> </label>
            <textarea  name="product_details" class="form-control" cols="10" rows="3"></textarea>
        </div>
        <div class="col-md-4">
            <div class="row">
                &nbsp;&nbsp;&nbsp;&nbsp; <p class="checkbox_input_wrap p-0 m-0"> <input type="checkbox" name="is_show_in_ecom" id="is_show_in_ecom" value="1"> &nbsp; <b>Product wil be displayed in E-Commerce.</b>  &nbsp; </p>
                <p class="checkbox_input_wrap p-0 m-0"> <input type="checkbox" name="is_show_emi_on_pos" id="is_show_emi_on_pos" value="1"> &nbsp; <b>Enable IMEI or SL NO</b>  &nbsp;</p>
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        @if (json_decode($generalSettings->product, true)['is_enable_price_tax'] == '1')
            <div class="col-md-3 ">
                <label><b>Tax :</b> </label>
                <select class="form-control" name="tax_id" id="sale_tax_id">
                    <option value="">NoTax</option>
                    @foreach ($taxes as $tax)
                        <option value="{{ $tax->id.'-'.$tax->tax_percent }}">{{ $tax->tax_name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-md-3">
            <label><b>Alert quentity :</b></label>
            <input type="number" name="alert_quantity" class="form-control"
                autocomplete="off" id="sale_alert_quantity" value="0">
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <table class="table modal-table table-sm">
                <thead>
                    <tr class="bg-secondary text-white">
                        <th>Default Purchase Price</th>
                        <th>x Margin(%)</th>
                        <th>Selling Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col-md-6 text-start">
                                    <label><b>Item Cost Exc.Tax :</b> <span class="text-danger">*</span></label>
                                    <input type="text" name="product_cost" class="form-control" autocomplete="off" id="sale_product_cost" placeholder="Unit Cost Exc. Tax">
                                    <span class="error error_sale_product_cost"></span>
                                </div>
                                <div class="col-md-6 text-start">
                                    <label><b>Item Cost (Inc.Tax) :</b><span class="text-danger">*</span></label>
                                    <input type="text" name="product_cost_with_tax"
                                    class="form-control" autocomplete="off"
                                    id="sale_product_cost_with_tax" placeholder="Unit Cost Inc. Tax">
                                    <span class="error error_sale_product_cost_with_tax"></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <label></label>
                            <input type="text" name="profit" class="form-control" autocomplete="off" id="sale_profit" value="{{ json_decode($generalSettings->business, true)['default_profit'] }}">
                        </td>
                        <td class="text-start">
                            <div class="row">
                                <div class="col-md-12 text-start">
                                <label><b>Price Exc.Tax :</b><span class="text-danger">*</span></label>
                                    <input type="text" name="product_price" class="form-control" autocomplete="off" id="sale_product_price">
                                    <span class="error error_sale_product_price"></span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <p><strong>Add Opening Stock</strong></p>
            <div class="table-responsive">
                <table class="table modal-table table-sm">
                    <thead>
                        <tr class="bg-secondary text-white">
                            <th>Business Location</th>
                            <th>Quantity</th>
                            <th>Unit Cost Inc.Tax</th>
                            <th>SubTotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="hidden" name="branch_id" id="os_branch_id" value="{{ auth()->user()->branch_id }}">
                                <p>
                                    {!! auth()->user()->branch_id ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code :  json_decode($generalSettings->business, true)['shop_name'] .'<b>(HO)</b>' !!}
                                </p>
                            </td>

                            <td>
                                <input required type="number" name="quantity" id="os_quantity" step="any" class="form-control" value="0.00">
                            </td>

                            <td>
                                <input type="number" name="unit_cost_inc_tax" id="os_unit_cost_inc_tax" step="any" class="form-control" value="0.00">
                            </td>

                            <td>
                                <b><span class="os_span_subtotal">0.00</span></b>
                                <input type="hidden" name="subtotal" id="os_subtotal" value="0.00">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success">Save</button>
            </div>
        </div>
    </div>
</form>

<script>
    var tax_percent = 0;
    $(document).on('change', '#sale_tax_id',function() {

        var tax = $(this).val();
        if (tax) {

            var split = tax.split('-');
            tax_percent = split[1];
        }else{

            tax_percent = 0;
        }
    });

    function costCalculate() {

        var product_cost = $('#sale_product_cost').val() ? $('#sale_product_cost').val() : 0;
        var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent ? tax_percent : 0);
        var product_cost_with_tax = parseFloat(product_cost) + calc_product_cost_tax;
        $('#sale_product_cost_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
        var profit = $('#sale_profit').val() ? $('#sale_profit').val() : 0;
        var calculate_profit = parseFloat(product_cost) / 100 * parseFloat(profit);
        var product_price = parseFloat(product_cost) + parseFloat(calculate_profit);
        $('#sale_product_price').val(parseFloat(product_price).toFixed(2));
    }

    $(document).on('input', '#sale_product_cost',function() {

        var unit_cost_exc_tax = $(this).val() ? $(this).val() : 0;
        var unit_costs_inc_tax = parseFloat(unit_cost_exc_tax) / 100 * parseFloat(tax_percent ? tax_percent : 0) + parseFloat(unit_cost_exc_tax);
        $('#os_unit_cost_inc_tax').val(parseFloat(unit_costs_inc_tax).toFixed(2));
        costCalculate();
    });

    $(document).on('change', '#sale_tax_id', function() {

        costCalculate();
    });

    $(document).on('input', '#sale_profit',function() {

        costCalculate();
    });

    // Reduce empty opening stock qty field
    $(document).on('blur', '#os_quantity', function () {

        if ($(this).val() == '') {

            $(this).val(parseFloat(0).toFixed(2));
        }
    });

    // Reduce empty opening stock unit cost field
    $(document).on('blur', '#os_unit_cost_inc_tax', function () {

        if ($(this).val() == '') {

            $(this).val(parseFloat(0).toFixed(2));
        }
    });

    $(document).on('input', '#os_quantity', function () {

        var qty = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        var unit_cost_exc_tax = tr.find('#os_unit_cost_inc_tax').val() ? tr.find('#os_unit_cost_inc_tax').val() : 0;
        var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_exc_tax);
        tr.find('.os_span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        tr.find('#os_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
    });

    $(document).on('input', '#os_unit_cost_inc_tax', function () {

        var unit_cost_exc_tax = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        var qty = tr.find('#os_quantity').val() ? tr.find('#os_quantity').val() : 0;
        var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_exc_tax);
        tr.find('.os_span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        tr.find('#os_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
    });

    $('#sale_category_id').on('change', function () {

        var category_id = $(this).val();
        $.ajax({
            url:"{{ url('common/ajax/call/category/subcategories/') }}"+"/"+category_id,
            async:true,
            type:'get',
            dataType: 'json',
            success:function(subcate){

                $('#sale_child_category_id').empty();
                $('#sale_child_category_id').append('<option value="">Select Sub-Category</option>');

                $.each(subcate, function(key, val){

                    $('#sale_child_category_id').append('<option value="'+val.id+'">'+val.name+'</option>');
                });
            }
        });
    });
</script>
