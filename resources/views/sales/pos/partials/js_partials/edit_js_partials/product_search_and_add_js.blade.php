<script>
    // Get all price group
    var priceGroups = @json($priceGroupProducts);
    var delay = (function() {
        var timer = 0;
        return function(callback, ms) {

            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $('#search_product').on('input', function(e) {

        $('.variant_list_area').empty();
        $('.select_area').hide();
        var keyWord = $(this).val();
        var __keyWord = keyWord.replaceAll('/', '~');
        var priceGroupId = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';
        delay(function() {
            searchProduct(__keyWord, priceGroupId);
        }, 200); //sendAjaxical is the name of remote-command
    });

    function searchProduct(keyWord, priceGroupId) {

        $('#search_product').focus();

        var isShowNotForSaleItem = 0;
        var url = "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem', ':priceGroupId']) }}";
        var route = url.replace(':keyWord', keyWord);
        route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);
        route = route.replace(':priceGroupId', priceGroupId);

        $.ajax({
            url: route,
            dataType: 'json',
            success: function(product) {

                if (!$.isEmptyObject(product.errorMsg || keyWord == '')) {

                    toastr.error(product.errorMsg);
                    $('#search_product').val("");
                    $('.select_area').hide();
                    $('#stock_quantity').val(parseFloat(0).toFixed(2));
                    return;
                }

                var discount = product.discount;

                if (
                    !$.isEmptyObject(product.product) ||
                    !$.isEmptyObject(product.variant_product) ||
                    !$.isEmptyObject(product.namedProducts)
                ) {

                    $('#search_product').addClass('is-valid');

                    if (!$.isEmptyObject(product.product)) {

                        $('#search_product').val('');
                        $('.select_area').hide();

                        var product = product.product;

                        if (product.variants.length == 0) {

                            var stock = product.product_branch_stock != null ? product.product_branch_stock.stock : 0;
                            var __stock = product.is_manage_stock == 0 ? Number.MAX_SAFE_INTEGER : stock;

                            if (__stock == 0) {

                                toastr.error("{{ __('Product stock is 0') }}");
                                return;
                            }

                            $('#stock_quantity').val(stock);

                            var uniqueIdForPreventDuplicateEntry = product.id + 'noid';
                            var uniqueIdValue = $('#' + uniqueIdForPreventDuplicateEntry).val();

                            if (uniqueIdValue == undefined) {

                                var taxPercent = product.tax != null ? product.tax.tax_percent : 0;
                                var price = 0;

                                var __price = priceGroups.filter(function(value) {

                                    return value.price_group_id == price_group_id && value.product_id == product.id;
                                });

                                if (__price.length != 0) {

                                    price = __price[0].price ? __price[0].price : product.product_price;
                                } else {

                                    price = product.product_price;
                                }

                                var discount_amount = 0;
                                if (discount.discount_type == 1) {

                                    discount_amount = discount.discount_amount
                                } else {

                                    discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                                }

                                var __price_with_discount = parseFloat(price) - parseFloat(discount_amount);

                                var tax_amount = parseFloat(__price_with_discount / 100 * taxPercent);
                                var unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);

                                if (product.tax_type == 2) {

                                    var inclusiveTax = 100 + parseFloat(taxPercent)
                                    var calcAmount = parseFloat(__price_with_discount) / parseFloat(inclusiveTax) * 100;
                                    tax_amount = parseFloat(__price_with_discount) - parseFloat(calcAmount);
                                    unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);
                                }

                                var name = product.name.length > 30 ? product.name.substring(0, 30) + '...' : product.name;

                                var tr = '';
                                tr += '<tr class="product_row">';
                                tr += '<td id="serial">1</td>';
                                tr += '<td class="text-start">';
                                tr += '<a href="#" onclick="editProduct(this); return false;" id="edit_product_link" tabindex="-1">' + name + '</a><br/>';
                                tr += '<span><small id="span_description" style="font-size:9px;"></small></span>';
                                tr += '<input type="hidden" id="is_show_emi_on_pos" value="' + product.is_show_emi_on_pos + '">';
                                tr += '<input type="hidden" name="descriptions[]" id="description">';
                                tr += '<input type="hidden" id="product_name" value="' + name + '">';
                                tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + product.id + '">';
                                tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="noid">';
                                tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '">';
                                tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + product.tax_type + '">';
                                tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent"  value="' + taxPercent + '">';
                                tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + parseFloat(tax_amount).toFixed(2) + '">';
                                tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="' + discount.discount_type + '">';
                                tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="' + discount.discount_amount + '">';
                                tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + parseFloat(discount_amount) + '">';
                                tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + (product.update_product_cost != null ? product.update_product_cost.net_unit_cost : product.product_cost_with_tax) + '">';
                                tr += '<input type="hidden" name="sale_product_ids[]" value="">';
                                tr += ' <input type="hidden" id="current_quantity" value="0">';
                                tr += '<input type="hidden" id="current_stock" value="' + __stock + '">';
                                tr += '<input type="hidden" class="unique_id" id="' + product.id + 'noid' + '" value="' + product.id + 'noid' + '">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<span class="fw-bold" id="span_quantity">' + parseFloat(1).toFixed(2) + '</span>';
                                tr += '<input type="hidden" step="any" name="quantities[]" id="quantity" value="1.00">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<span class="fw-bold" id="span_unit">' + product.unit.name + '</span>';
                                tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + product.unit.id + '">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="' + parseFloat(price).toFixed(2) + '">';
                                tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="' + parseFloat(unitPriceIncTax).toFixed(2) + '">';
                                tr += '<span class="fw-bold" id="span_unit_price_inc_tax">' + parseFloat(unitPriceIncTax).toFixed(2) + '</span>';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<span class="fw-bold" id="span_subtotal">' + parseFloat(unitPriceIncTax).toFixed(2) + '</span>';
                                tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(unitPriceIncTax).toFixed(2) + '">';
                                tr += '</td>';

                                tr += '<td><a href="#" class="action-btn c-delete" id="remove_product_btn" tabindex="-1"><span class="fas fa-trash"></span></a></td>';
                                tr += '</tr>';

                                $('#product_list').prepend(tr);
                                calculateTotalAmount();
                                activeSelectedItems();
                            } else {

                                var exTr = $('#' + uniqueIdForPreventDuplicateEntry).closest('tr');
                                var currentQty = exTr.find('#quantity').val() ? exTr.find('#quantity').val() : 0;
                                var updateQty = parseFloat(currentQty) + 1;

                                if (updateQty > __stock) {

                                    toastr.error("{{ __('Quantity exceed the current stock') }}");
                                    return;
                                }

                                exTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                                exTr.find('#span_quantity').html(parseFloat(updateQty).toFixed(2));

                                var priceIncTax = exTr.find('#unit_price_inc_tax').val() ? exTr.find('#unit_price_inc_tax').val() : 0;
                                var subtotal = parseFloat(priceIncTax) * parseFloat(updateQty);

                                exTr.find('#subtotal').val(parseFloat(subtotal).toFixed(2));
                                exTr.find('#span_subtotal').html(parseFloat(subtotal).toFixed(2));
                                calculateTotalAmount();
                            }
                        } else {

                            var li = "";
                            var taxPercent = product.tax != null ? product.tax.tax_percent : 0.00;

                            $.each(product.variants, function(key, variant) {

                                li += '<li class="mt-1">';
                                li += '<a href="#" onclick="salectVariant(this); return false;" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-v_name="' + variant.variant_name + '" data-p_tax_ac_id="' + (variant.product.tax_ac_id != null ? variant.product.tax_ac_id : '') + '" data-unit_id="' + product.unit.id + '" data-unit_name="' + product.unit.name + '" data-tax_type="' + product.tax_type + '" data-tax_percent="' + taxPercent + '" data-p_price_exc_tax="' + variant.variant_price + '" data-p_cost_inc_tax="' + (variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax) + '">' + product.name + ' - ' + variant.variant_name + ' - Price: ' + variant.variant_price + '</a>';
                                li += '</li>';
                            });

                            $('.variant_list_area').prepend(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    } else if (!$.isEmptyObject(product.variant_product)) {

                        $('#search_product').val('');
                        $('.select_area').hide();

                        var variant = product.variant_product;
                        var stock = variant.variant_branch_stock != null ? variant.variant_branch_stock.stock : 0;

                        var __stock = product.is_manage_stock == 0 ? Number.MAX_SAFE_INTEGER : stock;

                        if (__stock == 0) {

                            toastr.error("{{ __('Product stock is 0') }}");
                            return;
                        }

                        $('#stock_quantity').val(stock);

                        var taxPercent = variant.product.tax != null ? variant.product.tax.tax_percent : 0;

                        var uniqueIdForPreventDuplicateEntry = variant.product.id + '' + variant.id;
                        var uniqueIdValue = $('#' + uniqueIdForPreventDuplicateEntry).val();

                        if (uniqueIdValue == undefined) {

                            var price = 0;

                            var __price = priceGroups.filter(function(value) {

                                return value.price_group_id == price_group_id && value.product_id == variant.product.id && value.variant_id == variant.id;
                            });

                            if (__price.length != 0) {

                                price = __price[0].price ? __price[0].price : variant.variant_price;
                            } else {

                                price = variant.variant_price;
                            }

                            var discount_amount = 0;
                            if (discount.discount_type == 1) {

                                discount_amount = discount.discount_amount
                            } else {

                                discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                            }

                            var __price_with_discount = parseFloat(price) - parseFloat(discount_amount);

                            var tax_amount = parseFloat(__price_with_discount) / 100 * parseFloat(taxPercent);
                            var unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);

                            if (variant.product.tax_type == 2) {

                                var inclusiveTax = 100 + parseFloat(taxPercent)
                                var calcAmount = parseFloat(__price_with_discount) / parseFloat(inclusiveTax) * 100;
                                tax_amount = parseFloat(__price_with_discount) - parseFloat(calcAmount);
                                unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);
                            }

                            var name = variant.product.name.length > 30 ? variant.product.name.substring(0, 30) + '...' : variant.product.name;
                            var tr = '';
                            tr += '<tr class="product_row">';
                            tr += '<td id="serial">1</td>';

                            tr += '<td class="text-start">';
                            tr += '<a href="#" onclick="editProduct(this); return false;" id="edit_product_link" tabindex="-1">' + name + '</a><br/>';
                            tr += '<span><small id="span_description" style="font-size:9px;"></small></span>';
                            tr += '<input type="hidden" id="is_show_emi_on_pos" value="' + product.is_show_emi_on_pos + '">';
                            tr += '<input type="hidden" name="descriptions[]" id="description">';
                            tr += '<input type="hidden" id="product_name" value="' + name + ' - ' + variant.variant_name + '">';
                            tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + variant.product.id + '">';
                            tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + variant.id + '">';
                            tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + (variant.product.tax_ac_id != null ? variant.product.tax_ac_id : '') + '">';
                            tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + variant.product.tax_type + '">';
                            tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="' + taxPercent + '">';
                            tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + parseFloat(tax_amount) + '">';
                            tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="' + discount.discount_type + '">';
                            tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="' + discount.discount_amount + '">';
                            tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + parseFloat(discount_amount) + '">';
                            tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + (variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax) + '">';
                            tr += '<input type="hidden" name="sale_product_ids[]" value="">';
                            tr += ' <input type="hidden" id="current_quantity" value="0">';
                            tr += '<input type="hidden" id="current_stock" value="' + __stock + '">';
                            tr += '<input type="hidden" class="unique_id" id="' + variant.product.id + '' + variant.id + '" value="' + variant.product.id + '' + variant.id + '">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<span class="fw-bold" id="span_quantity">' + parseFloat(1).toFixed(2) + '</span>';
                            tr += '<input required type="hidden" step="any" name="quantities[]" id="quantity" value="1.00">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<span class="fw-bold" id="span_unit">' + variant.product.unit.name + '</span>';
                            tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + variant.product.unit.id + '">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input type="hidden" name="unit_prices_exc_tax[]" value="' + parseFloat(price).toFixed(2) + '" id="unit_price_exc_tax">';
                            tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="' + parseFloat(unitPriceIncTax).toFixed(2) + '">';
                            tr += '<span class="fw-bold" id="span_unit_price_inc_tax">' + parseFloat(unitPriceIncTax).toFixed(2) + '</span>';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<span class="fw-bold" id="span_subtotal">' + parseFloat(unitPriceIncTax).toFixed(2) + '</span>';
                            tr += '<input name="subtotals[]" type="hidden" id="subtotal" value="' + parseFloat(unitPriceIncTax).toFixed(2) + '">';
                            tr += '</td>';
                            tr += '<td><a href="#" class="action-btn c-delete" id="remove_product_btn" tabindex="-1"><span class="fas fa-trash"></span></a></td>';
                            tr += '</tr>';

                            $('#product_list').prepend(tr);
                            calculateTotalAmount();
                            activeSelectedItems();
                        } else {

                            var exTr = $('#' + uniqueIdForPreventDuplicateEntry).closest('tr');
                            var currentQty = exTr.find('#quantity').val() ? exTr.find('#quantity').val() : 0;
                            var updateQty = parseFloat(currentQty) + 1;

                            if (updateQty > __stock) {

                                toastr.error("{{ __('Quantity exceed the current stock') }}");
                                return;
                            }

                            exTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                            exTr.find('#span_quantity').html(parseFloat(updateQty).toFixed(2));

                            var priceIncTax = exTr.find('#unit_price_inc_tax').val() ? exTr.find('#unit_price_inc_tax').val() : 0;
                            var subtotal = parseFloat(priceIncTax) * parseFloat(updateQty);

                            exTr.find('#subtotal').val(parseFloat(subtotal).toFixed(2));
                            exTr.find('#span_subtotal').html(parseFloat(subtotal).toFixed(2));
                            calculateTotalAmount();
                        }
                    } else if (!$.isEmptyObject(product.namedProducts)) {

                        $('#current_stock').val('');

                        if (product.namedProducts.length > 0) {

                            var li = "";
                            var products = product.namedProducts;

                            $.each(products, function(key, product) {

                                var taxPercent = product.tax_percent != null ? product.tax_percent : 0;

                                var updateProductCost = product.update_product_cost != 0 && product.update_product_cost != null ? product.update_product_cost : product.product_cost_with_tax;

                                var updateVariantCost = product.update_variant_cost != 0 && product.update_variant_cost != null ? product.update_variant_cost : product.variant_cost_with_tax;

                                var __updateProductCost = product.is_variant == 1 ? updateVariantCost : updateProductCost;

                                if (product.is_variant == 1) {

                                    li += '<li class="mt-1">';
                                    li += '<a href="#" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-is_show_emi_on_pos="' + product.is_show_emi_on_pos + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-v_name="' + product.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-unit_id="' + product.unit_id + '" data-unit_name="' + product.unit_name + '" data-tax_percent="' + taxPercent + '" data-tax_type="' + product.tax_type + '" data-p_price_exc_tax="' + product.variant_price + '" data-p_cost_inc_tax="' + __updateProductCost + '">' + product.name + ' - ' + product.variant_name + ' - Price: ' + product.variant_price + '</a>';
                                    li += '</li>';
                                } else {

                                    li += '<li class="mt-1">';
                                    li += '<a href="#" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-is_show_emi_on_pos="' + product.is_show_emi_on_pos + '" data-p_name="' + product.name + '" data-v_name="" data-v_id="" data-unit_id="' + product.unit_id + '" data-unit_name="' + product.unit_name + '" data-p_price_exc_tax="' + product.product_price + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_percent="' + taxPercent + '" data-tax_type="' + product.tax_type + '" data-p_cost_inc_tax="' + __updateProductCost + '" data-description="' + product.is_show_emi_on_pos + '"  >' + product.name + ' - Price: ' + product.product_price + '</a>';
                                    li += '</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    }
                } else {

                    $('#search_product').addClass('is-invalid');
                    $('#search_product').select();
                }
            }
        });
    }

    // select single product and add stock adjustment table
    var keyName = 1;
    function selectProduct(e) {

        var price_group_id = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';

        $('.select_area').hide();
        $('#search_product').val('');

        var product_id = e.getAttribute('data-p_id');
        var variant_id = e.getAttribute('data-v_id') ? e.getAttribute('data-v_id') : 'noid';
        var unit_id = e.getAttribute('data-unit_id');
        var unit_name = e.getAttribute('data-unit_name');
        var is_manage_stock = e.getAttribute('data-is_manage_stock');
        var product_name = e.getAttribute('data-p_name');
        var variant_name = e.getAttribute('data-v_name');
        var product_code = e.getAttribute('data-p_code');
        var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');
        var product_price_exc_tax = e.getAttribute('data-p_price_exc_tax');
        var p_tax_ac_id = e.getAttribute('data-p_tax_ac_id');
        var p_tax_type = e.getAttribute('data-tax_type');
        var tax_percent = e.getAttribute('data-tax_percent');
        var is_show_emi_on_pos = e.getAttribute('data-is_show_emi_on_pos');
        $('#search_product').val('');

        var url = "{{ route('general.product.search.check.product.discount.with.single.or.variant.branch.stock', ['productId' => ':product_id', 'variantId' => ':variant_id', 'priceGroupId' => ':price_group_id', 'branchId' => auth()->user()->branch_id]) }}"
        var route = url.replace(':product_id', product_id);
        route = route.replace(':variant_id', variant_id);
        route = route.replace(':price_group_id', price_group_id);

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(data) {

                if ($.isEmptyObject(data.errorMsg)) {

                    if (data.stock == 0) {

                        toastr.error("{{ __('Product stock is 0') }}");
                        return;
                    }

                    if (is_manage_stock == 1) {

                        $('#stock_quantity').val(parseFloat(data.stock).toFixed(2));
                    }

                    var uniqueIdForPreventDuplicateEntry = product_id + variant_id;
                    var uniqueIdValue = $('#' + uniqueIdForPreventDuplicateEntry).val();

                    if (uniqueIdValue == undefined) {

                        var price = 0;
                        var __price = priceGroups.filter(function(value) {

                            if (variant_id != 'noid') {

                                return value.price_group_id == price_group_id && value.product_id == product_id && value.variant_id == variant_id;
                            } else {

                                return value.price_group_id == price_group_id && value.product_id == product_id;
                            }
                        });

                        if (__price.length != 0) {

                            price = __price[0].price ? __price[0].price : product_price_exc_tax;
                        } else {

                            price = product_price_exc_tax;
                        }

                        var discount = data.discount;

                        var discount_amount = 0;
                        if (discount.discount_type == 1) {

                            discount_amount = discount.discount_amount
                        } else {

                            discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                        }

                        var __price_with_discount = parseFloat(price) - parseFloat(discount_amount);

                        var tax_amount = parseFloat(__price_with_discount) / 100 * parseFloat(tax_percent);
                        var unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);

                        if (p_tax_type == 2) {

                            var inclusiveTax = 100 + parseFloat(tax_percent)
                            var calcAmount = parseFloat(__price_with_discount) / parseFloat(inclusiveTax) * 100;
                            tax_amount = parseFloat(__price_with_discount) - parseFloat(calcAmount);
                            unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);
                        }

                        var name = product_name.length > 35 ? product_name.substring(0, 35) + '...' : product_name;
                        var __name = name + (variant_name ? ' - ' + variant_name : '');

                        var tr = '';
                        tr += '<tr class="product_row">';
                        tr += '<td class="fw-bold" id="serial">1</td>';
                        tr += '<td class="text-start">';
                        tr += '<a href="#" onclick="editProduct(this); return false;" id="edit_product_link">' + __name + '</a><br/><input type="' + (is_show_emi_on_pos == 1 ? 'text' : 'hidden') + '" name="descriptions[]" class="form-control description_input scanable" placeholder="' + "{{ __('IMEI, Serial number or other info.') }}" + '">';
                        tr += '<input type="hidden" id="product_name" value="' + __name + '">';
                        tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + product_id + '">';
                        tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + variant_id + '">';
                        tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + p_tax_ac_id + '">';
                        tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + p_tax_type + '">';
                        tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="' + tax_percent + '">';
                        tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + parseFloat(tax_amount) + '">';
                        tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="' + discount.discount_type + '">';
                        tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="' + discount.discount_amount + '">';
                        tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + parseFloat(discount_amount) + '">';
                        tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + (product_cost_inc_tax) + '">';
                        tr += '<input type="hidden" name="sale_product_ids[]" value="">';
                        tr += ' <input type="hidden" id="current_quantity" value="0">';
                        tr += '<input type="hidden" id="current_stock" value="' + data.stock + '">';
                        tr += '<input type="hidden" class="unique_id" id="' + product_id + '' + variant_id + '" value="' + product_id + '' + variant_id + '">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<span class="fw-bold" id="span_quantity">' + parseFloat(1).toFixed(2) + '</span>';
                        tr += '<input required type="hidden" step="any" name="quantities[]" id="quantity" value="1.00">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<span class="fw-bold" id="span_unit">' + unit_name + '</span>';
                        tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + unit_id + '">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input type="hidden" name="unit_prices_exc_tax[]" value="' + parseFloat(price).toFixed(2) + '" id="unit_price_exc_tax">';
                        tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="' + parseFloat(unitPriceIncTax).toFixed(2) + '">';
                        tr += '<span class="fw-bold" id="span_unit_price_inc_tax">' + parseFloat(unitPriceIncTax).toFixed(2) + '</span>';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<span class="fw-bold" id="span_subtotal">' + parseFloat(unitPriceIncTax).toFixed(2) + '</span>';
                        tr += '<input name="subtotals[]" type="hidden" id="subtotal" value="' + parseFloat(unitPriceIncTax).toFixed(2) + '">';
                        tr += '</td>';
                        tr += '<td><a href="#" class="action-btn c-delete" id="remove_product_btn" tabindex="-1"><span class="fas fa-trash"></span></a></td>';
                        tr += '</tr>';

                        $('#product_list').append(tr);

                        calculateTotalAmount();
                        activeSelectedItems();
                    } else {

                        var exTr = $('#' + uniqueIdForPreventDuplicateEntry).closest('tr');
                        var currentQty = exTr.find('#quantity').val() ? exTr.find('#quantity').val() : 0;
                        var updateQty = parseFloat(currentQty) + 1;

                        if (updateQty > data.stock) {

                            toastr.error("{{ __('Quantity exceed the current stock') }}");
                            return;
                        }

                        exTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                        exTr.find('#span_quantity').html(parseFloat(updateQty).toFixed(2));

                        var priceIncTax = exTr.find('#unit_price_inc_tax').val() ? exTr.find('#unit_price_inc_tax').val() : 0;
                        var subtotal = parseFloat(priceIncTax) * parseFloat(updateQty);

                        exTr.find('#subtotal').val(parseFloat(subtotal).toFixed(2));
                        exTr.find('#span_subtotal').html(parseFloat(subtotal).toFixed(2));
                        calculateTotalAmount();
                    }
                } else {

                    toastr.error(data.errorMsg);
                }
            }
        });
    }

    $('body').keyup(function(e) {

        if (e.keyCode == 13 || e.keyCode == 9) {

            $(".selectProduct").click();
            $('#list').empty();
            keyName = e.keyCode;
        }
    });

    $(document).on('mouseenter', '#list>li>a', function() {

        $('#list>li>a').removeClass('selectProduct');
        $(this).addClass('selectProduct');
    });

    // Automatic remove searching product is found signal
    setInterval(function() {

        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function() {

        $('#search_product').removeClass('is-valid');
    }, 1000);

    $(document).on('click', '#hard_reload', function() {

        window.location.reload(true);
    });

    $(document).on('focus', '#edit_product_link', function() {

        $('.product_row').removeClass('active_tr');
        $(this).closest('tr').addClass('active_tr');
    });

    $(document).on('click', '.product_row', function() {

        $('.product_row').removeClass('active_tr');
        $(this).addClass('active_tr');
        $(this).find('#edit_product_link').focus();
    });

    $(document).on('keyup', '#edit_product_link', function(e) {

        var tr = $(this).closest('tr');
        var preTr = $(tr).prev();
        var next = $(tr).next();
        if (e.which == 40) {

            if (next.length > 0) {

                next.find('#edit_product_link').focus();
            }
        } else if (e.which == 38) {

            if (preTr.length > 0) {

                preTr.find('#edit_product_link').focus();
            }
        }
    });
</script>
