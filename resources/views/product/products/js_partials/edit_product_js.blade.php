<script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('assets/plugins/custom/dropify/js/dropify.min.js') }}"></script>
<script>
    $('.select2').select2();

    var units = @json($units);

    $('#photo').dropify({
        messages: {
            'default': 'Drag and drop a file here or click',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong happended.'
        }
    });

    // Set parent category in parent category form field
    $('.combo_price').hide();
    $('.combo_pro_table_field').hide();

    function costCalculate() {

        var taxPercent = $('#tax_ac_id').find('option:selected').data('tax_percent') ? $('#tax_ac_id').find('option:selected').data('tax_percent') : 0;

        var productCostExcTax = $('#product_cost').val() ? $('#product_cost').val() : 0;
        var tax_type = $('#tax_type').val() ? $('#tax_type').val() : 1;
        var taxAmount = parseFloat(productCostExcTax) / 100 * parseFloat(taxPercent);

        if (tax_type == 2) {

            var inclusiveTaxPercent = 100 + parseFloat(taxPercent);
            var inclusiveTax = parseFloat(productCostExcTax) / parseFloat(inclusiveTaxPercent) * 100;
            taxAmount = parseFloat(productCostExcTax) - parseFloat(inclusiveTax);
        }

        var productCostIncTax = parseFloat(productCostExcTax) + parseFloat(taxAmount);

        $('#product_cost_with_tax').val(parseFloat(productCostIncTax).toFixed(2));
        var profit = $('#profit').val() ? $('#profit').val() : 0;

        if (parseFloat(profit) > 0) {

            var profitAmount = parseFloat(productCostExcTax) / 100 * parseFloat(profit);
            var productPriceExcTax = parseFloat(productCostExcTax) + parseFloat(profitAmount);
            $('#product_price').val(parseFloat(productPriceExcTax).toFixed(2));
        }

        // calc package product profit
        var netTotalComboPrice = $('#total_combo_price').val() ? $('#total_combo_price').val() : 0;
        var calcTotalComboPrice = parseFloat(netTotalComboPrice) / 100 * parseFloat(profit) + parseFloat(netTotalComboPrice);
        $('#combo_price').val(parseFloat(calcTotalComboPrice).toFixed(2));
    }

    $(document).on('input', '#product_cost', function() {

        costCalculate();
    });

    $(document).on('input', '#product_price', function() {

        var productPriceExcTax = $(this).val() ? $(this).val() : 0;
        var productCostExcTax = $('#product_cost').val() ? $('#product_cost').val() : 0;
        var profitAmount = parseFloat(productPriceExcTax) - parseFloat(productCostExcTax);
        var __cost = parseFloat(productCostExcTax) > 0 ? parseFloat(productCostExcTax) : parseFloat(profitAmount);
        var profitPercent = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __profitPercent = profitPercent ? profitPercent : 0;
        $('#profit').val(parseFloat(__profitPercent).toFixed(2));
    });

    $(document).on('change', '#tax_ac_id', function() {

        costCalculate();
    });

    $(document).on('change', '#tax_type', function() {

        costCalculate();
    });

    $(document).on('input', '#profit', function() {

        costCalculate();
    });

    // call jquery method
    $(document).ready(function() {

        // Dispose Select area
        $(document).on('click', '.remove_select_area_btn', function(e) {

            e.preventDefault();
            $('.select_area').hide();
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('change', '#category_id', function(e) {
            e.preventDefault();

            var categoryId = $(this).val();

            var url = "{{ route('subcategories.by.category.id', ':category_id') }}";
            var route = url.replace(':category_id', categoryId);

            $.ajax({
                url: route,
                type: 'get',
                success: function(subCategories) {

                    $('#sub_category_id').empty();
                    $('#sub_category_id').append('<option value="">' + "{{ __('Select Subcategory') }}" + '</option>');

                    $.each(subCategories, function(key, val) {

                        $('#sub_category_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                    });
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

            $('.product_submit_button').prop('type', 'button');
        });

        var isAllowSubmit = true;
        $(document).on('click', '.product_submit_button', function() {

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            } else {

                $(this).prop('type', 'button');
            }
        });

        document.onkeyup = function() {
            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#save_changes').click();
                return false;
            }
        }

        // Add product by ajax
        $('#edit_product_form').on('submit', function(e) {

            e.preventDefault();
            $('.loading_button').removeClass('d-hide');
            var url = $(this).attr('action');

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    $('.loading_button').addClass('d-hide');
                    $('.error').html('');

                    isAjaxIn = true;
                    isAllowSubmit = true;

                    if ($.isEmptyObject(data.errorMsg)) {

                        toastr.success(data);

                        window.location = "{{ url()->previous() }}";
                    } else {

                        toastr.error(data.errorMsg);
                    }
                },
                error: function(err) {

                    $('.loading_button').addClass('d-hide');
                    $('.error').html('');

                    isAjaxIn = true;
                    isAllowSubmit = true;

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
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
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

            if ($(this).attr('id') == 'is_variant' && $('#is_variant').val() == 0) {

                $('#type').focus().select();
            }

            $('#' + nextId).focus().select();
        }
    });

    // CkEditor
    window.editors = {};
    document.querySelectorAll('.ckEditor').forEach((node, index) => {
        ClassicEditor
            .create(node, {})
            .then(newEditor => {
                newEditor.editing.view.change(writer => {
                    var height = node.getAttribute('data-height');
                    writer.setStyle('min-height', height + 'px', newEditor.editing.view.document.getRoot());
                });
                window.editors[index] = newEditor
            });
    });
</script>

<script>
    $(document).on('click', '#addUnit', function(e) {
        e.preventDefault();

        var url = "{{ route('units.create', 0) }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#unitAddOrEditModal').html(data);
                $('#unitAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#unit_name').focus();
                }, 500);
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

    $(document).on('click', '#addCategory', function(e) {
        e.preventDefault();

        var url = "{{ route('categories.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#categoryAddOrEditModal').html(data);
                $('#categoryAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#category_name').focus();
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

    $(document).on('click', '#addBrand', function(e) {
        e.preventDefault();

        var url = "{{ route('brands.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#brandAddOrEditModal').html(data);
                $('#brandAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#brand_name').focus();
                }, 500);
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

    $(document).on('click', '#addWarranty', function(e) {

        e.preventDefault();

        var url = "{{ route('warranties.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#warrantyAddOrEditModal').html(data);
                $('#warrantyAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#warranty_name').focus();
                }, 500);
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
</script>

<script>
    $(document).on('change', '#unit_id', function(e) {

        var baseUnitId = $(this).val();
        var baseUnitName = $(this).find('option:selected').data('main_unit_name');
        if (baseUnitId == '') {

            $('.multi_unit_create_area').hide();
            $('#has_multiple_unit').val(0);
            $('#multiple_unit_body').empty();
            $('.set_variant_multiple_units').empty();
            return;
        }

        $('#multiple_unit_body').empty();
        $('.set_variant_multiple_units').empty();

        var html = '';
        html += '<tr>';
        html += '<td class="text-start" style="min-width: 100px;">';
        html += '<span id="span_base_unit_name" class="fw-bold base_unit_name">1 ' + baseUnitName + '</span>';
        html += '<input type="hidden" name="base_unit_ids[]" id="base_unit_id" value="' + baseUnitId + '">';
        html += '<input type="hidden" name="product_unit_id[]">';
        html += '</td>';

        html += '<td class="text-start">';
        html += '<p class="fw-bold">X</p>';
        html += '</td>';

        html += '<td class="text-start">';
        html += '<input type="number" step="any" name="assigned_unit_quantities[]" class="form-control fw-bold multiple_unit_required_sometimes" id="assigned_unit_quantity" placeholder="{{ __('Quantity') }}">';
        html += '<input type="hidden" name="base_unit_multipliers[]" id="base_unit_multiplier">';
        html += '</td>';

        html += '<td class="text-start" style="min-width: 127px;">';
        html += '<div class="row align-items-end">';
        html += '<div class="col-md-2">';
        html += '<p class="fw-bold p-1">1</p>';
        html += '</div>';
        html += '<div class="col-md-10">';
        html += '<select name="assigned_unit_ids[]" class="form-control assigned_unit_id multiple_unit_required_sometimes" id="assigned_unit_id" style="min-width: 110px !important;">';
        html += '<option data-assigned_unit_name="" value="">{{ __('Unit') }}</option>';
        units.forEach(function(unit) {

            html += '<option data-assigned_unit_name="' + unit.name + '" value="' + unit.id + '">' + unit.name + '</option>';
        });
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</td>';

        html += '<td class="text-start">';
        html += '<input type="number" step="any" name="assigned_unit_costs_exc_tax[]" class="form-control fw-bold" id="assigned_unit_cost_exc_tax" placeholder="{{ __('0.00') }}">';
        html += '</td>';

        html += '<td class="text-start">';
        html += '<input readonly type="number" step="any" name="assigned_unit_costs_inc_tax[]"  class="form-control fw-bold" id="assigned_unit_cost_inc_tax" placeholder="{{ __('0.00') }}">';
        html += '</td>';

        html += '<td class="text-start">';
        html += '<input type="number" step="any" name="assigned_unit_prices_exc_tax[]" class="form-control fw-bold" id="assigned_unit_price_exc_tax" placeholder="{{ __('0.00') }}">';

        html += '</td>';
        html += '<td class="text-start">';
        html += '<a href="#" id="unit_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>';
        html += '</td>';
        html += '</tr>';

        $('#multiple_unit_body').append(html);
        $('.assigned_unit_id').select2();

        var hasMultipleUnit = $('#has_multiple_unit').val();
        if (hasMultipleUnit == 1) {

            $('.multiple_unit_required_sometimes').prop('required', true);
        } else {

            $('.multiple_unit_required_sometimes').prop('required', false);
        }
    });

    $(document).on('change', '#has_multiple_unit', function(e) {

        var baseUnitId = $('#unit_id').val();
        $('.multi_unit_create_area').hide();
        $('.set_variant_multiple_units_td').hide();
        $('.multiple_unit_required_sometimes').prop('required', false);
        if (baseUnitId == '') {

            toastr.error("{{ __('Please select an unit first.') }}");
            $(this).val(0);
            return;
        }

        if ($(this).val() == 1) {

            $('.multi_unit_create_area').show();
            $('.set_variant_multiple_units_td').show();
            $('.multiple_unit_required_sometimes').prop('required', true);
        }
    });

    var count = 0;
    $(document).on('click', '#add_more_unit_btn', function(e) {
        e.preventDefault();

        var baseUnitId = $('#unit_id').val();
        var baseUnitName = $('#unit_id').find('option:selected').data('main_unit_name');

        var assignedUnitIds = document.querySelectorAll('.assigned_unit_id');
        var length = assignedUnitIds.length;
        var lastIndex = length - 1;

        if (length > 0) {

            baseUnitId = $(assignedUnitIds[lastIndex]).val();
            baseUnitName = $(assignedUnitIds[lastIndex]).find('option:selected').data('assigned_unit_name');
        }

        lastBaseUnitId = $(assignedUnitIds[lastIndex]).val();
        lastAssignedUnitQuantity = $(assignedUnitIds[lastIndex]).val();
        if (length > 0 && lastBaseUnitId == '') {

            toastr.error("{{ __('To unit is not assigned in the last conversion.') }}", "{{ __('Set Multiple Unit') }}");
            return;
        }

        var baseUnitQuantities = document.querySelectorAll('#assigned_unit_quantity');
        lastAssignedUnitQuantity = $(baseUnitQuantities[lastIndex]).val();

        if (length > 0 && (lastAssignedUnitQuantity == '' || lastAssignedUnitQuantity == 0)) {

            var msg = lastAssignedUnitQuantity == '' ? "{{ __('Quantity is empty in the last conversion.') }}" : "{{ __('Quantity is 0.00 in the last conversion.') }}";
            toastr.error(msg, "{{ __('Set Multiple Unit') }}");
            return;
        }

        var html = '';
        html += '<tr>';
        html += '<td class="text-start" style="min-width: 100px;">';
        html += '<span id="span_base_unit_name" class="fw-bold base_unit_name">1 ' + baseUnitName + '</span>';
        html += '<input type="hidden" name="base_unit_ids[]" id="base_unit_id" value="' + baseUnitId + '">';
        html += '<input type="hidden" name="product_unit_ids[]">';
        html += '</td>';

        html += '<td class="text-start">';
        html += '<p class="fw-bold">X</p>';
        html += '</td>';

        html += '<td class="text-start">';
        html += '<input required type="number" step="any" name="assigned_unit_quantities[]" class="form-control fw-bold multiple_unit_required_sometimes" id="assigned_unit_quantity" placeholder="{{ __('Quantity') }}">';
        html += '<input type="hidden" name="base_unit_multipliers[]" id="base_unit_multiplier">';
        html += '</td>';

        html += '<td class="text-start" style="min-width: 127px;">';
        html += '<div class="row align-items-end">';
        html += '<div class="col-md-2">';
        html += '<p class="fw-bold p-1">1</p>';
        html += '</div>';
        html += '<div class="col-md-10">';
        html += '<select required name="assigned_unit_ids[]" class="form-control assigned_unit_id multiple_unit_required_sometimes" id="assigned_unit_id' + count + '" style="min-width: 110px !important;">';
        html += '<option data-assigned_unit_name="" value="">{{ __('Unit') }}</option>';
        units.forEach(function(unit) {

            html += '<option data-assigned_unit_name="' + unit.name + '" value="' + unit.id + '">' + unit.name + '</option>';
        });
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</td>';

        html += '<td class="text-start">';
        html += '<input required type="number" step="any" name="assigned_unit_costs_exc_tax[]" class="form-control fw-bold" id="assigned_unit_cost_exc_tax" placeholder="{{ __('0.00') }}">';
        html += '</td>';

        html += '<td class="text-start">';
        html += '<input required readonly type="number" step="any" name="assigned_unit_costs_inc_tax[]"  class="form-control fw-bold" id="assigned_unit_cost_inc_tax" placeholder="{{ __('0.00') }}">';
        html += '</td>';

        html += '<td class="text-start">';
        html += '<input required type="number" step="any" name="assigned_unit_prices_exc_tax[]" class="form-control fw-bold" id="assigned_unit_price_exc_tax" placeholder="{{ __('0.00') }}">';
        html += '<input type="hidden" name="assigned_unit_profit_margins[]" id="assigned_unit_profit_margin">';

        html += '</td>';
        html += '<td class="text-start">';
        html += '<a href="#" id="unit_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>';
        html += '</td>';
        html += '</tr>';

        $('#multiple_unit_body').append(html);
        $('#assigned_unit_id' + count, '#multiple_unit_body').select2();
        count++;
    });

    $(document).on('input', '#assigned_unit_quantity', function(e) {

        var tr = $(this).closest('tr');
        calculateMultipleUnitCostAndPrice(tr);
    });

    $(document).on('input', '#assigned_unit_cost_exc_tax', function(e) {

        var tr = $(this).closest('tr');
        calculateMultipleUnitCostAndPrice(tr, false);
    });

    function calculateMultipleUnitCostAndPrice(tr, isAutoCalculateUnitCostAndPrice = true) {

        var currentTr = tr;
        var defaulUnitCostExcTax = $('#product_cost').val() ? $('#product_cost').val() : 0;
        var taxPercent = $('#tax_ac_id').find('option:selected').data('tax_percent') ? $('#tax_ac_id').find('option:selected').data('tax_percent') : 0;
        var taxType = $('#tax_type').val() ? $('#tax_type').val() : 1;
        var defaulUnitPriceExcTax = $('#product_price').val() ? $('#product_price').val() : 0;

        var previousTr = currentTr.prev();
        var previousBaseUnitMeltiplier = previousTr.find('#base_unit_multiplier').val() ? previousTr.find('#base_unit_multiplier').val() : 1;
        var currentAssignedUnitQuantity = currentTr.find('#assigned_unit_quantity').val() ? currentTr.find('#assigned_unit_quantity').val() : 0;
        var currentBaseUnitMultiplier = parseFloat(currentAssignedUnitQuantity) * parseFloat(previousBaseUnitMeltiplier);
        currentTr.find('#base_unit_multiplier').val(parseFloat(currentBaseUnitMultiplier));

        var assignedUnitCostExcTax = 0;
        if (parseFloat(defaulUnitCostExcTax) > 0) {

            assignedUnitCostExcTax = parseFloat(defaulUnitCostExcTax) * parseFloat(currentBaseUnitMultiplier);
            var taxAmount = (parseFloat(assignedUnitCostExcTax) / 100) * parseFloat(taxPercent);

            if (taxType == 2) {

                __taxPercent = 100 + parseFloat(taxPercent);
                var inclusiveTaxAmount = parseFloat(assignedUnitCostExcTax) / parseFloat(__taxPercent) * 100;
                taxAmount = parseFloat(assignedUnitCostExcTax) - parseFloat(inclusiveTaxAmount);
            }

            var assignedUnitCostIncTax = parseFloat(assignedUnitCostExcTax) + parseFloat(taxAmount);

            if (isAutoCalculateUnitCostAndPrice == true) {

                __assignedUnitCostExcTax = assignedUnitCostExcTax > 0 ? parseFloat(assignedUnitCostExcTax).toFixed(2) : '';
                currentTr.find('#assigned_unit_cost_exc_tax').val(__assignedUnitCostExcTax);
                __assignedUnitCostIncTax = assignedUnitCostIncTax > 0 ? parseFloat(assignedUnitCostIncTax).toFixed(2) : '';
                currentTr.find('#assigned_unit_cost_inc_tax').val(parseFloat(__assignedUnitCostIncTax).toFixed(2));
            }
        }

        if (defaulUnitPriceExcTax > 0) {

            var assignedUnitPriceExcTax = parseFloat(defaulUnitPriceExcTax) * parseFloat(currentBaseUnitMultiplier);
            if (isAutoCalculateUnitCostAndPrice == true) {

                currentTr.find('#assigned_unit_price_exc_tax').val(parseFloat(assignedUnitPriceExcTax).toFixed(2));
            }
        }

        if (isAutoCalculateUnitCostAndPrice == false) {

            var manuallyAssignedUnitCostExcTax = currentTr.find('#assigned_unit_cost_exc_tax').val() ? currentTr.find('#assigned_unit_cost_exc_tax').val() : 0;
            taxAmount = (parseFloat(manuallyAssignedUnitCostExcTax) / 100) * parseFloat(taxPercent);

            if (taxType == 2) {

                __taxPercent = 100 + parseFloat(taxPercent);
                var inclusiveTaxAmount = parseFloat(manuallyAssignedUnitCostExcTax) / parseFloat(__taxPercent) * 100;
                taxAmount = parseFloat(manuallyAssignedUnitCostExcTax) - parseFloat(inclusiveTaxAmount);
            }

            var manuallyAssignedUnitCostIncTax = parseFloat(manuallyAssignedUnitCostExcTax) + parseFloat(taxAmount);
            __manuallyAssignedUnitCostIncTax = manuallyAssignedUnitCostIncTax > 0 ? parseFloat(manuallyAssignedUnitCostIncTax).toFixed(2) : '';
            currentTr.find('#assigned_unit_cost_inc_tax').val(__manuallyAssignedUnitCostIncTax);
        }
    }


    $(document).on('click', '#unit_remove_btn', function(e) {

        e.preventDefault();
        var tr = $(this).closest('tr').remove();
    });
</script>
