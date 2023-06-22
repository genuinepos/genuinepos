<script>
    // Show selling product's update modal
    var tableRowIndex = 0;
    $(document).on('click', '#edit_product', function(e) {
        e.preventDefault();
        var parentTableRow = $(this).closest('tr');
        tableRowIndex = parentTableRow.index();
        var quantity = parentTableRow.find('#quantity').val();
        var product_name = parentTableRow.find('.product-name').html();
        var product_variant = parentTableRow.find('.product_variant').html();
        var product_code = parentTableRow.find('.product-name').attr('title');
        var unit_price_exc_tax = parentTableRow.find('#unit_price_exc_tax').val();
        var unit_tax_percent = parentTableRow.find('#unit_tax_percent').val();
        var unit_tax_amount = parentTableRow.find('#unit_tax_amount').val();
        var unit_discount_type = parentTableRow.find('#unit_discount_type').val();
        var unit_discount = parentTableRow.find('#unit_discount').val();
        var unit_discount_amount = parentTableRow.find('#unit_discount_amount').val();
        var product_unit = parentTableRow.find('#unit').val();
        // Set modal heading
        var heading = product_name + ' - ' + (product_variant ? product_variant : '') + ' (' + product_code +
            ')';
        $('#product_info').html(heading);

        $('#e_quantity').val(parseFloat(quantity).toFixed(2));
        $('#e_unit_price').val(parseFloat(unit_price_exc_tax).toFixed(2));
        $('#e_unit_discount_type').val(unit_discount_type);
        $('#e_unit_discount').val(unit_discount);
        $('#e_discount_amount').val(unit_discount_amount);
        $('#e_unit_tax').empty();
        $('#e_unit_tax').append('<option value="0.00">No Tax</option>');

        taxArray.forEach(function(tax) {

            if (tax.tax_percent == unit_tax_percent) {

                $('#e_unit_tax').append('<option SELECTED value="' + tax.tax_percent + '">' + tax
                    .tax_name + '</option>');
            } else {

                $('#e_unit_tax').append('<option value="' + tax.tax_percent + '">' + tax.tax_name +
                    '</option>');
            }
        });

        $('#e_unit').empty();

        unites.forEach(function(unit) {

            if (unit == product_unit) {

                $('#e_unit').append('<option SELECTED value="' + unit + '">' + unit + '</option>');
            } else {

                $('#e_unit').append('<option value="' + unit + '">' + unit + '</option>');
            }
        });

        $('#editProductModal').modal('show');
    });

    //Update Selling producdt
    $(document).on('submit', '#update_selling_product', function(e) {
        e.preventDefault();
        var inputs = $('.edit_input');
        $('.error').html('');
        var countErrorField = 0;
        $.each(inputs, function(key, val) {

            var inputId = $(val).attr('id');
            var idValue = $('#' + inputId).val();

            if (idValue == '') {

                countErrorField += 1;
                var fieldName = $('#' + inputId).data('name');
                $('.error_' + inputId).html(fieldName + ' is required.');
            }
        });

        if (countErrorField > 0) {

            return;
        }

        var e_quantity = $('#e_quantity').val();
        var e_unit_price = $('#e_unit_price').val();
        var e_unit_discount_type = $('#e_unit_discount_type').val();
        var e_unit_discount = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
        var e_unit_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0.00;
        var e_unit_tax_percent = $('#e_unit_tax').val() ? $('#e_unit_tax').val() : 0.00;
        var e_unit = $('#e_unit').val();

        var productTableRow = $('#product_list tr:nth-child(' + (tableRowIndex + 1) + ')');
        // calculate unit tax
        productTableRow.find('.span_unit').html(e_unit);
        productTableRow.find('#unit').val(e_unit);
        productTableRow.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
        productTableRow.find('#unit_price_exc_tax').val(parseFloat(e_unit_price).toFixed(2));
        productTableRow.find('#unit_discount_type').val(e_unit_discount_type);
        productTableRow.find('#unit_discount').val(parseFloat(e_unit_discount).toFixed(2));
        productTableRow.find('#unit_discount_amount').val(parseFloat(e_unit_discount_amount).toFixed(2));

        var calsUninTaxAmount = parseFloat(e_unit_price) / 100 * parseFloat(e_unit_tax_percent);
        productTableRow.find('#unit_tax_percent').val(parseFloat(e_unit_tax_percent).toFixed(2));
        productTableRow.find('#unit_tax_amount').val(parseFloat(calsUninTaxAmount).toFixed(2));
        var calcUnitPriceWithDiscount = parseFloat(e_unit_price) - parseFloat(e_unit_discount_amount);
        var calcUnitPriceIncTax = parseFloat(calcUnitPriceWithDiscount) / 100 * parseFloat(e_unit_tax_percent) + parseFloat(calcUnitPriceWithDiscount);

        productTableRow.find('#unit_price_inc_tax').val(parseFloat(calcUnitPriceIncTax).toFixed(2));
        productTableRow.find('.span_unit_price_inc_tax').html(parseFloat(calcUnitPriceIncTax).toFixed(2));

        var calcSubtotal = parseFloat(calcUnitPriceIncTax) * parseFloat(e_quantity);
        productTableRow.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
        productTableRow.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        calculateTotalAmount();
        $('#editProductModal').modal('hide');
    });

    // Remove product form purchase product list (Table)
    $(document).on('click', '#remove_product_btn',function(e){
        e.preventDefault();

        $(this).closest('tr').remove();
        calculateTotalAmount();
    });
</script>
