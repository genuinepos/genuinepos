<script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var itemUnitsArray = [];
    var branch_id = "{{ auth()->user()->branch_id }}";
    var branch_name = "{{ $branchName }}";

    // Calculate total amount functionalitie
    function calculateTotalAmount() {
        var quantities = document.querySelectorAll('#quantity');
        var subtotals = document.querySelectorAll('#subtotal');

        // Update Total Item
        var total_item = 0;
        var total_qty = 0;
        quantities.forEach(function(qty) {

            total_item += 1;
            total_qty += qty.value;
        });

        $('#total_item').val(parseFloat(total_item));
        $('#total_qty').val(parseFloat(total_qty));

        // Update Net total Amount
        var netTotalAmount = 0;
        subtotals.forEach(function(subtotal) {

            netTotalAmount += parseFloat(subtotal.value);
        });

        $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));
    }

    function calculateEditOrAddAmount() {

        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;

        var subtotal = parseFloat(e_unit_cost_inc_tax) * parseFloat(e_quantity);
        console.log(subtotal);
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
    }

    // add purchase product by searching product code
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
        var keyword = $(this).val();
        var __keyword = keyword.replaceAll('/', '~');

        delay(function() {
            searchProduct(__keyword);
        }, 200);
    });

    function searchProduct(keyWord) {

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
                    $('#search_product').val("");
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

                            var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' : product.name;

                            var unique_id = product.id + 'noid';

                            $('#search_product').val(name);
                            $('#e_unique_id').val(unique_id);
                            $('#e_item_name').val(name);
                            $('#e_product_id').val(product.id);
                            $('#e_variant_id').val('noid');
                            $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_unit_cost_inc_tax').val(product.product_cost_with_tax);

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

                            $.each(product.variants, function(key, variant) {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                li += '<li class="mt-1">';
                                li += '<a onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-p_cost_inc_tax="' + variant.variant_cost_with_tax + '" data-v_name="' + variant.variant_name + '" href="#"><img style="width:20px; height:20px;"src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                li += '</li>';
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    } else if (!$.isEmptyObject(product.variant_product)) {

                        $('.select_area').hide();

                        var variant = product.variant_product;
                        var name = variant.product.name.length > 35 ? product.name.substring(0, 35) + '...' : variant_product.product.name;

                        $('#search_product').val(name + ' - ' + variant.variant_name);
                        $('#e_item_name').val(name + ' - ' + variant.variant_name);
                        $('#e_product_id').val(variant.product.id);
                        $('#e_variant_id').val(variant.id);
                        $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();

                        $('#e_unit_id').empty();
                        $('#e_unit_id').append('<option value="' + variant.product.unit.id +
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

                        calculateEditOrAddAmount();
                        $('#add_item').html('Add');
                    } else if (!$.isEmptyObject(product.namedProducts)) {

                        if (product.namedProducts.length > 0) {

                            var li = "";
                            var products = product.namedProducts;

                            $.each(products, function(key, product) {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                if (product.is_variant == 1) {

                                    li += '<li class="mt-1">';
                                    li += '<a onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-unit="' + product.unit_name + '" data-p_cost_inc_tax="' + product.variant_cost_with_tax + '" data-v_name="' + product.variant_name + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                    li += '</li>';

                                } else {

                                    li += '<li class="mt-1">';
                                    li += '<a onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-p_name="' + product.name + '" data-unit="' + product.unit_name + '" data-p_cost_inc_tax="' + product.product_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
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
            }
        });
    };

    var keyName = 1;

    function selectProduct(e) {

        $('.select_area').hide();
        $('#search_product').val('');

        var product_id = e.getAttribute('data-p_id');
        var variant_id = e.getAttribute('data-v_id');
        var product_name = e.getAttribute('data-p_name');
        var variant_name = e.getAttribute('data-v_name');
        var product_unit = e.getAttribute('data-unit');
        var unit_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');

        var url = "{{ route('general.product.search.product.unit.and.multiplier.unit', [':product_id']) }}"
        var route = url.replace(':product_id', product_id);

        $.ajax({
            url: route,
            dataType: 'json',
            success: function(baseUnit) {

                $('#search_product').val(product_name + (variant_name ? ' - ' + variant_name : ''));
                $('#e_item_name').val(product_name + (variant_name ? ' - ' + variant_name : ''));
                $('#e_product_id').val(product_id);
                $('#e_variant_id').val(variant_id ? variant_id : 'noid');
                $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));

                $('#e_unit_id').empty();
                $('#e_unit_id').append(
                    '<option value="' + baseUnit.id +
                    '" data-is_base_unit="1" data-unit_name="' + baseUnit.name +
                    '" data-base_unit_multiplier="1">' + baseUnit.name + '</option>'
                );

                itemUnitsArray[product_id] = [{
                    'unit_id': baseUnit.id,
                    'unit_name': baseUnit.name,
                    'unit_code_name': baseUnit.code_name,
                    'base_unit_multiplier': 1,
                    'multiplier_details': '',
                    'is_base_unit': 1,
                }];

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
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
        var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;

        var e_warehouse_id = $('#e_warehouse_id').val() ? $('#e_warehouse_id').val() : '';
        var warehouse_name = $('#e_warehouse_id').find('option:selected').data('w_name');

        var stock_location_name = '';
        if (e_warehouse_id) {

            stock_location_name = warehouse_name;
        } else {

            stock_location_name = branch_name;
        }

        if (e_quantity == '') {

            toastr.error('Quantity field must not be empty.');
            return;
        }

        if (e_product_id == '') {

            toastr.error('Please select a product.');
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
                if ($.isEmptyObject(data.errorMsg)) {

                    var stockLocationMessage = e_warehouse_id ? ' in selected warehouse' : ' in the company';
                    if (parseFloat(e_quantity) > parseFloat(data.stock)) {

                        toastr.error('Current stock is ' + parseFloat(data.stock) + stockLocationMessage);
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
                        tr += '<input type="hidden" class="unique_id" id="' + e_product_id + e_variant_id + e_warehouse_id + '" value="' + e_product_id + e_variant_id + e_warehouse_id + '">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="' + e_warehouse_id + '">';
                        tr += '<span id="stock_location_name">' + stock_location_name + '</span>';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<b><p id="span_quantity" class="fw-bold">' + parseFloat(e_quantity).toFixed(2) + '</p></b>';
                        tr += '<input type="hidden" name="quantities[]" id="quantity" value="' + parseFloat(e_quantity).toFixed(2) + '">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<b><span id="span_unit" class="fw-bold">' + e_unit_name + '</span></b>';
                        tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<span id="span_unit_cost_inc_tax" class="fw-bold">' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '</span>';
                        tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
                        tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                        tr += '</td>';
                        tr += '</tr>';

                        $('#stock_adjustment_product_list').append(tr);
                        clearEditItemFileds();
                        calculateTotalAmount();
                    } else {

                        var tr = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).closest('tr');

                        tr.find('#item_name').val(e_item_name);
                        tr.find('#product_id').val(e_product_id);
                        tr.find('#variant_id').val(e_variant_id);
                        tr.find('#span_unit_cost_inc_tax').html(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                        tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                        tr.find('#span_quantity').html(parseFloat(e_quantity).toFixed(2));
                        tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
                        tr.find('#span_unit').html(e_unit_name);
                        tr.find('#unit_id').val(e_unit_id);
                        tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
                        tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));

                        tr.find('.unique_id').val(e_product_id + e_variant_id + e_warehouse_id);
                        tr.find('.unique_id').attr('id', e_product_id + e_variant_id + e_warehouse_id);
                        tr.find('#warehouse_id').val(e_warehouse_id);
                        tr.find('#stock_location_name').html(stock_location_name);

                        clearEditItemFileds();
                        calculateTotalAmount();
                        return;
                    }
                } else {

                    toastr.error(data.errorMsg);
                }
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

        $('#search_product').val(item_name);
        $('#e_unique_id').val(unique_id);
        $('#e_warehouse_id').val(warehouse_id);
        $('#e_stock_location_name').val(stock_location_name);
        $('#e_item_name').val(item_name);
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id);
        $('#e_quantity').val(parseFloat(quantity).toFixed(2)).focus().select();
        $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
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

        $('#add_item').html('Edit');
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

            if ($(this).val() != '' && parseFloat($(this).val()) > 0) {

                if ($('#e_warehouse_id').val() == undefined) {

                    $('#add_item').focus();
                }else {

                    $('#e_warehouse_id').focus();
                }
            }
        }
    });

    $('#e_warehouse_id').on('change keypress click', function(e) {

        if (e.which == 0) {

            $('#add_item').focus();
        }
    });

    function clearEditItemFileds() {

        $('#search_product').val('').focus();
        $('#e_unique_id').val('');
        $('#e_item_name').val('');
        $('#e_product_id').val('');
        $('#e_variant_id').val('');
        $('#e_quantity').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
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

    //Add Stock Adjustment Request By Ajax
    $('#add_adjustment_form').on('submit', function(e) {
        e.preventDefault();

        var totalItem = $('#total_item').val();
        if (parseFloat(totalItem) == 0) {

            toastr.error('Product table is empty.', 'Some thing went wrong.');
            return;
        }

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.loading_button').hide();
                $('.error').html('');
                isAjaxIn = true;
                isAllowSubmit = true;
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                } else {

                    toastr.success(data);
                    window.location = "{{ route('stock.adjustments.index') }}";
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

                toastr.error("{{ __('Please check again all form fields.') }}", "{{ __('Some thing went wrong.') }}");

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

    $('body').keyup(function(e) {

        if (e.keyCode == 13 || e.keyCode == 9) {

            $(".selectProduct").click();
            $('#list').empty();
        }
    });

    $(document).on('click', function(e) {

        if ($(e.target).closest(".select_area").length === 0) {

            $('.select_area').hide();
            $('#list').empty();
        }
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
        }
    }

    setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            if ($(this).attr('id') == 'recovered_amount' && ($('#recovered_amount').val() == '' || $('#recovered_amount').val() == 0)) {

                $('#save').focus();
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
</script>
