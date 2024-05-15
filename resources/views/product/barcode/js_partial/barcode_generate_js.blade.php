<script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script>
<script>
    var taxes = @json($taxAccounts);

    var delay = (function() {

        var timer = 0;
        return function(callback, ms) {

            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $('#search_product').on('input', function(e) {

        $('.variant_list_area').empty();
        $('.select_area').hide();
        var keyWord = $(this).val();
        var __keyWord = keyWord.replaceAll('/', '~');
        delay(function() {
            searchProduct(__keyWord);
        }, 200);
    });

    function searchProduct(keyWord) {

        $('.variant_list_area').empty();
        $('.select_area').hide();

        var isShowNotForSaleItem = 1;
        var url = "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem']) }}";
        var route = url.replace(':keyWord', keyWord);
        route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);

        $.ajax({
            url: route,
            dataType: 'json',
            success: function(product) {

                if (!$.isEmptyObject(product.errorMsg)) {

                    toastr.error(product.errorMsg);
                    $('#search_product').val('');
                    return;
                }

                if (
                    !$.isEmptyObject(product.product) ||
                    !$.isEmptyObject(product.variant_product) ||
                    !$.isEmptyObject(product.namedProducts)
                ) {

                    $('#search_product').addClass('is-valid');
                    if (!$.isEmptyObject(product.product)) {

                        var product = product.product;

                        if (product.variants.length == 0) {

                            $('.select_area').hide();

                            var taxAcId = product.tax_ac_id != null ? product.tax_ac_id : 0;
                            var taxPercent = product.tax != null ? product.tax.tax_percent : 0;
                            var priceExcTax = product.product_price;
                            var priceIncTax = (priceExcTax / 100 * taxPercent) + priceExcTax;

                            if (product.tax_type == 2) {

                                var inclusiveTax = 100 + taxPercent;
                                var calcAmount = priceExcTax / inclusiveTax * 100;
                                var taxAmount = priceExcTax - calcAmount;
                                priceIncTax = priceExcTax + taxAmount;
                            }

                            var name = product.name.length > 25 ? product.name.substring(0, 25) + '...' : product.name;

                            var tr = '';
                            tr += '<tr>';
                            tr += '<td class="text-start">';
                            tr += '<span id="span_product_name">' + name + '</span>';
                            tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + product.id + '">';
                            tr += '<input type="hidden" name="product_names[]" value="' + name + '">';
                            tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="noid">';
                            tr += '<input type="hidden" name="product_variants[]" value="">';
                            tr += '<input type="hidden" name="product_codes[]" value="' + product.product_cost + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<span class="span_supplier_name"></span>';
                            tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="">';
                            tr += '<input type="hidden" name="supplier_prefixes[]" id="supplier_prefix" value="">';
                            tr += '<input type="hidden" name="tax_percents[]" id="tax_percent" value="' + taxPercent + '">';
                            tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + product.tax_type + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<input type="number" step="any" name="prices_exc_tax[]" class="form-control fw-bold" id="price_exc_tax" value="' + parseFloat(priceExcTax).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<select name="tax_ac_ids[]" class="form-control" id="tax_ac_id">';
                            tr += '<option data-tax_percent="0" value="">' + "{{ __('NoVat/Tax(0%)') }}" + '</option>';

                            taxes.forEach(function(tax) {

                                var selectedOption = tax.id == taxAcId ? 'SELECTED' : '';
                                tr += '<option ' + selectedOption + ' data-tax_percent="' + tax.tax_percent + '" value="' + tax.id + '">' + tax.name + '</option>>'
                            });

                            tr += '</select>';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<input type="number" step="any" name="prices_inc_tax[]" class="form-control fw-bold" id="price_inc_tax" value="' + parseFloat(priceIncTax).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<input type="number" name="left_quantities[]" class="form-control fw-bold" id="left_quantity" value="1">';
                            tr += '</td>';
                            tr += '<td class="text-start">';
                            tr += '<a href="#" class="btn btn-sm btn-danger remove_btn ms-1">X</a>';
                            tr += '</td>';
                            tr += '</tr>';

                            $('#barcode_product_list').prepend(tr);
                            $('#search_product').val('');
                            calculateQty();
                        } else {

                            var li = "";
                            $.each(product.variants, function(key, variant) {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/general_default.png') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                li += '<li>';
                                li += '<a href="#" onclick="selectProduct(this); return false;" data-product_id="' + product.id + '" data-variant_id="' + variant.id + '" data-product_name="' + product.name + '" data-variant_name="' + variant.variant_name + '" data-tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-tax_percent="' + (product.tax != null ? product.tax.tax_percent : 0) + '"  data-product_code="' + variant.variant_code + '" data-price_exc_tax="' + variant.variant_price + '"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                li += '</li>';
                            });

                            $('.variant_list_area').append(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    } else if(!$.isEmptyObject(product.namedProducts)) {

                        if (product.namedProducts.length > 0) {

                            var li = "";
                            var products = product.namedProducts;

                            $.each(products, function(key, product) {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/general_default.png') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                if (product.is_variant == 1) {

                                    li += '<li class="mt-1">';
                                    li += '<a  href="#" onclick="selectProduct(this); return false;" data-product_id="' + product.id + '" data-variant_id="' + product.variant_id + '" data-product_name="' + product.name + '" data-variant_name="' + product.variant_name + '" data-tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-tax_percent="' + (product.tax_percent != null ? product.tax_percent : 0) + '" data-product_code="' + product.variant_code + '" data-price_exc_tax="' + product.variant_price + '"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                    li += '</li>';
                                } else {

                                    li += '<li class="mt-1">';
                                    li += '<a href="#" onclick="selectProduct(this); return false;" data-product_id="' + product.id + '" data-variant_id="noid" data-variant_name="" data-product_name="' + product.name + '" data-tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '"  data-tax_type="' + product.tax_type + '" data-tax_percent="' + (product.tax_percent != null ? product.tax_percent : 0) + '" data-product_code="' + product.product_code + '" data-price_exc_tax="' + product.product_price + '"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                    li += '</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    } else if(!$.isEmptyObject(product.variant_product)) {

                        $('.select_area').hide();

                        var variant = product.variant_product;

                        var taxAcId = variant.product.tax_ac_id != null ? variant.product.tax_ac_id : '';
                        var taxPercent = variant.product.tax != null ? variant.product.tax.tax_percent : 0;
                        var priceExcTax = variant.variant_price;
                        var priceIncTax = (priceExcTax / 100 * taxPercent) + priceExcTax;

                        if (product.tax_type == 2) {

                            var inclusiveTax = 100 + taxPercent;
                            var calcAmount = priceExcTax / inclusiveTax * 100;
                            var taxAmount = priceExcTax - calcAmount;
                            priceIncTax = priceExcTax + taxAmount;
                        }

                        var name = variant.product.name.length > 35 ? product.name.substring(0, 35) + '...' : variant.product.name;

                        var tr = '';
                        tr += '<tr>';
                        tr += '<td class="text-start">';
                        tr += '<span id="span_product_name">' + name + '</span>';
                        tr += '<span id="span_variant_name">' + (variant.variant_name != null ? ' - ' + variant.variant_name : '') + '</span>';

                        tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + variant.product.id + '">';

                        tr += '<input type="hidden" name="product_names[]" value="' + name + '">';
                        tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + variant.id + '">';
                        tr += '<input type="hidden" name="variant_names[]" value="' + variant.variant_name + '">';
                        tr += '<input type="hidden" name="product_codes[]" value="' + variant.variant_code + '">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<span class="span_supplier_name"></span>';
                        tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="">';
                        tr += '<input type="hidden" name="supplier_prefixes[]" id="supplier_prefix" value="">';
                        tr += '<input type="hidden" name="tax_percents[]" id="tax_percent" value="' + taxPercent + '">';
                        tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + product.tax_type + '">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="number" step="any" name="prices_exc_tax[]" class="form-control fw-bold" id="price_exc_tax" value="' + parseFloat(priceExcTax).toFixed(2) + '">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<select name="tax_ac_ids[]" class="form-control" id="tax_ac_id">';
                        tr += '<option data-tax_percent="0" value="">' + "{{ __('NoVat/Tax(0%)') }}" + '</option>';

                        taxes.forEach(function(tax) {

                            var selectedOption = tax.id == taxAcId ? 'SELECTED' : '';

                            tr += '<option ' + selectedOption + ' data-tax_percent="' + tax.tax_percent + '" value="' + tax.id + '">' + tax.name + '</option>>'
                        });

                        tr += '</select>';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="number" step="any" name="prices_inc_tax[]" class="form-control fw-bold" id="price_inc_tax" value="' + parseFloat(priceIncTax).toFixed(2) + '">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="number" name="left_quantities[]" class="form-control fw-bold" id="left_quantity" value="1">';
                        tr += '</td>';
                        tr += '<td class="text-start">';
                        tr += '<a href="#" class="btn btn-sm btn-danger remove_btn ms-1">X</a>';
                        tr += '</td>';
                        tr += '</tr>';

                        $('#barcode_product_list').prepend(tr);
                        $('#search_product').val('');
                        calculateQty();
                    }
                } else {

                    $('#search_product').addClass('is-invalid');
                }
            }
        });
    }

    function selectProduct(e) {

        $('.select_area').hide();
        $('#search_product').val('');

        var productId = e.getAttribute('data-product_id');
        var variantId = e.getAttribute('data-variant_id');
        var productName = e.getAttribute('data-product_name');
        var variantName = e.getAttribute('data-variant_name');
        var taxAcId = e.getAttribute('data-tax_ac_id');
        var taxType = e.getAttribute('data-tax_type');
        var taxPercent = e.getAttribute('data-tax_percent');
        var productCode = e.getAttribute('data-product_code');
        var productPrice = e.getAttribute('data-price_exc_tax');

        var priceExcTax = productPrice;
        var priceIncTax = (parseFloat(priceExcTax) / 100 * parseFloat(taxPercent)) + parseFloat(priceExcTax);

        if (taxType == 2) {

            var inclusiveTax = 100 + parseFloat(taxPercent);
            var calcAmount = (parseFloat(priceExcTax) / parseFloat(inclusiveTax)) * 100;
            var taxAmount = parseFloat(priceExcTax) - parseFloat(calcAmount);
            priceIncTax = parseFloat(priceExcTax) + (taxAmount);
        }

        var name = productName.length > 35 ? productName.substring(0, 35) + '...' : productName;

        var tr = '';
        tr += '<tr>';
        tr += '<td class="text-start">';
        tr += '<span id="span_product_name">' + name + '</span>';
        tr += '<span id="span_variant_name">' + (variantName ? ' - ' + variantName : '') + '</span>';

        tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + productId + '">';

        tr += '<input type="hidden" name="product_names[]" value="' + name + '">';
        tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + variantId + '">';
        tr += '<input type="hidden" name="variant_names[]" value="' + variantName + '">';
        tr += '<input type="hidden" name="product_codes[]" value="' + productCode + '">';
        tr += '</td>';

        tr += '<td class="text-start">';
        tr += '<span class="span_supplier_name"></span>';
        tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="">';
        tr += '<input type="hidden" name="supplier_prefixes[]" id="supplier_prefix" value="">';
        tr += '<input type="hidden" name="tax_percents[]" id="tax_percent" value="' + taxPercent + '">';
        tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + taxType + '">';
        tr += '</td>';

        tr += '<td class="text-start">';
        tr += '<input type="number" step="any" name="prices_exc_tax[]" class="form-control fw-bold" id="price_exc_tax" value="' + parseFloat(priceExcTax).toFixed(2) + '">';
        tr += '</td>';

        tr += '<td class="text-start">';
        tr += '<select name="tax_ac_ids[]" class="form-control" id="tax_ac_id">';
        tr += '<option data-tax_percent="0" value="">' + "{{ __('NoVat/Tax(0%)') }}" + '</option>';

        taxes.forEach(function(tax) {

            var selectedOption = tax.id == taxAcId ? 'SELECTED' : '';
            tr += '<option ' + selectedOption + ' data-tax_percent="' + tax.tax_percent + '" value="' + tax.id + '">' + tax.name + '</option>>'
        });

        tr += '</select>';
        tr += '</td>';

        tr += '<td class="text-start">';
        tr += '<input type="number" step="any" name="prices_inc_tax[]" class="form-control fw-bold" id="price_inc_tax" value="' + parseFloat(priceIncTax).toFixed(2) + '">';
        tr += '</td>';

        tr += '<td class="text-start">';
        tr += '<input type="number" name="left_quantities[]" class="form-control fw-bold" id="left_quantity" value="1">';
        tr += '</td>';
        tr += '<td class="text-start">';
        tr += '<a href="#" class="btn btn-sm btn-danger remove_btn ms-1">X</a>';
        tr += '</td>';
        tr += '</tr>';

        $('#barcode_product_list').prepend(tr);
        $('#search_product').val('');
        calculateQty();

    }

    $(document).on('change', '#check_all', function() {

        if ($(this).is(':CHECKED', true)) {

            $('.check').click();
        } else {

            $('.check').click();
        }
    });

    $(document).on('click', '.check', function() {

        var tr = $(this).closest('tr');
        var product_id = tr.data('product_id');
        var product_code = tr.data('product_code');
        var product_name = tr.data('product_name');
        var variant_id = tr.data('variant_id');
        var variant_name = tr.data('variant_name');
        var tax_ac_id = tr.data('tax_ac_id');
        var tax_percent = tr.data('tax_percent');
        var tax_type = tr.data('tax_type');
        var price_exc_tax = tr.data('price_exc_tax');
        var price_inc_tax = tr.data('price_inc_tax');
        var supplier_id = tr.data('supplier_id');
        var supplier_name = tr.data('supplier_name');
        var supplier_prefix = tr.data('supplier_prefix');
        var label_qty = tr.data('label_qty');

        if ($(this).is(':CHECKED', true)) {

            var tr = '';
            tr += '<tr class="' + supplier_id + '' + product_id + '' + (variant_id ? variant_id : 'noid') + '">';
            tr += '<td class="text-start">';
            tr += '<span id="span_product_name">' + product_name + '</span>';

            if (variant_id) {

                tr += '<span id="span_variant_name">' + (variant_name ? ' - ' + variant_name : '') + '</span>';
            }

            tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + product_id + '">';

            tr += '<input type="hidden" name="product_names[]" value="' + product_name + '">';
            tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + variant_id + '">';
            tr += '<input type="hidden" name="variant_names[]" value="' + variant_name + '">';
            tr += '<input type="hidden" name="product_codes[]" value="' + product_code + '">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<span class="span_supplier_name">' + supplier_name + '</span>';
            tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="' + supplier_id + '">';
            tr += '<input type="hidden" name="supplier_prefixes[]" id="supplier_prefix" value="' + supplier_prefix + '">';
            tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + tax_type + '">';
            tr += '<input type="hidden" name="tax_percents[]" id="tax_percent" value="' + tax_percent + '">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<input type="number" step="any" name="prices_exc_tax[]" class="form-control fw-bold" id="price_exc_tax" value="' + parseFloat(price_exc_tax).toFixed(2) + '">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<select name="tax_ac_ids[]" class="form-control" id="tax_ac_id">';
            tr += '<option data-tax_percent="0" value="">NoVat/Tax(0%)</option>';
            taxes.forEach(function(tax) {

                var selectedOption = tax.id == tax_ac_id ? 'SELECTED' : '';
                tr += '<option ' + selectedOption + ' data-tax_percent="' + tax.tax_percent + '" value="' + tax.id + '">' + tax.name + '</option>>'
            });
            tr += '</select>';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<input type="number" step="any" name="prices_inc_tax[]" class="form-control fw-bold" id="price_inc_tax" value="' + parseFloat(price_inc_tax).toFixed(2) + '">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<input type="number" name="left_quantities[]" class="form-control fw-bold" id="left_quantity" value="' + label_qty + '">';
            tr += '</td>';
            tr += '</tr>';
            $('#barcode_product_list').prepend(tr);
            calculateQty();
        } else {

            $('.' + supplier_id + '' + product_id + '' + (variant_id ? variant_id : 'noid')).remove();
            calculateQty();
        }
    });

    function calculateQty() {

        var left_quantities = document.querySelectorAll('#left_quantity');
        var total_qty = 0;

        left_quantities.forEach(function(left_qty) {
            total_qty += parseFloat(left_qty.value);
        });

        $('#prepired_qty').html(total_qty);
    }

    function calculatePrice(tr) {

        var priceExcTax = tr.find('#price_exc_tax').val() ? tr.find('#price_exc_tax').val() : 0;
        var taxPercent = tr.find('#tax_ac_id').find('option:selected').data('tax_percent') ? tr.find('#tax_ac_id').find('option:selected').data('tax_percent') : 0;
        var taxType = tr.find('#tax_types').val();

        var priceIncTax = (parseFloat(priceExcTax) / 100 * parseFloat(taxPercent)) + parseFloat(priceExcTax);

        if (taxType == 2) {

            var inclusiveTax = 100 + parseFloat(taxPercent);
            var calcAmount = (parseFloat(priceExcTax) / parseFloat(inclusiveTax)) * 100;
            var taxAmount = parseFloat(priceExcTax) - parseFloat(calcAmount);
            priceIncTax = parseFloat(priceExcTax) + parseFloat(taxAmount);
        }

        tr.find('#price_inc_tax').val(parseFloat(priceIncTax).toFixed(2));
    }

    $(document).on('input', '#price_exc_tax', function() {
        var tr = $(this).closest('tr');
        calculatePrice(tr);
    });

    $(document).on('change', '#tax_ac_id', function() {

        var tr = $(this).closest('tr');

        var taxPercent = tr.find(this).find('option:selected').data('tax_percent') ? tr.find(this).find('option:selected').data('tax_percent') : 0;
        var res = tr.find('#tax_percent').val(parseFloat(taxPercent).toFixed(2));
        calculatePrice(tr);
    });

    $(document).on('input', '#left_quantity', function() {
        calculateQty();
    });

    $(document).on('click', '.remove_btn', function(e) {
        e.preventDefault();
        var tr = $(this).closest('tr').remove();
        calculateQty();
    });

    setInterval(function() {
        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function(){
        $('#search_product').removeClass('is-valid');
    }, 1000);

    $(document).on('click input keypress', '#search_product', function(e) {

        if(e.which == 13) {

            e.preventDefault();
        }
    });

    $('body').keyup(function(e) {

        if (e.keyCode == 13 || e.keyCode == 9){

            $(".selectProduct").click();
            $('#list').empty();
        }
    });
</script>

<script>
    var removableTr = '';
    $(document).on('click', '#emptyLabelQtyBtn', function(e) {

        e.preventDefault();

        removableTr = $(this).closest('tr');

        var url = $(this).attr('href');
        $('#lebel_empty_form').attr('action', url);
        $.confirm({
            'title': 'Confirmation',
            'content': "{{ __('Are you sure to delete?') }}",
            'buttons': {
                'Yes': {
                    'class': 'yes btn-modal-primary',
                    'action': function() {
                        $('#lebel_empty_form').submit();
                    }
                },
                'No': {
                    'class': 'no btn-danger',
                    'action': function() {
                        console.log('Deleted canceled.');
                    }
                }
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#lebel_empty_form', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                toastr.error(data);

                if (removableTr) {

                    removableTr.remove();
                    calculateQty();
                }
            }, error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    });
</script>
