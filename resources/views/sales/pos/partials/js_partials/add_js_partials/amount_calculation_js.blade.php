<script>
    // Calculate total amount functionalities
    function calculateTotalAmount() {

        var serials = document.querySelectorAll('#serial');
        var serialsArray = Array.from(serials);

        serials.forEach(function(element, index) {

            element.innerHTML = index + 1;
        });

        var quantities = document.querySelectorAll('#quantity');
        var subtotals = document.querySelectorAll('#subtotal');
        var unitTaxAmounts = document.querySelectorAll('#unit_tax_amount');

        // Update Total Item
        var total_item = 0;
        var total_qty = 0;

        quantities.forEach(function(qty) {
            total_item += 1;
            total_qty += parseFloat(qty.value);
        });

        $('#total_item').val(parseFloat(total_item));
        $('#total_qty').val(parseFloat(total_qty).toFixed(2));

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

        var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0;

        if ($('#order_discount_type').val() == 2) {

            var orderDisAmount = (parseFloat(netTotalAmount) / 100) * parseFloat(orderDiscount);
            $('#order_discount_amount').val(parseFloat(orderDisAmount).toFixed(2));
        } else {

            $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
        }

        var orderDiscountAmount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;

        // Calc order tax amount
        var orderTax = $('#sale_tax_ac_id').find('option:selected').data('order_tax_percent') ? $('#sale_tax_ac_id').find('option:selected').data('order_tax_percent') : 0;
        var calcOrderTaxAmount = (parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount)) / 100 * parseFloat(orderTax);
        $('#order_tax_amount').val(parseFloat(calcOrderTaxAmount).toFixed(2));

        // Update Total payable Amount
        var calcOrderTaxAmount = $('#order_tax_amount').val() ? $('#order_tax_amount').val() : 0;
        var shipmentCharge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;

        var calcTotalInvoiceAmount = parseFloat(netTotalAmount) -
            parseFloat(orderDiscountAmount) +
            parseFloat(calcOrderTaxAmount) +
            parseFloat(shipmentCharge);

        $('#total_invoice_amount').val(parseFloat(calcTotalInvoiceAmount).toFixed(2));

        var salesLedgerAmount = parseFloat(netTotalAmount) +
            parseFloat(shipmentCharge) -
            parseFloat(orderDiscountAmount) -
            parseFloat(productTotalTaxAmount);

        $('#sales_ledger_amount').val(parseFloat(salesLedgerAmount).toFixed(2));

        var accountDefaultBalanceType = $('#customer_account_id').find('option:selected').data('default_balance_type');
        var previousDue = $('#previous_due').val() ? $('#previous_due').val() : 0;
        var calcTotalReceivableAmount = 0;
        if (accountDefaultBalanceType == 'dr') {

            calcTotalReceivableAmount = parseFloat(previousDue) + parseFloat(calcTotalInvoiceAmount);
        } else {

            calcTotalReceivableAmount = parseFloat(previousDue) - parseFloat(calcTotalInvoiceAmount);
        }

        $('#total_receivable_amount').val(parseFloat(calcTotalReceivableAmount).toFixed(2));


        var receivedAmount = $('#received_amount').val() ? $('#received_amount').val() : 0;
        var changeAmount = parseFloat(receivedAmount) - parseFloat(calcTotalReceivableAmount);
        $('#change_amount').val(parseFloat(changeAmount >= 0 ? changeAmount : 0).toFixed(2));
        // var currentBalance = parseFloat(calcTotalReceivableAmount) - parseFloat(receivedAmount);
        // var __currentBalance = parseFloat(currentBalance) >= 0 ? parseFloat(currentBalance) : 0
        // $('#current_balance').val(parseFloat(__currentBalance).toFixed(2));

        var currentBalance = 0;
        if (accountDefaultBalanceType == 'dr') {

            currentBalance = parseFloat(calcTotalReceivableAmount) - parseFloat(receivedAmount);
        } else {

            currentBalance = parseFloat(calcTotalReceivableAmount) + parseFloat(receivedAmount);
        }

        var __currentBalance = currentBalance > 0 ? currentBalance : 0;

        $('#current_balance').val(parseFloat(__currentBalance).toFixed(2));
    }

    $(document).on('change', '#order_discount_type', function() {

        calculateTotalAmount();
    });

    // change purchase tax and calculate total amount
    $(document).on('change', '#sale_tax_ac_id', function() {

        var orderTaxPercent = $(this).find('option:selected').data('order_tax_percent') ? $(this).find('option:selected').data('order_tax_percent') : 0;
        $('#order_tax_percent').val(parseFloat(orderTaxPercent).toFixed(2));
        calculateTotalAmount();
    });

    // Input paying amount and calculate due amount
    $(document).on('input', '#received_amount', function() {

        calculateTotalAmount();
    });

    // Input order discount and calculate total amount
    $(document).on('input', '#order_discount', function() {

        calculateTotalAmount();
    });

    // Cash receive by modal input with change value
    $('#modal_received_amount').on('input', function() {

        var totalReceivable = $('#total_receivable_amount').val();
        // Update purchase due
        var receivedAmount = $(this).val() ? $(this).val() : 0;
        var changeAmount = parseFloat(receivedAmount) - parseFloat(totalReceivable);
        $('#modal_change_amount').val(parseFloat(changeAmount >= 0 ? changeAmount : 0).toFixed(2));
        var currentBalance = parseFloat(totalReceivable) - parseFloat(receivedAmount);
        $('#modal_current_balance').val(parseFloat(currentBalance >= 0 ? currentBalance : 0).toFixed(2));

        $('#received_amount').val(parseFloat(receivedAmount).toFixed(2));
        $('#change_amount').val(parseFloat(changeAmount >= 0 ? changeAmount : 0).toFixed(2));
        $('#current_balance').val(parseFloat(currentBalance >= 0 ? currentBalance : 0).toFixed(2));
    });

    @if (isset($jobCard))
        calculateTotalAmount();
    @endif
</script>
