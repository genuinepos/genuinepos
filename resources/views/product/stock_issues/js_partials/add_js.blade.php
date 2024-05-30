<script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('.select2').select2();

    var itemUnitsArray = [];
    var branch_id = "{{ auth()->user()->branch_id }}";
    var branch_name = "{{ $branchName }}";

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
        var keyWord = $(this).val();
        var __keyWord = keyWord.replaceAll('/', '~');

        delay(function() {
            searchProduct(__keyWord);
        }, 200); //sendAjaxical is the name of remote-command
    });

    function searchProduct(keyWord) {

        $('#search_product').focus();

        var isShowNotForSaleItem = 1;
        var url = "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem']) }}";
        var route = url.replace(':keyWord', keyWord);
        route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);

        $.ajax({
            url: route,
            dataType: 'json',
            success: function(product) {

                if (!$.isEmptyObject(product.errorMsg || keyWord == '')) {

                    toastr.error(product.errorMsg);
                    $('#search_product').val("");
                    $('.select_area').hide();
                    return;
                }

                var discount = product.discount;

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

                            if (product.is_manage_stock == 0) {

                                toastr.error("{{ __('Not-Manageable Stock/Service/Digital Product can not be added to stock issue product list.') }}");
                                return;
                            }

                            if (product.is_for_sale == 1) {

                                toastr.error("{{ __('Saleable Product can not be added to stock issue product list.') }}");
                                return;
                            }

                            var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' : product.name;

                            $('#search_product').val(name);
                            $('#e_item_name').val(name);
                            $('#e_product_id').val(product.id);
                            $('#e_variant_id').val('noid');
                            $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_unit_cost_inc_tax').val(product.update_product_cost ? product.update_product_cost.net_unit_cost : product.product_cost_with_tax);

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

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/general_default.png') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' : product.name;

                                li += '<li>';
                                li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-v_name="' + variant.variant_name + '" data-is_for_sale="' + product.is_for_sale + '" data-p_cost_inc_tax="' + (variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax) + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + variant.variant_name + '</a>';
                                li += '</li>';
                            });

                            $('.variant_list_area').append(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    } else if (!$.isEmptyObject(product.variant_product)) {

                        $('.select_area').hide();
                        $('#search_product').val('');

                        var variant = product.variant_product;

                        var stock = variant.variant_branch_stock ? variant.variant_branch_stock.all_stock : 0;

                        if (variant.product.is_manage_stock == 1) {

                            toastr.error("{{ __('Not-Manageable Stock/Service/Digital Product can not be added to used product list.') }}");
                            return;
                        }

                        var name = variant.product.name.length > 35 ? variant.product.name.substring(0, 35) + '...' : variant.product.name;

                        $('#search_product').val(name + ' - ' + variant.variant_name);
                        $('#e_item_name').val(name + ' - ' + variant.variant_name);
                        $('#e_product_id').val(variant.product.id);
                        $('#e_variant_id').val(variant.id);
                        $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_unit_cost_inc_tax').val(variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax);

                        $('#e_unit_id').empty();

                        $('#e_unit_id').append(
                            '<option value="' + variant.product.unit.id +
                            '" data-is_base_unit="1" data-unit_name="' + variant.product.unit.name +
                            '" data-base_unit_multiplier="1">' + variant.product.unit.name + '</option>'
                        );

                        itemUnitsArray[variant.product.id] = [{
                            'unit_id': variant.product.unit.id,
                            'unit_name': variant.product.unit.name,
                            'unit_code_name': variant.product.unit.code_name,
                            'base_unit_multiplier': 1,
                            'multiplier_details': '',
                            'is_base_unit': 1,
                        }];

                        if (variant.product.unit.child_units.length > 0) {

                            variant.product.unit.child_units.forEach(function(unit) {

                                var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + unit.name + ')';

                                itemUnitsArray[variant.product.id].push({
                                    'unit_id': unit.id,
                                    'unit_name': unit.name,
                                    'unit_code_name': unit.code_name,
                                    'base_unit_multiplier': unit.base_unit_multiplier,
                                    'multiplier_details': multiplierDetails,
                                    'is_base_unit': 0,
                                });

                                $('#e_unit_id').append(
                                    '<option value="' + unit.id +
                                    '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                    '" data-base_unit_multiplier="' + unit
                                    .base_unit_multiplier + '">' + unit.name +
                                    multiplierDetails + '</option>'
                                );
                            });
                        }

                        $('#add_item').html('Add');
                        calculateEditOrAddAmount();
                    } else if (!$.isEmptyObject(product.namedProducts)) {

                        if (product.namedProducts.length > 0) {

                            var li = "";
                            var products = product.namedProducts;

                            $.each(products, function(key, product) {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/general_default.png') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                var updateProductCost = product.update_product_cost != 0 && product.update_product_cost != null ? product.update_product_cost : product.product_cost_with_tax;

                                var updateVariantCost = product.update_variant_cost != 0 && product.update_variant_cost != null ? product.update_variant_cost : product.variant_cost_with_tax;

                                var __updateProductCost = product.is_variant == 1 ? updateVariantCost : updateProductCost;

                                if (product.is_variant == 1) {

                                    li += '<li>';
                                    li += '<a class="select_product" onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-v_name="' + product.variant_name + '" data-is_for_sale="' + product.is_for_sale + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-p_code="' + product.variant_code + '" data-p_cost_inc_tax="' + __updateProductCost + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                    li += '</li>';
                                } else {

                                    li += '<li>';
                                    li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-product_type="single" data-p_id="' + product.id + '" data-v_id="" data-is_manage_stock="' + product.is_manage_stock + '" data-p_name="' + product.name + '" data-v_name="" data-is_for_sale="' + product.is_for_sale + '" data-p_code="' + product.product_code + '" data-p_cost_inc_tax="' + __updateProductCost + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                    li += '</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    }
                } else {

                    $('#search_product').addClass('is-invalid');
                }
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Please check the connetion.') }}");
                    return;
                }
            }
        });
    }

    function selectProduct(e) {

        $('.select_area').hide();
        $('#search_product').val('');

        var product_id = e.getAttribute('data-p_id');
        var variant_id = e.getAttribute('data-v_id') ? e.getAttribute('data-v_id') : 'noid';
        var is_manage_stock = e.getAttribute('data-is_manage_stock');
        var is_for_sale = e.getAttribute('data-is_for_sale');
        var product_name = e.getAttribute('data-p_name');
        var variant_name = e.getAttribute('data-v_name');
        var product_code = e.getAttribute('data-p_code');
        var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');

        $('#search_product').val('');

        var url = "{{ route('general.product.search.product.unit.and.multiplier.unit', [':product_id']) }}"
        var route = url.replace(':product_id', product_id);

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(unit) {

                if ($.isEmptyObject(unit.errorMsg)) {

                    if (is_manage_stock == 0) {

                        toastr.error("{{ __('Not-Manageable Stock/Service/Digital Product can not be added to used product list.') }}");
                        return;
                    }

                    if (is_for_sale == 1) {

                        toastr.error("{{ __('Saleable Product can not be added to stock issue product list.') }}");
                        return;
                    }

                    var name = product_name.length > 35 ? product_name.substring(0, 35) + '...' : product_name;

                    $('#search_product').val(name + (variant_name ? ' - ' + variant_name : ''));
                    $('#e_item_name').val(name + (variant_name ? ' - ' + variant_name : ''));
                    $('#e_product_id').val(product_id);
                    $('#e_variant_id').val(variant_id);
                    $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                    $('#e_unit_cost_inc_tax').val(parseFloat(product_cost_inc_tax).toFixed(2));

                    $('#e_unit_id').empty();
                    $('#e_unit_id').append(
                        '<option value="' + unit.id +
                        '" data-is_base_unit="1" data-unit_name="' + unit.name +
                        '" data-base_unit_multiplier="1">' + unit.name + '</option>'
                    );

                    itemUnitsArray[product_id] = [{
                        'unit_id': unit.id,
                        'unit_name': unit.name,
                        'unit_code_name': unit.code_name,
                        'base_unit_multiplier': 1,
                        'multiplier_details': '',
                        'is_base_unit': 1,
                    }];

                    $('#add_item').html('Add');

                    calculateEditOrAddAmount();
                } else {

                    toastr.error(unit.errorMsg);
                }
            }
        });
    }

    $('#add_item').on('click', function(e) {
        e.preventDefault();

        var e_unique_id = $('#e_unique_id').val();
        var e_item_name = $('#e_item_name').val();
        var e_product_id = $('#e_product_id').val();
        var e_variant_id = $('#e_variant_id').val();
        var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
        var e_unit_id = $('#e_unit_id').val();
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;

        var e_warehouse_id = $('#e_warehouse_id').val() ? $('#e_warehouse_id').val() : '';
        var warehouse_name = $('#e_warehouse_id').find('option:selected').data('w_name');

        var stock_location_name = '';
        if (e_warehouse_id) {

            stock_location_name = warehouse_name;
        } else {

            stock_location_name = branch_name;
        }

        if (e_quantity == '') {

            toastr.error("{{ __('Quantity field must not be empty.') }}");
            return;
        }

        if (e_product_id == '') {

            toastr.error("{{ __('Please select an item.') }}");
            return;
        }

        var route = '';
        if (e_variant_id != 'noid') {

            var url = "{{ route('general.product.search.variant.product.stock', [':product_id', ':variant_id', ':warehouse_id']) }}";
            route = url.replace(':product_id', e_product_id);
            route = route.replace(':variant_id', e_variant_id);
            route = route.replace(':warehouse_id', e_warehouse_id);
        } else {

            var url = "{{ route('general.product.search.single.product.stock', [':product_id', ':warehouse_id']) }}";
            route = url.replace(':product_id', e_product_id);
            route = route.replace(':warehouse_id', e_warehouse_id);
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

                var stockLocationMessage = e_warehouse_id ? " {{ __('in selected warehouse') }} " : " {{ __('in the Shop/Business') }} ";
                if (parseFloat(e_quantity) > parseFloat(data.stock)) {

                    toastr.error("{{ __('Current stock is') }} " + parseFloat(data.stock) + '/' + e_unit_name + stockLocationMessage);
                    return;
                }

                var uniqueIdForPreventDuplicateEntry = e_product_id + e_variant_id + e_warehouse_id;
                var uniqueIdValue = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).val();

                if (uniqueIdValue == undefined) {

                    var tr = '';
                    tr += '<tr id="select_item">';
                    tr += '<td class="text-start">';
                    tr += '<span class="product_name">' + e_item_name + '</span>';
                    tr += '<input type="hidden" id="item_name" value="' + e_item_name + '">';
                    tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + e_product_id + '">';
                    tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + e_variant_id + '">';
                    tr += '<input type="hidden" data-product_name="' + e_item_name + '" data-unit_name="' + e_unit_name + '" id="stock_limit" value="' + data.stock + '">';
                    tr += '<input type="hidden" class="unique_id" id="' + e_product_id + e_variant_id + e_warehouse_id + '" value="' + e_product_id + e_variant_id + e_warehouse_id + '">';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="' + e_warehouse_id + '">';
                    tr += '<span id="stock_location_name">' + stock_location_name + '</span>';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<span id="span_quantity" class="fw-bold">' + parseFloat(e_quantity).toFixed(2) + '</span>';
                    tr += '<input type="hidden" name="quantities[]" id="quantity" value="' + parseFloat(e_quantity).toFixed(2) + '">';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<b><span id="span_unit">' + e_unit_name + '</span></b>';
                    tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '">';
                    tr += '<span id="span_unit_cost_inc_tax" class="fw-bold">' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '</span>';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
                    tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '" tabindex="-1">';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                    tr += '</td>';
                    tr += '</tr>';

                    $('#stock_issue_product_list').append(tr);
                    clearEditItemFileds();
                    calculateTotalAmount();
                } else {

                    var tr = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).closest('tr');

                    tr.find('#item_name').val(e_item_name);
                    tr.find('#product_id').val(e_product_id);
                    tr.find('#variant_id').val(e_variant_id);
                    tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                    tr.find('#span_unit_cost_inc_tax').html(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                    tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
                    tr.find('#span_quantity').html(parseFloat(e_quantity).toFixed(2));
                    tr.find('#span_unit').html(e_unit_name);
                    tr.find('#unit_id').val(e_unit_id);
                    tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
                    tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
                    tr.find('.unique_id').val(e_product_id + e_variant_id + e_warehouse_id);
                    tr.find('.unique_id').attr('id', e_product_id + e_variant_id + e_warehouse_id);
                    tr.find('#warehouse_id').val(e_warehouse_id);
                    tr.find('#stock_location_name').html(stock_location_name);

                    clearEditItemFileds();
                    calculateTotalAmount();
                }

                $('#add_item').html('Add');
            }
        })
    });

    $(document).on('click', '#select_item', function(e) {

        var tr = $(this);
        var unique_id = tr.find('#unique_id').val();
        var warehouse_id = tr.find('#warehouse_id').val();
        var stock_location_name = tr.find('#stock_location_name').html();
        var item_name = tr.find('#item_name').val();
        var product_id = tr.find('#product_id').val();
        var variant_id = tr.find('#variant_id').val();
        var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
        var quantity = tr.find('#quantity').val();
        var unit_id = tr.find('#unit_id').val();
        var subtotal = tr.find('#subtotal').val();

        $('#e_unit_id').empty();

        itemUnitsArray[product_id].forEach(function(unit) {

            $('#e_unit_id').append(
                '<option ' + (unit_id == unit.unit_id ? 'selected' : '') +
                ' value="' + unit.unit_id + '" data-is_base_unit="' + unit.is_base_unit +
                '" data-unit_name="' + unit.unit_name + '" data-base_unit_multiplier="' + unit
                .base_unit_multiplier + '">' + unit.unit_name + unit.multiplier_details +
                '</option>'
            );
        });

        $('#search_product').val(item_name);
        $('#e_unique_id').val(unique_id);
        $('#e_warehouse_id').val(warehouse_id);
        $('#e_stock_location_name').val(stock_location_name);
        $('#e_item_name').val(item_name);
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id);
        $('#e_unit_id').val(unit_id);
        $('#e_quantity').val(parseFloat(quantity).toFixed(2)).focus().select();
        $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));

        $('#add_item').html("{{ __('Update') }}");
    });

    $('#e_quantity').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '') {

                $('#e_unit_id').focus();
            }
        }
    });

    $('#e_unit_id').on('change keypress click', function(e) {

        if (e.which == 0) {

            $('#e_unit_cost_inc_tax').focus().select();
        }

        calculateEditOrAddAmount();
    });

    $('#e_unit_cost_inc_tax').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '') {

                $('#e_warehouse_id').focus().select();
            }
        }
    });

    $('#e_warehouse_id').on('change keypress click', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 0) {

            $('#add_item').focus();
        }
    });

    function calculateEditOrAddAmount() {

        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;

        var subtotal = parseFloat(e_unit_cost_inc_tax) * parseFloat(e_quantity);
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
    }

    // Calculate total amount functionalitie
    function calculateTotalAmount() {

        var quantities = document.querySelectorAll('#quantity');
        var subtotals = document.querySelectorAll('#subtotal');
        // Update Total Item

        var total_item = 0;
        quantities.forEach(function(qty) {

            total_item += 1;
        });

        $('#total_item').val(parseFloat(total_item));

        var totalQty = 0;
        quantities.forEach(function(qty) {

            totalQty += parseFloat(qty.value);
        });

        $('#total_qty').val(parseFloat(totalQty).toFixed(2));

        // Update Net total Amount
        var netTotalAmount = 0;
        var i = 0;
        subtotals.forEach(function(subtotal) {

            netTotalAmount += parseFloat(subtotal.value);
        });
        $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));
    }

    function clearEditItemFileds() {

        $('#search_product').val('').focus();
        $('#e_unique_id').val('');
        $('#e_item_name').val('');
        $('#e_product_id').val('');
        $('#e_variant_id').val('');
        $('#e_base_unit_name').val('');
        $('#e_quantity').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_inc_tax').val(0);
        $('#e_subtotal').val(parseFloat(0).toFixed(2));
        $('#e_warehouse_id').val('');
        $('#add_item').html('Add');
    }

    $(document).on('click', '#remove_product_btn', function(e) {

        e.preventDefault();

        $(this).closest('tr').remove();

        calculateTotalAmount();

        setTimeout(function() {

            clearEditItemFileds();
        }, 5);
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
        } else {

            $(this).prop('type', 'button');
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
            $('#list').empty();
            return false;
        }
    }

    //Add use stock request by ajax
    $('#add_stock_issue_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');

        var currentTitle = document.title;

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
                $('.error').html('');
                $('.loading_button').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                if (!$.isEmptyObject(data.successMsg)) {

                    toastr.success(data.successMsg);
                    afterCreateSale();
                    return;
                } else {

                    toastr.success("{{ __('Stock issue is created Successfully.') }}");
                    afterCreateSale();
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });

                    var tempElement = document.createElement('div');
                    tempElement.innerHTML = data;
                    var filename = tempElement.querySelector('#title');

                    document.title = filename.innerHTML;

                    setTimeout(function() {
                        document.title = currentTitle;
                    }, 2000);

                    return;
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

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    // Automatic remove searching product is found signal
    setInterval(function() {

        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function() {

        $('#search_product').removeClass('is-valid');
    }, 1000);

    $('.submit_button').on('click', function() {

        var value = $(this).val();
        var data_status = $(this).data('status');
        var status = $('#status').val(data_status);
        $('#action').val(value);
    });

    $('body').keyup(function(e) {

        if (e.keyCode == 13 || e.keyCode == 9) {

            if ($(".selectProduct").attr('href') == undefined) {

                return;
            }

            $(".selectProduct").click();

            $('#list').empty();
            keyName = e.keyCode;
        }
    });

    function afterCreateSale() {

        $('.loading_button').hide();
        $('.hidden').val(parseFloat(0).toFixed(2));
        $('#add_stock_issue_form')[0].reset();
        $('#stock_issue_product_list').empty();
        $("#department_id").select2("destroy");
        $("#department_id").select2();
        $("#reported_by_id").select2("destroy");
        $("#reported_by_id").select2();
        $("#date").focus();
    }

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

    $('#select_print_page_size').on('change', function() {
        var value = $(this).val();
        $('#print_page_size').val(value);
    });

    $(document).on('click', function(e) {

        if ($(e.target).closest(".select_area").length === 0) {

            $('.select_area').hide();
            $('#list').empty();
        }
    });

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            $('#' + nextId).focus().select();
        }
    });
</script>

<script>
    $(document).on('click', '#addDepartment', function(e) {
        e.preventDefault();

        var url = "{{ route('hrm.departments.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#departmentAddOrEditModal').html(data);
                $('#departmentAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#department_name').focus();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });
</script>
