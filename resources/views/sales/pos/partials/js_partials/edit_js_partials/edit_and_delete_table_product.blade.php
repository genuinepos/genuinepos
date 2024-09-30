<script>
    function editProduct(e) {

        var tr = $(e).closest('tr');
        var unique_id = tr.find('.unique_id').val();
        var item_name = tr.find('#product_name').val();
        var product_id = tr.find('#product_id').val();
        var variant_id = tr.find('#variant_id').val();
        var tax_ac_id = tr.find('#tax_ac_id').val();
        var tax_type = tr.find('#tax_type').val();
        var unit_tax_amount = tr.find('#unit_tax_amount').val();
        var unit_discount_type = tr.find('#unit_discount_type').val();
        var unit_discount = tr.find('#unit_discount').val();
        var unit_discount_amount = tr.find('#unit_discount_amount').val();
        var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
        var quantity = tr.find('#quantity').val();
        var unit_id = tr.find('#unit_id').val();
        var unit_name = tr.find('#span_unit').html();
        var unit_price_exc_tax = tr.find('#unit_price_exc_tax').val();
        var unit_price_inc_tax = tr.find('#unit_price_inc_tax').val();
        var is_show_emi_on_pos = tr.find('#is_show_emi_on_pos').val();
        var description = tr.find('#description').val();
        var subtotal = tr.find('#subtotal').val();

        $('#editProductModal').modal('show');

        $('#e_unique_id').val(unique_id);
        $('#e_product_name').html(item_name);
        $('#e_description').val(description);
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id);
        $('#e_unit_id').empty();
        $('#e_unit_id').append('<option data-unit_name="' + unit_name + '" value="' + unit_id + '">' + unit_name + '</option>');
        $('#e_quantity').val(parseFloat(quantity).toFixed(2));
        $('#e_price_exc_tax').val(parseFloat(unit_price_exc_tax).toFixed(2));
        $('#e_price_inc_tax').val(parseFloat(unit_price_inc_tax).toFixed(2));
        $('#e_unit_discount_type').val(unit_discount_type);
        $('#e_unit_discount').val(parseFloat(unit_discount).toFixed(2));
        $('#e_discount_amount').val(parseFloat(unit_discount_amount).toFixed(2));
        $('#e_tax_ac_id').val(tax_ac_id);
        $('#e_tax_type').val(tax_type);
        $('#e_tax_amount').val(parseFloat(unit_tax_amount).toFixed(2));
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        $('#e_is_show_emi_on_pos').val(is_show_emi_on_pos);

        if (is_show_emi_on_pos == 1) {

            $('#description_field').show();
        } else {

            $('#description_field').hide();
        }

        setTimeout(function() {

            $('#e_quantity').focus().select();
        }, 500);

        $('#display_unit_cost_section').addClass('d-hide');
        $('#display_unit_cost').html(bdFormat(unit_cost_inc_tax));
    }

    function calculateEditOrAddAmount() {

        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0.00;
        var e_tax_type = $('#e_tax_type').val();
        var e_unit_discount_type = $('#e_unit_discount_type').val();
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_price_exc_tax = $('#e_price_exc_tax').val() ? $('#e_price_exc_tax').val() : 0;
        var e_unit_discount = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0;

        var discount_amount = 0;
        if (e_unit_discount_type == 1) {

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

    $('#e_quantity').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {
            e.preventDefault();

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

                $('#e_unit_discount_type').focus();
            }
        }
    });

    $('#e_unit_discount_type').on('change keypress click', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 0) {

            $('#e_unit_discount').focus().select();
        }
    });

    $('#e_unit_discount').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            $('#e_tax_ac_id').focus();
        }
    });

    $('#e_tax_ac_id').on('change keypress click', function(e) {

        calculateEditOrAddAmount();
        var val = $(this).val();
        var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();

        if (e.which == 0) {

            if (val) {

                $('#e_tax_type').focus();
            } else {

                if (e_is_show_emi_on_pos == 1) {

                    $('#e_description').focus().select();
                } else {

                    $('#edit_product').focus();
                }

            }
        }
    });

    $('#e_tax_type').on('change keypress click', function(e) {

        calculateEditOrAddAmount();
        var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();

        if (e.which == 0) {

            if (e_is_show_emi_on_pos == 1) {

                $('#e_description').focus().select();
            } else {

                $('#edit_product').focus();
            }
        }
    });

    $('#e_description').on('change keypress click', function(e) {

        if (e.which == 13) {

            $('#edit_product').focus();
        }
    });

    $('#edit_product').on('click', function(e) {
        e.preventDefault();

        var e_unique_id = $('#e_unique_id').val();
        var e_product_id = $('#e_product_id').val();
        var e_variant_id = $('#e_variant_id').val();
        var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
        var e_unit_id = $('#e_unit_id').val();
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_price_exc_tax = $('#e_price_exc_tax').val() ? $('#e_price_exc_tax').val() : 0;
        var e_unit_discount = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0;
        var e_unit_discount_type = $('#e_unit_discount_type').val();
        var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
        var e_tax_ac_id = $('#e_tax_ac_id').val();
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
        var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
        var e_tax_type = $('#e_tax_type').val();
        var e_price_inc_tax = $('#e_price_inc_tax').val() ? $('#e_price_inc_tax').val() : 0;
        var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
        var e_description = $('#e_description').val();

        if (e_quantity == '') {

            toastr.error("{{ __('Quantity field must not be empty.') }}");
            return;
        }

        var route = '';
        if (e_variant_id != 'noid') {

            var url = "{{ route('general.product.search.variant.product.stock', [':product_id', ':variant_id']) }}";
            route = url.replace(':product_id', e_product_id);
            route = route.replace(':variant_id', e_variant_id);
        } else {

            var url = "{{ route('general.product.search.single.product.stock', [':product_id']) }}";
            route = url.replace(':product_id', e_product_id);
        }

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                var stockLocationMessage = ' in the Store';
                if (parseFloat(e_quantity) > parseFloat(data.stock)) {

                    toastr.error('Current stock is ' + parseFloat(data.stock) + '/' + e_unit_name + stockLocationMessage);
                    return;
                }

                var tr = $('#' + e_unique_id).closest('tr');

                console.log(tr);

                tr.find('#tax_ac_id').val(e_tax_ac_id);
                tr.find('#tax_type').val(e_tax_type);
                tr.find('#unit_tax_percent').val(parseFloat(e_tax_percent).toFixed(2));
                tr.find('#unit_tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
                tr.find('#unit_discount_type').val(e_unit_discount_type);
                tr.find('#unit_discount').val(parseFloat(e_unit_discount).toFixed(2));
                tr.find('#unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
                tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
                tr.find('#span_quantity').html(parseFloat(e_quantity).toFixed(2));
                tr.find('#unit_price_exc_tax').val(parseFloat(e_price_exc_tax).toFixed(2));
                tr.find('#unit_price_inc_tax').val(parseFloat(e_price_inc_tax).toFixed(2));
                tr.find('#span_unit_price_inc_tax').html(parseFloat(e_price_inc_tax).toFixed(2));
                tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
                tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
                var __description = e_description.length > 30 ? e_description.substring(0, 40) + '...' : e_description;
                tr.find('#description').val(e_description);
                tr.find('#span_description').html(__description);

                $('#editProductModal').modal('hide');
                tr.find('#edit_product_link').focus();
                calculateTotalAmount();
            }
        })
    });

    // Remove product form purchase product list (Table)
    $(document).on('click', '#remove_product_btn', function(e) {
        e.preventDefault();

        $(this).closest('tr').remove();
        calculateTotalAmount();
        activeSelectedItems();
    });

    $(document).on('click', '#display_unit_cost_toggle_btn', function(e) {

        $('#display_unit_cost_section').toggle(500);

        setTimeout(function() {

            $('#display_unit_cost_section').hide(500);
        }, 1500);
    });
</script>
