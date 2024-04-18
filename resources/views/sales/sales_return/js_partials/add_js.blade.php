<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    var itemUnitsArray = [];
    var branch_name = "{{ $branchName }}";
    $('.select2').select2();

    var priceGroups = @json($priceGroupProducts);

    var ul = document.getElementById('list');
    var selectObjClassName = 'selectProduct';
    $('#sale_invoice_id').mousedown(function(e) {

        afterClickOrFocusSaleInvoiceId();
    }).focus(function(e) {

        ul = document.getElementById('invoice_list')
        selectObjClassName = 'selected_invoice';
    });

    function afterClickOrFocusSaleInvoiceId() {

        ul = document.getElementById('invoice_list')
        selectObjClassName = 'selected_invoice';
        $('#sale_invoice_id').val('');
        $('#customer_account_id').trigger('change');
        $('#current_balance').val(0.00);
        $('#sale_id').val('');
        $('#search_product').prop('disabled', false);
        $('#return_item_list').empty();
        $('.invoice_search_result').hide();
        $('#invoice_list').empty();
        calculateTotalAmount();
    }

    function afterFocusSearchItemField() {

        ul = document.getElementById('list');
        selectObjClassName = 'selectProduct';

        $('#sale_id').val('');
    }

    $('#search_product').focus(function(e) {

        afterFocusSearchItemField();
    });

    $('#sale_invoice_id').on('input', function() {

        $('.invoice_search_result').hide();

        var invoice_id = $(this).val();

        if (invoice_id === '') {

            $('.invoice_search_result').hide();
            $('#sale_id').val('');
            $('#search_product').prop('disabled', false);
            return;
        }

        var url = "{{ route('sales.search.by.invoice.id', [':invoice_id']) }}";
        var route = url.replace(':invoice_id', invoice_id);

        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.noResult)) {

                    $('.invoice_search_result').hide();
                } else {

                    $('.invoice_search_result').show();
                    $('#invoice_list').html(data);
                }
            }
        });
    });

    $(document).on('click', '#selected_invoice', function(e) {
        e.preventDefault();

        var sale_invoice_id = $(this).html();
        var sale_id = $(this).data('sale_id');
        var customer_account_id = $(this).data('customer_account_id');
        var customer_curr_balance = $(this).data('current_balance');

        var url = "{{ route('sale.products.for.sales.return', [':sale_id']) }}";
        var route = url.replace(':sale_id', sale_id);

        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    $('#sale_invoice_id').focus().select();
                    return;
                }

                itemUnitsArray = jQuery.parseJSON(data.units);

                $('#sale_invoice_id').val(sale_invoice_id.trim());
                $('#sale_id').val(sale_id);
                $('#customer_account_id').val(customer_account_id).trigger('change');
                $('#current_balance').val(customer_curr_balance);
                $('.invoice_search_result').hide();
                $('#return_item_list').empty();
                $('#search_product').prop('disabled', true);

                $('#return_item_list').html(data.view);
            }
        });
    });

    $(document).on('keyup', 'body', function(e) {

        if (e.keyCode == 13) {

            $('.' + selectObjClassName).click();
            $('.invoice_search_result').hide();
            $('.select_area').hide();
            $('#list').empty();
            $('#invoice_list').empty();
        }
    });

    var delay = (function() {
        var timer = 0;
        return function(callback, ms) {

            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $('#search_product').on('input', function(e) {
        $('.variant_list_area').empty();
        $('.select_area').hide();

        if ($('#customer_account_id').val() == '') {

            toastr.error("{{ __('Please select a listed customer first.') }}");
            $(this).val('');
            return;
        }

        var keyWord = $(this).val();
        var __keyWord = keyWord.replaceAll('/', '~');
        delay(function() {
            searchProduct(__keyWord);
        }, 200);
    });

    function searchProduct(keyWord) {

        $('#search_product').focus();
        var price_group_id = $('#price_group_id').val();

        var isShowNotForSaleItem = 1;
        var url = "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem']) }}";
        var route = url.replace(':keyWord', keyWord);
        route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);

        $.ajax({
            url: route,
            dataType: 'json',
            success: function(product) {

                if (keyWord == '') {

                    toastr.error(product.errorMsg);
                    $('#search_product').val("");
                    $('.select_area').hide();
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
                            $('#search_product').val('');

                            var price = 0;
                            var __price = priceGroups.filter(function(value) {

                                return value.price_group_id == price_group_id && value.product_id ==
                                    product.id;
                            });

                            if (__price.length != 0) {

                                price = __price[0].price ? __price[0].price : product.product_price;
                            } else {

                                price = product.product_price;
                            }

                            var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' : product.name;

                            $('#search_product').val(name);
                            $('#e_item_name').val(name);
                            $('#e_product_id').val(product.id);
                            $('#e_variant_id').val('noid');
                            $('#e_return_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#_unit_discount').val(parseFloat(0).toFixed(2));
                            $('#e_discount_type').val(1);
                            $('#e_tax_ac_id').val(product.tax_ac_id);
                            $('#e_tax_type').val(product.tax_type);
                            $('#e_unit_cost_exc_tax').val(parseFloat(product.product_cost_with_tax).toFixed(2));
                            $('#e_unit_price_exc_tax').val(parseFloat(price).toFixed(2));

                            $('#e_unit_id').empty();
                            $('#e_unit_id').append('<option value="' + product.unit.id +
                                '" data-is_base_unit="1" data-unit_name="' + product.unit.name +
                                '" data-base_unit_multiplier="1">' + product.unit.name + '</option>');

                            itemUnitsArray[product.id] = [{
                                'unit_id': product.unit.id,
                                'unit_name': product.unit.name,
                                'unit_code_name': product.unit.code_name,
                                'base_unit_multiplier': 1,
                                'multiplier_details': '',
                                'is_base_unit': 1,
                            }];

                            $('#add_item').html('Add');

                            calculateEditOrAddAmount();
                        } else {

                            var li = "";

                            $.each(product.variants, function(key, variant) {

                                var price = 0;
                                var __price = priceGroups.filter(function(value) {

                                    return value.price_group_id == price_group_id && value.product_id == product.id && value.variant_id == variant.id;
                                });

                                if (__price.length != 0) {

                                    price = __price[0].price ? __price[0].price : variant.variant_price;
                                } else {

                                    price = variant.variant_price;
                                }

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/' . tenant('id') . '/' . 'product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                li += '<li>';
                                li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-v_name="' + variant.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-p_code="' + variant.variant_code + '" data-p_cost_inc_tax="' + variant.variant_cost_with_tax + '" data-p_price_exc_tax="' + price + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + variant.variant_name + '</a>';
                                li += '</li>';
                            });

                            $('.variant_list_area').append(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    } else if (!$.isEmptyObject(product.variant_product)) {

                        $('.select_area').hide();
                        $('#search_product').val('');
                        var variant_product = product.variant_product;

                        var price = 0;
                        var __price = priceGroups.filter(function(value) {

                            return value.price_group_id == price_group_id && value.product_id == variant_product.product.id && value.variant_id == variant_product.id;
                        });

                        if (__price.length != 0) {

                            price = __price[0].price ? __price[0].price : variant_product.variant_price;
                        } else {

                            price = variant_product.variant_price;
                        }

                        var name = variant_product.product.name.length > 35 ? variant_product.product.name.substring(0, 35) + '...' : variant_product.product.name;

                        $('#search_product').val(name + ' - ' + variant_product.variant_name);
                        $('#e_item_name').val(name + ' - ' + variant_product.variant_name);
                        $('#e_product_id').val(variant_product.product.id);
                        $('#e_variant_id').val(variant_product.id);
                        $('#e_return_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_discount').val(parseFloat(0).toFixed(2));
                        $('#e_discount_type').val(1);
                        $('#e_tax_ac_id').val(variant_product.product.tax_id);
                        $('#e_tax_type').val(variant_product.product.tax_type);
                        $('#e_unit_cost_inc_tax').val(variant_product.variant_cost_with_tax);
                        $('#e_unit_price_exc_tax').val(price);

                        $('#e_unit_id').empty();
                        $('#e_unit_id').append('<option value="' + variant.product.unit.id +
                            '" data-is_base_unit="1" data-unit_name="' + variant.product.unit.name +
                            '" data-base_unit_multiplier="1">' + variant.product.unit.name + '</option>'
                        );

                        $('#add_item').html('Add');

                        calculateEditOrAddAmount();
                    } else if (!$.isEmptyObject(product.namedProducts)) {

                        if (product.namedProducts.length > 0) {

                            var li = "";
                            var products = product.namedProducts;

                            $.each(products, function(key, product) {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/' . tenant('id') . '/' . 'product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                if (product.is_variant == 1) {

                                    var price = 0;
                                    var __price = priceGroups.filter(function(value) {

                                        return value.price_group_id == price_group_id && value.product_id == product.id && value.variant_id == product.variant_id;
                                    });

                                    if (__price.length != 0) {

                                        price = __price[0].price ? __price[0].price : product.variant_price;
                                    } else {

                                        price = product.variant_price;
                                    }

                                    li += '<li>';
                                    li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-v_name="' + product.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-p_code="' + product.variant_code + '" data-p_cost_inc_tax="' + product.variant_cost_with_tax + '" data-p_price_exc_tax="' + price + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                    li += '</li>';

                                } else {

                                    var price = 0;
                                    var __price = priceGroups.filter(function(value) {

                                        return value.price_group_id == price_group_id && value.product_id == product.id;
                                    });

                                    if (__price.length != 0) {

                                        price = __price[0].price ? __price[0].price : product.product_price;
                                    } else {

                                        price = product.product_price;
                                    }

                                    li += '<li>';
                                    li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-product_type="single" data-p_id="' + product.id + '" data-v_id="" data-v_name="" data-is_manage_stock="' + product.is_manage_stock + '" data-p_name="' + product.name + '" data-p_code="' + product.product_code + '" data-tax_type="' + product.tax_type + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-p_cost_inc_tax="' + product.product_cost_with_tax + '" data-p_price_exc_tax="' + price + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                    li += '</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    }
                } else {

                    $('#search_product').addClass('is-invalid');
                    toastr.error('Product not found.', 'Failed');
                    $('#search_product').select();
                }
            }
        });
    }

    function selectProduct(e) {

        $('.select_area').hide();
        $('#search_product').val('');

        var product_id = e.getAttribute('data-p_id');
        var variant_id = e.getAttribute('data-v_id');
        var is_manage_stock = e.getAttribute('data-is_manage_stock');
        var product_name = e.getAttribute('data-p_name');
        var variant_name = e.getAttribute('data-v_name');
        var product_code = e.getAttribute('data-p_code');
        var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');
        var product_price_exc_tax = e.getAttribute('data-p_price_exc_tax');

        var p_tax_ac_id = e.getAttribute('data-p_tax_ac_id') != null ? e.getAttribute('data-p_tax_ac_id') : '';
        var p_tax_id = e.getAttribute('data-tax_id');
        var p_tax_type = e.getAttribute('data-tax_type');
        $('#search_product').val('');

        var url = "{{ route('general.product.search.product.unit.and.multiplier.unit', [':product_id']) }}"
        var route = url.replace(':product_id', product_id);

        $.ajax({
            url: route,
            dataType: 'json',
            success: function(baseUnit) {

                var name = product_name.length > 35 ? product_name.substring(0, 35) + '...' : product_name;

                $('#search_product').val(name + (variant_name ? ' - ' + variant_name : ''));
                $('#e_item_name').val(name + (variant_name ? ' - ' + variant_name : ''));
                $('#e_product_id').val(product_id);
                $('#e_variant_id').val(variant_id ? variant_id : 'noid');
                $('#e_return_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                $('#e_discount').val(parseFloat(0).toFixed(2));
                $('#e_discount_type').val(1);
                $('#e_tax_ac_id').val(p_tax_ac_id);
                $('#e_tax_type').val(p_tax_type);
                $('#e_unit_cost_inc_tax').val(parseFloat(product_cost_inc_tax).toFixed(2));
                $('#e_unit_price_exc_tax').val(parseFloat(product_price_exc_tax).toFixed(2));

                $('#e_unit_id').empty();
                $('#e_unit_id').append('<option value="' + baseUnit.id +
                    '" data-is_base_unit="1" data-unit_name="' + baseUnit.name +
                    '" data-base_unit_multiplier="1">' + baseUnit.name + '</option>');

                itemUnitsArray[product_id] = [{
                    'unit_id': baseUnit.id,
                    'unit_name': baseUnit.name,
                    'unit_code_name': baseUnit.code_name,
                    'base_unit_multiplier': 1,
                    'multiplier_details': '',
                    'is_base_unit': 1,
                }];

                $('#add_item').html('Add');

                calculateEditOrAddAmount();
            }
        });
    }

    $('#add_item').on('click', function(e) {
        e.preventDefault();

        var e_unique_id = $('#e_unique_id').val();
        var e_item_name = $('#e_item_name').val();
        var e_product_id = $('#e_product_id').val();
        var e_variant_id = $('#e_variant_id').val();
        var e_unit_id = $('#e_unit_id').val();
        var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
        var e_return_quantity = $('#e_return_quantity').val() ? $('#e_return_quantity').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
        var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;
        var e_discount_type = $('#e_discount_type').val();
        var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
        var e_tax_ac_id = $('#e_tax_ac_id').val();
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
        var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
        var e_tax_type = $('#e_tax_type').val();
        var e_unit_price_exc_tax = $('#e_unit_price_exc_tax').val() ? $('#e_unit_price_exc_tax').val() : 0;
        var e_unit_price_inc_tax = $('#e_unit_price_inc_tax').val() ? $('#e_unit_price_inc_tax').val() : 0;
        var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;

        if (e_return_quantity == '') {

            toastr.error('Quantity field must not be empty.');
            return;
        }

        if (e_product_id == '') {

            toastr.error('Please select a product.');
            return;
        }

        var uniqueIdForPreventDuplicateEntry = e_product_id + e_variant_id;
        var uniqueIdValue = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).val();

        if (uniqueIdValue == undefined) {

            var tr = '';
            tr += '<tr id="select_item">';
            tr += '<td class="text-start">';
            tr += '<span class="product_name">' + e_item_name + '</span>';
            tr += '<input type="hidden" id="item_name" value="' + e_item_name + '">';
            tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + e_product_id + '">';
            tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + e_variant_id + '">';
            tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + e_tax_ac_id + '">';
            tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + e_tax_type + '">';
            tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="' + e_tax_percent + '">';
            tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + parseFloat(e_tax_amount).toFixed(2) + '">';
            tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="' + e_discount_type + '">';
            tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="' + e_discount + '">';
            tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + e_discount_amount + '">';
            tr += '<input type="hidden" name="sale_product_ids[]" value="">';
            tr += '<input type="hidden" class="unique_id" id="' + e_product_id + e_variant_id + '" value="' + e_product_id + e_variant_id + '">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<span id="span_unit_price_inc_tax" class="fw-bold">' + parseFloat(e_unit_price_inc_tax).toFixed(2) + '</span>';
            tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="' + parseFloat(e_unit_price_exc_tax).toFixed(2) + '">';
            tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="' + parseFloat(e_unit_price_inc_tax).toFixed(2) + '">';
            tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<span id="span_sold_qty" class="fw-bold">0.00</span>';
            tr += '<input type="hidden" name="sold_quantities[]" value="0.00">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<span id="span_return_quantity" class="fw-bold">' + parseFloat(e_return_quantity).toFixed(2) + '</span>';
            tr += '<input type="hidden" name="return_quantities[]" id="return_quantity" value="' + parseFloat(e_return_quantity).toFixed(2) + '">';
            tr += '</td>';

            tr += '<td class="text text-start">';
            tr += '<span id="span_unit" class="fw-bold">' + e_unit_name + '</span>';
            tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
            tr += '</td>';

            tr += '<td class="text text-start">';
            tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
            tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '" tabindex="-1">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
            tr += '</td>';
            tr += '</tr>';

            $('#return_item_list').append(tr);
            clearEditItemFileds();
            calculateTotalAmount();
        } else {

            var tr = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).closest('tr');

            tr.find('#item_name').val(e_item_name);
            tr.find('#product_id').val(e_product_id);
            tr.find('#variant_id').val(e_variant_id);
            tr.find('#tax_ac_id').val(e_tax_ac_id);
            tr.find('#tax_type').val(e_tax_type);
            tr.find('#unit_tax_percent').val(parseFloat(e_tax_percent).toFixed(2));
            tr.find('#unit_tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
            tr.find('#unit_discount_type').val(e_discount_type);
            tr.find('#unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
            tr.find('#unit_discount').val(parseFloat(e_discount).toFixed(2));
            tr.find('#span_return_quantity').html(parseFloat(e_return_quantity).toFixed(2));
            tr.find('#return_quantity').val(parseFloat(e_return_quantity).toFixed(2));
            tr.find('#span_unit').html(e_unit_name);
            tr.find('#unit_id').val(e_unit_id);
            tr.find('#unit_price_exc_tax').val(parseFloat(e_unit_price_exc_tax).toFixed(2));
            tr.find('#unit_price_inc_tax').val(parseFloat(e_unit_price_inc_tax).toFixed(2));
            tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
            tr.find('#span_unit_price_inc_tax').html(parseFloat(e_unit_price_inc_tax).toFixed(2));
            tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
            tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
            tr.find('.unique_id').val(e_product_id + e_variant_id);
            tr.find('.unique_id').attr('id', e_product_id + e_variant_id);

            clearEditItemFileds();
            calculateTotalAmount();
        }

        $('#add_item').html('Add');
    });

    $(document).on('click', '#select_item', function(e) {

        var tr = $(this);
        var unique_id = tr.find('.unique_id').val();
        var item_name = tr.find('#item_name').val();
        var product_id = tr.find('#product_id').val();
        var unit_id = tr.find('#unit_id').val();
        var variant_id = tr.find('#variant_id').val();
        var tax_ac_id = tr.find('#tax_ac_id').val();
        var tax_type = tr.find('#tax_type').val();
        var unit_tax_percent = tr.find('#unit_tax_percent').val();
        var unit_tax_amount = tr.find('#unit_tax_amount').val();
        var unit_discount_type = tr.find('#unit_discount_type').val();
        var unit_discount_amount = tr.find('#unit_discount_amount').val();
        var unit_discount = tr.find('#unit_discount').val();
        var unit_price_exc_tax = tr.find('#unit_price_exc_tax').val();
        var unit_price_inc_tax = tr.find('#unit_price_inc_tax').val();
        var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
        var return_quantity = tr.find('#return_quantity').val();
        var subtotal = tr.find('#subtotal').val();

        console.log(unit_price_exc_tax);

        $('#e_unit_id').empty();

        itemUnitsArray[product_id].forEach(function(unit) {

            $('#e_unit_id').append('<option ' + (unit_id == unit.unit_id ? 'selected' : '') +
                ' value="' + unit.unit_id + '" data-is_base_unit="' + unit.is_base_unit +
                '" data-unit_name="' + unit.unit_name + '" data-base_unit_multiplier="' + unit
                .base_unit_multiplier + '">' + unit.unit_name + unit.multiplier_details +
                '</option>');
        });

        $('#search_product').val(item_name);
        $('#e_item_name').val(item_name);
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id);
        $('#e_return_quantity').val(parseFloat(return_quantity).toFixed(2)).focus().select();
        $('#e_unique_id').val(unique_id);
        $('#e_unit_price_exc_tax').val(unit_price_exc_tax);
        $('#e_unit_price_inc_tax').val(unit_price_inc_tax);
        $('#e_unit_cost_inc_tax').val(unit_cost_inc_tax);
        $('#e_discount').val(unit_discount);
        $('#e_discount_type').val(unit_discount_type);
        $('#e_discount_amount').val(unit_discount_amount);
        $('#e_tax_ac_id').val(tax_ac_id);
        $('#e_tax_amount').val(unit_tax_amount);
        $('#e_tax_type').val(tax_type);
        $('#e_subtotal').val(subtotal);
        $('#add_item').html('Edit');
    });

    function calculateEditOrAddAmount() {

        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0.00;
        var e_tax_type = $('#e_tax_type').val();
        var e_discount_type = $('#e_discount_type').val();
        var e_return_quantity = $('#e_return_quantity').val() ? $('#e_return_quantity').val() : 0;
        var e_unit_price_exc_tax = $('#e_unit_price_exc_tax').val() ? $('#e_unit_price_exc_tax').val() : 0;
        var e_unit_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;

        var discount_amount = 0;
        if (e_discount_type == 1) {

            discount_amount = e_unit_discount;
        } else {

            discount_amount = (parseFloat(e_unit_price_exc_tax) / 100) * parseFloat(e_unit_discount);
        }

        var unitPriceWithDiscount = parseFloat(e_unit_price_exc_tax) - parseFloat(discount_amount);
        var taxAmount = (parseFloat(unitPriceWithDiscount) / 100) * parseFloat(e_tax_percent);
        var unitPriceIncTax = parseFloat(unitPriceWithDiscount) + parseFloat(taxAmount);

        if (e_tax_type == 2) {

            var inclusiveTax = 100 + parseFloat(e_tax_percent);
            var calcTax = parseFloat(unitPriceWithDiscount) / parseFloat(inclusiveTax) * 100;
            taxAmount = parseFloat(unitPriceWithDiscount) - parseFloat(calcTax);
            unitPriceIncTax = parseFloat(unitPriceWithDiscount) + parseFloat(taxAmount);
        }

        $('#e_tax_amount').val(parseFloat(taxAmount).toFixed(2));
        $('#e_discount_amount').val(parseFloat(discount_amount).toFixed(2));
        $('#e_unit_price_inc_tax').val(parseFloat(unitPriceIncTax).toFixed(2));

        var subtotal = parseFloat(unitPriceIncTax) * parseFloat(e_return_quantity);
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
    }

    $('#e_return_quantity').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '') {

                $('#e_unit_id').focus();
            }
        }
    });

    $('#e_unit_id').on('change keypress click', function(e) {

        if (e.which == 0) {

            $('#e_unit_price_exc_tax').focus().select();
        }

        calculateEditOrAddAmount();
    });

    $('#e_unit_price_exc_tax').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            $('#e_discount').focus().select();
        }
    });

    $('#e_discount').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            $('#e_discount_type').focus();
        }
    });

    $('#e_discount_type').on('change keypress click', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 0) {

            $('#e_tax_ac_id').focus();
        }
    });

    $('#e_tax_ac_id').on('change keypress click', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 0) {

            $('#e_tax_type').focus();
        }
    });

    $('#e_tax_type').on('change keypress click', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 0) {

            $('#add_item').focus();
        }
    });

    // Calculate total amount functionalitie
    function calculateTotalAmount() {

        var quantities = document.querySelectorAll('#return_quantity');
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
        $('#total_qty').val(parseFloat(total_qty));

        // Update Net total Amount
        var netTotalAmount = 0;
        var itemTotalTaxAmount = 0;
        var i = 0;
        subtotals.forEach(function(subtotal) {

            netTotalAmount += parseFloat(subtotal.value);
            itemTotalTaxAmount += (quantities[i].value ? quantities[i].value : 0) * (unitTaxAmounts[i].value ? unitTaxAmounts[i].value : 0);
            i++;
        });

        $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

        if ($('#return_discount_type').val() == 2) {

            var returnDisAmount = parseFloat(netTotalAmount) / 100 * parseFloat($('#return_discount').val() ? $('#return_discount').val() : 0);
            $('#return_discount_amount').val(parseFloat(returnDisAmount).toFixed(2));
        } else {

            var returnDiscount = $('#return_discount').val() ? $('#return_discount').val() : 0;
            $('#return_discount_amount').val(parseFloat(returnDiscount).toFixed(2));
        }

        var returnDiscountAmount = $('#return_discount_amount').val() ? $('#return_discount_amount').val() : 0;

        // Calc order tax amount
        var returnTaxPercent = $('#return_tax_ac_id').find('option:selected').data('return_tax_percent') ? $('#return_tax_ac_id').find('option:selected').data('return_tax_percent') : 0;
        var calReturnTaxAmount = (parseFloat(netTotalAmount) - parseFloat(returnDiscountAmount)) / 100 * parseFloat(returnTaxPercent);

        $('#return_tax_amount').val(parseFloat(calReturnTaxAmount).toFixed(2));

        var calcTotalAmount = parseFloat(netTotalAmount) - parseFloat(returnDiscountAmount) + parseFloat(calReturnTaxAmount);

        $('#total_return_amount').val(parseFloat(calcTotalAmount).toFixed(2));

        var salesLedgerAmount = parseFloat(netTotalAmount) -
            parseFloat(returnDiscountAmount) -
            parseFloat(itemTotalTaxAmount);

        $('#sale_ledger_amount').val(salesLedgerAmount);

        var paidAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
        var closingBalance = $('#closing_balance').val() ? $('#closing_balance').val() : 0;
        var accountDefaultBalanceType = $('#customer_account_id').find('option:selected').data('default_balance_type');
        var currentBalance = 0;
        if (accountDefaultBalanceType == 'dr') {

            currentBalance = parseFloat(closingBalance) - parseFloat(calcTotalAmount) + parseFloat(paidAmount);
        } else {

            currentBalance = parseFloat(closingBalance) + parseFloat(calcTotalAmount) - parseFloat(paidAmount);
        }

        $('#current_balance').val(parseFloat(currentBalance).toFixed(2));
    }

    $(document).on('input', '#return_discount', function() {

        calculateTotalAmount();
    });

    $(document).on('change', '#return_tax_ac_id', function() {

        calculateTotalAmount();
        var returnTaxPercent = $(this).find('option:selected').data('return_tax_percent') ? $(this).find('option:selected').data('return_tax_percent') : 0;
        $('#return_tax_percent').val(parseFloat(returnTaxPercent).toFixed(2));
    });

    $(document).on('input', '#paying_amount', function() {

        calculateTotalAmount();
    });

    $(document).on('click', '#remove_product_btn', function(e) {
        e.preventDefault();

        $(this).closest('tr').remove();
        calculateTotalAmount();
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.submit_button', function() {

        var value = $(this).val();
        $('#action').val(value);

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    document.onkeyup = function() {

        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.ctrlKey && e.which == 13) {

            $('#save_and_print').click();
            return false;
        } else if (e.shiftKey && e.which == 13) {

            $('#save').click();
            return false;
        } else if (e.which == 27) {

            $('.select_area').hide();
            $('.invoice_search_result').hide();

            $('#list').empty();
            $('#invoice_list').empty();

            return false;
        }
    }

    //Add Sales return request by ajax
    $('#add_sales_return_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                $('.error').html('');

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                if (!$.isEmptyObject(data.successMsg)) {

                    toastr.success(data.successMsg);
                    afterCreateSalesReturn();
                } else {

                    toastr.success("{{ __('Successfully Sale return is created.') }}");
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });

                    afterCreateSalesReturn();
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    function clearEditItemFileds() {

        $('#e_unique_id').val('');
        $('#search_product').val('').focus();
        $('#e_item_name').val('');
        $('#e_product_id').val('');
        $('#e_variant_id').val('');
        $('#e_return_quantity').val(0.00);
        $('#e_discount').val(parseFloat(0).toFixed(2));
        $('#e_discount_type').val(1);
        $('#e_discount_amount').val(parseFloat(0).toFixed(2));
        $('#e_tax_ac_id').val('');
        $('#e_tax_amount').val(parseFloat(0).toFixed(2));
        $('#e_tax_type').val(1);
        $('#e_unit_price_exc_tax').val(parseFloat(0).toFixed(2));
        $('#e_unit_price_inc_tax').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
        $('#e_subtotal').val(parseFloat(0).toFixed(2));
    }

    function afterCreateSalesReturn() {

        $('.loading_button').hide();
        $('#sale_id').val('');
        $('#add_sales_return_form')[0].reset();
        $('#return_item_list').empty();

        $('#current_balance').val(0);

        $('#search_product').prop('disabled', false);

        $("#customer_account_id").select2("destroy");
        $("#customer_account_id").select2();

        $("#sale_account_id").select2("destroy");
        $("#sale_account_id").select2();

        document.getElementById('customer_account_id').focus();
    }

    // Automatic remove searching product is found signal
    setInterval(function() {

        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function() {

        $('#search_product').removeClass('is-valid');
    }, 1000);

    $(document).on('change', '#customer_account_id', function() {

        $('#closing_balance').val(parseFloat(0).toFixed(2));

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

                $('#closing_balance').val(parseFloat(data.closing_balance_in_flat_amount).toFixed(2));
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

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            if ($(this).attr('id') == 'price_group_id' && $('#search_product').is(':disabled') == true) {

                $('#e_return_quantity').focus().select();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            if (nextId == 'warehouse_id' && $('#warehouse_id').val() == undefined) {

                $('#sale_account_id').focus();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    var dateFormat = "{{ $generalSettings['business_or_shop__date_format'] }}";
    var _expectedDateFormat = '';
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });

    $('#payment_method_id').on('change', function() {

        var account_id = $(this).find('option:selected').data('account_id');
        setMethodAccount(account_id);
    });

    function setMethodAccount(account_id) {

        if (account_id) {

            $('#account_id').val(account_id);
        } else if (account_id === '') {
            return;
            // $('#account_id option:first-child').prop("selected", true);
        }
    }

    setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));

    setTimeout(function() {

        $('#customer_account_id').focus().select();
    }, 1000);

    $('#select_print_page_size').on('change', function() {
        var value = $(this).val();
        $('#print_page_size').val(value);
    });
</script>

<script src="{{ asset('assets/plugins/custom/select_li/selectli.custom.js') }}"></script>
