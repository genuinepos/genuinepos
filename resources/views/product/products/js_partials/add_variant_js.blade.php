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
            html += '<a href="#" class="select_variant_child" data-child="' + child.name + '">' + child.name + '</a>';
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

    $(document).on('input', '#product_price', function() {


    });

    $(document).on('input', '#variant_assigned_unit_cost_exc_tax', function() {

        var parentTr = $(this).closest('tr');
        calculateVariantUnitCostAndPrice(parentTr);
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

    var variant_code_sequel = 0;
    // Select Variant and show variant creation area
    $(document).on('change', '#is_variant', function() {

        var product_cost = $('#product_cost').val();
        var has_multiple_unit = $('#has_multiple_unit').val();
        var product_cost_with_tax = $('#product_cost_with_tax').val();
        var profit = $('#profit').val();
        var product_price = $('#product_price').val();

        if ($(this).val() == 1 && (product_cost == '' || product_price == '')) {

            $(this).val(0);
            alert("{{ __('After creating the variant, product cost and product price field must not be empty.') }}");
            return;
        }

        if (has_multiple_unit == 1) {

            var assignedUnitIds = document.querySelectorAll('.assigned_unit_id');
            var length = assignedUnitIds.length;
            var lastIndex = length - 1;

            lastBaseUnitId = $(assignedUnitIds[lastIndex]).val();
            lastAssignedUnitQuantity = $(assignedUnitIds[lastIndex]).val();
            if (length > 0 && lastBaseUnitId == '') {

                toastr.error("{{ __('To unit is not assigned in the last conversion.') }}", "{{ __('Set Multiple Unit') }}");
                $(this).val(0);
                return;
            }

            var baseUnitQuantities = document.querySelectorAll('#assigned_unit_quantity');
            lastAssignedUnitQuantity = $(baseUnitQuantities[lastIndex]).val();

            if (length > 0 && (lastAssignedUnitQuantity == '' || lastAssignedUnitQuantity == 0)) {

                var msg = lastAssignedUnitQuantity == '' ? "{{ __('Quantity is empty in the last conversion.') }}" : "{{ __('Quantity is 0.00 in the last conversion.') }}";
                toastr.error(msg, "{{ __('Set Multiple Unit') }}");
                $(this).val(0);
                return;
            }
        }

        var code = $('#code').val();
        var auto_generated_code = $('#auto_generated_code').val();
        var variant_code = code ? code + '-' + (++variant_code_sequel) : auto_generated_code + '-' + (++variant_code_sequel);

        $('#variant_code').val(variant_code);
        $('#variant_cost_exc_tax').val(parseFloat(product_cost).toFixed(2));
        $('#variant_cost_inc_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
        $('#variant_price_exc_tax').val(parseFloat(product_price).toFixed(2));
        $('#variant_profit').val(parseFloat(profit).toFixed(2));

        if ($(this).val() == 1) {

            $('.dynamic_variant_create_area').show(500);
            $('#variant_combination').prop('required', true);
            $('#variant_cost_exc_tax').prop('required', true);
            $('#variant_cost_inc_tax').prop('required', true);
            $('#variant_profit').prop('required', true);
            $('#variant_price_exc_tax').prop('required', true);

            var variantRows = document.querySelectorAll('#variant_row');
            var variantRowLength = variantRows.length;

            var firstTr = variantRows[variantRowLength - 1];
            var firstTrIndexNumber = $(firstTr).find('#index_number').val();

            if (has_multiple_unit == 1 && variantRowLength == 1) {

                var html = '';
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

                    html += '<tr id="unit_table_row">';
                    html += '<td><span class="fw-bold base_unit_name">' + assignedUnitName + '</span>';
                    html += '<input type="hidden" name="variant_base_unit_ids[' + firstTrIndexNumber + '][]" id="variant_base_unit_id" value="' + baseUnitId + '">';
                    html += '<input type="hidden" name="variant_assigned_unit_quantities[' + firstTrIndexNumber + '][]" id="variant_assigned_unit_qunatity" value="' + parseFloat(assignedUnitQuantity) + '">';
                    html += '<input type="hidden" name="variant_base_unit_multipliers[' + firstTrIndexNumber + '][]" id="variant_base_unit_multiplier" value="' + parseFloat(baseUnitMultiplier) + '">';
                    html += '<input type="hidden" name="variant_assigned_unit_ids[' + firstTrIndexNumber + '][]" id="variant_assigned_unit_id" value="' + assignedUnitId + '">';
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="number" step="any" name="variant_assigned_unit_costs_exc_tax[' + firstTrIndexNumber + '][]" class="form-control fw-bold" id="variant_assigned_unit_cost_exc_tax" value="'+parseFloat(assignedUnitCostExcTax).toFixed(2)+'" placeholder="{{ __("Unit Cost Exc. Tax") }}">';
                    html += '</td>';
                    html += '<td>';
                    html += '<input readonly type="number" step="any" name="variant_assigned_unit_costs_inc_tax[' + firstTrIndexNumber + '][]" class="form-control fw-bold" id="variant_assigned_unit_cost_inc_tax" value="'+parseFloat(assignedUnitCostIncTax).toFixed(2)+'" placeholder="{{ __("Unit Cost Inc. Tax") }}">';
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="number" step="any" name="variant_assigned_unit_prices_exc_tax[' + firstTrIndexNumber + '][]" class="form-control fw-bold" id="variant_assigned_unit_price_exc_tax" value="'+parseFloat(assignedUnitPriceExcTax).toFixed(2)+'" placeholder="{{ __("Unit Price Exc. Tax") }}">';
                    html += '</td>';
                    html += '</tr>';
                });

                html += '</table>';
                html += '</td>';

                $('#set_variant_multiple_units').html(html);
            }
        } else {

            $('.dynamic_variant_create_area').hide(500);
            $('#variant_combination').prop('required', false);
            $('#variant_costing').prop('required', false);
            $('#variant_costing_with_tax').prop('required', false);
            $('#variant_profit').prop('required', false);
            $('#variant_price_exc_tax').prop('required', false);
        }
    });

    var indexNumber = 1;
    $(document).on('click', '#add_more_variant_btn', function(e) {
        e.preventDefault();

        // var variant_code = code ? code + '-' + (++variant_code_sequel) : auto_generated_code + '-' + (++variant_code_sequel);

        var has_multiple_unit = $('#has_multiple_unit').val();
        var productCostExcTax = $('#product_cost').val();
        var productCostIncTax = $('#product_cost_with_tax').val();
        var profit = $('#profit').val();
        var productPriceExcTax = $('#product_price').val();

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
        html += '<input type="hidden" name="index_numbers[]" id="index_number" value="' + indexNumber + '">';
        html += '</td>';
        html += '<td><input type="text" name="variant_codes[]" id="variant_code" class="form-control fw-bold" placeholder="' + "{{ __('Variant Code') }}" + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_costs_exc_tax[]" class="form-control fw-bold" id="variant_cost_exc_tax" value="' + parseFloat(productCostExcTax).toFixed(2) + '" placeholder="' + "{{ __('Cost Exc. Tax') }}" + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_costs_inc_tax[]" class="form-control fw-bold" id="variant_cost_inc_tax" value="' + parseFloat(productCostIncTax).toFixed(2) + '" placeholder="' + "{{ __('Cost Inc. Tax') }}" + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_profits[]" class="form-control fw-bold" id="variant_profit" value="' + parseFloat(profit).toFixed(2) + '" placeholder="' + "{{ __('Profit') }}" + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_prices_exc_tax[]" class="form-control fw-bold" id="variant_price_exc_tax" value="' + parseFloat(productPriceExcTax).toFixed(2) + '" placeholder="' + "{{ __('Price Inc. Tax') }}" + '">';
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
        indexNumber++;

        regenerateVariantCode();
    });

    function regenerateVariantCode() {

        var code = $('#code').val();
        var auto_generated_code = $('#auto_generated_code').val();

        var variantCodes = document.querySelectorAll('input[name="variant_codes[]"]');
        var variantCodesArray = Array.from(variantCodes);
        var reversed = variantCodesArray.reverse();

        var length = variantCodesArray.length;
        var i = length;
        for (var index = length - 1; index >= 0; index--) {

            var variant_code = code ? code + '-' + (i) : auto_generated_code + '-' + (i);
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

    function indexNumberFromStart() {
        indexNumber = 1;
    }
    indexNumberFromStart();
</script>
