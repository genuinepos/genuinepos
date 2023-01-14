<style>
    h6.checkbox_input_wrap {font-size: 13px;}
</style>
<form id="add_product_form" action="{{ route('purchases.add.product') }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-3">
            <label><b>@lang('menu.product_name') </b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="add_name" placeholder="@lang('menu.product_name')"/>
            <span class="error error_add_name"></span>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.product_code') (SKU) </b> <span class="text-danger">*</span></label>
            <input type="text" name="product_code" class="form-control" placeholder="@lang('menu.product_code')"/>
            <span class="error error_add_product_code"></span>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.barcode_type') </b></label>
            <select class="form-control" name="barcode_type" id="barcode_type">
                <option value="CODE128">Code 128 (C128)</option>
                <option value="CODE39">Code 39 (C39)</option>
                <option value="EAN13">EAN-13</option>
                <option value="UPC">UPC</option>
            </select>
        </div>

        <div class="col-md-3 ">
            <label><b> @lang('menu.unit') </b> <span class="text-danger">*</span></label>
            <select class="form-control product_unit" name="unit_id" id="add_unit_id">
                <option value="">@lang('menu.select_unit')</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }}({{ $unit->code_name }})</option>
                @endforeach
            </select>
            <span class="error error_add_unit_id"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        @if ($generalSettings['product__is_enable_categories'] == '1')
            <div class="col-md-3">
                <label><b>@lang('menu.category') </b> </label>
                <select class="form-control category" name="category_id" id="add_category_id">
                    <option value="">@lang('menu.select_category')</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <span class="error error_add_category_id"></span>
            </div>
        @endif

        @if ($generalSettings['product__is_enable_categories'] == '1' && $generalSettings['product__is_enable_sub_categories'] == '1')
            <div class="col-md-3 parent_category">
                <label><b>@lang('menu.child_category') :</b></label>
                <select class="form-control" name="sub_category_id" id="add_sub_category_id">
                    <option value="">@lang('menu.select_child_category_first')</option>
                </select>
            </div>
        @endif

        @if ($generalSettings['product__is_enable_brands'] == '1')
            <div class="col-md-3">
                <label><b>@lang('menu.brand') </b></label>
                <select class="form-control" data-live-search="true" name="brand_id"
                    id="add_brand_id">
                    <option value="">@lang('menu.select_brand')</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if ($generalSettings['product__is_enable_warranty'] == '1')
            <div class="col-md-3">
                <label><b>@lang('menu.warranty') </b></label>
                <select class="form-control" name="warranty_id" id="add_warranty_id">
                    <option value="">@lang('menu.select_warranty')</option>
                    @foreach ($warranties as $warranty)
                        <option value="{{ $warranty->id }}">{{ $warranty->name }} ({{$warranty->type == 1 ? 'Warranty' : 'Guaranty'}})</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-8">
            <label><b>@lang('menu.description') </b> </label>
            <textarea  name="product_details" class="form-control" cols="10" rows="3">
            </textarea>
        </div>

        <div class="col-md-4">
            <div class="row mt-5">
                <p class="checkbox_input_wrap p-0 m-0"> <input type="checkbox" name="is_show_in_ecom" id="is_show_in_ecom" value="1"> &nbsp; {{ __('Product wil be displayed in E-Commerce') }}. &nbsp; </p>
                <p class="checkbox_input_wrap p-0 m-0"> <input type="checkbox" name="is_show_emi_on_pos" id="is_show_emi_on_pos" value="1"> &nbsp; @lang('menu.enable_imei_or_serial') &nbsp;</p>
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        @if ($generalSettings['product__is_enable_price_tax'] == '1')
            <div class="col-md-3 ">
                <label><b>@lang('menu.tax') </b> </label>
                <select class="form-control" name="tax_id" id="add_tax_id">
                    <option value="">@lang('menu.no_tax')</option>
                    @foreach ($taxes as $tax)
                        <option value="{{ $tax->id.'-'.$tax->tax_percent }}">{{ $tax->tax_name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-md-3">
            <label><b>@lang('menu.alert_quantity') </b></label>
            <input type="number" name="alert_quantity" class="form-control"
                autocomplete="off" id="add_alert_quantity" value="0">
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <table class="table modal-table table-sm custom-table">
                <thead>
                    <tr class="bg-secondary">
                        <th class="text-white">@lang('menu.default_purchase_price')</th>
                        <th class="text-white">@lang('menu.x_margin')(%)</th>
                        <th class="text-white">@lang('menu.selling_price')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col-md-6 text-start">
                                    <label><b>@lang('menu.unit_cost_exc_tax') </b> <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="product_cost" id="add_product_cost" class="form-control" autocomplete="off" placeholder="@lang('menu.unit_cost_exc_tax')">
                                    <span class="error error_add_product_cost"></span>
                                </div>
                                <div class="col-md-6 text-start">
                                    <label><b>@lang('menu.unit_cost_inc_tax') </b> <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="product_cost_with_tax"
                                    class="form-control" autocomplete="off"
                                    id="add_product_cost_with_tax" placeholder="@lang('menu.unit_cost_inc_tax')">
                                    <span class="error error_add_product_cost_with_tax"></span>
                                </div>
                            </div>
                        </td>

                        <td>
                            <label></label>
                            <input type="number" step="any" name="profit" class="form-control" autocomplete="off" id="add_profit" value="{{ $generalSettings['business__default_profit'] }}"
                            placeholder="Profix Margin">
                        </td>

                        <td class="text-start">
                            <label><b>@lang('menu.price_exc_tax') </b> <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="product_price" class="form-control"
                                    autocomplete="off" id="add_product_price" placeholder="@lang('menu.price_exc_tax')">
                            <span class="error error_add_product_price"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
            </div>
        </div>
    </div>
</form>

<script>
    var tax_percent = 0;
    $(document).on('change', '#add_tax_id',function() {
        var tax = $(this).val();
        if (tax) {
            var split = tax.split('-');
            tax_percent = split[1];
        } else {
            tax_percent = 0;
        }
    });

    function costCalculate() {
        var product_cost = $('#add_product_cost').val() ? $('#add_product_cost').val() : 0;
        var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent ? tax_percent : 0);
        var product_cost_with_tax = parseFloat(product_cost) + calc_product_cost_tax;
        $('#add_product_cost_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
        var profit = $('#add_profit').val() ? $('#add_profit').val() : 0;
        var calculate_profit = parseFloat(product_cost) / 100 * parseFloat(profit);
        var product_price = parseFloat(product_cost) + parseFloat(calculate_profit);
        $('#add_product_price').val(parseFloat(product_price).toFixed(2));
    }

    $(document).on('input', '#add_product_cost',function() {
        console.log($(this).val());
        costCalculate();
    });

    $(document).on('change', '#add_tax_id', function() {
        costCalculate();
    });

    $(document).on('input', '#add_profit',function() {
        costCalculate();
    });

    // Add product by ajax
    $('#add_product_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $('.submit_button').prop('type', 'button');
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.error').html('');
                toastr.success('Successfully product is added.');
                $.ajax({
                    url:"{{url('purchases/recent/product')}}"+"/"+data.id,
                    type:'get',
                    success:function(data){

                        $('#addProductModal').modal('hide');
                        $('.loading_button').hide();
                        $('.submit_button').prop('type', 'submit');
                        $('#purchase_list').prepend(data);
                        calculateTotalAmount();
                        document.getElementById('search_product').focus();
                    }
                });
            },
            error : function(err) {

                $('.error').html('');
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                }else if (err.status == 500) {

                    toastr.error('Server error please contact to the support.');
                }

                toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_add_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('#add_category_id').on('change', function () {
        var category_id = $(this).val();
        $.ajax({
            url:"{{ url('common/ajax/call/category/subcategories/') }}"+"/"+category_id,
            async:true,
            type:'get',
            dataType: 'json',
            success:function(subcate){

                $('#add_sub_category_id').empty();
                $('#add_sub_category_id').append('<option value="">Select Sub-Category</option>');

                $.each(subcate, function(key, val){

                    $('#add_sub_category_id').append('<option value="'+val.id+'">'+val.name+'</option>');
                });
            }
        });
    });
</script>
