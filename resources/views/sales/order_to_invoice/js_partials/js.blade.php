<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    var itemUnitsArray = [];
    var branch_id = "{{ auth()->user()->branch_id }}";
    var branch_name = "{{ $branchName }}";

    $('#sales_order_product_id').on('select2:close', function(e) {

        var product_name = $(this).find('option:selected').data('name');
        var product_id = $(this).find('option:selected').data('p_id');
        var variant_id = $(this).find('option:selected').data('v_id') ? $(this).find('option:selected').data('v_id') : 'noid';
        var is_manage_stock = $(this).find('option:selected').data('is_manage_stock');
        var unit_cost_inc_tax = $(this).find('option:selected').data('p_cost_inc_tax');
        var unit_price_exc_tax = $(this).find('option:selected').data('p_price_exc_tax');
        var unit_discount = $(this).find('option:selected').data('p_discount');
        var unit_discount_type = $(this).find('option:selected').data('p_discount_type');
        var unit_discount_amount = $(this).find('option:selected').data('p_discount_amount');
        var unit_price_inc_tax = $(this).find('option:selected').data('p_price_inc_tax');
        var tax_ac_id = $(this).find('option:selected').data('p_tax_ac_id') != null ? $(this).find('option:selected').data('p_tax_ac_id') : '';
        var tax_type = $(this).find('option:selected').data('tax_type');
        var is_show_emi_on_pos = $(this).find('option:selected').data('is_show_emi_on_pos');

        var ordered_quantity = $(this).find('option:selected').data('p_ordered_quantity');
        var delivered_quantity = $(this).find('option:selected').data('p_delivered_quantity');
        var left_quantity = $(this).find('option:selected').data('p_left_quantity');

        var url = "{{ route('general.product.search.check.product.discount.with.stock', ['productId' => ':product_id', 'variantId' => ':variant_id', 'priceGroupId' => 'no_id', 'branchId' => auth()->user()->branch_id]) }}"
        var route = url.replace(':product_id', product_id);
        route = route.replace(':variant_id', variant_id);

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(data) {

                if ($.isEmptyObject(data.errorMsg)) {

                    if (is_manage_stock == 1) {

                        $('#stock_quantity').val(parseFloat(data.stock).toFixed(2));
                    }

                    if (is_show_emi_on_pos == 0) {

                        $('#e_descriptions').prop('readonly', true);
                    } else {

                        $('#e_descriptions').prop('readonly', false);
                    }

                    $('#e_product_name').val(product_name);
                    $('#e_product_id').val(product_id);
                    $('#e_variant_id').val(variant_id);
                    $('#e_ordered_quantity').val(parseFloat(ordered_quantity).toFixed(2)).focus().select();
                    $('#e_delivered_quantity').val(parseFloat(delivered_quantity).toFixed(2));
                    $('#e_left_quantity').val(parseFloat(left_quantity).toFixed(2));
                    $('#e_quantity').val(parseFloat(0).toFixed(2)).focus().select();
                    $('#e_price_exc_tax').val(parseFloat(unit_price_exc_tax).toFixed(2));
                    $('#e_discount').val(parseFloat(unit_discount).toFixed(2));
                    $('#e_discount_type').val(unit_discount_type);
                    $('#e_discount_amount').val(parseFloat(unit_discount_amount).toFixed(2));
                    $('#e_tax_ac_id').val(tax_ac_id);
                    $('#e_tax_type').val(tax_type);
                    $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
                    $('#e_is_show_emi_on_pos').val(is_show_emi_on_pos);

                    $('#e_unit_id').empty();
                    $('#e_unit_id').append(
                        '<option value="' + data.unit.id + '" data-is_base_unit="1" data-unit_name="' + data.unit.name + '" data-base_unit_multiplier="1">' + data.unit.name + '</option>'
                    );

                    itemUnitsArray[product_id] = [{
                        'unit_id': data.unit.id,
                        'unit_name': data.unit.name,
                        'unit_code_name': data.unit.code_name,
                        'base_unit_multiplier': 1,
                        'multiplier_details': '',
                        'is_base_unit': 1,
                    }];

                    $('#add_item').html('Add');

                    calculateEditOrAddAmount();
                } else {

                    toastr.error(data.errorMsg);
                }
            }
        });
    });

    $('#add_item').on('click', function(e) {
        e.preventDefault();

        var sales_order_product_id = $('#sales_order_product_id').val();

        var e_unique_id = $('#e_unique_id').val();
        var e_product_name = $('#e_product_name').val();
        var e_product_id = $('#e_product_id').val();
        var e_variant_id = $('#e_variant_id').val();
        var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
        var e_unit_id = $('#e_unit_id').val();
        var e_ordered_quantity = $('#e_ordered_quantity').val() ? $('#e_ordered_quantity').val() : 0;
        var e_delivered_quantity = $('#e_delivered_quantity').val() ? $('#e_delivered_quantity').val() : 0;
        var e_left_quantity = $('#e_left_quantity').val() ? $('#e_left_quantity').val() : 0;
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_price_exc_tax = $('#e_price_exc_tax').val() ? $('#e_price_exc_tax').val() : 0;
        var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;
        var e_discount_type = $('#e_discount_type').val();
        var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
        var e_tax_ac_id = $('#e_tax_ac_id').val();
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
        var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
        var e_tax_type = $('#e_tax_type').val();
        var e_price_inc_tax = $('#e_price_inc_tax').val() ? $('#e_price_inc_tax').val() : 0;
        var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
        var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();
        var e_descriptions = $('#e_descriptions').val();
        var stock_quantity = $('#stock_quantity').val();

        var e_warehouse_id = $('#e_warehouse_id').val() ? $('#e_warehouse_id').val() : '';
        var warehouse_name = $('#e_warehouse_id').find('option:selected').data('w_name');

        if (parseFloat(e_left_quantity) < 0) {

            toastr.error("{{ __('Deliver Quantity must not be greater then left quantity.') }}");
            return;
        }

        var stock_location_name = '';
        if (e_warehouse_id) {

            stock_location_name = warehouse_name;
        } else {

            stock_location_name = branch_name;
        }

        if (e_quantity == '') {

            toastr.error("{{ __('Quantity field must not be empty.') }}");
            return;
        }

        if (e_product_id == '') {

            toastr.error("{{ __('Please select an item.') }}");
            return;
        }

        var route = '';
        if (e_variant_id != 'noid') {

            var url = "{{ route('general.product.search.variant.product.stock', [':product_id', ':variant_id', ':warehouse_id']) }}";
            route = url.replace(':product_id', e_product_id);
            route = route.replace(':variant_id', e_variant_id);
            route = route.replace(':warehouse_id', e_warehouse_id);
        } else {

            var url = "{{ route('general.product.search.single.product.stock', [':product_id', ':warehouse_id']) }}";
            route = url.replace(':product_id', e_product_id);
            route = route.replace(':warehouse_id', e_warehouse_id);
        }

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(data) {

                var status = $('#status').val();

                if (status == 1 || status == '') {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    var stockLocationMessage = e_warehouse_id ? "{{ __('in selected warehouse') }} " : "{{ __('in the Store') }} ";
                    if (parseFloat(e_quantity) > parseFloat(data.stock)) {

                        toastr.error("{{ __('Current stock is') }} " + parseFloat(data.stock) + '/' + e_unit_name + stockLocationMessage);
                        return;
                    }
                }

                var uniqueIdForPreventDuplicateEntry = e_product_id + e_variant_id + e_warehouse_id;
                var uniqueIdValue = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).val();

                if (uniqueIdValue == undefined) {

                    var tr = '';
                    tr += '<tr id="select_product">';

                    tr += '<td class="text-start">';
                    tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="' + e_warehouse_id + '">';
                    tr += '<span id="stock_location_name">' + stock_location_name + '</span>';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<span class="product_name">' + e_product_name + '</span>';
                    tr += '<input type="hidden" id="product_name" value="' + e_product_name + '">';
                    tr += '<input type="hidden" name="is_show_emi_on_pos" id="is_show_emi_on_pos" value="' + e_is_show_emi_on_pos + '">';
                    tr += '<input type="hidden" name="descriptions[]" id="descriptions" value="' + e_descriptions + '">';
                    tr += '<input type="hidden" id="sales_order_product_ids" value="' + sales_order_product_id + '">';
                    tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + e_product_id + '">';
                    tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + e_variant_id + '">';
                    tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + e_tax_ac_id + '">';
                    tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + e_tax_type + '">';
                    tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="' + e_tax_percent + '">';
                    tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + parseFloat(e_tax_amount).toFixed(2) + '">';
                    tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="' + e_discount_type + '">';
                    tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="' + e_discount + '">';
                    tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + e_discount_amount + '">';
                    tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + e_unit_cost_inc_tax + '">';
                    tr += '<input type="hidden" id="current_stock" value="' + stock_quantity + '">';
                    tr += '<input type="hidden" data-product_name="' + e_product_name + '" data-unit_name="' + e_unit_name + '" id="stock_limit" value="' + data.stock + '">';
                    tr += '<input type="hidden" class="unique_id" id="' + e_product_id + e_variant_id + e_warehouse_id + '" value="' + e_product_id + e_variant_id + e_warehouse_id + '">';
                    tr += '</td>';

                    // tr += '<td class="text-start">';
                    // tr += '<span id="span_ordered_quantity" class="fw-bold">' + parseFloat(e_ordered_quantity).toFixed(2) + '</span>';
                    // tr += '<input type="hidden" id="ordered_quantity" value="' + parseFloat(e_ordered_quantity).toFixed(2) + '">';
                    // tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<span id="span_left_quantity" class="text-danger fw-bold">' + parseFloat(e_left_quantity).toFixed(2) + '</span>';
                    tr += '<input type="hidden" id="ordered_quantity" value="' + parseFloat(e_ordered_quantity).toFixed(2) + '">';
                    tr += '<input type="hidden" id="delivered_quantity" value="' + parseFloat(e_delivered_quantity).toFixed(2) + '">';
                    tr += '<input type="hidden" id="left_quantity" value="' + parseFloat(e_left_quantity).toFixed(2) + '">';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<span id="span_quantity" class="fw-bold">' + parseFloat(e_quantity).toFixed(2) + '</span>';
                    tr += '<input type="hidden" name="quantities[]" id="quantity" value="' + parseFloat(e_quantity).toFixed(2) + '">';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<b><span id="span_unit">' + e_unit_name + '</span></b>';
                    tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="' + parseFloat(e_price_exc_tax).toFixed(2) + '">';
                    tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="' + parseFloat(e_price_inc_tax).toFixed(2) + '">';
                    tr += '<span id="span_unit_price_inc_tax" class="fw-bold">' + parseFloat(e_price_inc_tax).toFixed(2) + '</span>';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
                    tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '" tabindex="-1">';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                    tr += '</td>';
                    tr += '</tr>';

                    var index = getProductLastIndex(e_product_id, e_variant_id);
                    var __index = index > 0 ? index : 0;
                    // $('#sale_product_list').append(tr);
                    // $('#sale_product_list  tbody tr').eq(__index).after(tr);
                    var $tableRows = $('.sale-product-table tbody tr');
                    if ($tableRows.length > 0) {
                        $tableRows.eq(__index).after(tr);
                    } else {
                        $('.sale-product-table tbody').append(tr);
                    }

                    clearEditItemFileds();
                    calculateTotalAmount();
                    recalculateRunningLeftQty(e_product_id, e_variant_id);
                } else {

                    var tr = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).closest('tr');

                    tr.find('#product_name').val(e_product_name);
                    tr.find('#product_id').val(e_product_id);
                    tr.find('#variant_id').val(e_variant_id);
                    tr.find('#tax_ac_id').val(e_tax_ac_id);
                    tr.find('#tax_type').val(e_tax_type);
                    tr.find('#unit_tax_percent').val(parseFloat(e_tax_percent).toFixed(2));
                    tr.find('#unit_tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
                    tr.find('#unit_discount_type').val(e_discount_type);
                    tr.find('#unit_discount').val(parseFloat(e_discount).toFixed(2));
                    tr.find('#unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
                    tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                    tr.find('#ordered_quantity').val(parseFloat(e_ordered_quantity).toFixed(2));
                    tr.find('#span_ordered_quantity').html(parseFloat(e_ordered_quantity).toFixed(2));
                    tr.find('#left_quantity').val(parseFloat(e_left_quantity).toFixed(2));
                    tr.find('#span_left_quantity').html(parseFloat(e_left_quantity).toFixed(2));
                    tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
                    tr.find('#span_quantity').html(parseFloat(e_quantity).toFixed(2));
                    tr.find('#span_unit').html(e_unit_name);
                    tr.find('#unit_id').val(e_unit_id);
                    tr.find('#unit_price_exc_tax').val(parseFloat(e_price_exc_tax).toFixed(2));
                    tr.find('#unit_price_inc_tax').val(parseFloat(e_price_inc_tax).toFixed(2));
                    tr.find('#span_unit_price_inc_tax').html(parseFloat(e_price_inc_tax).toFixed(2));
                    tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
                    tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
                    tr.find('#is_show_emi_on_pos').val(e_is_show_emi_on_pos);
                    tr.find('#descriptions').val(e_descriptions);
                    tr.find('#stock_limit').val(data.stock);
                    tr.find('#stock_limit').data('unit_name', e_unit_name);
                    tr.find('.unique_id').val(e_product_id + e_variant_id + e_warehouse_id);
                    tr.find('.unique_id').attr('id', e_product_id + e_variant_id + e_warehouse_id);
                    tr.find('#warehouse_id').val(e_warehouse_id);
                    tr.find('#stock_location_name').html(stock_location_name);

                    clearEditItemFileds();
                    calculateTotalAmount();
                    recalculateRunningLeftQty(e_product_id, e_variant_id);
                }

                $('#add_item').html('Add');
            }
        })
    });

    $(document).on('click', '#select_product', function(e) {

        var tr = $(this);
        var sales_order_product_ids = tr.find('#sales_order_product_ids').val();
        var unique_id = tr.find('#unique_id').val();
        var warehouse_id = tr.find('#warehouse_id').val();
        var stock_location_name = tr.find('#stock_location_name').html();
        var product_name = tr.find('#product_name').val();
        var product_id = tr.find('#product_id').val();
        var variant_id = tr.find('#variant_id').val();
        var tax_ac_id = tr.find('#tax_ac_id').val();
        var tax_type = tr.find('#tax_type').val();
        var unit_tax_amount = tr.find('#unit_tax_amount').val();
        var unit_discount_type = tr.find('#unit_discount_type').val();
        var unit_discount = tr.find('#unit_discount').val();
        var unit_discount_amount = tr.find('#unit_discount_amount').val();
        var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
        var ordered_quantity = tr.find('#ordered_quantity').val();
        var delivered_quantity = tr.find('#delivered_quantity').val();
        var quantity = tr.find('#quantity').val();
        var unit_id = tr.find('#unit_id').val();
        var unit_price_exc_tax = tr.find('#unit_price_exc_tax').val();
        var unit_price_inc_tax = tr.find('#unit_price_inc_tax').val();
        var subtotal = tr.find('#subtotal').val();
        var is_show_emi_on_pos = tr.find('#is_show_emi_on_pos').val();
        var descriptions = tr.find('#descriptions').val();
        var current_stock = tr.find('#current_stock').val();

        $('#e_unit_id').empty();

        itemUnitsArray[product_id].forEach(function(unit) {

            $('#e_unit_id').append(
                '<option ' + (unit_id == unit.unit_id ? 'selected' : '') +
                ' value="' + unit.unit_id + '" data-is_base_unit="' + unit.is_base_unit +
                '" data-unit_name="' + unit.unit_name + '" data-base_unit_multiplier="' + unit
                .base_unit_multiplier + '">' + unit.unit_name + unit.multiplier_details +
                '</option>'
            );
        });

        if (is_show_emi_on_pos == 0) {

            $('#e_descriptions').prop('readonly', true);
        } else {

            $('#e_descriptions').prop('readonly', false);
        }

        $('#e_unique_id').val(unique_id);
        $('#sales_order_product_id').val(sales_order_product_ids).trigger('change');
        $('#e_warehouse_id').val(warehouse_id);
        $('#e_stock_location_name').val(stock_location_name);
        $('#e_product_name').val(product_name);
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id);
        $('#e_unit_id').val(unit_id);
        $('#e_ordered_quantity').val(parseFloat(ordered_quantity).toFixed(2));
        $('#e_delivered_quantity').val(parseFloat(delivered_quantity).toFixed(2));
        $('#e_current_quantity').val(parseFloat(quantity).toFixed(2));
        $('#e_quantity').val(parseFloat(quantity).toFixed(2)).focus().select();
        $('#e_price_exc_tax').val(parseFloat(unit_price_exc_tax).toFixed(2));
        $('#e_discount').val(parseFloat(unit_discount).toFixed(2));
        $('#e_discount_type').val(unit_discount_type);
        $('#e_discount_amount').val(parseFloat(unit_discount_amount).toFixed(2));
        $('#e_tax_ac_id').val(tax_ac_id);
        $('#e_tax_amount').val(parseFloat(unit_tax_amount).toFixed(2));
        $('#e_tax_type').val(tax_type);
        $('#e_price_inc_tax').val(parseFloat(unit_price_inc_tax).toFixed(2));
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
        $('#e_is_show_emi_on_pos').val(is_show_emi_on_pos);
        $('#e_descriptions').val(descriptions);
        $('#stock_quantity').val(parseFloat(current_stock).toFixed(2));

        calculateEditOrAddAmount();

        $('#add_item').html("{{ __('Update') }}");
    });

    function recalculateRunningLeftQty(productId, variantId) {

        var runningDeliverQty = 0;
        var totalDelivedQty = 0;
        var lastRunningDeliverQty = 0;
        var lastOrderedQty = 0;
        var lastTr = null;
        var totalTr = 0;
        $('#sale_product_list > tr').each(function(index, tr) {

            var rowProductId = $(tr).find('#product_id').val();
            var rowVariantId = $(tr).find('#variant_id').val();

            if (productId == rowProductId && variantId == rowVariantId) {

                var currentDeliverQty = $(tr).find('#quantity').val() ? $(tr).find('#quantity').val() : 0;
                var orderedQty = $(tr).find('#ordered_quantity').val() ? $(tr).find('#ordered_quantity').val() : 0;
                var deliveredQty = $(tr).find('#delivered_quantity').val() ? $(tr).find('#delivered_quantity').val() : 0;

                totalDelivedQty += parseFloat(currentDeliverQty);

                var runningLeftQty = (parseFloat(orderedQty) - parseFloat(deliveredQty)) - parseFloat(currentDeliverQty) - parseFloat(runningDeliverQty);
                lastRunningDeliverQty = (parseFloat(orderedQty) - parseFloat(deliveredQty)) - parseFloat(currentDeliverQty) - parseFloat(runningDeliverQty);
                lastOrderedQty = parseFloat(orderedQty);

                $(tr).find('#left_quantity').val(parseFloat(runningLeftQty).toFixed(2));
                // $(tr).find('#span_left_quantity').html(parseFloat(runningLeftQty).toFixed(2));
                $(tr).find('#span_ordered_quantity').html('<i class="fa-solid fa-down-long text-dark"></i>');
                // $(tr).find('#span_ordered_quantity').html('');
                $(tr).find('#span_left_quantity').html('<i class="fa-solid fa-down-long text-dark"></i>');
                // $(tr).find('#span_left_quantity').html('');
                runningDeliverQty += parseFloat(currentDeliverQty);

                lastTr = $(tr);
                totalTr += 1;
            }
        });

        var extra = totalTr > 1 ? '<span class="text-dark"> (Total Del. : ' + parseFloat(totalDelivedQty).toFixed(2) + ')</span>' : '';

        lastTr.find('#span_ordered_quantity').html(parseFloat(lastOrderedQty).toFixed(2));
        lastTr.find('#span_left_quantity').html(parseFloat(lastRunningDeliverQty).toFixed(2) + extra);
    }

    function getProductLastIndex(productId, variantId) {

        var lastIndex = 0;
        $('#sale_product_list > tr').each(function(index, tr) {

            var rowProductId = $(tr).find('#product_id').val();
            var rowVariantId = $(tr).find('#variant_id').val();

            if (productId == rowProductId && variantId == rowVariantId) {

                lastIndex = index;
            }
        });

        return lastIndex;
    }

    function calculateEditOrAddAmount() {

        var e_product_id = $('#e_product_id').val();
        var e_variant_id = $('#e_variant_id').val();
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0.00;
        var e_tax_type = $('#e_tax_type').val();
        var e_discount_type = $('#e_discount_type').val();
        var e_ordered_quantity = $('#e_ordered_quantity').val() ? $('#e_ordered_quantity').val() : 0;
        var e_delivered_quantity = $('#e_delivered_quantity').val() ? $('#e_delivered_quantity').val() : 0;
        var e_left_quantity = $('#e_left_quantity').val() ? $('#e_left_quantity').val() : 0;
        var e_current_quantity = $('#e_current_quantity').val() ? $('#e_current_quantity').val() : 0;
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_price_exc_tax = $('#e_price_exc_tax').val() ? $('#e_price_exc_tax').val() : 0;
        var e_unit_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;

        var quantity = 0;
        $('#sale_product_list > tr').each(function(index, tr) {

            var productId = $(tr).find('#product_id').val();
            var variantId = $(tr).find('#variant_id').val();

            if (productId == e_product_id && e_variant_id == variantId) {

                var deliverQty = $(tr).find('#quantity').val() ? $(tr).find('#quantity').val() : 0;
                quantity += parseFloat(deliverQty);
            }
        });

        // var leftQty = (parseFloat(e_ordered_quantity) - parseFloat(quantity) - parseFloat(e_quantity));
        var leftQty = ((parseFloat(e_ordered_quantity) - parseFloat(e_delivered_quantity)) - parseFloat(quantity) - parseFloat(e_quantity)) + parseFloat(e_current_quantity);
        $('#e_left_quantity').val(parseFloat(leftQty).toFixed(2));

        var discount_amount = 0;
        if (e_discount_type == 1) {

            discount_amount = e_unit_discount;
        } else {

            discount_amount = (parseFloat(e_price_exc_tax) / 100) * parseFloat(e_unit_discount);
        }

        var unitPriceWithDiscount = parseFloat(e_price_exc_tax) - parseFloat(discount_amount);
        var taxAmount = parseFloat(unitPriceWithDiscount) / 100 * parseFloat(e_tax_percent);
        var unitPriceIncTax = parseFloat(unitPriceWithDiscount) + parseFloat(taxAmount);

        if (e_tax_type == 2) {

            var inclusiveTax = 100 + parseFloat(e_tax_percent);
            var calcTax = parseFloat(unitPriceWithDiscount) / parseFloat(inclusiveTax) * 100;
            taxAmount = parseFloat(unitPriceWithDiscount) - parseFloat(calcTax);
            unitPriceIncTax = parseFloat(unitPriceWithDiscount) + parseFloat(taxAmount);
        }

        $('#e_tax_amount').val(parseFloat(taxAmount).toFixed(2));
        $('#e_discount_amount').val(parseFloat(parseFloat(discount_amount)).toFixed(2));
        $('#e_price_inc_tax').val(parseFloat(parseFloat(unitPriceIncTax)).toFixed(2));

        var subtotal = parseFloat(unitPriceIncTax) * parseFloat(e_quantity);
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
    }

    function calculateTotalAmount() {

        var quantities = document.querySelectorAll('#quantity');
        var subtotals = document.querySelectorAll('#subtotal');
        var unitTaxAmounts = document.querySelectorAll('#unit_tax_amount');
        // Update Total Item

        var total_item = 0;
        quantities.forEach(function(qty) {

            total_item += 1;
        });

        $('#total_item').val(parseFloat(total_item));

        // Update Net total Amount
        var netTotalAmount = 0;
        var productTotalTaxAmount = 0;
        var i = 0;
        subtotals.forEach(function(subtotal) {

            netTotalAmount += parseFloat(subtotal.value);
            productTotalTaxAmount += (quantities[i].value ? quantities[i].value : 0) * (unitTaxAmounts[i].value ? unitTaxAmounts[i].value : 0);
            i++;
        });
        $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

        var totalQty = 0;
        quantities.forEach(function(qty) {

            totalQty += parseFloat(qty.value);
        });

        $('#total_qty').val(parseFloat(totalQty).toFixed(2));

        if ($('#order_discount_type').val() == 2) {

            var orderDisAmount = parseFloat(netTotalAmount) / 100 * parseFloat($('#order_discount').val() ? $('#order_discount').val() : 0);
            $('#order_discount_amount').val(parseFloat(orderDisAmount).toFixed(2));
        } else {

            var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0;
            $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
        }

        var orderDiscountAmount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;
        // Calc order tax amount
        var orderTax = $('#sale_tax_ac_id').find('option:selected').data('order_tax_percent') ? $('#sale_tax_ac_id').find('option:selected').data('order_tax_percent') : 0;

        var calcOrderTaxAmount = (parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount)) / 100 * parseFloat(orderTax);
        $('#order_tax_amount').val(parseFloat(calcOrderTaxAmount).toFixed(2));

        // Update Total Invoice Amount
        var calcOrderTaxAmount = $('#order_tax_amount').val() ? $('#order_tax_amount').val() : 0;
        var shipmentCharge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;

        var calcInvoiceAmount = parseFloat(netTotalAmount) -
            parseFloat(orderDiscountAmount) +
            parseFloat(calcOrderTaxAmount) +
            parseFloat(shipmentCharge);

        $('#total_invoice_amount').val(parseFloat(calcInvoiceAmount).toFixed(2));

        var salesLedgerAmount = parseFloat(netTotalAmount) +
            parseFloat(shipmentCharge) -
            parseFloat(orderDiscountAmount) -
            parseFloat(productTotalTaxAmount);

        $('#sales_ledger_amount').val(salesLedgerAmount);

        var status = $('#status').val() ? $('#status').val() : 1;

        var closing_balance = $('#closing_balance').val() ? $('#closing_balance').val() : 0;
        var invoice_amount = status == 1 ? calcInvoiceAmount : 0;
        var received_amount = $('#received_amount').val() ? $('#received_amount').val() : 0;

        var accountDefaultBalanceType = $('#default_balance_type').val();
        var currentBalance = 0;
        if (accountDefaultBalanceType == 'dr') {

            currentBalance = parseFloat(closing_balance) + parseFloat(invoice_amount) - parseFloat(received_amount);
        } else {

            currentBalance = parseFloat(closing_balance) - parseFloat(invoice_amount) + parseFloat(received_amount);
        }

        $('#current_balance').val(parseFloat(currentBalance).toFixed(2));
    }

    function clearEditItemFileds() {

        $('#e_unique_id').val('');
        $('#e_product_name').val('');
        $('#e_product_id').val('');
        $('#e_variant_id').val('');
        $('#e_base_unit_name').val('');
        $('#e_ordered_quantity').val(parseFloat(0).toFixed(2));
        $('#e_delivered_quantity').val(parseFloat(0).toFixed(2));
        $('#e_left_quantity').val(parseFloat(0).toFixed(2));
        $('#e_quantity').val(parseFloat(0).toFixed(2));
        $('#e_current_quantity').val(parseFloat(0).toFixed(2));
        $('#e_price_exc_tax').val(parseFloat(0).toFixed(2));
        $('#e_discount_type').val(1);
        $('#e_discount').val(parseFloat(0).toFixed(2));
        $('#e_discount_amount').val(parseFloat(0).toFixed(2));
        $('#e_tax_ac_id').val('');
        $('#e_tax_amount').val(parseFloat(0).toFixed(2));
        $('#e_tax_type').val(1);
        $('#e_price_inc_tax').val(parseFloat(0).toFixed(2));
        $('#e_subtotal').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_inc_tax').val(0);
        $('#e_is_show_discription').val('');
        $('#stock_quantity').val(parseFloat(0).toFixed(2));
        $('#e_warehouse_id').val('');

        $("#sales_order_product_id").val('');
        $("#sales_order_product_id").select2("destroy");
        $("#sales_order_product_id").select2();
        $("#sales_order_product_id").focus();

        $('#add_item').html('Add');
    }

    $(document).on('click', '#remove_product_btn', function(e) {

        e.preventDefault();

        $(this).closest('tr').remove();

        calculateTotalAmount();
        recalculateRunningLeftQty(e_product_id, e_variant_id);

        setTimeout(function() {

            clearEditItemFileds();
        }, 5);
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.submit_button').prop('type', 'button');
    });

    $('#e_quantity').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '') {

                $('#e_unit_id').focus();
            }
        }
    });

    $('#e_unit_id').on('change keypress click', function(e) {

        if (e.which == 0) {

            $('#e_price_exc_tax').focus().select();
        }

        calculateEditOrAddAmount();
    });

    $('#e_price_exc_tax').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '') {

                $('#e_discount').focus().select();
            }
        }
    });

    $('#e_discount').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '' && $(this).val() > 0) {

                $('#e_discount_type').focus();
            } else {

                $('#e_tax_ac_id').focus();
            }
        }
    });

    $('#e_discount_type').on('change keypress click', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 0) {

            $('#e_tax_ac_id').focus();
        }
    });

    $('#e_tax_ac_id').on('change keypress click', function(e) {

        calculateEditOrAddAmount();
        var val = $(this).val();
        var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();
        var status = $('#status').val();

        if (e.which == 0) {

            if (val) {

                $('#e_tax_type').focus();
            } else {

                if (e_is_show_emi_on_pos == 1) {

                    $('#e_descriptions').focus().select();
                } else {

                    if (status == 1) {

                        var warehouse = $('#e_warehouse_id').val();
                        if (warehouse != undefined) {

                            $('#e_warehouse_id').focus();
                        } else {

                            $('#add_item').focus();
                        }
                    } else {

                        $('#add_item').focus();
                    }
                }
            }
        }
    });

    $('#e_tax_type').on('change keypress click', function(e) {

        calculateEditOrAddAmount();
        var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();
        var status = $('#status').val();

        if (e.which == 0) {

            if (e_is_show_emi_on_pos == 1) {

                $('#e_descriptions').focus().select();
            } else {

                if (status == 1 || status == '') {

                    var warehouse = $('#e_warehouse_id').val();
                    if (warehouse != undefined) {

                        $('#e_warehouse_id').focus();
                    } else {

                        $('#add_item').focus();
                    }
                } else {

                    $('#add_item').focus();
                }
            }
        }
    });

    $('#e_descriptions').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            var warehouse = $('#e_warehouse_id').val();
            if (warehouse != undefined) {

                $('#e_warehouse_id').focus();
            } else {

                $('#add_item').focus();
            }
        }
    });

    $('#e_warehouse_id').on('change keypress click', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 0) {

            $('#add_item').focus();
        }
    });

    $(document).on('input', '#received_amount', function() {

        calculateTotalAmount();
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.submit_button', function() {

        var value = $(this).val();
        $('#action').val(value);

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    document.onkeyup = function() {

        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.ctrlKey && e.which == 13) { // Ctrl + Enter

            $('#final_and_print').click();

            return false;
        } else if (e.shiftKey && e.which == 13) { // Shift + Enter

            if (status == 1) {

                $('#final').click();
            }

            return false;
        }
    }

    $('#add_sale_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var status = $('#status').val();

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.error').html('');
                $('.loading_button').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                if (!$.isEmptyObject(data.successMsg)) {

                    toastr.success(data.successMsg);
                    afterCreateSale();
                    return;
                } else {

                    toastr.success("{{ __('Invoice created Successfully.') }}");
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });

                    afterCreateSale();
                    return;
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }

                // toastr.error('Please check again all form fields.', 'Some thing went wrong.');
                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    function afterCreateSale() {

        @if (isset($order))

            setTimeout(function() {
                window.location = "{{ url()->previous() }}";
            }, 2000);
        @endif

        $('.loading_button').hide();
        $('.hidden').val(parseFloat(0).toFixed(2));
        $('#add_sale_form')[0].reset();
        $('#sale_product_list').empty();
    }


    $('#payment_method_id').on('change', function() {

        var account_id = $(this).find('option:selected').data('account_id');
        setMethodAccount(account_id);
    });

    $('#select_print_page_size').on('change', function() {
        var value = $(this).val();
        $('#print_page_size').val(value);
    });

    function setMethodAccount(account_id) {

        if (account_id) {

            $('#account_id').val(account_id);
        } else if (account_id === '') {

            // $('#account_id option:first-child').prop("selected", true);
            return;
        }
    }

    setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));

    var dateFormat = "{{ $generalSettings['business_or_shop__date_format'] }}";
    var _expectedDateFormat = '';
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
</script>

