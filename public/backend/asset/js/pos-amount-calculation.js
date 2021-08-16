 // Calculate total amount functionalitie
 function calculateTotalAmount(){
    var indexs = document.querySelectorAll('#index');
    indexs.forEach(function(index) {
        var className = index.getAttribute("class");
        var rowIndex = $('.' + className).closest('tr').index();
        $('.' + className).closest('tr').find('.serial').html(rowIndex + 1);
    });

    var quantities = document.querySelectorAll('#quantity');
    var subtotals = document.querySelectorAll('#subtotal');
    // Update Total Item
    var total_item = 0;
    var total_qty = 0;
    quantities.forEach(function(qty){
        total_item += 1;
        total_qty += parseFloat(qty.value)
    });

    $('#total_item').val(parseFloat(total_item));
    $('.mb_total_item').val(parseFloat(total_item));
    $('#total_qty').val(parseFloat(total_qty).toFixed(2));
    $('.mb_total_qty').val(parseFloat(total_qty).toFixed(2));

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
        var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0;
        $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
    }

    var orderDiscountAmount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;
    // Calc order tax amount
    var orderTax = $('#order_tax').val() ? $('#order_tax').val() : 0;
    var calcOrderTaxAmount = (parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount)) / 100 * parseFloat(orderTax) ;
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