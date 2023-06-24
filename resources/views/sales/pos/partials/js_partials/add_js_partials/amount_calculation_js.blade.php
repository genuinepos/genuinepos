<script>
    // Calculate total amount functionalities
    function calculateTotalAmount() {

        var indexes = document.querySelectorAll('#index');
        indexes.forEach(function (index) {
            var className = index.getAttribute("class");
            var rowIndex = $('.' + className).closest('tr').index();
            $('.' + className).closest('tr').find('.serial').html(rowIndex + 1);
        });

        var quantities = document.querySelectorAll('#quantity');
        var subtotals = document.querySelectorAll('#subtotal');
        // Update Total Item
        var total_item = 0;
        var total_qty = 0;

        quantities.forEach(function (qty) {
            total_item += 1;
            total_qty += parseFloat(qty.value)
        });

        $('#total_item').val(parseFloat(total_item));
        $('.mb_total_item').val(parseFloat(total_item));
        $('#total_qty').val(parseFloat(total_qty).toFixed(2));
        $('.mb_total_qty').val(parseFloat(total_qty).toFixed(2));

        // Update Net total Amount
        var netTotalAmount = 0;

        subtotals.forEach(function (subtotal) {

            netTotalAmount += parseFloat(subtotal.value);
        });

        $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

        var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0;

        if ($('#order_discount_type').val() == 2) {

            var orderDisAmount = (parseFloat(netTotalAmount) / 100) * parseFloat(orderDiscount);
            $('#order_discount_amount').val(parseFloat(orderDisAmount).toFixed(2));
        } else {

            $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
        }

        var orderDiscountAmount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;

        // Calc order tax amount
        var orderTax = $('#order_tax').val() ? $('#order_tax').val() : 0;
        var calcOrderTaxAmount = (parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount)) / 100 * parseFloat(orderTax);
        $('#order_tax_amount').val(parseFloat(calcOrderTaxAmount).toFixed(2));

        // Update Total payable Amount
        var calcOrderTaxAmount = $('#order_tax_amount').val() ? $('#order_tax_amount').val() : 0;
        var shipmentCharge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;
        var previousDue = $('#previous_due').val() ? $('#previous_due').val() : 0;

        var calcInvoicePayable = parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount) + parseFloat(calcOrderTaxAmount) + parseFloat(shipmentCharge);

        $('#total_invoice_payable').val(parseFloat(calcInvoicePayable).toFixed(2));

        var ex_inv_payable_amount = $('#ex_inv_payable_amount').val() ? $('#ex_inv_payable_amount').val() : 0;
        var ex_inv_paid = $('#ex_inv_paid').val() ? $('#ex_inv_paid').val() : 0;
        var exchange_item_total_price = $('#exchange_item_total_price').val() ? $('#exchange_item_total_price').val() : 0;

        var calcTotalPayableAmount = parseFloat(netTotalAmount) -
            parseFloat(orderDiscountAmount) +
            parseFloat(calcOrderTaxAmount) +
            parseFloat(shipmentCharge) +
            parseFloat(previousDue);

        $('#total_payable_amount').val(parseFloat(calcTotalPayableAmount).toFixed(2));

        //$('#paying_amount').val(parseFloat(calcTotalPayableAmount).toFixed(2));
        // Update purchase due

        var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
        var changeAmount = parseFloat(payingAmount) - parseFloat(calcTotalPayableAmount);
        $('#change_amount').val(parseFloat(changeAmount >= 0 ? changeAmount : 0).toFixed(2));
        var calcTotalDue = parseFloat(calcTotalPayableAmount) - parseFloat(payingAmount);
        $('#total_due').val(parseFloat(calcTotalDue >= 0 ? calcTotalDue : 0).toFixed(2));
    }

    $(document).on('change', '#order_discount_type', function () {

        calculateTotalAmount();
    });

    // change purchase tax and calculate total amount
    $(document).on('change', '#order_tax', function () {

        calculateTotalAmount();
    });

    // Input paying amount and calculate due amount
    $(document).on('input', '#paying_amount', function () {

        calculateTotalAmount();
    });

    // Input order discount and calculate total amount
    $(document).on('input', '#order_discount', function () {

        calculateTotalAmount();
    });

    $(document).on('input', '#quantity', function () {
        var qty = $(this).val() ? $(this).val() : 0;

        if (qty < 0) {

            $(this).val(0);
        }

        if (parseFloat(qty) >= 0) {

            var tr = $(this).closest('tr');
            var qty_limit = tr.find('#qty_limit').val();
            var unit = tr.find('#unit').val();

            if (parseInt(qty) > parseInt(qty_limit)) {

                toastr.error('Quantity Limit Is - ' + qty_limit + ' ' + unit);
                $(this).val(qty_limit);
                var unitPrice = tr.find('#unit_price_inc_tax').val();
                var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty_limit);
                tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount();
                return;
            }

            var unitPrice = tr.find('#unit_price_inc_tax').val();
            var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty);
            tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
            calculateTotalAmount();
        }
    });

    // Calculate unit discount
    $('#e_unit_discount').on('input', function () {

        var discountValue = $(this).val() ? $(this).val() : 0.00;

        if ($('#e_unit_discount_type').val() == 1) {

            $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
        } else {

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
        } else {

            var unit_price = $('#e_unit_price').val();
            var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
            $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
        }
    });

    // Cash receive by modal input with change value
    $('#modal_paying_amount').on('input', function () {

        var totalPayable = $('#total_payable_amount').val();
        // Update purchase due
        var payingAmount = $(this).val() ? $(this).val() : 0;
        var changeAmount = parseFloat(payingAmount) - parseFloat(totalPayable);
        $('#modal_change_amount').val(parseFloat(changeAmount >= 0 ? changeAmount : 0).toFixed(2));
        var calcTotalDue = parseFloat(totalPayable) - parseFloat(payingAmount);
        $('#modal_total_due').val(parseFloat(calcTotalDue >= 0 ? calcTotalDue : 0).toFixed(2));

        $('#paying_amount').val(parseFloat(payingAmount).toFixed(2));
        $('#change_amount').val(parseFloat(changeAmount >= 0 ? changeAmount : 0).toFixed(2));
        $('#total_due').val(parseFloat(calcTotalDue >= 0 ? calcTotalDue : 0).toFixed(2));
    });

    // Remove product form purchase product list (Table)
    $(document).on('click', '#remove_product_btn',function(e){
        e.preventDefault();

        $(this).closest('tr').remove();
        calculateTotalAmount();
        activeSelectedItems();
    });
</script>
