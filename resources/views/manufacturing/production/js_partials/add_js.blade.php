<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('#tax_ac_id').on('change', function() {

        var tax_percent = $('#tax_ac_id').find('option:selected').data('product_tax_percent');
        $('#unit_tax_percent').val(tax_percent);
        __productPricingCalculate();
    });

    $('#tax_type').on('change', function() {

        __productPricingCalculate();
    });

    //Get process data
    $(document).on('change', '#process_id', function(e) {
        e.preventDefault();

        getIngredients();
    });

    $(document).on('change', '#stock_warehouse_id', function(e) {
        e.preventDefault();

        var process_id = $('#process_id').val()
        if (process_id) {

            getIngredients();
        }
    });

    function getIngredients() {

        var processId = $('#process_id').val();
        var stockWarehouseId = $('#stock_warehouse_id').val() ? $('#stock_warehouse_id').val() : '';

        var tax_ac_id = $('#process_id').find('option:selected').data('tax_ac_id');
        var tax_type = $('#process_id').find('option:selected').data('tax_type');
        var product_id = $('#process_id').find('option:selected').data('p_id');
        var variant_id = $('#process_id').find('option:selected').data('v_id');
        var __variant_id = variant_id ? variant_id : 'noid';
        var total_output_qty = $('#process_id').find('option:selected').data('total_output_qty');
        var unit_id = $('#process_id').find('option:selected').data('unit_id');
        var total_ingredient_cost = $('#process_id').find('option:selected').data('total_ingredient_cost');
        var additional_production_cost = $('#process_id').find('option:selected').data('addl_production_cost');
        var net_cost = $('#process_id').find('option:selected').data('net_cost');

        var url = "{{ route('manufacturing.process.ingredients.for.production', ['processId' => ':processId', 'warehouseId' => ':warehouseId']) }}";

        var route = url.replace(':processId', processId);
        route = route.replace(':warehouseId', stockWarehouseId);

        $('#ingredient_list').empty();
        __calculateTotalAmount();

        $.ajax({
            url: route,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $('#product_id').val(product_id);
                $('#variant_id').val(__variant_id);
                $('#total_output_quantity').val(total_output_qty);
                $('#total_final_output_quantity').val(total_output_qty);
                $('#total_parameter_quantity').val(total_output_qty);
                $('#unit_id').val(unit_id);
                $('#additional_production_cost').val(additional_production_cost);
                $('#total_ingredient_cost').val(total_ingredient_cost);
                $('#span_total_ingredient_cost').html(total_ingredient_cost);
                $('#net_cost').val(net_cost);
                $('#tax_ac_id').val(tax_ac_id);
                $('#tax_type').val(tax_type);

                $('#ingredient_list').html(data);
                __calculateTotalAmount();
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    }

    $(document).on('input', '#total_output_quantity', function() {

        var presentQty = $(this).val() ? $(this).val() : 0;
        var totalParameterQty = $('#total_parameter_quantity').val() ? $('#total_parameter_quantity').val() : 0;
        var meltipilerQty = parseFloat(presentQty) / parseFloat(totalParameterQty);
        var allTr = $('#ingredient_list').find('tr');

        allTr.each(function() {

            var parameterInputQty = $(this).find('#parameter_input_quantity').val();
            var updateInputQty = parseFloat(meltipilerQty) * parseFloat(parameterInputQty);
            $(this).find('#input_quantity').val(parseFloat(updateInputQty).toFixed(2));
            __calculateIngredientsTableAmount($(this));
        });

        __calculateTotalAmount();
    });

    $(document).on('input', '#total_wasted_quantity', function() {
        __calculateTotalAmount();
    });

    $(document).on('input', '#additional_production_cost', function() {
        __calculateTotalAmount();
    });

    $(document).on('input', '#input_quantity', function() {
        var value = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        tr.find('#parameter_input_quantity').val(parseFloat(value).toFixed(2));
        __calculateIngredientsTableAmount(tr);
    });

    var errorCount = 0;

    function __calculateIngredientsTableAmount(tr) {

        var inputQty = tr.find('#input_quantity').val() ? tr.find('#input_quantity').val() : 0;
        var unitCostIncTax = tr.find('#unit_cost_inc_tax').val();
        var limitQty = tr.find('#qty_limit').val();
        var unitName = tr.find('#qty_limit').data('unit');
        var regexp = /^\d+\.\d{0,2}$/;
        tr.find('#input_qty_error').html('');

        // if (regexp.test(parseFloat(inputQty)) == true) {

        //     tr.find('#input_qty_error').html('Deciaml value is not allowed.');
        //     errorCount++;
        // } else if(parseFloat(inputQty) > parseFloat(limitQty)) {

        //     tr.find('#input_qty_error').html('Only '+limitQty+' '+unitName+' is available.');
        //     errorCount++;
        // }

        if (parseFloat(inputQty) > parseFloat(limitQty)) {

            tr.find('#input_qty_error').html('Only ' + limitQty + ' ' + unitName + ' is available.');
            errorCount++;
        }

        var subtotal = parseFloat(inputQty) * parseFloat(unitCostIncTax);
        tr.find('#subtotal').val(parseFloat(subtotal).toFixed(2));
        tr.find('#span_subtotal').html(parseFloat(subtotal).toFixed(2));
        __calculateTotalAmount();
    }

    function __calculateTotalAmount() {

        var subtotals = document.querySelectorAll('#subtotal');
        var totalIngredientCost = 0;
        subtotals.forEach(function(subtotal) {

            totalIngredientCost += parseFloat(subtotal.value);
        });

        $('#total_ingredient_cost').val(parseFloat(totalIngredientCost));
        $('#span_total_ingredient_cost').html(parseFloat(totalIngredientCost).toFixed(2));
        var output_total_qty = $('#total_output_quantity').val() ? $('#total_output_quantity').val() : 0;
        var wast_qty = $('#total_wasted_quantity').val() ? $('#total_wasted_quantity').val() : 0;
        var calsQtyWithWastedQty = parseFloat(output_total_qty) - parseFloat(wast_qty);
        $('#total_final_output_quantity').val(calsQtyWithWastedQty);
        var additionalProductionCost = $('#additional_production_cost').val() ? $('#additional_production_cost').val() : 0;
        var netCost = parseFloat(totalIngredientCost) + parseFloat(additionalProductionCost);
        $('#net_cost').val(parseFloat(netCost).toFixed(2));
        __productPricingCalculate();
    }

    function __productPricingCalculate() {

        var tax_percent = $('#tax_ac_id').find('option:selected').data('product_tax_percent');

        var net_cost = $('#net_cost').val() ? $('#net_cost').val() : 0;
        var final_output_qty = $('#total_final_output_quantity').val() ? $('#total_final_output_quantity').val() : 0;

        var par_unit_cost_exc_tax = parseFloat(final_output_qty) > 0 ? parseFloat(net_cost) / parseFloat(final_output_qty) : 0;

        var tax_type = $('#tax_type').val();
        var calc_product_cost_tax = parseFloat(par_unit_cost_exc_tax) / 100 * parseFloat(tax_percent);

        if (tax_type == 2) {

            var inclusive_tax_percent = 100 + parseFloat(tax_percent);
            var calc_tax = parseFloat(par_unit_cost_exc_tax) / parseFloat(inclusive_tax_percent) * 100;
            calc_product_cost_tax = parseFloat(par_unit_cost_exc_tax) - parseFloat(calc_tax);
        }

        $('#unit_tax_amount').val(parseFloat(calc_product_cost_tax).toFixed(2));

        var per_unit_cost_inc_tax = parseFloat(par_unit_cost_exc_tax) + parseFloat(calc_product_cost_tax);
        $('#per_unit_cost_exc_tax').val(parseFloat(par_unit_cost_exc_tax).toFixed(2));
        $('#per_unit_cost_inc_tax').val(parseFloat(per_unit_cost_inc_tax).toFixed(2));

        var profit_margin = $('#profit_margin').val() ? $('#profit_margin').val() : 0;

        if (profit_margin > 0) {

            var calculate_margin = parseFloat(par_unit_cost_exc_tax) / 100 * parseFloat(profit_margin);
            var per_unit_price_exc_tax = parseFloat(par_unit_cost_exc_tax) + parseFloat(calculate_margin);
            $('#per_unit_price_exc_tax').val(parseFloat(per_unit_price_exc_tax).toFixed(2));
        }
    }

    // $('#per_unit_cost_exc_tax').on('input', function() {

    //     __productPricingCalculate();
    // });

    $('#profit_margin').on('input', function() {

        __productPricingCalculate();
    });

    $(document).on('input', '#per_unit_price_exc_tax', function() {

        var per_unit_price_exc_tax = $(this).val() ? $(this).val() : 0;
        var per_unit_cost_exc_tax = $('#per_unit_cost_exc_tax').val() ? $('#per_unit_cost_exc_tax').val() : 0;
        var profitAmount = parseFloat(per_unit_price_exc_tax) - parseFloat(per_unit_cost_exc_tax);
        var __cost = parseFloat(per_unit_cost_exc_tax) > 0 ? parseFloat(per_unit_cost_exc_tax) : parseFloat(profitAmount);
        var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __calcProfit = calcProfit ? calcProfit : 0;
        $('#profit_margin').val(parseFloat(__calcProfit).toFixed(2));
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.submit_button', function() {

        var value = $(this).val();
        $('#action_type').val(value);

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
        }
    }

    $('#add_production_form').on('submit', function(e) {
        e.preventDefault();

        if ($('#store_warehouse_count').val() != undefined && $('#store_warehouse_id').val() == '' && $('#status').val() == 1) {

            $.confirm({
                'title': 'Confirmation',
                'content': "{{ __('You have forgotten to select the warehouse. Do you want to continue?') }}",
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary',
                        'action': function() {
                            submitProductForm();
                        }
                    },
                    'No': {
                        'class': 'no btn-success',
                        'action': function() {
                            console.log('ok');
                        }
                    }
                }
            });

            return;
        }

        submitProductForm();
    });

    function submitProductForm() {
        errorCount = 0;

        $('.loading_button').show();
        var url = $('#add_production_form').attr('action');;
        var currentTitle = document.title;

        var allTr = $('#ingredient_list').find('tr');
        allTr.each(function() {

            __calculateIngredientsTableAmount($(this));
        });

        if (errorCount > 0) {

            $('.loading_button').hide();
            toastr.error("{{ __('Please check again all form fields.') }}", "{{ __('Some thing went wrong.') }}");
            return;
        }

        isAjaxIn = false;
        isAllowSubmit = false;

        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: new FormData($('#add_production_form')[0]),
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
                } else if (!$.isEmptyObject(data.successMsg)) {

                    $('#add_production_form')[0].reset();
                    $('#ingredient_list').empty();
                    toastr.success(data.successMsg);
                } else {

                    $('#add_production_form')[0].reset();
                    $('#ingredient_list').empty();
                    toastr.success('Successfully production is created.');
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
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;

                $('.loading_button').hide();
                $('.error').html('');
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error please contact to the support.') }}");
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
    }

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

            if (nextId == 'stock_warehouse_id' && $('#stock_warehouse_id').val() == undefined) {

                $('#process_id').focus();
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
