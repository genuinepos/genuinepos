<style>
    .set-height{
        position: relative;
    }
</style>
<div class="set-height">
    <div class="data_preloader submit_preloader">
        <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
    </div>
    <div class="table-responsive">
        <table class="table data__table modal-table table-sm sale-product-table">
            <thead>
                <tr>
                    <th scope="col">SL</th>
                    <th scope="col">Name</th>
                    <th scope="col">Qty/Weight</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Price.Inc.Tax</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col"><i class="fas fa-trash-alt text-danger"></i></th>
                </tr>
            </thead>

            <tbody id="product_list">
            
            </tbody>
        </table>
    </div>
</div>

<script>
    var unique_index = 0;
    var delay = (function() {
        var timer = 0;
        return function(callback, ms) {
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $('#search_product').on('input', function(e) {
        $('.select_area').hide();
        $('.variant_list_area').empty();
        var product_code = $(this).val() ? $(this).val() : 'no_key_word';
        var branch_id = $('#branch_id').val();
        var warehouse_id = $('#warehouse_id').val();
        delay(function() { searchProduct(product_code, branch_id, warehouse_id); }, 200); //sendAjaxical is the name of remote-command
    });

    function searchProduct(product_code, branch_id, warehouse_id) {
        var price_group_id = $('#price_group_id').val();
        $.ajax({
            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) 
                url:"{{ url('sales/search/product/in/warehouse') }}"+"/"+product_code+"/"+warehouse_id,
            @else
                url:"{{ url('sales/search/product') }}"+"/"+product_code+"/"+branch_id,
            @endif
            dataType: 'json',
            success: function(product) {
                if(!$.isEmptyObject(product.errorMsg || product_code == '')){
                    toastr.error(product.errorMsg); 
                    $('#search_product').val("");
                    $('.select_area').hide();
                    return;
                }

                var qty_limit = product.qty_limit;
                if (!$.isEmptyObject(product.product) || !$.isEmptyObject(product.variant_product) || !$.isEmptyObject(product.namedProducts)) {
                    $('#search_product').addClass('is-valid');
                    if (!$.isEmptyObject(product.product)) {
                        $('#search_product').val('');
                        $('.select_area').hide();
                        var product = product.product;
                        if (product.product_variants.length == 0) {
                            $('#stock_quantity').val(qty_limit);
                            $('.select_area').hide();
                            $('#search_product').val('');
                            product_ids = document.querySelectorAll('#product_id');
                            var sameProduct = 0;
                            product_ids.forEach(function(input) {
                                if (input.value == product.id) {
                                    sameProduct += 1;
                                    var className = input.getAttribute('class');
                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.' + className).closest('tr');
                                    var presentQty = closestTr.find('#quantity').val();
                                    var previousQty = closestTr.find('#previous_qty').val();
                                    var limit = closestTr.find('#qty_limit').val()
                                    var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                                    if (parseFloat(qty_limit) == parseFloat(presentQty)) {
                                        toastr.error('Quantity Limit is - ' + qty_limit + ' ' + product.unit.name);
                                        return;
                                    }
                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                    //Update Subtotal
                                    var unitPrice = closestTr.find('#unit_price_inc_tax').val();
                                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                    closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                    closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                    calculateTotalAmount();
                                    return;
                                }
                            });

                            if (sameProduct == 0) {
                                var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0;
                                var tax_amount = parseFloat(product.tax != null ? product.product_price / 100 * product.tax.tax_percent : 0);
                                var tr = '';
                                tr += '<tr>';
                                tr += '<td class="serial">1</td>';
                        
                                tr += '<td class="text-start">';
                                tr += '<a class="product-name text-info" id="edit_product" title="'+product.product_code+'" href="#">' +
                                    product.name + '</a><br/><input type="'+(product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control description_input scanable" placeholder="IMEI, Serial number or other info.">';
                                tr += '<input value="' + product.id +'" type="hidden" class="productId-' + product.id +
                                    '" id = "product_id" name="product_ids[]" >';
                                tr +='<input input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                                tr +='<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="' +
                                    tax_percent + '">';
                                tr +='<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="' +
                                    parseFloat(tax_amount).toFixed(2) + '">';
                                tr +='<input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                                tr +='<input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">';
                                tr +='<input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                                tr += '<input value="' + product.product_cost_with_tax +'" name="unit_costs_inc_tax[]" type="hidden" id="unit_costs_inc_tax">';
                                tr += '<input type="hidden" id="previous_qty" value="0.00">';
                                tr += '<input type="hidden" id="qty_limit" value="' +
                                    qty_limit + '">';
                                tr += '<input class="index-' + unique_index +'" type="hidden" id="index">';
                                tr += '</td>';

                                tr += '<td>';
                                tr +='<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<b><span class="span_unit">' + product.unit.name +
                                    '</span></b>';
                                tr += '<input name="units[]" type="hidden" id="unit" value="' +
                                    product.unit.name +'">';
                                tr += '</td>';

                                tr += '<td>';

                                var price = 0;
                                var __price = price_groups.filter(function (value) {
                                    return value.price_group_id == price_group_id && value.product_id == product.id;
                                });

                                if (__price.length != 0) {
                                    price = __price[0].price ? __price[0].price : product.product_price;
                                } else {
                                    price = product.product_price;
                                }

                                tr +='<input name="unit_prices_exc_tax[]" type="hidden" value="'+ parseFloat(price).toFixed(2) +'" id="unit_price_exc_tax">';
                                var unitPriceIncTax = parseFloat(price) / 100 * parseFloat(tax_percent) + parseFloat(price);
                                tr +='<input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="'+parseFloat(unitPriceIncTax).toFixed(2) +'">';
                                tr += '<b><span class="span_unit_price_inc_tax">'+ parseFloat(unitPriceIncTax).toFixed(2) +'</span> </b>';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="' + parseFloat(unitPriceIncTax).toFixed(
                                    2) + '" name="subtotals[]" type="hidden" id="subtotal">';
                                tr += '<b><span class="span_subtotal">' + parseFloat(
                                    unitPriceIncTax).toFixed(2) + '</span></b>';
                                tr += '</td>';

                                tr +='<td><a href="#" class="action-btn c-delete" id="remove_product_btn"><span class="fas fa-trash "></span></a></td>';
                                tr += '</tr>';

                                $('#product_list').prepend(tr);
                                calculateTotalAmount();
                                unique_index++;
                            }
                        } else {
                            var li = "";
                            // <img style="width:30px; height:30px;" src="' +imgUrl + '/' + product.thumbnail_photo + '"> 
                            var imgUrl = "{{ asset('public/uploads/product/thumbnail') }}";
                            var tax_percent = product.tax_id != null ? product.tax.tax_percent :
                                0.00;
                            $.each(product.product_variants, function(key, variant) {
                                var tax_amount = parseFloat(product.tax != null ? variant.variant_price / 100 * product.tax.tax_percent : 0.00);
                                var unitPriceIncTax = (parseFloat(variant.variant_price) / 100 * tax_percent) + parseFloat(variant.variant_price);
                                li += '<li class="mt-1">';
                                li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="' +product.id + '" data-v_id="' + variant.id +'" data-p_name="' + product.name +'" data-p_tax_id="' +product.tax_id + '" data-unit="' + product.unit.name + '" data-tax_percent="' + tax_percent +'" data-tax_amount="' + tax_amount +'" data-v_code="' + variant.variant_code +'" data-v_price="' + variant.variant_price +'" data-v_name="' + variant.variant_name +'" data-v_cost_inc_tax="' + variant.variant_cost_with_tax +'" href="#">' +product.name + ' - ' + variant.variant_name + ' (' +variant.variant_code + ')' + ' - Price: ' +parseFloat(unitPriceIncTax).toFixed(2) + '</a>';
                                li += '</li>';
                            });
                            $('.variant_list_area').prepend(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }

                    } else if (!$.isEmptyObject(product.variant_product)) {
                        $('#stock_quantity').val(qty_limit);
                        $('#search_product').val('');
                        $('.select_area').hide();
                        var variant_product = product.variant_product;
                        console.log(variant_product);
                        var tax_percent = variant_product.product.tax_id != null ?
                            variant_product.product.tax.percent : 0;
                        var tax_rate = parseFloat(variant_product.product.tax != null ?
                            variant_product.variant_price / 100 * tax_percent : 0);
                        var variant_ids = document.querySelectorAll('#variant_id');
                        var sameVariant = 0;
                        variant_ids.forEach(function(input) {
                            console.log(input.value);
                            if (input.value != 'noid') {
                                if (input.value == variant_product.id) {
                                    sameVariant += 1;
                                    var className = input.getAttribute('class');
                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.' + className).closest('tr');
                                    var presentQty = closestTr.find('#quantity').val();
                                    var previousQty = closestTr.find('#previous_qty').val();
                                    var limit = closestTr.find('#qty_limit').val()
                                    var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                                    if (parseFloat(qty_limit) == parseFloat(presentQty)) {
                                        toastr.error('Quantity Limit is - ' + qty_limit + ' ' + variant_product.product.unit.name);
                                        return;
                                    }
                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                    //Update Subtotal
                                    var unitPrice = closestTr.find('#unit_price_inc_tax').val();
                                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                    closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                    closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                    calculateTotalAmount();
                                    return;
                                }
                            }
                        });

                        if (sameVariant == 0) {
                            var tax_percent = variant_product.product.tax_id != null ?
                                variant_product.product.tax.tax_percent : 0;
                            var tax_amount = parseFloat(variant_product.product.tax != null ?
                                variant_product.variant_price / 100 * variant_product.product.tax.tax_percent : 0);
                            var tr = '';
                            tr += '<tr>';
                            tr += '<td class="serial">1</td>';
                            tr += '<td class="text-start">';
                            tr += '<a class="product-name text-info" id="edit_product" title="'+variant_product.variant_code+'" href="#">' +
                                variant_product.product.name + ' - ' + variant_product.variant_name + '</a><br/><input type="'+(variant_product.product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control description_input scanable" placeholder="IMEI, Serial number or other info.">';
                            tr += '<input value="' + variant_product.product.id +
                                '" type="hidden" class="productId-' + variant_product.product.id + '" id = "product_id" name="product_ids[]" >';
                            tr += '<input input value="' + variant_product.id +'" type="hidden" class="variantId-' + variant_product.id +'" id="variant_id" name="variant_ids[]">';
                            tr +='<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="' +tax_percent + '">';
                            tr +='<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="' +parseFloat(tax_amount).toFixed(2) + '">';
                            tr +='<input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                            tr +='<input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">';
                            tr +='<input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                            tr += '<input value="' + variant_product.variant_cost_with_tax +
                                '" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                            tr += '<input type="hidden" id="previous_qty" value="0.00">';
                            tr += '<input type="hidden" id="qty_limit" value="' + qty_limit +
                                '">';
                            tr += '<input class="index-' + unique_index +
                                '" type="hidden" id="index">';
                            tr += '</td>';

                            tr += '<td>';
                            
                            tr +='<input value="1.00" required name="quantities[]" type="text" class="form-control text-center" id="quantity">';
                            
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<b><span class="span_unit">'+ variant_product.product.unit.name +'</span></b>';
                            tr += '<input name="units[]" type="hidden" id="unit" value="'+ variant_product.product.unit.name +'">';
                            tr += '</td>';

                            tr += '<td>';

                            var price = 0;
                            var __price = price_groups.filter(function (value) {
                                return value.price_group_id == price_group_id && value.product_id == variant_product.product.id && value.variant_id == variant_product.id;
                            });

                            if (__price.length != 0) {
                                price = __price[0].price ? __price[0].price : variant_product.variant_price;
                            } else {
                                price = variant_product.variant_price;
                            }

                            tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+ parseFloat(price).toFixed(2) +'" id="unit_price_exc_tax">';
                            var unitPriceIncTax = parseFloat(price) / 100 * parseFloat(tax_percent) + parseFloat(price);

                            tr +='<input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="'+ parseFloat(unitPriceIncTax).toFixed(2)+'">';
                            tr += '<b><span class="span_unit_price_inc_tax">'+ parseFloat(unitPriceIncTax).toFixed(2) +'</span> </b>';
                            tr += '</td>';
                            tr += '<td>';
                            tr += '<input value="' + parseFloat(unitPriceIncTax).toFixed(2) +
                                '" name="subtotals[]" type="hidden" id="subtotal">';
                            tr += '<b><span class="span_subtotal">' + parseFloat(unitPriceIncTax).toFixed(2) + '</span></b>';
                            tr += '</td>';
                            tr +='<td><a href="#" class="action-btn c-delete" id="remove_product_btn"><span class="fas fa-trash "></span></a></td>';
                            tr += '</tr>';

                            $('#product_list').prepend(tr);
                            calculateTotalAmount();
                            unique_index++;
                        }
                    } else if (!$.isEmptyObject(product.namedProducts)) {
                        $('#current_stock').val('');
                        if (product.namedProducts.length > 0) {
                            var li = "";
                            var imgUrl = "{{ asset('public/uploads/product/thumbnail') }}";
                            // <img style="width:30px; height:30px;" src="' + imgUrl + '/' + product.thumbnail_photo + '"> 
                            var products = product.namedProducts;
                            $.each(products, function(key, product) {
                                var tax_percent = product.tax_id != null ? product.tax
                                    .tax_percent : 0;
                                if (product.product_variants.length > 0) {
                                    $.each(product.product_variants, function(key,
                                        variant) {
                                        var tax_amount = parseFloat(product.tax != null ? variant.variant_price / 100 * product.tax.tax_percent : 0.00);
                                        var unitPriceIncTax = (parseFloat(variant.variant_price) / 100 * tax_percent) + parseFloat(variant.variant_price);
                                        li += '<li class="mt-1">';
                                        li +='<a class="select_variant_product s" onclick="salectVariant(this); return false;" data-p_id="' +product.id + '" data-v_id="' +variant.id + '" data-p_name="' +product.name + '" data-p_tax_id="' +product.tax_id + '" data-unit="' +product.unit.name +'" data-tax_percent="' +tax_percent +'" data-tax_amount="' + tax_amount +'" data-v_code="' + variant.variant_code + '" data-v_price="'+variant.variant_price +'" data-v_name="' + variant.variant_name +'" data-v_cost_inc_tax="' + variant.variant_cost_with_tax +'" href="#">' + product.name + ' - ' + variant.variant_name + ' (' + variant.variant_code + ')' + ' - Price: ' + parseFloat(unitPriceIncTax).toFixed(2) + '</a>';
                                        li += '</li>';
                                    });
                                } else {
                                    // <img style="width:30px; height:30px;" src="' +imgUrl + '/' + product.thumbnail_photo +'">
                                    var tax_amount = parseFloat(product.tax != null ? product.product_price / 100 * product.tax.tax_percent : 0);
                                    var unitPriceIncTax = (parseFloat(product.product_price) / 100 * tax_percent) + parseFloat(product.product_price);
                                    li += '<li class="mt-1">';
                                    li +='<a class="select_single_product s" onclick="singleProduct(this); return false;" data-p_id="' +
                                        product.id + '" data-p_name="' + product.name +
                                        '" data-unit="' + product.unit.name + '" data-p_code="' + product.product_code +'" data-p_price_exc_tax="' + product.product_price + '" data-p_tax_percent="' +tax_percent + '" data-p_tax_amount="' +tax_amount + '" data-p_cost_inc_tax="' +product.product_cost_with_tax +'" data-description="'+product.is_show_emi_on_pos+'"  href="#">' +product.name + ' (' + product.product_code +')' + ' - Price: ' + parseFloat(unitPriceIncTax).toFixed(2) + '</a>';
                                    li += '</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    }
                } else {
                    $('#search_product').addClass('is-invalid');
                }
            }
        });
    }
</script>