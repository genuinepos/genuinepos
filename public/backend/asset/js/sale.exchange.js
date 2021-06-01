$(document).on('input', '#ex_quantity',function () {
    var ex_qty = $(this).val();
    var closestTr = $(this).closest('tr');
    var soldQty = closestTr.find('#sold_quantity').val();
    console.log(soldQty);
    if (parseFloat(ex_qty) < 0) {
        var sum = parseFloat(soldQty) - parseFloat(ex_qty);
        console.log(sum);
        if (sum < 0) {
            toastr.error('Exchange Quantity substruction value must not be theater then sold Quantity.');
            $(this).val(- parseFloat(soldQty));
        }
    }
});