<script>
    var ul = document.getElementById('list');
    var selectObjClassName = 'selectProduct';
    $('#order_id').mousedown(function(e) {

        afterClickOrFocusSalesOrderId();
    }).focus(function(e) {

        ul = document.getElementById('list')
        selectObjClassName = 'selected_order';
    });

    function afterClickOrFocusSalesOrderId() {

        ul = document.getElementById('list')
        selectObjClassName = 'selected_order';
        $('#customer_name').val('');
        $('#customer_account_id').val('');
        $('#order_id').val('');
        $('#closing_balance').val(0.00);
        $('#sales_order_id').val('');
        $('#sale_product_list').empty();
        $('#sales_order_product_id').empty();
        $('.order_search_result').hide();
        $('#list').empty();
        calculateTotalAmount();
    }

    $(document).on('keyup', 'body', function(e) {

        if (e.keyCode == 13) {

            $('.' + selectObjClassName).click();
            $('.order_search_result').hide();
            $('.select_area').hide();
            $('#list').empty();
        }
    });

    $('#order_id').on('input', function() {

        $('.order_search_result').hide();

        var order_id = $(this).val();

        if (order_id === '') {

            $('.order_search_result').hide();
            $('#sales_order_id').val('');
            return;
        }

        var url = "{{ route('sale.orders.search', [':keyWord']) }}";
        var route = url.replace(':keyWord', order_id);

        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.noResult)) {

                    $('.order_search_result').hide();
                    $('#list').empty();
                } else {

                    $('.order_search_result').show();
                    $('#list').html(data);
                }
            }
        });
    });

    $(document).on('click', '#selected_order', function(e) {
        e.preventDefault();

        var order_id = $(this).html();
        var sales_order_id = $(this).data('sales_order_id');
        var customer_name = $(this).data('customer_name');
        var customer_account_id = $(this).data('customer_account_id');
        var closing_balance = $(this).data('closing_balance');
        var default_balance_type = $(this).data('default_balance_type');

        var url = "{{ route('sale.products.for.sales.order.to.invoice', [':salesOrderId']) }}";
        var route = url.replace(':salesOrderId', sales_order_id);

        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    $('#order_id').focus().select();
                    return;
                }

                itemUnitsArray = jQuery.parseJSON(data.units);

                $('#order_id').val(order_id.trim());
                $('#sales_order_id').val(sales_order_id);
                $('#customer_account_id').val(customer_account_id);
                $('#customer_name').val(customer_name);
                $('#closing_balance').val(closing_balance);
                $('#default_balance_type').val(default_balance_type);
                $('.order_search_result').hide();
                $('#sales_order_product_id').empty();

                $('#sales_order_product_id').html(data.view);
                $('#sales_order_product_id').focus();
                calculateTotalAmount();
            }
        });
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            if ($(this).attr('id') == 'account_id') {

                $('#final_and_print').focus().select();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var status = $('#status').val();
        var nextId = $(this).data('next');

        if (e.which == 13) {

            if ($(this).attr('id') == 'order_discount' && ($('#order_discount').val() == '' || $('#order_discount').val() == 0)) {
                $('#sale_tax_ac_id').focus();
                return;
            }

            if ($(this).attr('id') == 'shipment_charge') {

                $('#received_amount').focus().select();

                return;
            }

            if ($(this).attr('id') == 'receive_amount' && ($('#receive_amount').val() == '' || $('#receive_amount').val() == 0)) {

                $('#final_and_print').focus();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });
</script>

<script src="{{ asset('assets/plugins/custom/select_li/selectli.custom.js') }}"></script>
