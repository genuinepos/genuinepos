<script>
    $('#customer_account_id').select2();

    $(document).on('change', '#customer_account_id', function() {

        $('#previous_due').val(parseFloat(0).toFixed(2));
        $('#earned_point').val(0);
        $('#pre_redeemed').val(0);

        var accountId = $(this).val();
        if (accountId == '') {

            return;
        }

        var branchId = "{{ auth()->user()->branch_id == null ? 'NULL' : auth()->user()->branch_id }}";
        var subSubGroupNumber = $(this).find('option:selected').data('sub_sub_group_number');
        var __branchId = subSubGroupNumber != 6 ? branchId : null;
        var filterObj = {
            branch_id: __branchId,
            from_date: null,
            to_date: null,
        };

        var url = "{{ route('accounts.balance', ':accountId') }}";
        var route = url.replace(':accountId', accountId);

        $.ajax({
            url: route,
            type: 'get',
            data: filterObj,
            success: function(data) {

                if (rpayment_settings.enable_rp == '1') {

                    $('#earned_point').val(data.reward_point);
                    var __point_amount = parseFloat(data.reward_point) * parseFloat(rpayment_settings.redeem_amount_per_unit_rp);
                    $('#trial_point_amount').val(parseFloat(__point_amount).toFixed(2));
                }

                $('#previous_due').val(parseFloat(data.closing_balance_in_flat_amount).toFixed(2));
                calculateTotalAmount();
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    });

    @if (auth()->user()->can('customer_add'))
        $('#addContact').on('click', function(e) {

            e.preventDefault();

            var url = "{{ route('contacts.create', App\Enums\ContactType::Customer->value) }}";

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#addOrEditContactModal').html(data);
                    $('#addOrEditContactModal').modal('show');

                    setTimeout(function() {

                        $('#contact_name').focus();
                    }, 500);

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
        });
    @endif

    @if (auth()->user()->can('product_add'))
        $('#addProduct').on('click', function() {

            $.ajax({
                url: "{{ route('quick.product.create') }}",
                type: 'get',
                success: function(data) {

                    $('#addQuickProductModal').empty();
                    $('#addQuickProductModal').html(data);
                    $('#addQuickProductModal').modal('show');

                    setTimeout(function() {

                        $('#quick_product_name').focus();
                    }, 1000);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.quick_product_submit_button').prop('type', 'button');
        });

        var isAllowQuickProductSubmit = true;
        $(document).on('click', '.quick_product_submit_button', function() {

            if (isAllowQuickProductSubmit) {

                $(this).prop('type', 'submit');
            } else {

                $(this).prop('type', 'button');
            }
        });

        // Add product by ajax
        $(document).on('submit', '#add_quick_product_form', function(e) {
            e.preventDefault();
            $('.quick_product_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            isQuickProductAjaxIn = false;
            isAllowQuickProductSubmit = false;

            $.ajax({
                beforeSend: function() {
                    isQuickProductAjaxIn = true;
                },
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    $('.quick_product_loading_btn').hide();
                    isQuickProductAjaxIn = true;
                    isAllowQuickProductSubmit = true;
                    $('#addQuickProductModal').empty();
                    $('#addQuickProductModal').modal('hide');
                    toastr.success("{{ __('Product is added successfully.') }}");

                    var stock = data.product_branch_stock != null ? data.product_branch_stock.stock : 0;
                    var __stock = data.is_manage_stock == 0 ? Number.MAX_SAFE_INTEGER : stock;
                    if (__stock == 0) {

                        toastr.error("{{ __('Product stock is 0') }}");
                        return;
                    }

                    $('#stock_quantity').val(stock);

                    var taxPercent = data.tax != null ? data.tax.tax_percent : 0;
                    var priceExcTax = data.product_price;
                    var taxAmount = parseFloat(priceExcTax / 100 * taxPercent);
                    var unitPriceIncTax = parseFloat(priceExcTax) + parseFloat(taxAmount);

                    if (data.tax_type == 2) {

                        var inclusiveTax = 100 + parseFloat(taxPercent)
                        var calcAmount = parseFloat(priceExcTax) / parseFloat(inclusiveTax) * 100;
                        taxAmount = parseFloat(priceExcTax) - parseFloat(calcAmount);
                        unitPriceIncTax = parseFloat(priceExcTax) + parseFloat(taxAmount);
                    }

                    var name = data.name.length > 30 ? data.name.substring(0, 30) + '...' : data.name;

                    var tr = '';
                    tr += '<tr class="product_row">';
                    tr += '<td id="serial">1</td>';
                    tr += '<td class="text-start">';
                    tr += '<a href="#" onclick="editProduct(this); return false;" id="edit_product_link" tabindex="-1">' + name + '</a><br/>';
                    tr += '<span><small id="span_description" style="font-size:9px;"></small></span>';
                    tr += '<input type="hidden" id="is_show_emi_on_pos" value="' + data.is_show_emi_on_pos + '">';
                    tr += '<input type="hidden" name="descriptions[]" id="description">';
                    tr += '<input type="hidden" id="product_name" value="' + name + '">';
                    tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + data.id + '">';
                    tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="noid">';
                    tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + (data.tax_ac_id != null ? data.tax_ac_id : '') + '">';
                    tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + data.tax_type + '">';
                    tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent"  value="' + taxPercent + '">';
                    tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + parseFloat(taxAmount).toFixed(2) + '">';
                    tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="1">';
                    tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="0">';
                    tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="0">';
                    tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + (data.product_cost_with_tax) + '">';
                    tr += ' <input type="hidden" id="current_quantity" value="0">';
                    tr += '<input type="hidden" id="current_stock" value="' + __stock + '">';
                    tr += '<input type="hidden" class="unique_id" id="' + data.id + 'noid' + '" value="' + data.id + 'noid' + '">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<span class="fw-bold" id="span_quantity">' + parseFloat(1).toFixed(2) + '</span>';
                    tr += '<input type="hidden" step="any" name="quantities[]" id="quantity" value="1.00">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<span class="fw-bold" id="span_unit">' + data.unit.name + '</span>';
                    tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + data.unit.id + '">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="' + parseFloat(priceExcTax).toFixed(2) + '">';
                    tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="' + parseFloat(unitPriceIncTax).toFixed(2) + '">';
                    tr += '<span class="fw-bold" id="span_unit_price_inc_tax">' + parseFloat(unitPriceIncTax).toFixed(2) + '</span>';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<span class="fw-bold" id="span_subtotal">' + parseFloat(unitPriceIncTax).toFixed(2) + '</span>';
                    tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(unitPriceIncTax).toFixed(2) + '">';
                    tr += '</td>';

                    tr += '<td><a href="#" class="action-btn c-delete" id="remove_product_btn" tabindex="-1"><span class="fas fa-trash"></span></a></td>';
                    tr += '</tr>';

                    $('#product_list').prepend(tr);
                    calculateTotalAmount();
                },
                error: function(err) {

                    isQuickProductAjaxIn = true;
                    isAllowQuickProductSubmit = true;
                    $('.quick_product_loading_btn').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_quick_product_' + key + '').html(error[0]);
                    });
                }
            });

            if (isQuickProductAjaxIn == false) {

                isAllowQuickProductSubmit = true;
            }
        });
    @endif
</script>
