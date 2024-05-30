<script>
    var itemUnitsArray = @json($itemUnitsArray);

    function calculateTotalAmount() {

        var subtotals = document.querySelectorAll('#subtotal');
        var totalIngredientCost = 0;

        subtotals.forEach(function(subtotal) {
            totalIngredientCost += parseFloat(subtotal.value);
        });

        $('#total_ingredient_cost').val(parseFloat(totalIngredientCost));
        $('#span_total_ingredient_cost').html(parseFloat(totalIngredientCost).toFixed(2));
        var productionCost = $('#additional_production_cost').val() ? $('#additional_production_cost').val() : 0;
        var netCost = parseFloat(totalIngredientCost) + parseFloat(productionCost);
        $('#net_cost').val(parseFloat(netCost).toFixed(2));
    }

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

                        if (product.product_variants.length == 0) {

                            $('.select_area').hide();

                            var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' : product.name;
                            var unique_id = product.id + 'noid';

                            $('#search_product').val(name);
                            $('#e_unique_id').val(unique_id);
                            $('#e_item_name').val(name);
                            $('#e_product_id').val(product.id);
                            $('#e_variant_id').val('noid');
                            $('#e_final_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_unit_cost_exc_tax').val(product.product_cost);

                            $('#e_unit_tax_type').val(product.tax_type);
                            $('#e_tax_ac_id').val(product.tax_ac_id);

                            $('#e_unit_id').empty();
                            $('#e_unit_id').append('<option value="' + product.unit.id + '" data-is_base_unit="1" data-unit_name="' + product.unit.name + '" data-base_unit_multiplier="1">' + product.unit.name + '</option>');

                            itemUnitsArray[product.id] = [{
                                'unit_id': product.unit.id,
                                'unit_name': product.unit.name,
                                'unit_code_name': product.unit.code_name,
                                'base_unit_multiplier': 1,
                                'multiplier_details': '',
                                'is_base_unit': 1,
                            }];

                            calculateEditOrAddAmount();
                            $('#add_item').html('Add');
                        } else {

                            var li = "";
                            $.each(product.product_variants, function(key, variant) {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/general_default.png') }}" : "{{ asset('uploads/' . tenant('id') . '/' . 'product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                li += '<li>';
                                li += '<a href="#" class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-v_name="' + variant.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-p_code="' + variant.variant_code + '" data-p_cost_exc_tax="' + variant.variant_cost + '"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                li += '</li>';
                            });

                            $('.variant_list_area').append(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    } else if (!$.isEmptyObject(product.namedProducts)) {

                        if (product.namedProducts.length > 0) {

                            var li = "";
                            var products = product.namedProducts;

                            $.each(products, function(key, product) {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/general_default.png') }}" : "{{ asset('uploads/' . tenant('id') . '/' . 'product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                if (product.is_variant == 1) {

                                    li += '<li class="mt-1">';
                                    li += '<a href="#" class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-v_name="' + product.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-v_code="' + product.variant_code + '" data-p_cost_exc_tax="' + product.variant_cost + '" data-p_price="' + product.variant_price + '"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                    li += '</li>';
                                } else {

                                    li += '<li class="mt-1">';
                                    li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-p_name="' + product.name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '"  data-tax_type="' + product.tax_type + '" data-p_code="' + product.product_code + '" data-p_cost_exc_tax="' + product.product_cost + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                    li += '</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    } else if (!$.isEmptyObject(product.variant_product)) {

                        $('.select_area').hide();

                        var variant = product.variant_product;
                        var name = variant.product.name.length > 35 ? product.name.substring(0, 35) + '...' : variant.product.name;
                        var unique_id = variant.product.id + variant.id;

                        $('#e_unique_id').val(unique_id);
                        $('#search_product').val(name + ' - ' + variant.variant_name);
                        $('#e_item_name').val(name + ' - ' + variant.variant_name);
                        $('#e_product_id').val(variant.product.id);
                        $('#e_variant_id').val(variant.id);
                        $('#e_final_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_unit_cost_exc_tax').val(variant.variant_cost);

                        $('#e_unit_tax_type').val(variant.product.tax_type);
                        $('#e_tax_ac_id').val(variant.product.tax_ac_id);

                        $('#e_unit_id').empty();
                        $('#e_unit_id').append('<option value="' + variant.product.unit.id + '" data-is_base_unit="1" data-unit_name="' + variant.product.unit.name + '" data-base_unit_multiplier="1">' + variant.product.unit.name + '</option>');

                        itemUnitsArray[variant.product.id] = [{
                            'unit_id': variant.product.unit.id,
                            'unit_name': variant.product.unit.name,
                            'unit_code_name': variant.product.unit.code_name,
                            'base_unit_multiplier': 1,
                            'multiplier_details': '',
                            'is_base_unit': 1,
                        }];

                        calculateEditOrAddAmount();
                        $('#add_item').html('Add');
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

        var product_id = e.getAttribute('data-p_id');
        var variant_id = e.getAttribute('data-v_id');
        var product_name = e.getAttribute('data-p_name');
        var variant_name = e.getAttribute('data-v_name');
        var tax_ac_id = e.getAttribute('data-p_tax_ac_id');
        var tax_type = e.getAttribute('data-tax_type');
        var product_code = e.getAttribute('data-p_code');
        var product_cost = e.getAttribute('data-p_cost_exc_tax');

        var url = "{{ route('general.product.search.product.unit.and.multiplier.unit', [':product_id']) }}"
        var route = url.replace(':product_id', product_id);

        $.ajax({
            url: route,
            dataType: 'json',
            success: function(baseUnit) {

                var unique_id = product_id + variant_id;

                $('#e_unique_id').val(unique_id);
                $('#search_product').val(product_name + (variant_name ? ' - ' + variant_name : ''));
                $('#e_item_name').val(product_name + (variant_name ? ' - ' + variant_name : ''));
                $('#e_product_id').val(product_id);
                $('#e_variant_id').val(variant_id ? variant_id : 'noid');
                $('#e_final_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                $('#e_unit_cost_exc_tax').val(parseFloat(product_cost).toFixed(2));
                $('#e_unit_tax_type').val(tax_type);
                $('#e_tax_ac_id').val(tax_ac_id);

                $('#e_unit_id').empty();
                $('#e_unit_id').append('<option value="' + baseUnit.id + '" data-is_base_unit="1" data-unit_name="' + baseUnit.name + '" data-base_unit_multiplier="1">' + baseUnit.name + '</option>');

                itemUnitsArray[product_id] = [{
                    'unit_id': baseUnit.id,
                    'unit_name': baseUnit.name,
                    'unit_code_name': baseUnit.code_name,
                    'base_unit_multiplier': 1,
                    'multiplier_details': '',
                    'is_base_unit': 1,
                }];

                calculateEditOrAddAmount();

                $('#add_item').html('Add');

            }
        });
    }

    function calculateEditOrAddAmount() {

        var e_final_quantity = $('#e_final_quantity').val() ? $('#e_final_quantity').val() : 0;
        var e_unit_cost_exc_tax = $('#e_unit_cost_exc_tax').val() ? $('#e_unit_cost_exc_tax').val() : 0;
        var e_unit_tax_type = $('#e_unit_tax_type').val();
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0.00;

        var taxAmount = parseFloat(e_unit_cost_exc_tax) / 100 * parseFloat(e_tax_percent);
        var unitCostIncTax = parseFloat(e_unit_cost_exc_tax) + parseFloat(taxAmount);
        if (e_unit_tax_type == 2) {

            var inclusiveTax = 100 + parseFloat(e_tax_percent);
            var calcTax = parseFloat(e_unit_cost_exc_tax) / parseFloat(inclusiveTax) * 100;
            taxAmount = parseFloat(e_unit_cost_exc_tax) - parseFloat(calcTax);
            unitCostIncTax = parseFloat(e_unit_cost_exc_tax) + parseFloat(taxAmount);
        }

        $('#e_tax_amount').val(parseFloat(parseFloat(taxAmount)).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(parseFloat(unitCostIncTax)).toFixed(2));

        var subtotal = parseFloat(unitCostIncTax) * parseFloat(e_final_quantity);
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
    }

    $('#add_item').on('click', function(e) {
        e.preventDefault();

        var e_unique_id = $('#e_unique_id').val();
        var e_item_name = $('#e_item_name').val();
        var e_product_id = $('#e_product_id').val();
        var e_variant_id = $('#e_variant_id').val();
        var e_final_quantity = $('#e_final_quantity').val() ? $('#e_final_quantity').val() : 0;
        var e_unit_id = $('#e_unit_id').val();
        var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
        var e_unit_cost_exc_tax = $('#e_unit_cost_exc_tax').val() ? $('#e_unit_cost_exc_tax').val() : 0;
        var e_tax_ac_id = $('#e_tax_ac_id').val();
        var e_unit_tax_type = $('#e_unit_tax_type').val();
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
        var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
        var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;

        if (e_product_id == '') {

            toastr.error('Please select a item.');
            return;
        }

        if (e_final_quantity == '') {

            toastr.error('Quantity field must not be empty.');
            return;
        }

        var uniqueId = e_product_id + e_variant_id;

        var uniqueIdValue = $('#' + e_product_id + e_variant_id).val();

        if (uniqueIdValue == undefined) {

            var tr = '';
            tr += '<tr id="select_item">';
            tr += '<td>';
            tr += '<span id="span_item_name">' + e_item_name + '</span>';
            tr += '<input type="hidden" id="item_name" value="' + e_item_name + '">';
            tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + e_product_id + '">';
            tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + e_variant_id + '">';
            tr += '<input type="hidden" name="process_ingredient_ids[]">';
            tr += '<input type="hidden" id="' + uniqueId + '" value="' + uniqueId + '">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_quantity_unit" class="fw-bold">' + parseFloat(e_final_quantity).toFixed(2) + '/' + e_unit_name + '</span>';
            tr += '<input type="hidden" name="final_quantities[]" id="final_quantity" value="' + e_final_quantity + '">';
            tr += '<input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="' + e_unit_id + '">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_unit_cost_exc_tax" class="fw-bold">' + parseFloat(e_unit_cost_exc_tax).toFixed(2) + '</span>';
            tr += '<input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="' + e_unit_cost_exc_tax + '">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_tax_percent" class="fw-bold">' + e_tax_percent + '%' + '</span>';
            tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + e_tax_ac_id + '">';
            tr += '<input type="hidden" name="unit_tax_types[]" id="unit_tax_type" value="' + e_unit_tax_type + '">';
            tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="' + e_tax_percent + '">';
            tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + parseFloat(e_tax_amount).toFixed(2) + '">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_unit_cost_inc_tax" class="fw-bold">' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '</span>';
            tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
            tr += '<input type="hidden" name="subtotals[]" value="' + parseFloat(e_subtotal).toFixed(2) + '" id="subtotal">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
            tr += '</td>';
            tr += '</tr>';

            $('#ingredient_list').prepend(tr);
            clearEditItemFileds();
            calculateTotalAmount();
        } else {

            var tr = $('#' + uniqueId).closest('tr');
            tr.find('#item_name').val(e_item_name);
            tr.find('#span_item_name').html(e_item_name);
            tr.find('#product_id').val(e_product_id);
            tr.find('#variant_id').val(e_variant_id);
            tr.find('#final_quantity').val(parseFloat(e_final_quantity).toFixed(2));
            tr.find('#unit_id').val(e_unit_id);
            tr.find('#span_quantity_unit').html(parseFloat(e_final_quantity).toFixed(2) + '/' + e_unit_name);
            tr.find('#unit_cost_exc_tax').val(parseFloat(e_unit_cost_exc_tax).toFixed(2));
            tr.find('#span_unit_cost_exc_tax').html(parseFloat(e_unit_cost_exc_tax).toFixed(2));
            tr.find('#tax_ac_id').val(e_tax_ac_id);
            tr.find('#unit_tax_type').val(e_unit_tax_type);
            tr.find('#span_tax_percent').html(parseFloat(e_tax_percent).toFixed(2) + '%');
            tr.find('#unit_tax_percent').val(parseFloat(e_tax_percent).toFixed(2));
            tr.find('#unit_tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
            tr.find('#span_unit_cost_inc_tax').html(parseFloat(e_unit_cost_inc_tax).toFixed(2));
            tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
            tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
            clearEditItemFileds();
            calculateTotalAmount();
        }
    });

    $(document).on('click', '#select_item', function(e) {

        var tr = $(this);
        var item_name = tr.find('#item_name').val();
        var product_id = tr.find('#product_id').val();
        var variant_id = tr.find('#variant_id').val();
        var final_quantity = tr.find('#final_quantity').val();
        var unit_id = tr.find('#unit_id').val();
        var unit_cost_exc_tax = tr.find('#unit_cost_exc_tax').val();
        var tax_ac_id = tr.find('#tax_ac_id').val();
        var unit_tax_type = tr.find('#unit_tax_type').val();
        var unit_tax_amount = tr.find('#unit_tax_amount').val();
        var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
        var subtotal = tr.find('#subtotal').val();

        $('#e_unit_id').empty();

        itemUnitsArray[product_id].forEach(function(unit) {

            $('#e_unit_id').append('<option ' + (unit_id == unit.unit_id ? 'selected' : '') + ' value="' + unit.unit_id + '" data-is_base_unit="' + unit.is_base_unit + '" data-unit_name="' + unit.unit_name + '" data-base_unit_multiplier="' + unit.base_unit_multiplier + '">' + unit.unit_name + unit.multiplier_details + '</option>');
        });

        $('#search_product').val(item_name);
        $('#e_item_name').val(item_name);
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id);
        $('#e_final_quantity').val(parseFloat(final_quantity).toFixed(2)).focus().select();
        $('#e_unit_cost_exc_tax').val(parseFloat(unit_cost_exc_tax).toFixed(2));
        $('#e_tax_ac_id').val(tax_ac_id);
        $('#e_unit_tax_type').val(unit_tax_type);
        $('#e_tax_amount').val(parseFloat(unit_tax_amount).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));

        $('#add_item').html("{{ __('Update') }}");
    });

    function clearEditItemFileds() {

        $('#search_product').val('').focus();
        $('#e_unique_id').val('');
        $('#e_item_name').val('');
        $('#e_product_id').val('');
        $('#e_variant_id').val('');
        $('#e_final_quantity').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_exc_tax').val(parseFloat(0).toFixed(2));
        $('#e_tax_ac_id').val('');
        $('#e_tax_type').val(1);
        $('#e_tax_amount').val(0);
        $('#e_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
        $('#e_subtotal').val(parseFloat(0).toFixed(2));
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

    // Quantity increase or dicrease and clculate row amount
    $(document).on('input keypress', '#e_final_quantity', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '') {

                $('#e_unit_id').focus();
            }
        }
    });

    $('#e_unit_id').on('change keypress click', function(e) {

        if (e.which == 0) {

            $('#e_unit_cost_exc_tax').focus().select();
        }
    });

    // Change tax percent and clculate row amount
    $(document).on('input keypress', '#e_unit_cost_exc_tax', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '') {

                $('#e_tax_ac_id').focus().select();
            }
        }
    });

    // Change tax percent and clculate row amount
    $('#e_tax_ac_id').on('change keypress click', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 0) {

            if ($(this).val() != '') {

                $('#e_unit_tax_type').focus();
            } else {

                $('#add_item').focus();
            }
        }
    });

    $(document).on('change keypress click', '#e_unit_tax_type', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 0) {

            $('#add_item').focus();
        }
    });

    $(document).on('input', '#additional_production_cost', function() {

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
        } else {

            $(this).prop('type', 'button');
        }
    });

    document.onkeyup = function() {
        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.ctrlKey && e.which == 13) {

            $('#save').click();
            return false;
        } else if (e.which == 27) {

            $('.select_area').hide();
            $('#list').empty();
            return false;
        }
    }

    //Edit Production Process request by ajax
    $('#edit_process_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.error').html('');
                $('.loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                }

                toastr.success(data);
                window.location = "{{ url()->previous() }}";
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('body').keyup(function(e) {

        if (e.keyCode == 13) {

            $(".selectProduct").click();
            $('#list').empty();
        }
    });

    setInterval(function() {
        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function() {
        $('#search_product').removeClass('is-valid');
    }, 1000);

    $(document).on('click', function(e) {

        if ($(e.target).closest(".select_area").length === 0) {

            $('.select_area').hide();
            $('#list').empty();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            $('#' + nextId).focus().select();
        }
    });
</script>

<script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script>
