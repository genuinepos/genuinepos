<script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
<script>
    $('.select2').select2();

    var productTable = $('.data_tbl').DataTable({
        "processing": true,
        "serverSide": true,
        // aaSorting: [[0, 'asc']],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('products.index', App\Enums\BooleanType::True->value) }}",
        },
        columns: [{
                data: 'name',
                name: 'name'
            },
            {
                data: 'product_cost_with_tax',
                name: 'product_cost_with_tax',
                className: 'fw-bold'
            },
            {
                data: 'product_price',
                name: 'product_price',
                className: 'fw-bold'
            },
            {
                data: 'action',
                name: 'name'
            },
        ],
    });

    // Set parent category in parent category form field
    $('.combo_price').hide();
    $('.combo_pro_table_field').hide();

    function costCalculate() {

        var tax_percent = $('#tax_ac_id').find('option:selected').data('tax_percent');
        var product_cost = $('#product_cost').val() ? $('#product_cost').val() : 0;
        var tax_type = $('#tax_type').val();
        var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent);

        if (tax_type == 2) {

            var __tax_percent = 100 + parseFloat(tax_percent);
            var calc_tax = parseFloat(product_cost) / parseFloat(__tax_percent) * 100;
            calc_product_cost_tax = parseFloat(product_cost) - parseFloat(calc_tax);
        }

        var product_cost_with_tax = parseFloat(product_cost) + calc_product_cost_tax;
        $('#product_cost_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
        var profit = $('#profit').val() ? $('#profit').val() : 0;

        if (parseFloat(profit) > 0) {

            var calculate_profit = parseFloat(product_cost) / 100 * parseFloat(profit);
            var product_price = parseFloat(product_cost) + parseFloat(calculate_profit);
            $('#product_price').val(parseFloat(product_price).toFixed(2));
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

        var selling_price = $(this).val() ? $(this).val() : 0;
        var product_cost = $('#product_cost').val() ? $('#product_cost').val() : 0;
        var profitAmount = parseFloat(selling_price) - parseFloat(product_cost);
        var __cost = parseFloat(product_cost) > 0 ? parseFloat(product_cost) : parseFloat(profitAmount);
        var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __calcProfit = calcProfit ? calcProfit : 0;
        $('#profit').val(parseFloat(__calcProfit).toFixed(2));
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

    // Variant all functionality
    var variantsWithChild = @json($bulkVariants);

    var variant_row_index = 0;
    $(document).on('change', '#variants', function() {
        var id = $(this).val();
        var parentTableRow = $(this).closest('tr');
        variant_row_index = parentTableRow.index();

        $('.modal_variant_child').empty();

        var html = '';

        var variant = variantsWithChild.filter(function(variant) {
            return variant.id == id;
        });

        $.each(variant[0].bulk_variant_child, function(key, child) {
            html += '<li class="modal_variant_child_list">';
            html += '<a class="select_variant_child" data-child="' + child.name + '" href="#">' + child.name + '</a>';
            html += '</li>';
        });

        $('.modal_variant_child').html(html);
        $('#VairantChildModal').modal('show');
        $(this).val('');
    });

    $(document).on('click', '.select_variant_child', function(e) {

        e.preventDefault();
        var child = $(this).data('child');
        var parent_tr = $('.dynamic_variant_body tr:nth-child(' + (variant_row_index + 1) + ')');
        var child_value = parent_tr.find('#variant_combination').val();
        var filter = child_value == '' ? '' : ',';
        var variant_combination = parent_tr.find('#variant_combination').val(child_value + filter + child);
        $('#VairantChildModal').modal('hide');
    });

    $(document).on('input', '#variant_costing', function() {

        var parentTableRow = $(this).closest('tr');
        variant_row_index = parentTableRow.index();
        calculateVariantAmount(variant_row_index);
    });

    $(document).on('input', '#variant_profit', function() {

        var parentTableRow = $(this).closest('tr');
        variant_row_index = parentTableRow.index();
        calculateVariantAmount(variant_row_index);
    });

    function calculateVariantAmount(variant_row_index) {

        var parent_tr = $('.dynamic_variant_body tr:nth-child(' + (variant_row_index + 1) + ')');
        var tax = $('#tax_ac_id').find('option:selected').data('tax_percent');
        var variant_costing = parent_tr.find('#variant_costing');
        var variant_costing_with_tax = parent_tr.find('#variant_costing_with_tax');
        var variant_profit = parent_tr.find('#variant_profit').val() ? parent_tr.find('#variant_profit').val() : 0.00;
        var variant_price_exc_tax = parent_tr.find('#variant_price_exc_tax');

        var tax_rate = parseFloat(variant_costing.val()) / 100 * tax;
        var cost_with_tax = parseFloat(variant_costing.val()) + tax_rate;
        variant_costing_with_tax.val(parseFloat(cost_with_tax).toFixed(2));

        var profit = parseFloat(variant_costing.val()) / 100 * parseFloat(variant_profit) + parseFloat(variant_costing.val());
        variant_price_exc_tax.val(parseFloat(profit).toFixed(2));
    }

    var variant_code_sequel = 0;
    // Select Variant and show variant creation area
    $(document).on('change', '#is_variant', function() {

        var product_cost = $('#product_cost').val();
        var product_cost_with_tax = $('#product_cost_with_tax').val();
        var profit = $('#profit').val();
        var product_price = $('#product_price').val();

        if ($(this).val() == 1 && (product_cost == '' || product_price == '')) {

            $(this).val(0);
            alert('After creating the variant, product cost and product price field must not be empty.');
            return;
        }

        var code = $('#code').val();
        var auto_generated_code = $('#auto_generated_code').val();
        var variant_code = code ? code + '-' + (++variant_code_sequel) : auto_generated_code + '-' + (++variant_code_sequel);

        $('#variant_code').val(variant_code);
        $('#variant_costing').val(parseFloat(product_cost).toFixed(2));
        $('#variant_costing_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
        $('#variant_price_exc_tax').val(parseFloat(product_price).toFixed(2));
        $('#variant_profit').val(parseFloat(profit).toFixed(2));

        if ($(this).val() == 1) {

            $('.dynamic_variant_create_area').show(500);
            $('#variant_combination').prop('required', true);
            $('#variant_costing').prop('required', true);
            $('#variant_costing_with_tax').prop('required', true);
            $('#variant_profit').prop('required', true);
            $('#variant_price_exc_tax').prop('required', true);
        } else {

            $('.dynamic_variant_create_area').hide(500);
            $('#variant_combination').prop('required', false);
            $('#variant_costing').prop('required', false);
            $('#variant_costing_with_tax').prop('required', false);
            $('#variant_profit').prop('required', false);
            $('#variant_price_exc_tax').prop('required', false);
        }
    });

    // Get default profit
    var defaultProfit = {{ $generalSettings['business_or_shop__default_profit'] > 0 ? $generalSettings['business_or_shop__default_profit'] : 0 }};

    $(document).on('click', '#add_more_variant_btn', function(e) {
        e.preventDefault();

        // var variant_code = code ? code + '-' + (++variant_code_sequel) : auto_generated_code + '-' + (++variant_code_sequel);

        var product_cost = $('#product_cost').val();
        var product_cost_with_tax = $('#product_cost_with_tax').val();
        var profit = $('#profit').val();
        var product_price = $('#product_price').val();
        var html = '';
        html += '<tr id="more_new_variant">';
        html += '<td>';
        html += '<select class="form-control" name="" id="variants">';
        html += '<option value="">' + "{{ __('Create Combination') }}" + '</option>';

        $.each(variantsWithChild, function(key, val) {

            html += '<option value="' + val.id + '">' + val.name + '</option>';
        });

        html += '</select>';
        html += '<input type="text" name="variant_combinations[]" id="variant_combination" class="form-control fw-bold" placeholder="Variant Combination" required>';
        html += '</td>';
        html += '<td><input type="text" name="variant_codes[]" id="variant_code" class="form-control fw-bold" placeholder="Variant Code">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_costings[]" class="form-control fw-bold" placeholder="Cost" id="variant_costing" value="' + parseFloat(product_cost).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_costings_with_tax[]" class="form-control fw-bold" placeholder="Cost inc.tax" id="variant_costing_with_tax" value="' + parseFloat(product_cost_with_tax).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_profits[]" class="form-control fw-bold" placeholder="Profit" value="' + parseFloat(profit).toFixed(2) + '" id="variant_profit">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_prices_exc_tax[]" class="form-control fw-bold" placeholder="Price inc.tax" id="variant_price_exc_tax" value="' + parseFloat(product_price).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html += '<input type="file" name="variant_image[]" class="form-control" id="variant_image">';
        html += '</td>';
        html += '<td><a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a></td>';
        html += '</tr>';
        $('.dynamic_variant_body').prepend(html);

        regenerateVariantCode();
    });
    // Variant all functionality end

    function get_form_part(type) {

        var url = "{{ route('products.form.part', ':type') }}";
        var route = url.replace(':type', type)
        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(html) {

                $('#form_part').html(html);
            }
        });
    }

    function regenerateVariantCode() {

        var code = $('#code').val();
        var auto_generated_code = $('#auto_generated_code').val();

        var variantCodes = document.querySelectorAll('input[name="variant_codes[]"]');
        var variantCodesArray = Array.from(variantCodes);
        var reversed = variantCodesArray.reverse();

        var length = variantCodesArray.length;
        var i = length;
        for (var index = length - 1; index >= 0; index--) {

            var variant_code = code ? code + '-' + (i) : auto_generated_code + '-' + (i);
            reversed[index].value = variant_code;
            i--;
        }
    }

    // Romove variant table row
    $(document).on('click', '#variant_remove_btn', function(e) {

        e.preventDefault();
        $(this).closest('tr').remove();
        regenerateVariantCode();
    });

    // call jquery method
    var actionDirection = '';
    $(document).ready(function() {

        // Select product and show specific product creation fields or area
        $('#type').on('change', function() {

            var value = $(this).val();
            if (value == 2) {

                toastr.error('Add Combo product feature is temporary disabled. Comming soon.');
                $(this).val(1);
                return;
            }

            get_form_part(value);
        });

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

            actionDirection = $(this).val();

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            } else {

                $(this).prop('type', 'button');
            }
        });

        document.onkeyup = function() {
            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#save_and_new').click();
                return false;
            } else if (e.shiftKey && e.which == 13) {

                $('#save').click();
                return false;
            }
        }

        // Add product by ajax
        $('#add_product_form').on('submit', function(e) {

            e.preventDefault();
            $('.loading_button').removeClass('d-hide');
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

                    $('.loading_button').addClass('d-hide');
                    $('.error').html('');

                    isAjaxIn = true;
                    isAllowSubmit = true;

                    if ($.isEmptyObject(data.errorMsg)) {

                        toastr.success("{{ __('Product added successfully.') }}");
                        variant_code_sequel = 0;

                        if (actionDirection == 'save') {

                            window.location = "{{ route('products.index') }}";
                        } else {

                            $('#add_product_form')[0].reset();
                            get_form_part(1);
                            $('#profit').val(parseFloat(defaultProfit).toFixed(2));
                            document.getElementById('name').focus();
                            getLastid();
                            generateProductCode();
                            productTable.ajax.reload();

                            $("#unit_id").select2("destroy");
                            $("#unit_id").select2();
                            $("#category_id").select2("destroy");
                            $("#category_id").select2();
                            $("#sub_category_id").select2("destroy");
                            $("#sub_category_id").select2();
                            $("#brand_id").select2("destroy");
                            $("#brand_id").select2();
                            $("#warranty_id").select2("destroy");
                            $("#warranty_id").select2();
                            $("#branch_id").select2("destroy");
                            $("#branch_id").select2();
                        }
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

        // Automatic remove searching product not found signal
        setInterval(function() {

            $('#search_product').removeClass('is-invalid');
        }, 350);

        // Automatic remove searching product is found signal
        setInterval(function() {

            $('#search_product').removeClass('is-valid');
        }, 1000);
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

    function generateProductCode() {

        var product_serial = $('#product_serial').val();
        var code_prefix = $('#code_prefix').val();
        var productCode = code_prefix + product_serial;
        $('#auto_generated_code').val(productCode);
    }
    generateProductCode();

    function getLastid() {

        $.get("{{ route('products.get.last.product.id') }}", function(productSerial) {

            $('#product_serial').val(productSerial);
            generateProductCode();
        });
    }

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

    @if (isset($product) && count($product->variants) > 0)
        regenerateVariantCode();
    @endif

    $('#name').focus().select();
</script>
