<script>
    // Variant all functionality
    var variantsWithChild = @json($bulkVariants);
    var variant_row_index = 0;
    $(document).on('change', '#variants', function() {
        var id = $(this).val();
        var parentTableRow = $(this).closest('tr');
        variant_row_index = parentTableRow.index();

        $('.modal_variant_child').empty();

        var html = '';
        var variant = variantsWithChild.filter(function(variant) {
            return variant.id == id;
        });

        $.each(variant[0].bulk_variant_child, function(key, child) {
            html += '<li class="modal_variant_child_list">';
            html += '<a class="select_variant_child" data-child="' + child.name + '" href="#">' + child.name + '</a>';
            html += '</li>';
        });

        $('.modal_variant_child').html(html);
        $('#VairantChildModal').modal('show');
        $(this).val('');
    });

    $(document).on('click', '.select_variant_child', function(e) {
        e.preventDefault();

        var child = $(this).data('child');
        var parent_tr = $('.dynamic_variant_body tr:nth-child(' + (variant_row_index + 1) + ')');
        var child_value = parent_tr.find('#variant_combination').val();
        var filter = child_value == '' ? '' : ',';
        var variant_combination = parent_tr.find('#variant_combination').val(child_value + filter + child);
        $('#VairantChildModal').modal('hide');
    });

    $(document).on('input', '#variant_cost_exc_tax', function() {

        var parentTr = $(this).closest('tr');
        calculateVariantAmount(parentTr);
        calculateVariantAllUnitsCostAndPrice(parentTr);
    });

    $(document).on('input', '#variant_profit', function() {

        var parentTr = $(this).closest('tr');
        calculateVariantAmount(parentTr);
        calculateVariantAllUnitsCostAndPrice(parentTr, false, true);
    });

    $(document).on('input', '#variant_price_exc_tax', function() {

        var parentTr = $(this).closest('tr');
        var varinatPriceExcTax = $(this).val() ? $(this).val() : 0;
        var variantCostExcTax = parentTr.find('#variant_cost_exc_tax').val() ? $('#variant_cost_exc_tax').val() : 0;
        var profitAmount = parseFloat(varinatPriceExcTax) - parseFloat(variantCostExcTax);
        var __cost = parseFloat(variantCostExcTax) > 0 ? parseFloat(variantCostExcTax) : parseFloat(profitAmount);
        var profitMargin = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __profitMargin = parseFloat(profitMargin) ? parseFloat(profitMargin) : 0;
        parentTr.find('#variant_profit').val(parseFloat(__profitMargin).toFixed(2));

        calculateVariantAllUnitsCostAndPrice(parentTr, false, true);
    });

    function calculateVariantAmount(parentTr) {

        var taxPercent = $('#tax_ac_id').find('option:selected').data('tax_percent');
        var taxType = $('#tax_type').val() ? $('#tax_type').val() : 1;
        var variantCostExcTax = parentTr.find('#variant_cost_exc_tax').val() ? parentTr.find('#variant_cost_exc_tax').val() : 0;
        var variantProfit = parentTr.find('#variant_profit').val() ? parentTr.find('#variant_profit').val() : 0;
        var variantPriceExcTax = parentTr.find('#variant_price_exc_tax');

        var taxAmount = parseFloat(variantCostExcTax) / 100 * parseFloat(taxPercent);
        if (taxType == 2) {

            var inclusiveTaxPercent = 100 + parseFloat(taxPercent);
            var inclusiveTax = (parseFloat(variantCostExcTax) / parseFloat(inclusiveTaxPercent)) * 100;
            taxAmount = parseFloat(variantCostExcTax) - parseFloat(inclusiveTax);
        }
        var variantCostIncTax = parseFloat(variantCostExcTax) + parseFloat(taxAmount);
        parentTr.find('#variant_cost_inc_tax').val(parseFloat(variantCostIncTax).toFixed(2));

        var priceExcTax = parseFloat(variantCostExcTax) / 100 * parseFloat(variantProfit) + parseFloat(variantCostExcTax);
        parentTr.find('#variant_price_exc_tax').val(parseFloat(priceExcTax).toFixed(2));
    }

    function calculateVariantAllUnitsCostAndPrice(tr, isAutoCalculateUnitCost = true, isAutoCalculatePrice = true) {

        var currentTr = tr;
        var variantCostExcTax = currentTr.find('#variant_cost_exc_tax').val() ? currentTr.find('#variant_cost_exc_tax').val() : 0;
        var variantCostIncTax = currentTr.find('#variant_cost_inc_tax').val() ? currentTr.find('#variant_cost_inc_tax').val() : 0;
        var variantPriceExcTax = currentTr.find('#variant_price_exc_tax').val() ? currentTr.find('#variant_price_exc_tax').val() : 0;
        var nextTr = tr.next();
        if (nextTr.attr('id') == 'set_variant_multiple_units') {

            var unitsTable = nextTr.find('table');

            if (unitsTable != undefined) {

                unitsTable.find('tbody').find('tr').each(function(index) {

                    var baseUnitMultiplier = $(this).find('#variant_base_unit_multiplier').val() ? $(this).find('#variant_base_unit_multiplier').val() : 0;

                    if (isAutoCalculateUnitCost == true) {

                        var variantUnitCostExcTax = parseFloat(baseUnitMultiplier) * parseFloat(variantCostExcTax);
                        var variantUnitCostIncTax = parseFloat(baseUnitMultiplier) * parseFloat(variantCostIncTax);
                        $(this).find('#variant_assigned_unit_cost_exc_tax').val(parseFloat(variantUnitCostExcTax).toFixed(2));
                        $(this).find('#variant_assigned_unit_cost_inc_tax').val(parseFloat(variantUnitCostIncTax).toFixed(2));
                    }

                    if (isAutoCalculatePrice == true) {

                        var variantUnitPriceExcTax = parseFloat(baseUnitMultiplier) * parseFloat(variantPriceExcTax);
                        $(this).find('#variant_assigned_unit_price_exc_tax').val(parseFloat(variantUnitPriceExcTax).toFixed(2));
                    }
                });
            }
        }
    }

    function calculateVariantUnitCostAndPrice(tr) {

        var taxPercent = $('#tax_ac_id').find('option:selected').data('tax_percent') ? $('#tax_ac_id').find('option:selected').data('tax_percent') : 0;
        var taxType = $('#tax_type').val() ? $('#tax_type').val() : 1;

        var variantAssignedUnitCostExcTax = tr.find('#variant_assigned_unit_cost_exc_tax').val() ? tr.find('#variant_assigned_unit_cost_exc_tax').val() : 0;

        var taxAmount = (parseFloat(variantAssignedUnitCostExcTax) / 100) * parseFloat(taxPercent);
        if (taxType == 2) {

            var inclusiveTaxPercent = 100 + parseFloat(taxPercent);
            var inclusiveTax = (parseFloat(variantAssignedUnitCostExcTax) / parseFloat(inclusiveTaxPercent)) * 100;
            taxAmount = parseFloat(variantAssignedUnitCostExcTax) - parseFloat(inclusiveTax);
        }

        var variantAssignedUnitCostIncTax = parseFloat(variantAssignedUnitCostExcTax) + parseFloat(taxAmount);
        tr.find('#variant_assigned_unit_cost_inc_tax').val(parseFloat(variantAssignedUnitCostIncTax).toFixed(2));
    }

    var indexNumber = parseInt("{{ count($product->variants) }}");
    $(document).on('click', '#add_more_variant_btn', function(e) {
        e.preventDefault();

        var has_multiple_unit = $('#has_multiple_unit').val();
        var productCostExcTax = $('#product_cost').val();
        var productCostIncTax = $('#product_cost_with_tax').val();
        var profit = $('#profit').val();
        var productPriceIncTax = $('#product_price').val();
        var html = '';
        html += '<tr id="more_new_variant">';
        html += '<td>';
        html += '<select class="form-control" name="" id="variants">';
        html += '<option value="">' + "{{ __('Create Combination') }}" + '</option>';

        $.each(variantsWithChild, function(key, val) {

            html += '<option value="' + val.id + '">' + val.name + '</option>';
        });

        html += '</select>';
        html += '<input required type="text" name="variant_combinations[]" id="variant_combination" class="form-control fw-bold" placeholder="' + "{{ __('Variant Combination') }}" + '">';
        html += '<input type="hidden" name="product_variant_ids[]">';
        html += '</td>';
        html += '<td><input type="text" name="variant_codes[]" id="variant_code" class="form-control new_variant_code fw-bold" placeholder="' + "{{ __('Variant Code') }}" + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_costs_exc_tax[]" class="form-control fw-bold" id="variant_cost_exc_tax" value="' + parseFloat(productCostExcTax).toFixed(2) + '" placeholder="' + "{{ __('Variant Cost Exc. Tax') }}" + '">';
        html += '</td>';
        html += '<td>';
        html += '<input readonly required type="number" step="any" name="variant_costs_inc_tax[]" class="form-control fw-bold" id="variant_cost_inc_tax" value="' + parseFloat(productCostIncTax).toFixed(2) + '" placeholder="' + "{{ __('Variant Cost Inc. Tax') }}" + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_profits[]" class="form-control fw-bold" id="variant_profit"  value="' + parseFloat(profit).toFixed(2) + '" placeholder="' + "{{ __('Variant Profit Margin') }}" + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_prices_exc_tax[]" class="form-control fw-bold" id="variant_price_exc_tax"  value="' + parseFloat(productPriceIncTax).toFixed(2) + '" placeholder="' + "{{ __('Variant Price Exc. Tax') }}" + '">';
        html += '</td>';
        html += '<td>';
        html += '<input type="file" name="variant_image[]" class="form-control" id="variant_image">';
        html += '</td>';
        html += '<td><a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a></td>';
        html += '</tr>';

        if (has_multiple_unit == 1) {

            html += '<tr id="set_variant_multiple_units" class="set_variant_multiple_units">';
            html += '<td colspan="8">';
            html += '<table class="table modal-table table-sm" id="set_variant_multiple_unit_table">';
            html += '<tr>';
            html += '<th>{{ __("Unit") }}</th>';
            html += '<th>{{ __("Unit Cost Exc. Tax") }}</th>';
            html += '<th>{{ __("Unit Cost Inc. Tax") }}</th>';
            html += '<th>{{ __("Unit Price Exc. Tax") }}</th>';
            html += '</tr>';

            $('#multiple_unit_body').find('tr').each(function(index) {

                var baseUnitName = $(this).find('#span_base_unit_name').html();
                var baseUnitId = $(this).find('#base_unit_id').val();
                var assignedUnitQuantity = $(this).find('#assigned_unit_quantity').val();
                var baseUnitMultiplier = $(this).find('#base_unit_multiplier').val();
                var assignedUnitId = $(this).find('.assigned_unit_id').val();
                var assignedUnitName = $(this).find('.assigned_unit_id').find('option:selected').data('assigned_unit_name');
                var assignedUnitCostExcTax = $(this).find('#assigned_unit_cost_exc_tax').val();
                var assignedUnitCostIncTax = $(this).find('#assigned_unit_cost_inc_tax').val();
                var assignedUnitPriceExcTax = $(this).find('#assigned_unit_price_exc_tax').val();

                if (assignedUnitId) {

                    html += '<tr id="unit_table_row">';
                    html += '<td><span class="fw-bold base_unit_name">' + assignedUnitName + '</span>';
                    html += '<input type="hidden" name="variant_base_unit_ids[' + indexNumber + '][]" id="variant_base_unit_id" value="' + baseUnitId + '">';
                    html += '<input type="hidden" name="variant_assigned_unit_quantities[' + indexNumber + '][]" id="variant_assigned_unit_qunatity" value="' + parseFloat(assignedUnitQuantity) + '">';
                    html += '<input type="hidden" name="variant_base_unit_multipliers[' + indexNumber + '][]" id="variant_base_unit_multiplier" value="' + parseFloat(baseUnitMultiplier) + '">';
                    html += '<input type="hidden" name="variant_assigned_unit_ids[' + indexNumber + '][]" id="variant_assigned_unit_id" value="' + assignedUnitId + '">';
                    html += '<input type="hidden" name="product_variant_unit_ids[' + indexNumber + '][]">';
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="number" step="any" name="variant_assigned_unit_costs_exc_tax[' + indexNumber + '][]" class="form-control fw-bold" id="variant_assigned_unit_cost_exc_tax" value="' + parseFloat(assignedUnitCostExcTax).toFixed(2) + '" placeholder="{{ __("Unit Cost Exc. Tax") }}">';
                    html += '</td>';
                    html += '<td>';
                    html += '<input readonly type="number" step="any" name="variant_assigned_unit_costs_inc_tax[' + indexNumber + '][]" class="form-control fw-bold" id="variant_assigned_unit_cost_inc_tax" value="' + parseFloat(assignedUnitCostIncTax).toFixed(2) + '" placeholder="{{ __("Unit Cost Inc. Tax") }}">';
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="number" step="any" name="variant_assigned_unit_prices_exc_tax[' + indexNumber + '][]" class="form-control fw-bold" id="variant_assigned_unit_price_exc_tax" value="' + parseFloat(assignedUnitPriceExcTax).toFixed(2) + '" placeholder="{{ __("Unit Price Exc. Tax") }}">';
                    html += '</td>';
                    html += '</tr>';
                }
            });

            html += '</table>';
            html += '</td>';
            html += '</tr>';
        }else {

            html += '<tr id="set_variant_multiple_units" class="set_variant_multiple_units"></tr>';
        }

        $('.dynamic_variant_body').prepend(html);

        regenerateVariantCode();
    });

    // Variant all functionality end
    function regenerateVariantCode() {

        var old_variant_code = document.querySelectorAll('.old_variant_code');
        var oldVariantCode = Array.from(old_variant_code);
        var oldVariantCodeLength = oldVariantCode.length;

        var allVariantSerial = [];
        old_variant_code.forEach(function(sl) {

            var val = sl.value;
            var splitVal = val.split("-");

            if (splitVal[1] != undefined) {

                allVariantSerial.push(splitVal[1]);
            }
        });

        var maxSerial = Math.max(0, ...allVariantSerial);

        var code = $('#code').val();
        var current_product_code = $('#current_product_code').val();

        var newVariantCodes = document.querySelectorAll('.new_variant_code');
        var newVariantCodesArray = Array.from(newVariantCodes);
        var reversed = newVariantCodesArray.reverse();

        // var length = variantCodesArray.length;
        var length = newVariantCodesArray.length;
        var i = length;
        for (var index = length - 1; index >= 0; index--) {

            var variant_code = code ? code + '-' + (i + maxSerial) : current_product_code + '-' + (i + maxSerial);
            reversed[index].value = variant_code;
            i--;
        }
    }

    // Romove variant table row
    $(document).on('click', '#variant_remove_btn', function(e) {

        e.preventDefault();
        var tr = $(this).closest('tr');
        var nextTr = tr.next();

        if (tr.next().attr('id') == 'set_variant_multiple_units') {

            nextTr.remove();
        }

        tr.remove();
        regenerateVariantCode();
    });
</script>
