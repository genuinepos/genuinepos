<script src="{{ asset('assets/plugins/custom/select_li/selectli.custom.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $('.select2').select2();

    @if (isset($order))
        var itemUnitsArray = @json($itemUnitsArray);
    @else
        var itemUnitsArray = [];
    @endif

    function calculateTotalAmount() {

        var quantities = document.querySelectorAll('#quantity');
        var linetotals = document.querySelectorAll('#linetotal');
        var unitTaxAmounts = document.querySelectorAll('#unit_tax_amount');

        var total_item = 0;
        var total_qty = 0;
        quantities.forEach(function(qty) {
            total_item += 1;
            total_qty += parseFloat(qty.value)
        });

        $('#total_qty').val(parseFloat(total_qty));
        $('#total_item').val(parseFloat(total_item));

        //Update Net Total Amount
        var netTotalAmount = 0;
        var productTotalTaxAmount = 0;
        var i = 0;
        linetotals.forEach(function(linetotal) {

            netTotalAmount += parseFloat(linetotal.value);
            productTotalTaxAmount += (quantities[i].value ? quantities[i].value : 0) * (unitTaxAmounts[i].value ? unitTaxAmounts[i].value : 0);
            i++;
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
        var netTotalWithDiscount = parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount);

        var purchaseTaxPercent = $('#purchase_tax_ac_id').find('option:selected').data('purchase_tax_percent') ?
            $('#purchase_tax_ac_id').find('option:selected').data('purchase_tax_percent') :
            0;
        var purchaseTaxAmount = parseFloat(netTotalWithDiscount) / 100 * parseFloat(purchaseTaxPercent);

        $('#purchase_tax_amount').val(parseFloat(purchaseTaxAmount).toFixed(2));

        var shipmentCharge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;

        var calcTotalPurchaseAmount = parseFloat(netTotalAmount) -
            parseFloat(orderDiscountAmount) +
            parseFloat(purchaseTaxAmount) +
            parseFloat(shipmentCharge);

        $('#total_purchase_amount').val(parseFloat(calcTotalPurchaseAmount).toFixed(2));

        var purchaseLedgerAmount = parseFloat(netTotalAmount) +
            parseFloat(shipmentCharge) -
            parseFloat(orderDiscountAmount) -
            parseFloat(productTotalTaxAmount);

        $('#purchase_ledger_amount').val(purchaseLedgerAmount);

        var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
        var closingBalance = $('#closing_balance').val() ? $('#closing_balance').val() : 0;
        var accountDefaultBalanceType = $('default_balance_type').val();
        var currentBalance = 0;
        if (accountDefaultBalanceType == 'dr') {

            currentBalance = parseFloat(closingBalance) - parseFloat(calcTotalPurchaseAmount) + parseFloat(payingAmount);
        } else {

            currentBalance = parseFloat(closingBalance) + parseFloat(calcTotalPurchaseAmount) - parseFloat(payingAmount);
        }

        $('#current_balance').val(parseFloat(currentBalance).toFixed(2));
    }
    calculateTotalAmount();

    function calculateEditOrAddAmount() {

        var e_ordered_quantity = $('#e_ordered_quantity').val() ? $('#e_ordered_quantity').val() : 0;
        var e_received_quantity = $('#e_received_quantity').val() ? $('#e_received_quantity').val() : 0;
        var e_pending_quantity = $('#e_pending_quantity').val() ? $('#e_pending_quantity').val() : 0;
        var e_current_quantity = $('#e_current_quantity').val() ? $('#e_current_quantity').val() : 0;

        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_unit_cost_exc_tax = $('#e_unit_cost_exc_tax').val() ? $('#e_unit_cost_exc_tax').val() : 0;
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
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

        // var pendingQty = ((parseFloat(e_ordered_quantity) - parseFloat(e_received_quantity)) - parseFloat(e_quantity)) + parseFloat(e_current_quantity);
        var pendingQty = ((parseFloat(e_ordered_quantity) - parseFloat(e_received_quantity)) - parseFloat(e_quantity));
        $('#e_pending_quantity').val(parseFloat(pendingQty).toFixed(2));
    }

    $('#add_product').on('click', function(e) {
        e.preventDefault();

        var e_unique_id = $('#e_unique_id').val();
        var e_has_batch_no_expire_date = $('#e_has_batch_no_expire_date').val();
        var e_product_name = $('#e_product_name').val();
        var e_product_id = $('#e_product_id').val();
        var e_variant_id = $('#e_variant_id').val();
        var e_ordered_quantity = $('#e_ordered_quantity').val() ? $('#e_ordered_quantity').val() : 0;
        var e_pending_quantity = $('#e_pending_quantity').val() ? $('#e_pending_quantity').val() : 0;
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_unit_id = $('#e_unit_id').val();
        var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
        var e_unit_cost_exc_tax = $('#e_unit_cost_exc_tax').val() ? $('#e_unit_cost_exc_tax').val() : 0;
        var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;
        var e_discount_type = $('#e_discount_type').val();
        var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
        var e_tax_ac_id = $('#e_tax_ac_id').val();
        var e_tax_type = $('#e_tax_type').val();
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
        var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
        var e_unit_cost_with_discount = $('#e_unit_cost_with_discount').val() ? $('#e_unit_cost_with_discount').val() : 0;
        var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
        var e_linetotal = $('#e_linetotal').val() ? $('#e_linetotal').val() : 0;
        var e_profit_margin = $('#e_profit_margin').val() ? $('#e_profit_margin').val() : 0;
        var e_selling_price = $('#e_selling_price').val() ? $('#e_selling_price').val() : 0;
        var e_batch_number = e_has_batch_no_expire_date == 1 ? $('#e_batch_number').val() : '';
        var e_expire_date = e_has_batch_no_expire_date == 1 ? $('#e_expire_date').val() : '';
        var e_lot_number = $('#e_lot_number').val();
        var e_description = $('#e_description').val();

        if (parseFloat(e_pending_quantity) < 0) {

            toastr.error("{{ __('Receive Quantity must not be greater then pending quantity.') }}");
            return;
        }

        if (e_product_id == '') {

            toastr.error("{{ __('Please select a item.') }}");
            return;
        }

        if (e_quantity == '') {

            toastr.error("{{ __('Quantity field must not be empty.') }}");
            return;
        }

        var uniqueId = e_product_id + e_variant_id;

        var tr = $('#' + uniqueId).closest('tr');
        tr.find('#product_name').val(e_product_name);
        tr.find('#span_product_name').html(e_product_name);
        tr.find('#product_id').val(e_product_id);
        tr.find('#variant_id').val(e_variant_id);
        tr.find('#description').val(e_description);
        tr.find('#batch_number').val(e_batch_number);
        tr.find('#expire_date').val(e_expire_date);
        tr.find('#span_batch_expire_date').html(e_has_batch_no_expire_date == 1 ? e_batch_number + ' | ' + e_expire_date : 'N/a');
        tr.find('#lot_number').val(e_lot_number);
        tr.find('#span_lot_number').html(e_lot_number);
        tr.find('#ordered_quantity').val(parseFloat(e_ordered_quantity).toFixed(2));
        tr.find('#span_ordered_quantity_unit').html(parseFloat(e_ordered_quantity).toFixed(2) + '/' + e_unit_name);
        tr.find('#pending_quantity').val(parseFloat(e_pending_quantity).toFixed(2));
        tr.find('#span_pending_quantity_unit').html(parseFloat(e_pending_quantity).toFixed(2) + '/' + e_unit_name);
        tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
        tr.find('#unit_id').val(e_unit_id);
        tr.find('#span_quantity_unit').html(parseFloat(e_quantity).toFixed(2) + '/' + e_unit_name);
        tr.find('#unit_cost_exc_tax').val(parseFloat(e_unit_cost_exc_tax).toFixed(2));
        tr.find('#span_unit_cost_exc_tax').html(parseFloat(e_unit_cost_exc_tax).toFixed(2));
        tr.find('#unit_discount_type').val(e_discount_type);
        tr.find('#unit_discount').val(parseFloat(e_discount).toFixed(2));
        tr.find('#unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
        tr.find('#span_discount_amount').html(parseFloat(e_discount_amount).toFixed(2));
        tr.find('#unit_cost_with_discount').val(parseFloat(e_unit_cost_with_discount).toFixed(2));
        tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
        tr.find('#tax_ac_id').val(e_tax_ac_id);
        tr.find('#tax_type').val(e_tax_type);
        tr.find('#span_tax_percent').html(parseFloat(e_tax_percent).toFixed(2) + '%');
        tr.find('#unit_tax_percent').val(parseFloat(e_tax_percent).toFixed(2));
        tr.find('#unit_tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
        tr.find('#span_unit_cost_inc_tax').html(parseFloat(e_unit_cost_inc_tax).toFixed(2));
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
    });

    $(document).on('click', '#select_product', function(e) {

        var tr = $(this);
        var product_name = tr.find('#product_name').val();
        var description = tr.find('#description').val();
        var lot_number = tr.find('#lot_number').val();
        var batch_number = tr.find('#batch_number').val();
        var expire_date = tr.find('#expire_date').val();
        var has_batch_no_expire_date = tr.find('#has_batch_no_expire_date').val();
        var product_id = tr.find('#product_id').val();
        var variant_id = tr.find('#variant_id').val();
        var ordered_quantity = tr.find('#ordered_quantity').val();
        var received_quantity = tr.find('#received_quantity').val();
        var pending_quantity = tr.find('#pending_quantity').val();
        var quantity = tr.find('#quantity').val();
        var unit_id = tr.find('#unit_id').val();
        var unit_cost_exc_tax = tr.find('#unit_cost_exc_tax').val();
        var unit_discount_type = tr.find('#unit_discount_type').val();
        var unit_discount = tr.find('#unit_discount').val();
        var unit_discount_amount = tr.find('#unit_discount_amount').val();
        var unit_cost_with_discount = tr.find('#unit_cost_with_discount').val();
        var subtotal = tr.find('#subtotal').val();
        var tax_ac_id = tr.find('#tax_ac_id').val();
        var tax_type = tr.find('#tax_type').val();
        var unit_tax_amount = tr.find('#unit_tax_amount').val();
        var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
        var linetotal = tr.find('#linetotal').val();
        var profit = tr.find('#profit').val();
        var selling_price = tr.find('#selling_price').val();

        $('#e_unit_id').empty();

        itemUnitsArray[product_id].forEach(function(unit) {

            $('#e_unit_id').append('<option ' + (unit_id == unit.unit_id ? 'selected' : '') + ' value="' + unit.unit_id + '" data-is_base_unit="' + unit.is_base_unit + '" data-unit_name="' + unit.unit_name + '" data-base_unit_multiplier="' + unit.base_unit_multiplier + '">' + unit.unit_name + unit.multiplier_details + '</option>');
        });

        $('#product_name').val(product_name);
        $('#e_product_name').val(product_name);
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id);
        $('#e_ordered_quantity').val(parseFloat(ordered_quantity).toFixed(2));
        $('#e_received_quantity').val(parseFloat(received_quantity).toFixed(2));
        $('#e_pending_quantity').val(parseFloat(pending_quantity).toFixed(2));
        $('#e_quantity').val(parseFloat(quantity).toFixed(2)).focus().select();
        $('#e_current_quantity').val(parseFloat(quantity).toFixed(2));
        $('#e_unit_cost_exc_tax').val(parseFloat(unit_cost_exc_tax).toFixed(2));
        $('#e_discount_type').val(unit_discount_type);
        $('#e_discount').val(parseFloat(unit_discount).toFixed(2));
        $('#e_discount_amount').val(parseFloat(unit_discount_amount).toFixed(2));
        $('#e_tax_ac_id').val(tax_ac_id);
        $('#e_tax_type').val(tax_type);
        $('#e_tax_amount').val(parseFloat(unit_tax_amount).toFixed(2));
        $('#e_unit_cost_with_discount').val(parseFloat(unit_cost_with_discount).toFixed(2));
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
        $('#e_linetotal').val(parseFloat(linetotal).toFixed(2));
        $('#e_profit_margin').val(parseFloat(profit).toFixed(2));
        $('#e_selling_price').val(parseFloat(selling_price).toFixed(2));
        $('#e_lot_number').val(lot_number);
        $('#e_batch_number').val(batch_number);
        $('#e_expire_date').val(expire_date);
        $('#e_description').val(description);
        $('#e_has_batch_no_expire_date').val(has_batch_no_expire_date);

        if (has_batch_no_expire_date == 1) {

            $('#e_batch_number').prop('readonly', false);
            $('#e_expire_date').prop('readonly', false);
            $('.batch_no_expire_date_fields').removeClass('d-none');
        } else {

            $('#e_batch_number').prop('readonly', true);
            $('#e_expire_date').prop('readonly', true);
            $('.batch_no_expire_date_fields').addClass('d-none');
        }

        calculateEditOrAddAmount();

        $('#add_product').html('Update');
    });

    function clearEditItemFileds() {

        $('#e_unique_id').val('');
        $('#e_product_name').val('');
        $('#e_product_id').val('');
        $('#e_variant_id').val('');
        $('#e_ordered_quantity').val(parseFloat(0).toFixed(2));
        $('#e_received_quantity').val(parseFloat(0).toFixed(2));
        $('#e_pending_quantity').val(parseFloat(0).toFixed(2));
        $('#e_quantity').val(parseFloat(0).toFixed(2));
        $('#e_current_quantity').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_exc_tax').val(parseFloat(0).toFixed(2));
        $('#e_discount').val(parseFloat(0).toFixed(2));
        $('#e_discount_type').val(1);
        $('#e_discount_amount').val(parseFloat(0).toFixed(2));
        $('#e_tax_ac_id').val('');
        $('#e_tax_type').val(1);
        $('#e_tax_amount').val(0);
        $('#e_unit_cost_with_discount').val(parseFloat(0).toFixed(2));
        $('#e_subtotal').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
        $('#e_linetotal').val(parseFloat(0).toFixed(2));
        $('#e_profit_margin').val(parseFloat(0).toFixed(2));
        $('#e_selling_price').val(parseFloat(0).toFixed(2));
        $('#e_lot_number').val('');
        $('#e_batch_number').val('');
        $('#e_expire_date').val('');
        $('#e_description').val('');
        $('#add_product').html('Add');
        $('.batch_no_expire_date_fields').addClass('d-none');
    }

    // Quantity increase or dicrease and clculate row amount
    $(document).on('input keypress', '#e_quantity', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '') {

                $('#e_unit_id').focus();
            }
        }
    });

    $('#e_unit_id').on('change keypress click', function(e) {

        if (e.which == 0) {

            $('#e_unit_cost_exc_tax').focus().select();
        }
    });

    // Change tax percent and clculate row amount
    $(document).on('input keypress', '#e_unit_cost_exc_tax', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '') {

                $('#e_discount').focus().select();
            }
        }
    });

    // Input discount and clculate row amount
    $(document).on('input keypress', '#e_discount', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '' && $(this).val() > 0) {

                $('#e_discount_type').focus().select();
            } else {

                $('#e_tax_ac_id').focus();
            }
        }
    });

    // Input discount and clculate row amount
    $(document).on('change keypress click', '#e_discount_type', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 0) {

            $('#e_tax_ac_id').focus();
        }
    });

    // Change tax percent and clculate row amount
    $('#e_tax_ac_id').on('change keypress click', function(e) {

        calculateEditOrAddAmount();

        var e_has_batch_no_expire_date = $('#e_has_batch_no_expire_date').val();

        if (e.which == 0) {

            if ($(this).val() != '') {

                $('#e_tax_type').focus();
            } else {

                if (e_has_batch_no_expire_date == 0) {

                    if ($('#e_lot_number').val() != undefined) {

                        $('#e_lot_number').focus().select();
                    } else {

                        $('#e_description').focus().select();
                    }
                } else {

                    $('#e_batch_number').focus().select();
                }
            }
        }
    });

    // Change tax percent and clculate row amount
    $(document).on('change keypress click', '#e_tax_type', function(e) {

        calculateEditOrAddAmount();
        var e_has_batch_no_expire_date = $('#e_has_batch_no_expire_date').val();
        if (e.which == 0) {

            if (e_has_batch_no_expire_date == 0) {

                if ($('#e_lot_number').val() != undefined) {

                    $('#e_lot_number').focus().select();
                } else {

                    $('#e_description').focus().select();
                }
            } else {

                $('#e_batch_number').focus().select();
            }
        }
    });

    $('#e_batch_number').on('input keypress', function(e) {

        var e_has_batch_no_expire_date = $('#e_has_batch_no_expire_date').val();

        if (e.which == 13) {

            if (e_has_batch_no_expire_date == 1) {

                $('#e_expire_date').focus().select();
            } else {

                if ($('#e_lot_number').val() != undefined) {

                    $('#e_lot_number').focus().select();
                } else {

                    $('#e_description').focus().select();
                }
            }
        }
    });

    $('#e_expire_date').on('input keypress', function(e) {

        if (e.which == 13) {

            if ($('#e_lot_number').val() != undefined) {

                $('#e_lot_number').focus().select();
            } else {

                $('#e_description').focus().select();
            }

        }
    });

    $('#e_lot_number').on('input keypress', function(e) {

        if (e.which == 13) {

            $('#e_description').focus().select();
        }
    });

    $('#e_description').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        var xMargin = $('#e_profit_margin').val();
        if (e.which == 13) {

            if (xMargin != undefined) {

                $('#e_profit_margin').focus().select();
            } else {

                $('#add_product').focus();
            }
        }
    });

    // Input profit margin and clculate row amount
    $(document).on('input keypress', '#e_profit_margin', function(e) {

        calculateEditOrAddAmount();
        if (e.which == 13) {

            $('#e_selling_price').focus().select();
        }
    });

    $(document).on('input keypress', '#e_selling_price', function(e) {

        var selling_price = $(this).val() ? $(this).val() : 0;
        var unitCostWithDiscount = $('#e_unit_cost_with_discount').val() ? $('#e_unit_cost_with_discount').val() : 0;
        var profitAmount = parseFloat(selling_price) - parseFloat(unitCostWithDiscount);
        var __cost = parseFloat(unitCostWithDiscount) > 0 ? parseFloat(unitCostWithDiscount) : parseFloat(profitAmount);
        var xMargin = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __xMargin = xMargin ? xMargin : 0;
        $('#e_profit_margin').val(parseFloat(__xMargin).toFixed(2));

        if (e.which == 13) {

            $('#add_product').focus();
        }
    });

    $(document).on('blur', '#paying_amount', function() {

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
    $(document).on('input', '#shipment_charge', function() {

        calculateTotalAmount();
    });

    // // chane purchase tax and clculate total amount
    $(document).on('change', '#purchase_tax_ac_id', function() {
        calculateTotalAmount();
        var purchaseTaxPercent = $(this).find('option:selected').data('purchase_tax_percent') ? $(this).find('option:selected').data('purchase_tax_percent') : 0;
        $('#purchase_tax_percent').val(parseFloat(purchaseTaxPercent).toFixed(2));
    });

    // Input paying amount and clculate due amount
    $(document).on('input', '#paying_amount', function() {
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

        if (e.ctrlKey && e.which == 13) {

            $('#save_and_print').click();
            return false;
        } else if (e.shiftKey && e.which == 13) {

            $('#save').click();
            return false;
        } else if (e.which == 27) {

            $('.select_area').hide();
            $('#list').empty();
            return false;
        }
    }

    //Add purchase request by ajax
    $('#add_purchase_form').on('submit', function(e) {
        e.preventDefault();

        var total_qty = $('#total_qty').val();
        if (parseFloat(total_qty) == 0) {

            toastr.error("{{ __('Received qunatity must not be 0') }}");
            return;
        }

        $('.loading_button').show();
        var url = $(this).attr('action');

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

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                if (data.successMsg) {

                    toastr.success(data.successMsg);
                    afterCreatePurchase();
                } else {

                    toastr.success("{{ __('Purchase created successfully.') }}");

                    afterCreatePurchase();

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

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

    function afterCreatePurchase() {

        @if (isset($order))

            setTimeout(function() {
                window.location = "{{ url()->previous() }}";
            }, 2000);
        @endif

        $('.loading_button').hide();
        $('.hidden').val(parseFloat(0).toFixed(2));
        $('#add_purchase_form')[0].reset();
        $('#purchase_product_list').empty();
        getPurchaseInvoiceId();
    }

    function getPurchaseInvoiceId() {

        $.ajax({
            url: "{{ route('purchases.invoice.id') }}",
            async: true,
            type: 'get',
            success: function(data) {

                $('#invoice_id').val(data);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    }

    setInterval(function() {
        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function() {
        $('#search_product').removeClass('is-valid');
    }, 1000);

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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_expire_date'),
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

    $('#payment_method_id').on('change', function() {

        var account_id = $(this).find('option:selected').data('account_id');
        setMethodAccount(account_id);
    });

    function setMethodAccount(account_id) {

        if (account_id) {

            $('#account_id').val(account_id);
        } else if (account_id === '') {

            $('#account_id option:first-child').prop("selected", true);
        }
    }

    setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));

    document.onkeyup = function() {
        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.ctrlKey && e.which == 13) {

            $('#save_and_print').click();
            return false;
        } else if (e.shiftKey && e.which == 13) {

            $('#save').click();
            return false;
        }
    }

    $(document).on('click', function(e) {

        if ($(e.target).closest(".select_area").length === 0) {

            $('.select_area').hide();
            $('#list').empty();
        }
    });

    $('#select_print_page_size').on('change', function() {
        var value = $(this).val();
        $('#print_page_size').val(value);
    });
</script>

<script>
    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            if (nextId == 'warehouse_id' && $('#warehouse_id').val() == undefined) {

                $('#date').focus().select();
                return;
            }

            if ($(this).attr('id') == 'paying_amount' && ($('#paying_amount').val() == 0 || $('#paying_amount').val() == '')) {

                $('#save_and_print').focus().select();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });
</script>

<script>
    var ul = document.getElementById('list');
    var selectObjClassName = 'selectProduct';
    $('#po_id').mousedown(function(e) {

        afterClickOrFocusPoId();
    }).focus(function(e) {

        ul = document.getElementById('list')
        selectObjClassName = 'selected_po';
    });

    function afterClickOrFocusPoId() {

        ul = document.getElementById('list')
        selectObjClassName = 'selected_po';

        $('#po_id').val('');
        $('#supplier_name').val('');
        $('#supplier_account_id').val('');
        $('#closing_balance').val(0.00);
        $('#purchase_order_id').val('');
        $('#purchase_product_list').empty();
        $('#purchase_order_product_id').empty();
        $('.po_search_result').hide();
        $('#list').empty();
        calculateTotalAmount();
    }

    $(document).on('keyup', 'body', function(e) {

        if (e.keyCode == 13) {

            $('.' + selectObjClassName).click();
            $('.po_search_result').hide();
            $('.select_area').hide();
            $('#list').empty();
        }
    });

    $('#po_id').on('input', function() {

        $('.po_search_result').hide();

        var po_id = $(this).val();

        if (po_id === '') {

            $('.po_search_result').hide();
            $('#purchase_order_id').val('');
            return;
        }

        var url = "{{ route('purchases.orders.search', [':keyWord']) }}";
        var route = url.replace(':keyWord', po_id);

        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.noResult)) {

                    $('.po_search_result').hide();
                    $('#list').empty();
                } else {

                    $('.po_search_result').show();
                    $('#list').html(data);
                }
            }
        });
    });

    $(document).on('click', '#selected_po', function(e) {
        e.preventDefault();

        var po_id = $(this).html();
        var purchase_order_id = $(this).data('purchase_order_id');
        var supplier_name = $(this).data('supplier_name');
        var supplier_account_id = $(this).data('supplier_account_id');
        var closing_balance = $(this).data('closing_balance');
        var default_balance_type = $(this).data('default_balance_type');

        var url = "{{ route('purchase.order.products.for.purchase.order.to.invoice', [':purchaseOrderId']) }}";
        var route = url.replace(':purchaseOrderId', purchase_order_id);

        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    $('#po_id').focus().select();
                    return;
                }

                itemUnitsArray = jQuery.parseJSON(data.units);

                $('#po_id').val(po_id.trim());
                $('#purchase_order_id').val(purchase_order_id);
                $('#supplier_account_id').val(supplier_account_id);
                $('#supplier_name').val(supplier_name);
                $('#closing_balance').val(closing_balance);
                $('#default_balance_type').val(default_balance_type);
                $('.po_search_result').hide();
                $('#purchase_product_list').empty();
                $('#purchase_product_list').html(data.view);
                calculateTotalAmount();
            }
        });
    });
</script>
