$(document).on('input', '#ex_quantity',function () {
    var ex_qty = $(this).val();
    var closestTr = $(this).closest('tr');
    var soldQty = closestTr.find('#sold_quantity').val();
    console.log(soldQty);
    if (parseFloat(ex_qty) < 0) {
        var sum = parseFloat(soldQty) + parseFloat(ex_qty);
        console.log(sum);
        if (sum < 0) {
            toastr.error('Exchange quantity substruction value must not be greater then sold quantity.');
            $(this).val(- parseFloat(soldQty));
        }
    }
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('submit', '#prepare_to_exchange',function (e) {
    e.preventDefault();
    var url = $(this).attr('action');
    var request = $(this).serialize();
    $.ajax({
        url:url,
        type:'post',
        data:request,
        success:function(data){
            $('#pos_submit_form')[0].reset();
            $('#product_list').empty();
            $('#account_id').val(defaultAccount);
            var qty_limits = data.qty_limits;
            $('#ex_sale_id').val(data.sale.id);
            $('#ex_inv_payable_amount').val(parseFloat(data.sale.total_payable_amount).toFixed(2));
            $('#ex_inv_paid').val(parseFloat(data.sale.paid).toFixed(2));
            $('#exchange_item_total_price').val(parseFloat(data.exchange_item_total_price).toFixed(2));
            var html = '';
            $.each(data.ex_items, function (key, item) {
                html += '<tr>';
                html += '<td class="serial">'+( key + 1 )+'</td>';
                html += '<td class="text-start">';
                html += '<a class="product-name text-info" id="edit_product" title="'+(item.variant ? item.variant.variant_code : item.product.product_code )+'" href="#">'+ item.product.name +(item.variant ? ' - '+item.variant.variant_name : '')+'</a><br/>';
                html += '<input type="'+(item.product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control description_input scanable" placeholder="IMEI, Serial number or other informations here." value="'+(item.description ? item.description : '')+'">';
                html += '<input value="'+item.product_id+'" type="hidden" class="productId-'+ item.product_id +'" id="product_id" name="product_ids[]">';
                html += '<input input value="'+(item.product_variant_id ? item.product_variant_id : 'noid')+'" type="hidden" class="variantId-'+(item.product_variant_id ? item.product_variant_id : '' )+'" id="variant_id" name="variant_ids[]">';
                html += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+ item.unit_tax_percent +'">'; 
                html += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+item.unit_tax_amount+'">';
                html += '<input value="'+item.unit_discount_type+'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                html += '<input value="'+item.unit_discount+'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                html += '<input value="'+item.unit_discount_amount+'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                html += '<input value="'+item.unit_cost_inc_tax+'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                html += '<input type="hidden" id="previous_qty" value="'+item.quantity+'">';
                html += '<input type="hidden" id="qty_limit" value="'+qty_limits[key]+'">';
                html += '<input class="index-'+key+'" type="hidden" id="index">';
                html += '</td>';
        
                html += '<td>';
                html += '<input value="'+(parseFloat(parseFloat(item.quantity) + parseFloat(item.ex_quantity)).toFixed(2))+'" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                html += '</td>';
                html += '<td>';
                html += '<b><span class="span_unit">'+item.unit+'</span></b>';
                html += '<input name="units[]" type="hidden" id="unit" value="'+item.unit+'">';
                html += '</td>';
                html += '<td>';
                html += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+item.unit_price_exc_tax +'" id="unit_price_exc_tax">';
                html += '<input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="'+item.unit_price_inc_tax +'">';
                html += '<b><span class="span_unit_price_inc_tax">'+item.unit_price_inc_tax+'</span></b>';
                html += '</td>';
                html += '<td>';
                var ex_quantity = parseFloat(item.quantity) + parseFloat(item.ex_quantity);
                var subtotal = parseFloat(item.unit_price_inc_tax) * parseFloat(ex_quantity)
                html += '<input value="'+parseFloat(subtotal).toFixed(2)+'" name="subtotals[]" type="hidden" id="subtotal">';
                html += '<b><span class="span_subtotal">'+parseFloat(subtotal).toFixed(2)+'</span></b>';
                html += '</td>';
                html += '<td><a href="#" class="action-btn c-delete" id="remove_product_btn"><span class="fas fa-trash "></span></a></td>';
                html += '</tr>';
            });
          
            $('#product_list').prepend(html);
            calculateTotalAmount();
            $('#exchangeModal').modal('hide');
        }
    });
});
