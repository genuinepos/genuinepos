$('#exchange').on('click', function (e) {
    e.preventDefault();

    $('#invoice_description').empty();
    $('#invoice_id').val('');
});

$(document).on('submit', '#search_inv_form', function (e) {
    e.preventDefault();

    $('#get_inv_preloader').show();
    var url = $(this).attr('action');
    var request = $(this).serialize();

    $.ajax({
        url: url,
        type: 'get',
        data: request,
        success: function (data) {

            $('#get_inv_preloader').hide();
            $('#exchange_invoice_description').empty();
            if (!$.isEmptyObject(data.errorMsg)) {

                toastr.error(data.errorMsg);
            } else {

                $('#exchange_invoice_description').html(data);
            }
        }, error: function (err) {

            $('#get_inv_preloader').hide();
            if (err.status == 0) {

                toastr.error('Net Connection Error. Reload This Page.');
                return;
            } else if (err.status == 500) {

                toastr.error('Server error. Please contact to the support team.');
                return;
            }
        }
    });
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('submit', '#prepare_to_exchange', function (e) {
    e.preventDefault();

    var url = $(this).attr('action');
    var request = $(this).serialize();

    $.ajax({
        url: url,
        type: 'post',
        data: request,
        success: function (data) {

            if (!$.isEmptyObject(data.errorMsg)) {

                toastr.error(data.errorMsg);
            }
            // return;
            var sale = data.sale;

            var tr = '';
            $.each(sale.sale_products, function (key, saleProduct) {

                if (saleProduct.ex_status == 1) {

                    var name = saleProduct.product.name.substring(0, 30);

                    var variantName = saleProduct.variant != null ? ' - ' + saleProduct.variant.variant_name : '';
                    var __name = name + variantName;

                    var productId = saleProduct.product_id;
                    var variantId = saleProduct.variant_id != null ? saleProduct.variant_id : 'noid';

                    tr += '<tr class="product_row" data-is_exchange_product="1">';
                    tr += '<td class="fw-bold" id="serial">' + (key + 1) + '</td>';
                    tr += '<td class="text-start">';
                    tr += '<a href="#" onclick="editProduct(this); return false;" id="edit_product_link" class="text-danger">' + __name + '</a><br/><input type="' + (saleProduct.product.is_show_emi_on_pos == 1 ? 'text' : 'hidden') + '" name="descriptions[]" class="form-control description_input" placeholder="IMEI, Serial number or other info." value="' + (saleProduct.description ? saleProduct.description : '') + '">';
                    tr += '<input type="hidden" id="product_name" value="' + __name + '">';
                    tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + productId + '">';
                    tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + variantId + '">';
                    tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + (saleProduct.tax_ac_id != null ? saleProduct.tax_ac_id : '') + '">';
                    tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + saleProduct.tax_type + '">';
                    tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="' + saleProduct.unit_tax_percent + '">';
                    tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + saleProduct.unit_tax_amount + '">';
                    tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="' + saleProduct.unit_discount_type + '">';
                    tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="' + saleProduct.unit_discount + '">';
                    tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + saleProduct.unit_discount_amount + '">';
                    tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + saleProduct.unit_cost_inc_tax + '">';
                    tr += '<input type="hidden" id="current_quantity" value="0">';
                    tr += '<input type="hidden" id="current_stock" value="0">';
                    tr += '<input type="hidden" name="exchanges[]" value="1">';
                    tr += '<input type="hidden" class="unique_id" id="' + productId + '' + variantId + '" value="' + productId + '' + variantId + '">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<span class="fw-bold '+(saleProduct.ex_quantity < 0 ? 'text-danger' : '')+'" id="span_quantity">' + saleProduct.ex_quantity + '</span>';
                    tr += '<input required type="hidden" step="any" name="quantities[]" id="quantity" value="' + saleProduct.ex_quantity + '">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<span class="fw-bold" id="span_unit">' + saleProduct.unit.name + '</span>';
                    tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + saleProduct.unit_id + '">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<span class="fw-bold" id="span_unit_price_inc_tax">' + saleProduct.unit_price_inc_tax + '</span>';
                    tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="' + saleProduct.unit_price_exc_tax + '">';
                    tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="' + saleProduct.unit_price_inc_tax + '">';
                    tr += '</td>';

                    var exQuantity = parseFloat(saleProduct.ex_quantity);
                    var subtotal = parseFloat(saleProduct.unit_price_inc_tax) * parseFloat(exQuantity);

                    tr += '<td>';
                    tr += '<span class="fw-bold '+(subtotal < 0 ? 'text-danger' : '')+'" id="span_subtotal">' + parseFloat(subtotal).toFixed(2) + '</span>';
                    tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(subtotal).toFixed(2) + '">';
                    tr += '</td>';
                    tr += '<td><a href="#" class="action-btn c-delete" id="remove_product_btn" tabindex="-1"><span class="fas fa-trash"></span></a></td>';
                    tr += '</tr>';
                }
            });

            $('#product_list').empty();
            $('#product_list').prepend(tr);
            $('#pos_submit_form')[0].reset();

            $('#ex_sale_id').val(sale.id);
            $('#sale_tax_ac_id').val(sale.sale_tax_ac_id);

            // $('#order_discount_type').val(data.sale.order_discount_type);

            // if (data.sale.order_discount_type == 1) {

            //     $('#order_discount').val('-' + data.sale.order_discount);
            // }else{

            //     $('#order_discount').val(data.sale.order_discount);
            // }

            // $('#order_discount_amount').val('-' + data.sale.order_discount_amount);

            //$('#previous_due').val(data.sale.due);

            $('#customer_account_id').val(data.sale.customer_account_id);
            calculateTotalAmount();
            var exchange_url = $('#exchange_url').val();
            $('#pos_submit_form').attr('action', exchange_url);
            $('#exchangeModal').modal('hide');
        }
    });
});

$('#exchangeModal').on('hidden.bs.modal', function () {
    $('#exchange_invoice_description').empty();
});

$('#exchangeModal').on('show.bs.modal', function () {

    setTimeout(function () {

        $('#invoice_id').focus();
    }, 500);
});
