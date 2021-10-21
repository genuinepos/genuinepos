<script src="{{ asset('public') }}/assets/plugins/custom/select_li/selectli.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Get all price group
    var price_groups = '';
    function getPriceGroupProducts(){
        $.ajax({
            url:"{{route('sales.product.price.groups')}}",
            success:function(data){
                price_groups = data;
            }
        });
    }
    getPriceGroupProducts();

    // Get all unite for form field
    var unites = [];
    function getUnites(){
        $.get("{{ route('purchases.get.all.unites') }}", function(units) {
            $.each(units, function(key, unit){
                unites.push(unit.name); 
            });
        });
    }
    getUnites();

    // Get all taxes for form field
    var taxArray;
    function getTaxes(){
        $.get("{{ route('purchases.get.all.taxes') }}", function(taxes) {
            taxArray = taxes;
            $('#order_tax').append('<option value="0.00">No Tax</option>');
            $.each(taxes, function(key, val){
                $('#order_tax').append('<option value="'+val.tax_percent+'">'+val.tax_name+'</option>');
            });
        });
    }
    getTaxes();

    // Calculate total amount functionalitie
    function calculateTotalAmount(){
        var quantities = document.querySelectorAll('#quantity');
        var subtotals = document.querySelectorAll('#subtotal');
        // Update Total Item
        var total_item = 0;
        quantities.forEach(function(qty){
            total_item += 1;
        });

        $('#total_item').val(parseFloat(total_item));

        // Update Net total Amount
        var netTotalAmount = 0;
        subtotals.forEach(function(subtotal){
            netTotalAmount += parseFloat(subtotal.value);
        });

        $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));
        if ($('#order_discount_type').val() == 2) {
            var orderDisAmount = parseFloat(netTotalAmount) /100 * parseFloat($('#order_discount').val() ? $('#order_discount').val() : 0);
            $('#order_discount_amount').val(parseFloat(orderDisAmount).toFixed(2));
        }else{
            var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0
            $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
        }
        
        // Calc order tax amount
        var orderDiscountAmount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;
        var orderTax = $('#order_tax').val() ? $('#order_tax').val() : 0;
        var calcOrderTaxAmount = (parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount)) / 100 * parseFloat(orderTax) ;
        $('#order_tax_amount').val(parseFloat(calcOrderTaxAmount).toFixed(2));
        
        // Update Total payable Amount
        var shipmentCharge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;
        var calcTotalPayableAmount = parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount) + parseFloat(calcOrderTaxAmount) + parseFloat(shipmentCharge);
        $('#total_payable_amount').val(parseFloat(calcTotalPayableAmount).toFixed(2));
    }

    var delay = (function() {
        var timer = 0;
        return function(callback, ms) {
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    var unique_index = 0;
    $('#search_product').on('input', function(e) {
        $('.variant_list_area').empty();
        $('.select_area').hide();
        var product_code = $(this).val();
        delay(function() { searchProduct(product_code); }, 200); //sendAjaxical is the name of remote-command
    });

    // add Sale product by searching product code
    function searchProduct(product_code){
        var price_group_id = $('#price_group_id').val();
        $('.variant_list_area').empty();
        $('.select_area').hide();
        $.ajax({
            url:"{{ url('sales/search/product') }}"+"/"+product_code,
            dataType: 'json',
            success:function(product){
                if(!$.isEmptyObject(product.errorMsg)){
                    toastr.error(product.errorMsg); 
                    $('#search_product').val("");
                    return;
                }
                var qty_limit = product.qty_limit;
                if(!$.isEmptyObject(product.product) || !$.isEmptyObject(product.variant_product) || !$.isEmptyObject(product.namedProducts)){
                    $('#search_product').addClass('is-valid');
                    if(!$.isEmptyObject(product.product)){
                        var product = product.product;
                        if(product.product_variants.length == 0){
                            $('.select_area').hide();
                            $('#search_product').val('');
                            $('#stock_quantity').val(parseFloat(qty_limit).toFixed(2));
                            product_ids = document.querySelectorAll('#product_id');
                            var sameProduct = 0;
                            product_ids.forEach(function(input){
                                if(input.value == product.id){
                                    sameProduct += 1;
                                    var className = input.getAttribute('class');
                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.'+className).closest('tr');
                                    var presentQty = closestTr.find('#quantity').val();
                                    var previousQty = closestTr.find('#previous_quantity').val();
                                    var limit = closestTr.find('#qty_limit').val()
                                    var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                                    if(parseFloat(qty_limit) == parseFloat(presentQty)){
                                        alert('Quantity exceeds stock quantity!');
                                        return;
                                    }
                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                                    
                                    //Update Subtotal
                                    var unitPrice = closestTr.find('#unit_price').val();
                                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                    closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                    closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                    calculateTotalAmount();
                                    return;
                                }
                            });

                            if(sameProduct == 0){
                                var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0;
                                var price = 0;
                                var __price = price_groups.filter(function (value) {
                                    return value.price_group_id == price_group_id && value.product_id == product.id;
                                });

                                if (__price.length != 0) {
                                    price = __price[0].price ? __price[0].price : product.product_price;
                                } else {
                                    price = product.product_price;
                                }
                                var tax_amount = parseFloat(price / 100 * tax_percent);
                                var unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);
                                if (product.tax_type == 2) {
                                    var inclusiveTax = 100 + parseFloat(tax_percent)
                                    var calcAmount = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                                    tax_amount = parseFloat(price) - parseFloat(calcAmount);
                                    var unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);
                                }
                                var tr = '';
                                tr += '<tr>';
                                tr += '<td colspan="2" class="text-start">';
                                tr += '<a href="#" class="text-success" id="edit_product">';
                                tr += '<span class="product_name">'+ product.name +'</span>';
                                tr += '<span class="product_variant"></span>'; 
                                tr += '<span class="product_code">'+ ' ('+product.product_code+')' +'</span>';
                                tr += '</a><br/><input type="'+ (product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="IMEI, Serial number or other informations here.">';
                                tr += '<input value="'+ product.id +'" type="hidden" class="productId-'+ product.id +'" id="product_id" name="product_ids[]">';
                                tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                                tr += '<input value="'+ product.tax_type +'" type="hidden" id="tax_type">';
                                tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+ tax_percent +'">';
                                tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+ parseFloat(tax_amount).toFixed(2) +'">';
                                tr += '<input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                                tr += '<input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">';
                                tr += '<input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                                tr += '<input value="'+ product.product_cost_with_tax +'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                                tr += '<input type="hidden" id="previous_quantity" value="0">';
                                tr += '<input type="hidden" id="qty_limit" value="'+ qty_limit +'">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                                tr += '</td>';
                                tr += '<td class="text">';
                                tr += '<span class="span_unit">'+ product.unit.name +'</span>'; 
                                tr += '<input  name="units[]" type="hidden" id="unit" value="'+ product.unit.name +'">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input readonly name="unit_prices_exc_tax[]" type="hidden"  id="unit_price_exc_tax" value="'+ parseFloat(price).toFixed(2) +'">';
                                tr += '<input readonly name="unit_prices[]" type="text" class="form-control form-control-sm text-center" id="unit_price" value="'+ parseFloat(unitPriceIncTax).toFixed(2) +'">';
                                tr += '</td>';

                                tr += '<td class="text text-center">';
                                tr += '<strong><span class="span_subtotal">'+ parseFloat(unitPriceIncTax).toFixed(2) +' </span></strong>'; 
                                tr += '<input value="'+ parseFloat(unitPriceIncTax).toFixed(2) +'" readonly name="subtotals[]" type="hidden"  id="subtotal">';
                                tr += '</td>';
                                tr += '<td class="text-center">';
                                tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                tr += '</td>';
                                tr += '</tr>';
                                $('#sale_list').append(tr);
                                calculateTotalAmount(); 
                            }
                        }else{
                            var li = "";
                            var imgUrl = "{{ asset('public/uploads/product/thumbnail') }}";
                            var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0.00;
                            $.each(product.product_variants, function(key, variant){
                                var tax_amount = parseFloat(variant.variant_price/100 * tax_percent);
                                var unitPriceIncTax = (parseFloat(variant.variant_price) / 100 * tax_percent) + parseFloat(variant.variant_price);
                                if (product.tax_type == 2) {
                                    var inclusiveTax = 100 + parseFloat(tax_percent);
                                    var calcTax = parseFloat(variant.variant_price) / parseFloat(inclusiveTax) * 100;
                                    var __tax_amount = parseFloat(variant.variant_price) - parseFloat(calcTax);
                                    unitPriceIncTax = parseFloat(variant.variant_price) + parseFloat(__tax_amount);
                                    tax_amount = __tax_amount;
                                }
                                li += '<li class="mt-1">';
                                li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-unit="'+product.unit.name+'" data-tax_percent="'+tax_percent+'" data-tax_type="'+product.tax_type+'" data-tax_amount="'+tax_amount+'" data-v_code="'+variant.variant_code+'" data-v_price="'+variant.variant_price+'" data-v_name="'+variant.variant_name+'" data-v_cost_inc_tax="'+variant.variant_cost_with_tax+'" href="#"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+variant.variant_name+' ('+variant.variant_code+')'+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                li += '</li>';
                            });
                            $('.variant_list_area').append(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    }else if(!$.isEmptyObject(product.variant_product)){
                        $('.select_area').hide();
                        $('#search_product').val('');
                        $('#stock_quantity').val(parseFloat(qty_limit).toFixed(2));
                        var variant_product = product.variant_product;
                        var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.tax_percent : 0;
                        var variant_ids = document.querySelectorAll('#variant_id');
                        var sameVariant = 0;
                        variant_ids.forEach(function(input){
                            if(input.value != 'noid'){
                                if(input.value == variant_product.id){
                                    sameVariant += 1;
                                    var className = input.getAttribute('class');
                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.'+className).closest('tr');
                                    var presentQty = closestTr.find('#quantity').val();
                                    var previousQty = closestTr.find('#previous_quantity').val();
                                    var limit = closestTr.find('#qty_limit').val()
                                    var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                                    if(parseFloat(qty_limit) == parseFloat(presentQty)){
                                        alert('Quantity exceeds stock quantity!');
                                        return;
                                    }
                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                                    
                                    //Update Subtotal
                                    var unitPrice = closestTr.find('#unit_price').val();
                                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                    closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                    closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                    calculateTotalAmount();
                                    return;
                                }
                            }    
                        });
                        
                        if(sameVariant == 0){
                            var price = 0;
                            var __price = price_groups.filter(function (value) {
                                return value.price_group_id == price_group_id && value.product_id == variant_product.product.id && value.variant_id == variant_product.id;
                            });

                            if (__price.length != 0) {
                                price = __price[0].price ? __price[0].price : variant_product.variant_price;
                            } else {
                                price = variant_product.variant_price;
                            }
                            var tax_amount = parseFloat(price / 100 * tax_percent);
                            var unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);
                            if (variant_product.product.tax_type == 2) {
                                var inclusiveTax = 100 + parseFloat(tax_percent)
                                var calcAmount = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                                tax_amount = parseFloat(price) - parseFloat(calcAmount);
                                var unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);
                            }
                            var tr = '';
                            tr += '<tr>';
                            tr += '<td colspan="2" class="text-start">';
                            tr += '<a href="#" class="text-success" id="edit_product">';
                            tr += '<span class="product_name">'+variant_product.product.name+'</span>';
                            tr += '<span class="product_variant">'+' -'+variant_product.variant_name+'- '+'</span>'; 
                            tr += '<span class="product_code">'+'('+variant_product.variant_code+')'+'</span>';
                            tr += '</a><br/><input type="'+(variant_product.product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="IMEI, Serial number or other informations here.">';
                            tr += '<input value="'+variant_product.product.id+'" type="hidden" class="productId-'+variant_product.product.id+'" id="product_id" name="product_ids[]">';
                            tr += '<input value="'+variant_product.id+'" type="hidden" class="variantId-'+variant_product.id+'" id="variant_id" name="variant_ids[]">';
                            tr += '<input value="'+variant_product.product.tax_type+'" type="hidden" id="tax_type">';
                            tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+tax_percent+'">';
                            tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(tax_amount).toFixed(2)+'">';
                            tr += '<input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                            tr += '<input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">';
                            tr += '<input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                            tr += '<input value="'+variant_product.variant_cost_with_tax+'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                            tr += '<input type="hidden" id="previous_quantity" value="0">';
                            tr += '<input type="hidden" id="qty_limit" value="'+qty_limit+'">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center form-control-sm" id="quantity">';
                            tr += '</td>';
                            tr += '<td class="text">';
                            tr += '<span class="span_unit">'+variant_product.product.unit.name+'</span>'; 
                            tr += '<input  name="units[]" type="hidden" id="unit" value="'+variant_product.product.unit.name+'">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+parseFloat(price).toFixed(2)+'" id="unit_price_exc_tax">';
                            tr += '<input readonly name="unit_prices[]" type="text" class="form-control form-control-sm text-center" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2) +'">';
                            tr += '</td>';

                            tr += '<td class="text text-center">';
                            tr += '<strong><span class="span_subtotal">'+parseFloat(unitPriceIncTax).toFixed(2)+'</span></strong>'; 
                            tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal">';
                            tr += '</td>';

                            tr += '<td class="text-center">';
                            tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';
                            $('#sale_list').append(tr);
                            calculateTotalAmount();
                        }    
                    }else if (!$.isEmptyObject(product.namedProducts)) {
                        if(product.namedProducts.length > 0){
                            var li = "";
                            var imgUrl = "{{asset('public/uploads/product/thumbnail')}}";
                            var products = product.namedProducts; 
                            $.each(products, function (key, product) {
                                var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0;
                                if (product.product_variants.length > 0) {
                                    $.each(product.product_variants, function(key, variant){
                                        var tax_amount = parseFloat(variant.variant_price/100 * tax_percent);
                                        var unitPriceIncTax = (parseFloat(variant.variant_price) / 100 * tax_percent) + parseFloat(variant.variant_price);
                                        if (product.tax_type == 2) {
                                            var inclusiveTax = 100 + parseFloat(tax_percent);
                                            var calcTax = parseFloat(variant.variant_price) / parseFloat(inclusiveTax) * 100;
                                            var __tax_amount = parseFloat(variant.variant_price) - parseFloat(calcTax);
                                            unitPriceIncTax = parseFloat(variant.variant_price) + parseFloat(__tax_amount);
                                            tax_amount = __tax_amount;
                                        }
                                        li += '<li>';
                                        li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-unit="'+product.unit.name+'" data-tax_percent="'+tax_percent+'" data-tax_type="'+product.tax_type+'" data-tax_amount="'+tax_amount+'" data-v_code="'+variant.variant_code+'" data-description="'+product.is_show_emi_on_pos+'" data-v_price="'+variant.variant_price+'" data-v_name="'+variant.variant_name+'" data-v_cost_inc_tax="'+variant.variant_cost_with_tax+'" href="#"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+variant.variant_name+' ('+variant.variant_code+')'+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                        li +='</li>';
                                    });
                                }else{
                                    var tax_amount = parseFloat(product.product_price/100 * tax_percent);
                                    var unitPriceIncTax = (parseFloat(product.product_price) / 100 * tax_percent) + parseFloat(product.product_price);
                                    if (product.tax_type == 2) {
                                        var inclusiveTax = 100 + parseFloat(tax_percent);
                                        var calcTax = parseFloat(product.product_price) / parseFloat(inclusiveTax) * 100;
                                        var __tax_amount = parseFloat(product.product_price) - parseFloat(calcTax);
                                        unitPriceIncTax = parseFloat(product.product_price) + parseFloat(__tax_amount);
                                        tax_amount = __tax_amount;
                                    }

                                    li += '<li>';
                                    li += '<a class="select_single_product" onclick="singleProduct(this); return false;" data-p_id="'+product.id+'" data-p_name="'+product.name+'" data-unit="'+product.unit.name+'" data-p_code="'+product.product_code+'" data-p_price_exc_tax="'+product.product_price+'" data-description="'+product.is_show_emi_on_pos+'" data-p_tax_percent="'+tax_percent+'" data-tax_type="'+product.tax_type+'"  data-p_tax_amount="'+tax_amount+'" data-p_cost_inc_tax="'+product.product_cost_with_tax+'" href="#"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' ('+product.product_code+')'+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                    li +='</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    }
                }else{
                    $('#search_product').addClass('is-invalid');
                    $('#search_product').val('');
                    toastr.error('Product not found.', 'Failed'); 
                }
            }
        });
    }

    // select single product and add stock adjustment table
    var keyName = '';
    function singleProduct(e){
        var price_group_id = $('#price_group_id').val();
        $('.select_area').hide();
        $('#search_product').val('');
        if (keyName == 13 || keyName == 1) {
            document.getElementById('search_product').focus();
        }

        var product_id = e.getAttribute('data-p_id');
        var product_name = e.getAttribute('data-p_name');
        var product_code = e.getAttribute('data-p_code');
        var product_unit = e.getAttribute('data-unit');
        var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');
        var product_price_exc_tax = e.getAttribute('data-p_price_exc_tax');
        var p_tax_percent = e.getAttribute('data-p_tax_percent');
        var p_tax_amount = e.getAttribute('data-p_tax_amount');
        var p_tax_type = e.getAttribute('data-tax_type');
        var description = e.getAttribute('data-description');
    
        $.ajax({
            url:"{{ url('sales/check/single/product/stock/') }}"+"/"+product_id,
            async:true,
            type:'get',
            dataType: 'json',
            success:function(singleProductQty){
                if($.isEmptyObject(singleProductQty.errorMsg)){
                    $('#stock_quantity').val(parseFloat(singleProductQty).toFixed(2));
                    var product_ids = document.querySelectorAll('#product_id');
                    var sameProduct = 0;
                    product_ids.forEach(function(input){
                        if(input.value == product_id){
                            sameProduct += 1;
                            var className = input.getAttribute('class');
                            // get closest table row for increasing qty and re calculate product amount
                            var closestTr = $('.'+className).closest('tr');
                            var presentQty = closestTr.find('#quantity').val();
                            var qty_limit = closestTr.find('#qty_limit').val();
                            if(parseFloat(qty_limit)  === parseFloat(presentQty)){
                                alert('Quantity exceeds stock quantity!');
                                return;
                            }
                            var updateQty = parseFloat(presentQty) + 1;
                            closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                            
                            //Update Subtotal
                            var unitPrice = closestTr.find('#unit_price').val();
                            var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);

                            closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                            closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                            calculateTotalAmount();
                            if (keyName == 9) {
                                closestTr.find('#quantity').focus();
                                closestTr.find('#quantity').select();
                                keyName = 1;
                            }
                            return;
                        }    
                    });

                    if(sameProduct == 0){
                        var price = 0;
                        var __price = price_groups.filter(function (value) {
                            return value.price_group_id == price_group_id && value.product_id == product_id;
                        });

                        if (__price.length != 0) {
                            price = __price[0].price ? __price[0].price : product_price_exc_tax;
                        } else {
                            price = product_price_exc_tax;
                        }

                        var tr = '';
                        tr += '<tr>';
                        tr += '<td colspan="2" class="text-start">';
                        tr += '<a href="#" class="text-success" id="edit_product">';
                        tr += '<span class="product_name">'+product_name+'</span>';
                        tr += '<span class="product_variant"></span>'; 
                        tr += '<span class="product_code">'+' ('+product_code+')'+'</span>';
                        tr += '</a><br/><input type="'+(description == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="IMEI, Serial number or other informations here.">';
                        tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                        tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                        tr += '<input value="'+p_tax_type+'" type="hidden" id="tax_type">';
                        tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+p_tax_percent+'">';
                        tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(p_tax_amount).toFixed(2)+'">';
                        tr += '<input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                        tr += '<input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">';
                        tr += '<input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                        tr += '<input value="'+product_cost_inc_tax+'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                        tr += '<input type="hidden" id="previous_quantity" value="0">';
                        tr += '<input type="hidden" id="qty_limit" value="'+singleProductQty+'">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                        tr += '</td>';
                        tr += '<td class="text">';
                        tr += '<b><span class="span_unit">'+product_unit+'</span></b>'; 
                        tr += '<input  name="units[]" type="hidden" id="unit" value="'+product_unit+'">';
                        tr += '</td>';
                        tr += '<td>';

                        tr += '<input readonly name="unit_prices_exc_tax[]" type="hidden" id="unit_price_exc_tax" value="'+parseFloat(price).toFixed(2)+'">';

                        var unitPriceIncTax = parseFloat(price) / 100 * parseFloat(p_tax_percent) + parseFloat(price);
                        if (p_tax_type == 2) {
                            var inclusiveTax = 100 + parseFloat(p_tax_percent);
                            var calcTax = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                            var __tax_amount = parseFloat(price) - parseFloat(calcTax);
                            unitPriceIncTax = parseFloat(price) + parseFloat(__tax_amount);
                        }

                        tr += '<input readonly name="unit_prices[]" type="text" class="form-control text-center" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2)+'">';
                        tr += '</td>';
                        tr += '<td class="text text-center">';
                        tr += '<strong><span class="span_subtotal"> '+parseFloat(unitPriceIncTax).toFixed(2)+' </span></strong>'; 
                        tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal">';
                        tr += '</td>';
                        tr += '<td class="text-center">';
                        tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#sale_list').prepend(tr);
                        calculateTotalAmount();  
                        if (keyName == 9) {
                            $("#quantity").select();
                            keyName = 1;
                        }
                    }
                }else{
                    toastr.error(singleProductQty.errorMsg);   
                }
            }
        });
    }

    // select variant product and add purchase table
    function salectVariant(e){
        var price_group_id = $('#price_group_id').val();
        if (keyName == 13 || keyName == 1) {
            document.getElementById('search_product').focus();
        }
        
        $('.select_area').hide();
        $('#search_product').val("");
        $('#selectVairantModal').modal('hide');
        var product_id = e.getAttribute('data-p_id');
        var product_name = e.getAttribute('data-p_name');
        var tax_percent = e.getAttribute('data-tax_percent');
        var product_unit = e.getAttribute('data-unit');
        var tax_id = e.getAttribute('data-p_tax_id');
        var tax_type = e.getAttribute('data-tax_type');
        var tax_amount = e.getAttribute('data-tax_amount');
        var variant_id = e.getAttribute('data-v_id');
        var variant_name = e.getAttribute('data-v_name');
        var variant_code = e.getAttribute('data-v_code');
        var variant_cost_inc_tax = e.getAttribute('data-v_cost_inc_tax');
        var variant_price = e.getAttribute('data-v_price');
        var description = e.getAttribute('data-description');

        $.ajax({
            url:"{{ url('sales/check/branch/variant/qty/') }}"+"/"+product_id+"/"+variant_id,
            async:true,
            type:'get',
            dataType: 'json',
            success:function(branchVariantQty){
                if($.isEmptyObject(branchVariantQty.errorMsg)){
                    $('#stock_quantity').val(parseFloat(branchVariantQty).toFixed(2));
                    var variant_ids = document.querySelectorAll('#variant_id');
                    var sameVariant = 0;
                    variant_ids.forEach(function(input){
                        if(input.value != 'noid'){
                            if(input.value == variant_id){
                                sameVariant += 1;
                                var className = input.getAttribute('class');
                                // get closest table row for increasing qty and re calculate product amount
                                var closestTr = $('.'+className).closest('tr');
                                var presentQty = closestTr.find('#quantity').val();
                        
                                var previousQty = closestTr.find('#previous_quantity').val();
                                var limit = closestTr.find('#qty_limit').val()
                                var qty_limit = parseFloat(previousQty) + parseFloat(limit);

                                if(parseFloat(qty_limit)  === parseFloat(presentQty)){
                                    alert('Quantity exceeds stock quantity!');
                                    return;
                                }
                                var updateQty = parseFloat(presentQty) + 1;
                                closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                                
                                //Update Subtotal
                                var unitPrice = closestTr.find('#unit_price').val();
                                var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                calculateTotalAmount();
                                if (keyName == 9) {
                                    closestTr.find('#quantity').focus();
                                    closestTr.find('#quantity').select();
                                    keyName = 1;
                                }
                                return;
                            }
                        }    
                    });

                    if(sameVariant == 0){
                        var price = 0;
                        var __price = price_groups.filter(function (value) {
                            return value.price_group_id == price_group_id && value.product_id == product_id && value.variant_id == variant_id;
                        });

                        if (__price.length != 0) {
                            price = __price[0].price ? __price[0].price : variant_price;
                        } else {
                            price = variant_price;
                        }
                        var tr = '';
                        tr += '<tr>';
                        tr += '<td colspan="2" class="text-start">';
                        tr += '<a href="#" class="text-success" id="edit_product">';
                        tr += '<span class="product_name">'+product_name+'</span>';
                        tr += '<span class="product_variant">'+' -'+variant_name+'- '+'</span>'; 
                        tr += '<span class="product_code">'+'('+variant_code+')'+'</span>';
                        tr += '</a><br/><input type="'+(description == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="IMEI, Serial number or other informations here.">';
                        tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                        tr += '<input value="'+variant_id+'" type="hidden" class="variantId-'+variant_id+'" id="variant_id" name="variant_ids[]">';
                        tr += '<input value="'+tax_type+'" type="hidden" id="tax_type">';
                        tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+tax_percent+'">';
                        tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(tax_amount).toFixed(2)+'">';
                        tr += '<input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                        tr += '<input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">';
                        tr += '<input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                        tr += '<input value="'+variant_cost_inc_tax+'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                        tr += '<input type="hidden" id="previous_quantity" value="0">';
                        tr += '<input type="hidden" id="qty_limit" value="'+branchVariantQty+'">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                        tr += '</td>';
                        tr += '<td class="text">';
                        tr += '<span class="span_unit">'+product_unit+'</span>'; 
                        tr += '<input  name="units[]" type="hidden" id="unit" value="'+product_unit+'">';
                        tr += '</td>';
                        tr += '<td>';

                        tr += '<input name="unit_prices_exc_tax[]" type="hidden" id="unit_price_exc_tax" value="'+parseFloat(price).toFixed(2)+'">';
                        var unitPriceIncTax = parseFloat(price) / 100 * parseFloat(tax_percent) + parseFloat(price);
                        if (tax_type == 2) {
                            var inclusiveTax = 100 + parseFloat(tax_percent);
                            var calcTax = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                            var __tax_amount = parseFloat(price) - parseFloat(calcTax);
                            unitPriceIncTax = parseFloat(price) + parseFloat(__tax_amount);
                        }
                        tr += '<input readonly name="unit_prices[]" type="text" class="form-control form-control-sm text-center" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2)+'">';
                        tr += '</td>';
                        tr += '<td class="text text-center">';
                        tr += '<strong><span class="span_subtotal">'+parseFloat(unitPriceIncTax).toFixed(2)+'</span></strong>'; 
                        tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal">';
                        tr += '</td>';
                        tr += '<td class="text-center">';
                        tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#sale_list').append(tr);
                        calculateTotalAmount();
                        if (keyName == 9) {
                            $("#quantity").select();
                            keyName = 1;
                        }
                    }
                }else{
                    toastr.warning(branchVariantQty.errorMsg);   
                }
            }
        });
    }

    // Quantity increase or dicrease and clculate row amount
    $(document).on('input', '#quantity', function(){
        var qty = $(this).val() ? $(this).val() : 0;
        if (parseFloat(qty) >= 0) {
            var tr = $(this).closest('tr');
            var previousQty = tr.find('#previous_quantity').val();
            var limit = tr.find('#qty_limit').val()
            var qty_limit = parseFloat(previousQty) + parseFloat(limit);
            if(parseInt(qty) > parseInt(qty_limit)){
                alert('Quantity exceeds stock quantity!');
                $(this).val(parseFloat(qty_limit).toFixed(2));
                var unitPrice = tr.find('#unit_price').val();
                var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty_limit);
                tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount();  
                return;
            }
            var unitPrice = tr.find('#unit_price').val();
            var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty);
            tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
            calculateTotalAmount(); 
        }
    });

    // Input order discount and clculate total amount
    $(document).on('input', '#order_discount', function(){
        calculateTotalAmount();
    });

    // Input order discount type and clculate total amount
    $(document).on('change', '#order_discount_type', function(){
        calculateTotalAmount();
    });

    // Input shipment charge and clculate total amount
    $(document).on('input', '#shipment_charge', function(){
        calculateTotalAmount();
    });

    // chane purchase tax and clculate total amount
    $(document).on('change', '#order_tax', function(){
        calculateTotalAmount();
    });

    // Input paying amount and clculate due amount
    $(document).on('input', '#paying_amount', function(){
        var payingAmount = $(this).val() ? $(this).val() : 0;
        var total_purchase_amount = $('#total_payable_amount').val() ? $('#total_payable_amount').val() : 0;
        var calcDueAmount = parseFloat(total_purchase_amount) - parseFloat(payingAmount);
        $('.label_total_due').html(parseFloat(calcDueAmount).toFixed(2));
        $('#total_due').val(parseFloat(calcDueAmount).toFixed(2));
    });

    // Dispose Select area 
    $(document).on('click', '.remove_select_area_btn', function(e){
        e.preventDefault();
        $('.select_area').hide();
    });

    // Remove product form purchase product list (Table) 
    $(document).on('click', '#remove_product_btn',function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
        calculateTotalAmount();
    });

    // Show selling product's update modal
    var tableRowIndex = 0;
    $(document).on('click', '#edit_product', function(e) {
        e.preventDefault();
        var parentTableRow = $(this).closest('tr');
        tableRowIndex = parentTableRow.index();
        var quantity = parentTableRow.find('#quantity').val();
        var product_name = parentTableRow.find('.product_name').html();
        var product_variant = parentTableRow.find('.product_variant').html();
        var product_code = parentTableRow.find('.product_code').html();
        var unit_price_exc_tax = parentTableRow.find('#unit_price_exc_tax').val();
        var unit_tax_percent = parentTableRow.find('#unit_tax_percent').val();
        var unit_tax_amount = parentTableRow.find('#unit_tax_amount').val();
        var unit_tax_type = parentTableRow.find('#tax_type').val();
        var unit_discount_type = parentTableRow.find('#unit_discount_type').val();
        var unit_discount = parentTableRow.find('#unit_discount').val();
        var unit_discount_amount = parentTableRow.find('#unit_discount_amount').val();
        var product_unit = parentTableRow.find('#unit').val();
        // Set modal heading
        var heading = product_name + (product_variant ? product_variant : '') + product_code;
        $('#product_info').html(heading);
        
        $('#e_quantity').val(parseFloat(quantity).toFixed(2));
        $('#e_unit_price').val(parseFloat(unit_price_exc_tax).toFixed(2));
        $('#e_unit_discount_type').val(unit_discount_type);
        $('#e_unit_discount').val(unit_discount);
        $('#e_discount_amount').val(unit_discount_amount);
        $('#e_unit_tax').empty();
        $('#e_unit_tax').append('<option value="0.00">No Tax</option>');
        taxArray.forEach(function (tax) {
            if (tax.tax_percent == unit_tax_percent) {
                $('#e_unit_tax').append('<option SELECTED value="'+tax.tax_percent+'">'+tax.tax_name+'</option>');
            }else{
                $('#e_unit_tax').append('<option value="'+tax.tax_percent+'">'+tax.tax_name+'</option>');
            }
        });
        $('#e_tax_type').val(unit_tax_type);
        $('#e_unit').empty();
        unites.forEach(function (unit) {
            if (unit == product_unit) {
                $('#e_unit').append('<option SELECTED value="'+unit+'">'+unit+'</option>');
            }else{
                $('#e_unit').append('<option value="'+unit+'">'+unit+'</option>');
            }
        });

        $('#editProductModal').modal('show');
    });

    // Calculate unit discount
    $('#e_unit_discount').on('input', function () {
        var discountValue = $(this).val() ? $(this).val() : 0.00;
        if ($('#e_unit_discount_type').val() == 1) {
            $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
        }else{
            var unit_price = $('#e_unit_price').val();
            var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
            $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
        }
    });

    // change unit discount type var productTableRow 
    $('#e_unit_discount_type').on('change', function () {
        var type = $(this).val();
        var discountValue = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
        if (type == 1) {
            $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
        }else {
            var unit_price = $('#e_unit_price').val();
            var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
            $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
        }
    });

    //Update Selling producdt
    $('#update_selling_product').on('submit', function (e) {
        e.preventDefault();
        var inputs = $('.edit_input');
        $('.error').html('');  
        var countErrorField = 0;  
        $.each(inputs, function(key, val){
            var inputId = $(val).attr('id');
            var idValue = $('#'+inputId).val();
            if(idValue == ''){
                countErrorField += 1;
                var fieldName = $('#'+inputId).data('name');
                $('.error_'+inputId).html(fieldName+' is required.');
            }
        });

        if(countErrorField > 0){
            return;
        }

        var e_quantity = $('#e_quantity').val();
        var e_unit_price = $('#e_unit_price').val();
        var e_unit_discount_type = $('#e_unit_discount_type').val() ? $('#e_unit_discount_type').val() : 1;
        var e_unit_discount = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
        var e_unit_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0.00;
        var e_unit_tax_percent = $('#e_unit_tax').val() ? $('#e_unit_tax').val() : 0.00;
        var e_unit_tax_type = $('#e_tax_type').val() ? $('#e_tax_type').val() : 1;
        var e_unit = $('#e_unit').val();

        var productTableRow = $('#sale_list tr:nth-child(' + (tableRowIndex + 1) + ')');
        // calculate unit tax 
        productTableRow.find('.span_unit').html(e_unit);
        productTableRow.find('#unit').val(e_unit);
        productTableRow.find('#unit').val(e_unit);
        productTableRow.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
        productTableRow.find('#unit_price_exc_tax').val(parseFloat(e_unit_price).toFixed(2));
        productTableRow.find('#unit_discount_type').val(e_unit_discount_type);
        productTableRow.find('#unit_discount').val(parseFloat(e_unit_discount).toFixed(2));
        productTableRow.find('#unit_discount_amount').val(parseFloat(e_unit_discount_amount).toFixed(2));

        var calcUnitPriceWithDiscount = parseFloat(e_unit_price) - parseFloat(e_unit_discount_amount);
        var calsUninTaxAmount = parseFloat(calcUnitPriceWithDiscount) / 100 * parseFloat(e_unit_tax_percent);
        if (e_unit_tax_type == 2) {
            var inclusiveTax = 100 + parseFloat(e_unit_tax_percent);
            var calc = parseFloat(calcUnitPriceWithDiscount) / parseFloat(inclusiveTax) * 100;
            calsUninTaxAmount = parseFloat(calcUnitPriceWithDiscount) - parseFloat(calc);
        }
        productTableRow.find('#unit_tax_percent').val(parseFloat(e_unit_tax_percent).toFixed(2));
        productTableRow.find('#tax_type').val(e_unit_tax_type);
        productTableRow.find('#unit_tax_amount').val(parseFloat(calsUninTaxAmount).toFixed(2));

        var calcUnitPriceIncTax = parseFloat(calcUnitPriceWithDiscount) + parseFloat(calsUninTaxAmount);
    
        productTableRow.find('#unit_price').val(parseFloat(calcUnitPriceIncTax).toFixed(2));

        var calcSubtotal = parseFloat(calcUnitPriceIncTax) * parseFloat(e_quantity);
        productTableRow.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
        productTableRow.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        $('#editProductModal').modal('hide');
        calculateTotalAmount();
    });

    // change unit price
    $('#e_unit_price').on('input', function () {
        var unit_price = $(this).val() ? $(this).val() : 0.00;
        var discountValue = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
        if ($('#e_unit_discount_type').val() == 1) {
            $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
        }else{
            var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
            $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
        }
    });

    //Add purchase request by ajax
    $('#edit_sale_form').on('submit', function(e){
        e.preventDefault();
        var totalItem = $('#total_item').val();
        if (parseFloat(totalItem) == 0) {
            toastr.error('Product table is empty.','Some thing want wrong.'); 
            return;
        }
        $('.loading_button').show();
        var url = $(this).attr('action');
        var inputs = $('.add_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');  
            var countErrorField = 0;  
        $.each(inputs, function(key, val){
            var inputId = $(val).attr('id');
            var idValue = $('#'+inputId).val();
            if(idValue == ''){
                countErrorField += 1;
                var fieldName = $('#'+inputId).data('name');
                $('.error_'+inputId).html(fieldName+' is required.');
            }
        });

        if(countErrorField > 0){
            $('.loading_button').hide();
            toastr.error('Please check again all form fields.','Some thing want wrong.'); 
            return;
        }

        $.ajax({
            url:url,
            type:'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(data){
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg,'ERROR'); 
                    $('.loading_button').hide();
                }else{
                    $('.loading_button').hide();
                    toastr.success(data.successMsg); 
                    window.location = "{{route('sales.index2')}}";
                }
            },error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');
                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.'); 
                }else{
                    toastr.error('Server error please contact to the support team.');
                }
            }
        });
    });

    // Automatic remove searching product is found signal 
    setInterval(function(){
        $('#search_product').removeClass('is-invalid');
    }, 500); 

    setInterval(function(){
        $('#search_product').removeClass('is-valid');
    }, 1000);

    // Disable branch field after add the products 
    $('.submit_button').on('click', function () {
        var value = $(this).val();
        $('#action').val(value); 
    });

    // Get editable data by ajax
    function getEditableSale(){
        $.ajax({
            url:"{{route('sales.get.editable.sale', $saleId)}}",
            type:'get',
            dataType: 'json',
            success:function(sale){
                var qty_limits = sale.qty_limits;
                var sale = sale.sale;
                $('#invoice_id').val(sale.invoice_id);
                $('#status').val(sale.status);
                $('#date').val(sale.date);
                $('#pay_term').val(sale.pay_term);
                $('#pay_term_number').val(sale.pay_term_number);
                $('#customer_name').val(sale.customer_id == null ? 'Walk-In-Customer' : sale.customer.name + '('+sale.customer.phone+')');
                $('#order_discount_type').val(sale.order_discount_type);
                $('#order_discount').val(sale.order_discount);
        
                $('#order_discount_amount').val(sale.order_discount_amount);
                $('#order_tax').val(sale.order_tax_percent);
                $('#order_tax_amount').val(sale.order_tax_amount);
                $('#shipment_details').val(sale.shipment_details);
                $('#shipment_address').val(sale.shipment_address);
                $('#shipment_charge').val(sale.shipment_charge);
                $('#shipment_status').val(sale.shipment_status);
                $('#delivered_to').val(sale.delivered_to);
                $('#sale_note').val(sale.sale_note);

                $.each(sale.sale_products, function (key, product) {
                    var tr = '';
                    tr += '<tr>';
                    tr += '<td colspan="2" class="text-start">';
                    tr += '<a href="#" class="text-success" id="edit_product">';
                    tr += '<span class="product_name">'+product.product.name+'</span>';
                    var variant = product.product_variant_id != null ? ' -'+product.variant.variant_name+'- ' : '';
                    tr += '<span class="product_variant">'+variant+'</span>'; 
                    var code = product.product_variant_id != null ? product.variant.variant_code : product.product.product_code;
                    tr += '<span class="product_code">'+'('+code+')'+'</span>';
                    tr += '</a><br/><input type="'+(product.product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="IMEI, Serial number or other informations here." value="'+(product.description ? product.description : '')+'">';
                    tr += '<input value="'+product.product_id+'" type="hidden" class="productId-'+product.product_id+'" id="product_id" name="product_ids[]">';

                    if (product.product_variant_id != null) {
                        tr += '<input value="'+product.product_variant_id+'" type="hidden" class="variantId-'+product.product_variant_id+'" id="variant_id" name="variant_ids[]">'; 
                    }else{
                        tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';  
                    }   

                    tr += '<input type="hidden" id="tax_type" value="'+product.product.tax_type+'">';

                    tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+product.unit_tax_percent+'">';
                    tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+product.unit_tax_amount+'">';
                    tr += '<input value="'+product.unit_discount_type+'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                    tr += '<input value="'+product.unit_discount+'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                    tr += '<input value="'+product.unit_discount_amount+'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                    tr += '<input value="'+product.unit_cost_inc_tax+'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                    tr += '<input type="hidden" id="previous_quantity" value="'+product.quantity+'">';
                    tr += '<input type="hidden" id="qty_limit" value="'+qty_limits[key]+'">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input value="'+product.quantity+'" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                    tr += '</td>';
                    tr += '<td class="text">';
                    tr += '<span class="span_unit">'+product.unit+'</span>'; 
                    tr += '<input  name="units[]" type="hidden" id="unit" value="'+product.unit+'">';
                    tr += '</td>';
                    tr += '<td>';
                    
                    tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+product.unit_price_exc_tax+'" id="unit_price_exc_tax">';
                    tr += '<input readonly name="unit_prices[]" type="text" class="form-control text-center" id="unit_price" value="'+product.unit_price_inc_tax+'">';
                    tr += '</td>';
                    tr += '<td class="text text-center">';
                    tr += '<strong><span class="span_subtotal">'+product.subtotal+'</span></strong>'; 
                    tr += '<input value="'+product.subtotal+'" readonly name="subtotals[]" type="hidden" id="subtotal">';
                    tr += '</td>';
                    tr += '<td class="text-center">';
                    tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                    tr += '</td>';
                    tr += '</tr>';
                    $('#sale_list').append(tr);
                });
                calculateTotalAmount();
            }
        });
    }
    getEditableSale();

    $('#add_product').on('click', function () {
        $.ajax({
            url:"{{ route('sales.add.product.modal.view') }}",
            type:'get',
            success:function(data){
                $('#add_product_body').html(data);
                $('#addProductModal').modal('show');
            }
        });
    });

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
        $('.os_unit_costs_exc_tax').val(parseFloat($(this).val()).toFixed(2));
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
    $(document).on('blur', '#os_unit_cost_exc_tax', function () {
        if ($(this).val() == '') {
            $(this).val(parseFloat(0).toFixed(2));
        } 
    });

    $(document).on('input', '#os_quantity', function () {
        var qty = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        var unit_cost_exc_tax = tr.find('#os_unit_cost_exc_tax').val() ? tr.find('#os_unit_cost_exc_tax').val() : 0;
        var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_exc_tax);
        tr.find('.os_span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        tr.find('#os_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
    });

    $(document).on('input', '#os_unit_cost_exc_tax', function () {
        var unit_cost_exc_tax = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        var qty = tr.find('#os_quantity').val() ? tr.find('#os_quantity').val() : 0;
        var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_exc_tax);
        tr.find('.os_span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        tr.find('#os_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
    });

    // Add product by ajax
    $(document).on('submit', '#add_product_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success('Successfully product is added.');
                $.ajax({
                    url:"{{url('sales/get/recent/product')}}"+"/"+data.id,
                    type:'get',
                    success:function(data){
                        $('.loading_button').hide();
                        $('#addProductModal').modal('hide');
                        if (!$.isEmptyObject(data.errorMsg)) {
                            toastr.error(data.errorMsg);
                        }else{
                            $('#sale_list').prepend(data);
                            calculateTotalAmount();
                        }
                    }
                });
            },
            error: function(err) {
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.', 'Some thing want wrong.');
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_sale_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('change', '#sale_category_id', function () {
        var category_id = $(this).val();
        $.ajax({
            url:"{{url('sales/get/all/sub/category')}}"+"/"+category_id,
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
    
    $(document).keypress(".scanable",function(event){
        if (event.which == '10' || event.which == '13') {
            event.preventDefault();
        }
    });

    $('body').keyup(function(e){
        if (e.keyCode == 13 || e.keyCode == 9){  
            $(".selectProduct").click();
            $('#list').empty();
            keyName = e.keyCode;
        }
    });

    $(document).on('mouseenter', '#list>li>a',function () {
        $('#list>li>a').removeClass('selectProduct');
        $(this).addClass('selectProduct');
    });

    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('datepicker'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });
</script>