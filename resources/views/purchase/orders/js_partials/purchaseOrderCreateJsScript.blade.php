<script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $('.collapse_table').on('click', function () {

        $('.last_p_product_list').toggle(500);
    });

    $('#supplier_id').on('change', function () {

        var pay_term = $(this).find('option:selected').data('pay_term');
        var pay_term_number = $(this).find('option:selected').data('pay_term_number');

        if (pay_term && pay_term_number) {

            $('#pay_term').val(pay_term);
            $('#pay_term_number').val(pay_term_number);
        }else{

            $('#pay_term').val('');
            $('#pay_term_number').val('');
        }
    });

    $('#addSupplier').on('click', function () {
        $.get("{{route('purchases.add.quick.supplier.modal')}}", function(data) {

            $('#add_supplier_modal_body').html(data);
            $('#addSupplierModal').modal('show');

        });
    });

    function calculateTotalAmount(){

        var quantities = document.querySelectorAll('#quantity');
        var linetotals = document.querySelectorAll('#linetotal');
        var total_item = 0;
        var total_qty = 0;

        quantities.forEach(function(qty){
            total_item += 1;
            total_qty += parseFloat(qty.value)
        });

        $('#total_qty').val(parseFloat(total_qty));
        $('#total_item').val(parseFloat(total_item));

        //Update Net Total Amount
        var netTotalAmount = 0;
        linetotals.forEach(function(linetotal){

            netTotalAmount += parseFloat(linetotal.value);
        });

        $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

        var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0;
        var orderDiscountType = $('#order_discount_type').val();

        var orderDiscountAmount = 0;
        if (orderDiscountType == 1) {

            orderDiscountAmount = parseFloat(orderDiscount).toFixed(2);
            $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
        } else {

            orderDiscountAmount = parseFloat(netTotalAmount) / 100 * parseFloat(orderDiscount);
            $('#order_discount_amount').val(parseFloat(orderDiscountAmount).toFixed(2));
        }

        // Update total purchase amount
        var netTotalWithDiscount = parseFloat(netTotalAmount) - orderDiscountAmount;

        var purchaseTaxPercent = $('#purchase_tax_percent').val() ? $('#purchase_tax_percent').val() : 0;
        var purchaseTaxAmount = parseFloat(netTotalWithDiscount) / 100 * parseFloat(purchaseTaxPercent);

        $('#purchase_tax_amount').val(parseFloat(purchaseTaxAmount).toFixed(2));

        var shipment_charge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;

        var calcTotalOrderedAmount = parseFloat(netTotalAmount)
                                    - parseFloat(orderDiscountAmount)
                                    + parseFloat(purchaseTaxAmount)
                                    + parseFloat(shipment_charge);

        $('#total_ordered_amount').val(parseFloat(calcTotalOrderedAmount).toFixed(2));

        var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
        var calcOrderDue = parseFloat(calcTotalOrderedAmount) - parseFloat(payingAmount);
        $('#order_due').val(parseFloat(calcOrderDue).toFixed(2));
    }

    var delay = (function() {

        var timer = 0;
        return function(callback, ms) {

            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $('#search_product').on('input', function(e) {

        $('.variant_list_area').empty();
        $('.select_area').hide();
        var keyWord = $(this).val();
        var __keyWord = keyWord.replaceAll('/', '~');
        delay(function() { searchProduct(__keyWord); }, 200); //sendAjaxical is the name of remote-command
    });

    function searchProduct(keyWord) {

        $('.variant_list_area').empty();
        $('.select_area').hide();

        $.ajax({
            url:"{{ url('purchases/search/product') }}"+"/"+keyWord,
            dataType: 'json',
            success:function(product) {

                if (!$.isEmptyObject(product.errorMsg)) {

                    toastr.error(product.errorMsg);
                    $('#search_product').val('');
                    return;
                }

                if(
                    !$.isEmptyObject(product.product) ||
                    !$.isEmptyObject(product.variant_product) ||
                    !$.isEmptyObject(product.namedProducts)
                ){

                    $('#search_product').addClass('is-valid');
                    if(!$.isEmptyObject(product.product)) {

                        var product = product.product;

                        if(product.product_variants.length == 0) {

                            $('.select_area').hide();

                            var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' : product.name;
                            var unique_id = product.id+'noid';

                            $('#search_product').val(name);
                            $('#e_unique_id').val(unique_id);
                            $('#e_item_name').val(name);
                            $('#e_unit').val(product.unit.name);
                            $('#e_product_id').val(product.id);
                            $('#e_variant_id').val('noid');
                            $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_unit_cost_exc_tax').val(product.product_cost);
                            $('#e_discount').val(parseFloat(0).toFixed(2));
                            $('#e_discount_type').val(1);
                            $('#e_discount_amount').val(parseFloat(0).toFixed(2));
                            $('#e_tax_type').val(parseFloat(product.tax_type).toFixed(2));
                            $('#e_tax_percent').val(product.tax_percent);
                            $('#e_profit_margin').val(parseFloat(product.profit).toFixed(2));
                            $('#e_selling_price').val(parseFloat(product.product_price).toFixed(2));

                            calculateEditOrAddAmount();
                            $('#add_item').html('Add');
                        } else {

                            var li = "";
                            var imgUrl = "{{asset('uploads/product/thumbnail')}}";

                            $.each(product.product_variants, function(key, variant){

                                li += '<li>';
                                li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-v_name="'+variant.variant_name+'" data-has_batch_no_expire_date="'+product.has_batch_no_expire_date+'" data-unit_name="'+product.unit_name+'" data-tax_percent="'+(product.tex_percent != null ? product.tex_percent : '')+'" data-tax_type="'+product.tax_type+'" data-p_code="'+variant.variant_code+'" data-p_cost_exc_tax="'+variant.variant_cost+'" data-p_profit="'+variant.variant_profit+'" data-p_price="'+variant.variant_price+'" href="#"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+ product.name +'</a>';
                                li +='</li>';
                            });

                            $('.variant_list_area').append(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    }else if(!$.isEmptyObject(product.namedProducts)) {

                        if(product.namedProducts.length > 0) {

                            var li = "";
                            var imgUrl = "{{asset('uploads/product/thumbnail')}}";
                            var products = product.namedProducts;

                            $.each(products, function (key, product) {

                                if (product.is_variant == 1) {

                                    li += '<li class="mt-1">';
                                    li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="'+product.id+'" data-v_id="'+product.variant_id+'" data-p_name="'+product.name+'" data-v_name="'+product.variant_name+'" data-has_batch_no_expire_date="'+product.has_batch_no_expire_date+'" data-unit_name="'+product.unit_name+'" data-tax_percent="'+(product.tax_percent != null ? product.tax_percent : '')+'" data-tax_type="'+product.tax_type+'" data-v_code="'+product.variant_code+'" data-p_cost_exc_tax="'+product.variant_cost+'" data-p_profit="'+product.variant_profit+'" data-p_price="'+product.variant_price+'" href="#"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+product.variant_name+'</a>';
                                    li +='</li>';
                                } else {

                                    li += '<li class="mt-1">';
                                    li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-p_id="'+product.id+'" data-p_name="'+product.name+'" data-has_batch_no_expire_date="'+product.has_batch_no_expire_date+'" data-unit_name="'+product.unit_name+'" data-tax_percent="'+(product.tax_percent != null ? product.tax_percent : '')+'" data-tax_type="'+product.tax_type+'" data-p_code="'+product.product_code+'" data-p_cost_exc_tax="'+product.product_cost+'" data-p_profit="'+product.profit+'" data-p_price="'+product.product_price+'" href="#"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> ' + product.name + '</a>';
                                    li +='</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    } else if(!$.isEmptyObject(product.variant_product)) {

                        $('.select_area').hide();

                        var variant = product.variant_product;

                        var name = variant.product.name.length > 35 ? product.name.substring(0, 35) + '...' : variant.product.name;

                        var unique_id = variant.product.id+variant.id;

                        $('#e_unique_id').val(unique_id);
                        $('#search_product').val(name +' - '+variant.variant_name);
                        $('#e_item_name').val(name+' - '+variant.variant_name);
                        $('#e_unit').val(variant.product.unit.name);
                        $('#e_product_id').val(variant.product.id);
                        $('#e_variant_id').val(variant.id);
                        $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_unit_cost_exc_tax').val(variant.variant_cost);
                        $('#e_discount_type').val(1);
                        $('#e_showing_discount').val(parseFloat(0).toFixed(2));
                        $('#e_discount_amount').val(parseFloat(0).toFixed(2));
                        $('#e_tax_type').val(parseFloat(variant.product.tax_type).toFixed(2));
                        $('#e_tax_percent').val(variant.product.tax_percent);
                        $('#e_profit_margin').val(parseFloat(variant.variant_profit).toFixed(2));
                        $('#e_selling_price').val(parseFloat(variant.variant_price).toFixed(2));

                        calculateEditOrAddAmount();
                        $('#add_item').html('Add');
                    }
                } else {

                    $('#search_product').addClass('is-invalid');
                }
            }
        });
    }

    // select single product and add purchase table
    function selectProduct(e) {

        $('.select_area').hide();
        $('#search_product').val('');

        var product_id = e.getAttribute('data-p_id');
        var variant_id = e.getAttribute('data-v_id');
        var product_name = e.getAttribute('data-p_name');
        var variant_name = e.getAttribute('data-v_name');
        var unit_name = e.getAttribute('data-unit_name');
        var tax_percent = e.getAttribute('data-tax_percent');
        var tax_type = e.getAttribute('data-tax_type');
        var product_code = e.getAttribute('data-p_code');
        var product_cost = e.getAttribute('data-p_cost_exc_tax');
        var product_profit = e.getAttribute('data-p_profit');
        var product_price = e.getAttribute('data-p_price');

        var unique_id = product_id+variant_id;

        $('#e_unique_id').val(unique_id);
        $('#search_product').val(product_name + (variant_name ? ' - '+variant_name : ''));
        $('#e_item_name').val(product_name+(variant_name ? ' - '+variant_name : '' ));
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id ? variant_id : 'noid');
        $('#e_unit').val(unit_name);
        $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
        $('#e_unit_cost_exc_tax').val(parseFloat(product_cost).toFixed(2));
        $('#e_discount').val(parseFloat(0).toFixed(2));
        $('#e_discount_type').val(1);
        $('#e_discount_amount').val(parseFloat(0).toFixed(2));
        $('#e_tax_type').val(tax_type);
        $('#e_tax_percent').val(tax_percent);
        $('#e_profit_margin').val(parseFloat(product_profit).toFixed(2));
        $('#e_selling_price').val(parseFloat(product_price).toFixed(2));
        calculateEditOrAddAmount();

        $('#add_item').html('Add');
    }

    function calculateEditOrAddAmount() {

        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_unit_cost_exc_tax = $('#e_unit_cost_exc_tax').val() ? $('#e_unit_cost_exc_tax').val() : 0;
        var e_tax_percent = $('#e_tax_percent').val() ? $('#e_tax_percent').val() : 0;
        var e_tax_type = $('#e_tax_type').val();
        var e_discount_type = $('#e_discount_type').val();
        var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;

        var discount_amount = 0;
        if (e_discount_type == 1) {

            discount_amount = parseFloat(e_discount);
        } else {

            discount_amount = (parseFloat(e_unit_cost_exc_tax) / 100) * parseFloat(e_discount);
        }

        var costWithDiscount = parseFloat(e_unit_cost_exc_tax) - parseFloat(discount_amount);
        $('#e_unit_cost_with_discount').val(parseFloat(parseFloat(costWithDiscount)).toFixed(2));

        var subtotal = parseFloat(costWithDiscount) * parseFloat(e_quantity);
        $('#e_subtotal').val(parseFloat(parseFloat(subtotal)).toFixed(2));

        var taxAmount = parseFloat(costWithDiscount) / 100 * parseFloat(e_tax_percent);
        var unitCostIncTax = parseFloat(costWithDiscount) + parseFloat(taxAmount);
        if (e_tax_type == 2) {

            var inclusiveTax = 100 + parseFloat(e_tax_percent);
            var calcTax = parseFloat(costWithDiscount) / parseFloat(inclusiveTax) * 100;
            taxAmount = parseFloat(costWithDiscount) - parseFloat(calcTax);
            unitCostIncTax = parseFloat(costWithDiscount) + parseFloat(taxAmount);
        }

        $('#e_tax_amount').val(parseFloat(parseFloat(taxAmount)).toFixed(2));
        $('#e_discount_amount').val(parseFloat(parseFloat(discount_amount)).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(parseFloat(unitCostIncTax)).toFixed(2));

        var linetotal = parseFloat(unitCostIncTax) * parseFloat(e_quantity);
        $('#e_linetotal').val(parseFloat(linetotal).toFixed(2));

        // Update selling price
        var profit = $('#e_profit_margin').val() ? $('#e_profit_margin').val() : 0;
        var sellingPrice = parseFloat(costWithDiscount) / 100 * parseFloat(profit) + parseFloat(costWithDiscount);
        $('#e_selling_price').val(parseFloat(sellingPrice).toFixed(2));
    }

    $('#add_item').on('click', function (e) {
        e.preventDefault();

        var e_unique_id = $('#e_unique_id').val();
        var e_has_batch_no_expire_date = $('#e_has_batch_no_expire_date').val();
        var e_item_name = $('#e_item_name').val();
        var e_product_id = $('#e_product_id').val();
        var e_variant_id = $('#e_variant_id').val();
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_unit = $('#e_unit').val();
        var e_unit_cost_exc_tax = $('#e_unit_cost_exc_tax').val() ? $('#e_unit_cost_exc_tax').val() : 0;
        var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;
        var e_discount_type = $('#e_discount_type').val();
        var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
        var e_tax_type = $('#e_tax_type').val();
        var e_tax_percent = $('#e_tax_percent').val() ? $('#e_tax_percent').val() : 0;
        var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
        var e_unit_cost_with_discount = $('#e_unit_cost_with_discount').val() ? $('#e_unit_cost_with_discount').val() : 0;
        var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
        var e_linetotal = $('#e_linetotal').val() ? $('#e_linetotal').val() : 0;
        var e_profit_margin = $('#e_profit_margin').val() ? $('#e_profit_margin').val() : 0;
        var e_selling_price = $('#e_selling_price').val() ? $('#e_selling_price').val() : 0;
        var e_description = $('#e_description').val();

        if (e_product_id == '') {

            toastr.error('Please select a item.');
            return;
        }

        if (e_quantity == '') {

            toastr.error('Quantity field must not be empty.');
            return;
        }

        var uniqueId = e_product_id+e_variant_id;

        var uniqueIdValue = $('#'+e_product_id+e_variant_id).val();

        if (uniqueIdValue == undefined) {

            var tr = '';
            tr += '<tr id="select_item">';
            tr += '<td>';
            tr += '<span id="span_item_name">'+e_item_name+'</span>';
            tr += '<input type="hidden" id="item_name" value="'+e_item_name+'">';
            tr += '<input type="hidden" name="descriptions[]" id="description" value="'+e_description+'">';
            tr += '<input type="hidden" name="product_ids[]" id="product_id" value="'+e_product_id+'">';
            tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="'+e_variant_id+'">';
            tr += '<input type="hidden" id="'+uniqueId+'" value="'+uniqueId+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_quantity_unit" class="fw-bold">'+parseFloat(e_quantity).toFixed(2)+'/'+e_unit+'</span>';
            tr += '<input type="hidden" name="quantities[]" id="quantity" value="'+e_quantity+'">';
            tr += '<input type="hidden" name="units[]" step="any" id="unit" value="'+e_unit+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_unit_cost_exc_tax" class="fw-bold">'+parseFloat(e_unit_cost_exc_tax).toFixed(2)+'</span>';
            tr += '<input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="'+e_unit_cost_exc_tax+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_discount_amount" class="fw-bold">'+parseFloat(e_discount_amount).toFixed(2)+'</span>';
            tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="'+e_discount_type+'">';
            tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="'+parseFloat(e_discount).toFixed(2)+'">';
            tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="'+parseFloat(e_discount_amount).toFixed(2)+'">';
            tr += '<input type="hidden" value="'+parseFloat(e_subtotal).toFixed(2)+'" name="subtotals[]" id="subtotal">';
            tr += '<input type="hidden" name="unit_costs_with_discount[]" id="unit_cost_with_discount" value="'+parseFloat(e_unit_cost_with_discount).toFixed(2)+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_tax_percent" class="fw-bold">'+e_tax_percent+'%'+'</span>';
            tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="'+e_tax_type+'">';
            tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="'+e_tax_percent+'">';
            tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="'+parseFloat(e_tax_amount).toFixed(2)+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_unit_cost_inc_tax" class="fw-bold">'+parseFloat(e_unit_cost_inc_tax).toFixed(2)+'</span>';
            tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="'+parseFloat(e_unit_cost_inc_tax).toFixed(2)+'">';
            tr += '<input type="hidden" name="net_unit_costs[]" id="net_unit_cost" value="'+parseFloat(e_unit_cost_inc_tax).toFixed(2)+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_linetotal" class="fw-bold">'+parseFloat(e_linetotal).toFixed(2)+'</span>';
            tr += '<input type="hidden" name="linetotals[]" value="'+parseFloat(e_linetotal).toFixed(2)+'" id="linetotal">';
            tr += '</td>';

            @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
                tr += '<td>';
                tr += '<span id="span_profit" class="fw-bold">'+parseFloat(e_profit_margin).toFixed(2)+'</span>';
                tr += '<input type="hidden" name="profits[]" id="profit" value="'+parseFloat(e_profit_margin).toFixed(2)+'">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span id="span_showing_selling_price" class="fw-bold">'+parseFloat(e_selling_price).toFixed(2)+'</span>';
                tr += '<input type="hidden" name="selling_prices[]" id="selling_price" value="'+parseFloat(e_selling_price).toFixed(2)+'">';
                tr += '</td>';
            @endif

            tr += '<td class="text-start">';
            tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
            tr += '</td>';
            tr += '</tr>';

            $('#purchase_order_product_list').prepend(tr);
            clearEditItemFileds();
            calculateTotalAmount();
        } else {

            var tr = $('#'+uniqueId).closest('tr');
            tr.find('#item_name').val(e_item_name);
            tr.find('#span_item_name').html(e_item_name);
            tr.find('#product_id').val(e_product_id);
            tr.find('#variant_id').val(e_variant_id);
            tr.find('#description').val(e_description);
            tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
            tr.find('#span_quantity_unit').html(parseFloat(e_quantity).toFixed(2)+'/'+e_unit);
            tr.find('#unit_cost_exc_tax').val(parseFloat(e_unit_cost_exc_tax).toFixed(2));
            tr.find('#span_unit_cost_exc_tax').html(parseFloat(e_unit_cost_exc_tax).toFixed(2));
            tr.find('#unit_discount_type').val(e_discount_type);
            tr.find('#unit_discount').val(parseFloat(e_discount).toFixed(2));
            tr.find('#unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
            tr.find('#span_discount_amount').html(parseFloat(e_discount_amount).toFixed(2));
            tr.find('#unit_cost_with_discount').val(parseFloat(e_unit_cost_with_discount).toFixed(2));
            tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
            tr.find('#tax_type').val(e_tax_type);
            tr.find('#span_tax_percent').html(parseFloat(e_tax_percent).toFixed(2)+'%');
            tr.find('#unit_tax_percent').val(parseFloat(e_tax_percent).toFixed(2));
            tr.find('#unit_tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
            tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
            tr.find('#net_unit_cost').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
            tr.find('#linetotal').val(parseFloat(e_linetotal).toFixed(2));
            tr.find('#span_linetotal').html(parseFloat(e_linetotal).toFixed(2));
            tr.find('#profit').val(parseFloat(e_profit_margin).toFixed(2));
            tr.find('#span_profit').html(parseFloat(e_profit_margin).toFixed(2));
            tr.find('#selling_price').val(parseFloat(e_selling_price).toFixed(2));
            tr.find('#span_selling_price').html(parseFloat(e_selling_price).toFixed(2));
            clearEditItemFileds();
            calculateTotalAmount();
        }
    });

    $(document).on('click', '#select_item',function (e) {

        var tr = $(this);
        var item_name = tr.find('#item_name').val();
        var description = tr.find('#description').val();
        var product_id = tr.find('#product_id').val();
        var variant_id = tr.find('#variant_id').val();
        var quantity = tr.find('#quantity').val();
        var unit = tr.find('#unit').val();
        var unit_cost_exc_tax = tr.find('#unit_cost_exc_tax').val();
        var unit_discount_type = tr.find('#unit_discount_type').val();
        var unit_discount = tr.find('#unit_discount').val();
        var unit_discount_amount = tr.find('#unit_discount_amount').val();
        var unit_cost_with_discount = tr.find('#unit_cost_with_discount').val();
        var subtotal = tr.find('#subtotal').val();
        var tax_percent = tr.find('#unit_tax_percent').val();
        var tax_type = tr.find('#tax_type').val();
        var unit_tax_amount = tr.find('#unit_tax_amount').val();
        var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
        var linetotal = tr.find('#linetotal').val();
        var profit = tr.find('#profit').val();
        var selling_price = tr.find('#selling_price').val();

        $('#search_product').val(item_name);
        $('#e_item_name').val(item_name);
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id);
        $('#e_unit').val(unit);
        $('#e_quantity').val(parseFloat(quantity).toFixed(2)).focus().select();
        $('#e_unit_cost_exc_tax').val(parseFloat(unit_cost_exc_tax).toFixed(2));
        $('#e_discount_type').val(unit_discount_type);
        $('#e_discount').val(parseFloat(unit_discount).toFixed(2));
        $('#e_discount_amount').val(parseFloat(unit_discount_amount).toFixed(2));
        $('#e_tax_percent').val(tax_percent);
        $('#e_tax_type').val(tax_type);
        $('#e_tax_amount').val(parseFloat(unit_tax_amount).toFixed(2));
        $('#e_unit_cost_with_discount').val(parseFloat(unit_cost_with_discount).toFixed(2));
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
        $('#e_linetotal').val(parseFloat(linetotal).toFixed(2));
        $('#e_profit_margin').val(parseFloat(profit).toFixed(2));
        $('#e_selling_price').val(parseFloat(selling_price).toFixed(2));
        $('#e_description').val(description);
        $('#e_quantity').focus().select();
        $('#add_item').html('Edit');
    });

    function clearEditItemFileds() {

        $('#search_product').val('').focus();
        $('#e_unique_id').val('');
        $('#e_item_name').val('');
        $('#e_product_id').val('');
        $('#e_variant_id').val('');
        $('#e_quantity').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_exc_tax').val(parseFloat(0).toFixed(2));
        $('#e_discount').val(parseFloat(0).toFixed(2));
        $('#e_discount_type').val(1);
        $('#e_discount_amount').val(parseFloat(0).toFixed(2));
        $('#e_tax_percent').val('');
        $('#e_tax_type').val(1);
        $('#e_tax_amount').val(0);
        $('#e_unit_cost_with_discount').val(parseFloat(0).toFixed(2));
        $('#e_subtotal').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
        $('#e_linetotal').val(parseFloat(0).toFixed(2));
        $('#e_profit_margin').val(parseFloat(0).toFixed(2));
        $('#e_selling_price').val(parseFloat(0).toFixed(2));
        $('#e_description').val('');
        $('#add_item').html('Add');
    }

    // Quantity increase or dicrease and clculate row amount
    $(document).on('input keypress', '#e_quantity', function(e) {

        calculateEditOrAddAmount();

        if(e.which == 13) {

            if ($(this).val() != '') {

                $('#e_unit').focus();
            }
        }
    });

    $('#e_unit').on('change keypress click', function (e) {

        if(e.which == 0) {

            $('#e_unit_cost_exc_tax').focus().select();
        }
    });

    // Change tax percent and clculate row amount
    $(document).on('input keypress', '#e_unit_cost_exc_tax', function(e) {

        calculateEditOrAddAmount();

        if(e.which == 13) {

            if ($(this).val() != '') {

                $('#e_discount').focus().select();
            }
        }
    });

    // Input discount and clculate row amount
    $(document).on('input keypress', '#e_discount', function(e) {

        calculateEditOrAddAmount();

        if(e.which == 13) {

            if ($(this).val() != '' && $(this).val() > 0) {

                $('#e_discount_type').focus().select();
            }else{

                $('#e_tax_percent').focus();
            }
        }
    });

    // Input discount and clculate row amount
    $(document).on('change keypress click', '#e_discount_type', function(e) {

        calculateEditOrAddAmount();

        if(e.which == 0) {

            $('#e_tax_percent').focus();
        }
    });

    // Change tax percent and clculate row amount
    $('#e_tax_percent').on('change keypress click', function (e) {

        calculateEditOrAddAmount();

        var e_has_batch_no_expire_date = $('#e_has_batch_no_expire_date').val();

        if(e.which == 0) {

            if ($(this).val() != '') {

                $('#e_tax_type').focus();
            }else {

                $('#e_description').focus().select();
            }
        }
    });

    // Change tax percent and clculate row amount
    $(document).on('change keypress click', '#e_tax_type', function(e){

        calculateEditOrAddAmount();
        if(e.which == 0) {

            $('#e_description').focus().select();
        }
    });

    $('#e_description').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        var xMargin = $('#e_profit_margin').val();
        if(e.which == 13) {

            if (xMargin != undefined) {

                $('#e_profit_margin').focus().select();
            } else {

                $('#add_item').focus();
            }
        }
    });

    // Input profit margin and clculate row amount
    $(document).on('input keypress', '#e_profit_margin', function(e) {

        calculateEditOrAddAmount();
        if(e.which == 13) {

            $('#e_selling_price').focus().select();
        }
    });

    $(document).on('input keypress', '#e_selling_price',function(e) {

        var selling_price = $(this).val() ? $(this).val() : 0;
        var unitCostWithDiscount = $('#e_unit_cost_with_discount').val() ? $('#e_unit_cost_with_discount').val() : 0;
        var profitAmount = parseFloat(selling_price) - parseFloat(unitCostWithDiscount);
        var __cost = parseFloat(unitCostWithDiscount) > 0 ? parseFloat(unitCostWithDiscount) : parseFloat(profitAmount);
        var xMargin = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __xMargin = xMargin ? xMargin : 0;
        $('#e_profit_margin').val(parseFloat(__xMargin).toFixed(2));

        if(e.which == 13) {

            $('#add_item').focus();
        }
    });

    $(document).on('blur', '#paying_amount', function(){

        if ($(this).val() == '') {

            $(this).val(parseFloat(0).toFixed(2));
        }
    });

    // // Input order discount and clculate total amount
    $(document).on('input', '#order_discount', function() {

        calculateTotalAmount();
    });

    // // Input order discount type and clculate total amount
    $(document).on('change', '#order_discount_type', function() {

        calculateTotalAmount();
    });

    // // Input shipment charge and clculate total amount
    $(document).on('input', '#shipment_charge', function(){

        calculateTotalAmount();
    });

    // // chane purchase tax and clculate total amount
    $(document).on('change', '#order_tax_percent', function(){
        calculateTotalAmount();
    });

    // Input paying amount and clculate due amount
    $(document).on('input', '#paying_amount', function(){
        calculateTotalAmount();
    });

    // Remove product form purchase product list (Table)
    $(document).on('click', '#remove_product_btn',function(e){

        e.preventDefault();
        $(this).closest('tr').remove();
        calculateTotalAmount();

        setTimeout(function () {

            clearEditItemFileds();
        }, 5);
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.submit_button',function () {

        var value = $(this).val();
        $('#action').val(value);

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }else {

            $(this).prop('type', 'button');
        }
    });

    document.onkeyup = function () {
        var e = e || window.event; // for IE to cover IEs window event-object

        if(e.ctrlKey && e.which == 13) {

            $('#save_and_print').click();
            return false;
        }else if (e.shiftKey && e.which == 13) {

            $('#save').click();
            return false;
        }else if (e.which == 27) {

            $('.select_area').hide();
            $('#list').empty();
            return false;
        }
    }

    //Add purchase request by ajax
    $('#add_purchase_order_form').on('submit', function(e){
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function(){
                isAjaxIn = true;
            },
            url:url,
            type:'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(data){

                isAjaxIn = true;
                isAllowSubmit = true;

                $('.error').html('');
                $('.loading_button').hide();
                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg,'ERROR');
                }else if (data.successMsg) {

                    toastr.success(data.successMsg);
                    $('#add_purchase_order_form')[0].reset();
                    $('#purchase_order_product_list').empty();

                    $("#supplier_id").select2("destroy");
                    $("#supplier_id").select2();
                }else{

                    toastr.success('Successfully Purchase Order Created.');
                    $('#add_purchase_order_form')[0].reset();
                    $('#purchase_order_product_list').empty();

                    $("#supplier_id").select2("destroy");
                    $("#supplier_id").select2();

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{asset('assets/css/print/purchase.print.css')}}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
                }
            },error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if(err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }

                toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    setInterval(function() {
        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function(){
        $('#search_product').removeClass('is-valid');
    }, 1000);

    // Show add product modal with data
    $('#add_product').on('click', function () {

        $.ajax({
            url:"{{route('purchases.add.product.modal.view')}}",
            type:'get',
            success:function(data){

                $('#addQuickProductModal').html(data);
                $('#addQuickProductModal').modal('show');
            }
        });
    });

    $(document).on('submit', '#add_quick_product_form', function(e) {
        e.preventDefault();

        $('.quick_product_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        isAjaxIn = false;
        isAllowSubmit = false;

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.quick_product_loading_button').hide();
                $('#addQuickProductModal').modal('hide');
                toastr.success('Successfully product is added.');

                var product = data;
                var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' : product.name;
                var taxPercent = product.tax != null ? product.tax.tax_percent : '';

                $('#search_product').val(name);
                $('#e_item_name').val(name);
                $('#e_product_id').val(product.id);
                $('#e_variant_id').val('noid');
                $('#e_unit').val(product.unit.name);
                $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                $('#e_unit_cost_exc_tax').val(parseFloat(product.product_cost).toFixed(2));
                $('#e_discount').val(parseFloat(0).toFixed(2));
                $('#e_discount_type').val(1);
                $('#e_discount_amount').val(parseFloat(0).toFixed(2));
                $('#e_tax_percent').val(taxPercent)
                $('#e_tax_type').val(product.tax_type);
                $('#e_profit_margin').val(parseFloat(product.profit).toFixed(2));
                $('#e_selling_price').val(parseFloat(product.product_price).toFixed(2));
                $('#e_description').val('');

                calculateEditOrAddAmount();
                $('#add_item').html('Add');
            },error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.quick_product_loading_button').hide();

                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }else if (err.status == 500) {

                    toastr.error('Server Error. Please contact to the support team.');
                    return;
                }else if (err.status == 403) {

                    toastr.error('Assess denied.');
                    return;
                }

                toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_quick_product_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    $('body').keyup(function(e) {

        if (e.keyCode == 13 || e.keyCode == 9){

            $(".selectProduct").click();
            $('#list').empty();
            keyName = e.keyCode;
        }
    });

    var dateFormat = "{{ $generalSettings['business__date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('date'),
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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('delivery_date'),
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

    $('#payment_method_id').on('change', function () {

        var account_id = $(this).find('option:selected').data('account_id');
        setMethodAccount(account_id);
    });

    function setMethodAccount(account_id) {

        if (account_id) {

            $('#account_id').val(account_id);
        }else if(account_id === ''){

            $('#account_id option:first-child').prop("selected", true);
        }
    }

    setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));

    document.onkeyup = function () {
        var e = e || window.event; // for IE to cover IEs window event-object

        if(e.ctrlKey && e.which == 13) {

            $('#save_and_print').click();
            return false;
        }else if (e.shiftKey && e.which == 13) {

            $('#save').click();
            return false;
        }
    }
</script>