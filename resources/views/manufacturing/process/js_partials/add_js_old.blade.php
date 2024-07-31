<script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script>
<script>
    $('#search_product').on('input', function(e) {

        $('.variant_list_area').empty();
        $('.select_area').hide();
        var product_code = $(this).val();
        delay(function() {
            searchProduct(product_code);
        }, 200); //sendAjaxical is the name of remote-command
    });

    var delay = (function() {

        var timer = 0;
        return function(callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    function searchProduct(product_code) {

        $('.variant_list_area').empty();
        $('.select_area').hide();
        $.ajax({
            url: "{{ url('purchases/search/product') }}" + "/" + product_code,
            dataType: 'json',
            success: function(product) {

                if (!$.isEmptyObject(product.errorMsg)) {

                    toastr.error(product.errorMsg);
                    $('#search_product').val('');
                    return;
                }

                if (!$.isEmptyObject(product.product) || !$.isEmptyObject(product.variant_product) || !$.isEmptyObject(product.namedProducts)) {

                    $('#search_product').addClass('is-valid');

                    if (!$.isEmptyObject(product.product)) {

                        var product = product.product;
                        if (product.product_variants.length == 0) {

                            $('.select_area').hide();
                            $('#search_product').val('');
                            product_ids = document.querySelectorAll('#product_id');
                            var sameProduct = 0;
                            product_ids.forEach(function(input) {

                                if (input.value == product.id) {

                                    sameProduct += 1;
                                    var className = input.getAttribute('class');
                                    var closestTr = $('.' + className).closest('tr');
                                    // update same product qty
                                    var presentQty = closestTr.find('#final_quantity').val();
                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#final_quantity').val(updateQty);
                                    var unitCostIncTax = closestTr.find('#unit_cost_inc_tax').val();
                                    // update subtotal
                                    var totalCost = parseFloat(unitCostIncTax) * parseFloat(updateQty);
                                    closestTr.find('#subtotal').val(parseFloat(totalCost).toFixed(2));
                                    closestTr.find('#span_subtotal').html(parseFloat(totalCost).toFixed(2));
                                    __calculateTotalAmount();
                                    return;
                                }
                            });

                            if (sameProduct == 0) {

                                var tr = '';
                                tr += '<tr class="text-start">';
                                tr += '<td>';
                                tr += '<span class="product_name">' + product.name + '</span><br>';
                                tr += '<span class="product_variant"></span>';
                                tr += '<input value="' + product.id + '" type="hidden" class="productId-' + product.id + '" id="product_id" name="product_ids[]">';
                                tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="1" required name="final_quantities[]" type="number" step="any" class="form-control text-center" id="final_quantity">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<select name="unit_ids[]" id="unit_id" class="form-control">';
                                unites.forEach(function(unit) {

                                    if (product.unit.id == unit.id) {

                                        tr += '<option SELECTED value="' + unit.id + '">' + unit.name + '</option>';
                                    } else {

                                        tr += '<option value="' + unit.id + '">' + unit.name + '</option>';
                                    }
                                })
                                tr += '</select>';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input readonly value="' + product.product_cost_with_tax + '" type="text" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" class="form-control text-center">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input readonly value="' + product.product_cost_with_tax + '" type="text" name="subtotals[]" id="subtotal" class="form-control text-center">';
                                tr += '</td>';

                                tr += '<td class="text-start">';
                                tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
                                tr += '</td>';

                                tr += '</tr>';
                                $('#ingredient_list').prepend(tr);
                                __calculateTotalAmount();
                            }
                        } else {

                            var li = "";
                            $.each(product.product_variants, function(key, variant) {
                                li += '<li>';
                                li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="' + product.id + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '"  data-unit="' + product.unit.id + '" data-v_code="' + variant.variant_code + '" data-v_cost="' + variant.variant_cost + '" data-v_cost_with_tax="' + variant.variant_cost_with_tax + '"  data-v_name="' + variant.variant_name + '" href="#">' + product.name + ' - ' + variant.variant_name + ' (' + variant.variant_code + ')' + ' - Unit Cost: ' + variant.variant_cost_with_tax + '</a>';
                                li += '</li>';
                            });
                            $('.variant_list_area').append(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    } else if (!$.isEmptyObject(product.namedProducts)) {

                        if (product.namedProducts.length > 0) {

                            var li = "";
                            var products = product.namedProducts;

                            $.each(products, function(key, product) {

                                if (product.is_variant == 1) {

                                    li += '<li class="mt-1">';
                                    li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="' + product.id + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '"  data-unit="' + product.unit_id + '" data-v_code="' + product.variant_code + '" data-v_cost="' + product.variant_cost + '" data-v_cost_with_tax="' + product.variant_cost_with_tax + '" data-v_name="' + product.variant_name + '" href="#">' + product.name + ' - ' + product.variant_name + ' (' + product.variant_code + ')' + ' - Unit Cost: ' + product.variant_cost_with_tax + '</a>';
                                    li += '</li>';
                                } else {

                                    li += '<li class="mt-1">';
                                    li += '<a class="select_single_product" onclick="singleProduct(this); return false;" data-p_id="' + product.id + '" data-p_name="' + product.name + '" data-p_tax_id="' + product.tax_id + '" data-unit="' + product.unit_id + '" data-p_code="' + product.product_code + '" data-p_cost_with_tax="' + product.product_cost_with_tax + '" data-p_name="' + product.name + '" href="#">' + product.name + ' (' + product.product_code + ')' + ' - Unit Cost: ' + product.product_cost_with_tax + '</a>';
                                    li += '</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    } else if (!$.isEmptyObject(product.variant_product)) {

                        $('.select_area').hide();
                        $('#search_product').val('');

                        var variant_product = product.variant_product;
                        var variant_ids = document.querySelectorAll('#variant_id');
                        var sameVariant = 0;

                        variant_ids.forEach(function(input) {

                            if (input.value != 'noid') {

                                if (input.value == variant_product.id) {

                                    sameVariant += 1;
                                    var className = input.getAttribute('class');
                                    var closestTr = $('.' + className).closest('tr');
                                    // update same product qty
                                    var presentQty = closestTr.find('#final_quantity').val();
                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#final_quantity').val(updateQty);
                                    var unitCostIncTax = closestTr.find('#unit_cost_inc_tax').val();
                                    // update subtotal
                                    var totalCost = parseFloat(unitCostIncTax) * parseFloat(updateQty);
                                    closestTr.find('#subtotal').val(parseFloat(totalCost).toFixed(2));
                                    __calculateTotalAmount();
                                    return;
                                }
                            }
                        });

                        if (sameVariant == 0) {

                            var tr = '';
                            tr += '<tr class="text-center">';
                            tr += '<td>';
                            tr += '<span class="product_name">' + variant_product.product.name + '</span>';
                            tr += '<span class="product_variant">(' + variant_product.variant_name + ')</span>';
                            tr += '<input value="' + variant_product.product.id + '" type="hidden" class="productId-' + variant_product.product.id + '" id="product_id" name="product_ids[]">';
                            tr += '<input value="' + variant_product.id + '" type="hidden" class="variantId-' + variant_product.id + '" id="variant_id" name="variant_ids[]">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input value="1" required name="final_quantities[]" type="number" step="any" class="form-control text-start" id="final_quantity">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<select name="unit_ids[]" id="unit_id" class="form-control">';

                            unites.forEach(function(unit) {

                                if (product.unit.id == unit.id) {

                                    tr += '<option SELECTED value="' + unit.id + '">' + unit.name + '</option>';
                                } else {

                                    tr += '<option value="' + unit.id + '">' + unit.name + '</option>';
                                }
                            });

                            tr += '</select>';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input readonly value="' + variant_product.variant_cost_with_tax + '" type="text" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" class="form-control text-start">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input readonly value="' + variant_product.variant_cost_with_tax + '" type="text" name="subtotals[]" id="subtotal" class="form-control text-start">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash"></span></a>';
                            tr += '</td>';

                            tr += '</tr>';
                            $('#purchase_list').prepend(tr);
                            __calculateTotalAmount();
                        }
                    }
                } else {

                    $('#search_product').addClass('is-invalid');
                }
            }
        });
    }

    // select single product and add purchase table
    var keyName = 1;

    function singleProduct(e) {

        if (keyName == 13 || keyName == 1) {

            document.getElementById('search_product').focus();
        }

        $('.select_area').hide();
        $('#search_product').val('');

        var productId = e.getAttribute('data-p_id');
        var productName = e.getAttribute('data-p_name');
        var productUnit = e.getAttribute('data-unit');
        var productCode = e.getAttribute('data-p_code');
        var productCostIncTax = e.getAttribute('data-p_cost_with_tax');
        product_ids = document.querySelectorAll('#product_id');
        var sameProduct = 0;
        product_ids.forEach(function(input) {

            if (input.value == productId) {

                sameProduct += 1;
                var className = input.getAttribute('class');
                var closestTr = $('.' + className).closest('tr');
                // update same product qty
                var presentQty = closestTr.find('#final_quantity').val();
                var updateQty = parseFloat(presentQty) + 1;
                closestTr.find('#final_quantity').val(updateQty);
                var unitCostIncTax = closestTr.find('#unit_cost_inc_tax').val();
                // update subtotal
                var totalCost = parseFloat(unitCostIncTax) * parseFloat(updateQty);
                closestTr.find('#subtotal').val(parseFloat(totalCost).toFixed(2));
                __calculateTotalAmount();

                if (keyName == 9) {

                    closestTr.find('#final_quantity').focus();
                    closestTr.find('#final_quantity').select();
                    keyName = 1;
                }
                return;
            }
        });

        if (sameProduct == 0) {

            var tr = '';
            tr += '<tr class="text-start">';
            tr += '<td>';
            tr += '<span class="product_name">' + productName + '</span><br>';
            tr += '<span class="product_variant"></span>';
            tr += '<input value="' + productId + '" type="hidden" class="productId-' + productId + '" id="product_id" name="product_ids[]">';
            tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input value="1" required name="final_quantities[]" type="number" step="any" class="form-control text-center" id="final_quantity">';
            tr += '</td>';

            tr += '<td>';
            tr += '<select name="unit_ids[]" id="unit_id" class="form-control">';

            unites.forEach(function(unit) {

                if (productUnit == unit.id) {

                    tr += '<option SELECTED value="' + unit.id + '">' + unit.name + '</option>';
                } else {

                    tr += '<option value="' + unit.id + '">' + unit.name + '</option>';
                }
            });

            tr += '</select>';
            tr += '</td>';

            tr += '<td>';
            tr += '<input readonly value="' + productCostIncTax + '" type="text" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" class="form-control text-center">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input readonly value="' + productCostIncTax + '" type="text" name="subtotals[]" id="subtotal" class="form-control text-center">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
            tr += '</td>';

            tr += '</tr>';
            $('#ingredient_list').prepend(tr);

            __calculateTotalAmount();
            if (keyName == 9) {
                $("#final_quantity").select();
                keyName = 1;
            }
        }
    }

    // select variant product and add purchase table
    function salectVariant(e) {

        if (keyName == 13 || keyName == 1) {

            document.getElementById('search_product').focus();
        }

        $('.select_area').hide();
        $('#search_product').val("");
        $('#search_product').val('');

        var productId = e.getAttribute('data-p_id');
        var productName = e.getAttribute('data-p_name');
        var productUnit = e.getAttribute('data-unit');
        var productCode = e.getAttribute('data-p_code');
        var variantId = e.getAttribute('data-v_id');
        var variantName = e.getAttribute('data-v_name');
        var variantCode = e.getAttribute('data-v_code');
        var variantCostIncTax = e.getAttribute('data-v_cost_with_tax');
        variant_id = document.querySelectorAll('#variant_id');

        __calculateTotalAmount();
        var sameVariant = 0;

        variant_id.forEach(function(input) {

            if (input.value != 'noid') {

                if (input.value == variantId) {

                    sameVariant += 1;
                    var className = input.getAttribute('class');
                    var closestTr = $('.' + className).closest('tr');
                    // update same product qty
                    var presentQty = closestTr.find('#final_quantity').val();
                    var updateQty = parseFloat(presentQty) + 1;
                    closestTr.find('#final_quantity').val(updateQty);
                    var unitCostIncTax = closestTr.find('#unit_cost_inc_tax').val();
                    // update subtotal
                    var totalCost = parseFloat(unitCostIncTax) * parseFloat(updateQty);
                    closestTr.find('#subtotal').val(parseFloat(totalCost).toFixed(2));
                    __calculateTotalAmount();
                    return;
                }
            }
        });

        if (sameVariant == 0) {

            var tr = '';
            tr += '<tr>';
            tr += '<td class="text-start">';
            tr += '<span class="product_name">' + productName + '</span>';
            tr += '<span class="product_variant">(' + variantName + ')</span>';
            tr += '<input value="' + productId + '" type="hidden" class="productId-' + productId + '" id="product_id" name="product_ids[]">';
            tr += '<input value="' + variantId + '" type="hidden" class="variantId-' + variantId + '" id="variant_id" name="variant_ids[]">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<input value="1" required name="final_quantities[]" type="number" step="any" class="form-control text-center" id="final_quantity">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<select name="unit_ids[]" id="unit_id" class="form-control">';

            unites.forEach(function(unit) {

                if (productUnit == unit.id) {

                    tr += '<option SELECTED value="' + unit.id + '">' + unit.name + '</option>';
                } else {

                    tr += '<option value="' + unit.id + '">' + unit.name + '</option>';
                }
            });

            tr += '</select>';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<input readonly value="' + variantCostIncTax + '" type="text" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" class="form-control text-center">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<input readonly value="' + variantCostIncTax + '" type="text" name="subtotals[]" id="subtotal" class="form-control text-center">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash"></span></a>';
            tr += '</td>';

            tr += '</tr>';
            $('#ingredient_list').prepend(tr);
            __calculateTotalAmount();

            if (keyName == 9) {

                $("#final_quantity").select();
                keyName = 1;
            }
        }
    }

    // Quantity increase or dicrease and clculate row amount
    $(document).on('input', '#final_quantity', function() {
        var tr = $(this).closest('tr');
        __calculateIngredientsTableAmount(tr);
    });

    $(document).on('input', '#production_cost', function() {
        var tr = $(this).closest('tr');
        __calculateTotalAmount();
    });

    function __calculateIngredientsTableAmount(tr) {
        var qty = tr.find('#final_quantity').val() ? tr.find('#final_quantity').val() : 0;
        //Update subtotal
        var unitCostIncTax = tr.find('#unit_cost_inc_tax').val();
        var totalCost = parseFloat(unitCostIncTax) * parseFloat(qty);
        var subtotal = tr.find('#subtotal').val(parseFloat(totalCost).toFixed(2));
        __calculateTotalAmount();
    }

    function __calculateTotalAmount() {
        var subtotals = document.querySelectorAll('#subtotal');
        var totalIngredientCost = 0;
        subtotals.forEach(function(price) {
            totalIngredientCost += parseFloat(price.value);
        });

        $('#total_ingredient_cost').val(parseFloat(totalIngredientCost));
        $('#span_total_ingredient_cost').html(parseFloat(totalIngredientCost).toFixed(2));
        var productionCost = $('#production_cost').val() ? $('#production_cost').val() : 0;
        var totalCost = parseFloat(totalIngredientCost) + parseFloat(productionCost);
        $('#total_cost').val(parseFloat(totalCost).toFixed(2));
    }

    // Remove product form ingredient list (Table)
    $(document).on('click', '#remove_product_btn', function(e) {
        e.preventDefault();

        $(this).closest('tr').remove();
        __calculateTotalAmount();
    });

    //Add process request by ajax
    $('#add_process_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        $('.submit_button').prop('type', 'button');
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.error').html('');
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'sumbit');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                } else {

                    toastr.success(data);
                    window.location = "{{ route('manufacturing.process.index') }}";
                }
            },
            error: function(err) {

                $('.error').html('');
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('body').keyup(function(e) {

        if (e.keyCode == 13) {

            $(".selectProduct").click();
            $('#list').empty();
        }
    });

    $(document).keypress(".scanable", function(event) {

        if (event.which == '10' || event.which == '13') {

            event.preventDefault();
        }
    });

    setInterval(function() {
        $('#search_product').removeClass('is-invalid');
    }, 500);
    setInterval(function() {
        $('#search_product').removeClass('is-valid');
    }, 1000);
</script>
