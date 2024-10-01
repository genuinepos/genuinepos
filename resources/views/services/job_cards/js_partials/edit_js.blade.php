<script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="{{ asset('assets/plugins/custom/dropify/js/dropify.min.js') }}"></script>
{{-- <script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var productConfigurationItems = @json($productConfigurationItems);
    var defaultProblemsReportItems = @json($defaultProblemsReportItems);
    var defaultProductConditionItems = @json($defaultProductConditionItems);
    var defaultChecklist = "{{ $defaultChecklist }}";

    var input = document.querySelector('input[name="product_configuration"]');
    // init Tagify script on the above inputs
    tagify = new Tagify(input, {
        whitelist: productConfigurationItems,
        // maxTags: 10,
        dropdown: {
            maxItems: 20, // <- mixumum allowed rendered suggestions
            classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
            enabled: 0, // <- show suggestions on focus
            closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
        }
    });

    var input = document.querySelector('input[name="problems_report"]');
    // init Tagify script on the above inputs
    tagify = new Tagify(input, {
        whitelist: defaultProblemsReportItems,
        // maxTags: 10,
        dropdown: {
            maxItems: 20, // <- mixumum allowed rendered suggestions
            classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
            enabled: 0, // <- show suggestions on focus
            closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
        }
    });

    var input = document.querySelector('input[name="product_condition"]');
    // init Tagify script on the above inputs
    tagify = new Tagify(input, {
        whitelist: defaultProductConditionItems,
        // maxTags: 10,
        dropdown: {
            maxItems: 20, // <- mixumum allowed rendered suggestions
            classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
            enabled: 0, // <- show suggestions on focus
            closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
        }
    });

    $('#document').dropify({
        messages: {
            'default': "{{ __('Drag and drop a file here or click') }}",
            'replace': "{{ __('Drag and drop or click to replace') }}",
            'remove': "{{ __('Remove') }}",
            'error': "{{ __('Ooops, something wrong happended.') }}"
        }
    });

    $(document).on('change', '#service_type', function() {

        var value = $(this).val();

        $('.pick_up_on_address_field').hide();

        if (value == 2 || value == 3) {

            $('.pick_up_on_address_field').show();
        }
    });

    $(document).ready(function() {
        function formatState(state) {
            if (!state.id) {
                return state.text; // optgroup
            }

            var icon = $(state.element).data('icon');
            var color = $(state.element).data('color');

            var $state = $(
                '<span><i class="' + icon + '" style="color:' + color + '"></i> ' + state.text + '</span>'
            );
            return $state;
        };

        $("#status_id").select2({
            templateResult: formatState,
            templateSelection: formatState
        });
    });

    $(document).on('change', '#brand_id', function() {

        var brand_id = $(this).val();

        $('#device_model_id').empty();
        $('#device_model_id').append('<option data-checklist="" value="">' + "{{ __('Select Device Model') }}" + '</option>');

        getCheckList();

        var url = "{{ route('services.settings.device.models.by.brand') }}";

        $.ajax({
            url: url,
            type: 'get',
            data: {
                brand_id
            },
            success: function(models) {

                if (models.length > 0) {

                    $('#device_model_id').empty();
                    $('#device_model_id').append('<option data-checklist="" value="">' + "{{ __('Select Device Model') }}" + '</option>');

                    $.each(models, function(key, model) {

                        $('#device_model_id').append('<option data-checklist="' + model.service_checklist + '" value="' + model.id + '">' + model.name + '</option>');
                    });
                }
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

    $(document).on('change', '#device_id', function() {

        var device_id = $(this).val();

        $('#device_model_id').empty();
        $('#device_model_id').append('<option data-checklist="" value="">' + "{{ __('Select Device Model') }}" + '</option>');

        getCheckList();

        var url = "{{ route('services.settings.device.models.by.device') }}";

        $.ajax({
            url: url,
            type: 'get',
            data: {
                device_id
            },
            success: function(models) {

                if (models.length > 0) {

                    $('#device_model_id').empty();
                    $('#device_model_id').append('<option data-checklist="" value="">' + "{{ __('Select Device Model') }}" + '</option>');

                    $.each(models, function(key, model) {

                        $('#device_model_id').append('<option data-checklist="' + model.service_checklist + '" value="' + model.id + '">' + model.name + '</option>');
                    });
                }
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

    $(document).on('change', '#device_model_id', function() {

        getCheckList();
    });

    function getCheckList() {

        var device_model_id = $('#device_model_id').val();
        var checklist = $('#device_model_id').find('option:selected').data('checklist');

        var __checkList = checklist ? checklist : defaultChecklist;

        $('#check_list_area').empty();

        if (__checkList && device_model_id) {

            var arr = __checkList.split('|').map(function(item) {
                return item.trim();
            });

            arr.forEach(function(item, index) {

                if (item) {

                    var hrml = '';
                    hrml += '<div class="col-md-2">';
                    hrml += '<p class="fw-bold text-primary">' + item + '</p>';
                    hrml += '<div class="switch-toggle switch-candy">';
                    hrml += '<input id="' + index + '_yes" name="checklist[' + item + ']" type="radio" value="yes">';
                    hrml += '<label for="' + index + '_yes" class="text-success">✔</label>';

                    hrml += '<input id="' + index + '_no" name="checklist[' + item + ']" type="radio" value="no">';
                    hrml += '<label for="' + index + '_no" class="text-danger">❌</label>';

                    hrml += '<input id="' + index + '_na" name="checklist[' + item + ']" type="radio" checked value="na">';
                    hrml += '<label for="' + index + '_na">N/A</label>';
                    hrml += '<a></a>';
                    hrml += '</div>';
                    hrml += '</div>';
                    $('#check_list_area').append(hrml);
                }
                console.log(index + ' - ' + item);
            });


            // console.log(arr);
        } else {

            $('#check_list_area').append('<p>' + "{{ __('No Service Check List') }}" + '</p>');
        }
    }
</script>

<script>
    var itemUnitsArray = @json($itemUnitsArray);

    var ul = document.getElementById('list');
    var selectObjClassName = 'selectProduct';
    $('#quotation').mousedown(function(e) {

        afterClickOrFocusQuotation();
    }).focus(function(e) {

        afterClickOrFocusQuotation();
    });

    function afterClickOrFocusQuotation() {

        ul = document.getElementById('quotation_list')
        selectObjClassName = 'selected_quotation';
        $('#quotation').val('');
        $('#quotation_id').val('');
        $('.quotation_search_result').hide();
    }

    $('#search_product').focus(function(e) {

        afterFocusSearchItemField();
    });

    function afterFocusSearchItemField() {

        ul = document.getElementById('list');
        selectObjClassName = 'selectProduct';
    }

    $('#quotation').on('input', function() {

        $('.quotation_search_result').hide();

        var keyWord = $(this).val();

        if (keyWord === '') {

            $('.quotation_search_result').hide();
            $('#quotation_id').val('');
            return;
        }

        var url = "{{ route('services.quotations.search.by.quotation.id', [':keyWord']) }}";
        var route = url.replace(':keyWord', keyWord);

        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.noResult)) {

                    $('.quotation_search_result').hide();
                } else {

                    $('.quotation_search_result').show();
                    $('#quotation_list').html(data);
                }
            }
        });
    });

    $(document).on('click', '#selected_quotation', function(e) {
        e.preventDefault();

        var quotation = $(this).html();
        var quotation_id = $(this).data('quotation_id');
        var customer_account_id = $(this).data('customer_account_id');
        var customer_curr_balance = $(this).data('current_balance');

        var url = "{{ route('services.quotation.products.for.job.card', [':quotation_id']) }}";
        var route = url.replace(':quotation_id', quotation_id);

        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    $('#quotation').focus().select();
                    return;
                }

                itemUnitsArray = jQuery.parseJSON(data.units);

                $('#quotation').val(quotation.trim());
                $('#quotation_id').val(quotation_id);
                $('#customer_account_id').val(customer_account_id).trigger('change');
                $('#current_balance').val(customer_curr_balance);
                $('.quotation_search_result').hide();
                $('#jobcard_product_list').empty();
                $('#jobcard_product_list').html(data.view);
                calculateTotalAmount();
            }
        });
    });

    // Get all price group
    var priceGroups = @json($priceGroupProducts);

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
        var priceGroupId = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';
        delay(function() {
            searchProduct(__keyWord, priceGroupId);
        }, 200); //sendAjaxical is the name of remote-command
    });

    function searchProduct(keyWord, priceGroupId) {

        $('#search_product').focus();

        var isShowNotForSaleItem = 0;
        var url = "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem', ':priceGroupId']) }}";
        var route = url.replace(':keyWord', keyWord);
        route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);
        route = route.replace(':priceGroupId', priceGroupId);

        $.ajax({
            url: route,
            dataType: 'json',
            success: function(product) {

                if (!$.isEmptyObject(product.errorMsg || keyWord == '')) {

                    toastr.error(product.errorMsg);
                    $('#search_product').val("");
                    $('.select_area').hide();
                    $('#stock_quantity').val(parseFloat(0).toFixed(2));
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

                            var price = 0;
                            var __price = priceGroups.filter(function(value) {

                                return value.price_group_id == price_group_id && value.product_id == product.id;
                            });

                            if (__price.length != 0) {

                                price = __price[0].price ? __price[0].price : product.product_price;
                            } else {

                                price = product.product_price;
                            }

                            var discount_amount = 0;
                            if (discount.discount_type == 1) {

                                discount_amount = discount.discount_amount
                            } else {

                                discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                            }

                            var name = product.name + ' (' + product.product_code + ')';

                            $('#search_product').val(name);
                            $('#e_item_name').val(name);
                            $('#e_product_id').val(product.id);
                            $('#e_variant_id').val('noid');
                            $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_price_exc_tax').val(parseFloat(price).toFixed(2));
                            $('#e_discount').val(discount.discount_amount);
                            $('#e_discount_type').val(discount.discount_type);
                            $('#e_discount_amount').val(parseFloat(discount_amount).toFixed(2));
                            $('#e_tax_ac_id').val(product.tax_ac_id != null ? product.tax_ac_id : '');
                            $('#e_tax_type').val(product.tax_type);
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

                            product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/general_default.png') }}" : "{{ file_link('productThumbnail') }}" + product.thumbnail_photo;

                            var li = "";
                            $.each(product.variants, function(key, variant) {

                                var brand = product.brand != null ? ' | ' + product.brand.name : '';

                                li += '<li>';
                                li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + ' - ' + variant.variant_name + ' (' + variant.variant_code + ')' + '" data-v_name="' + variant.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-p_price_exc_tax="' + variant.variant_price + '" data-p_cost_inc_tax="' + (variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax) + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + variant.variant_name + ' (Price: ' + variant.variant_price + ' | ' + variant.variant_code + brand + ')' + (product.is_manage_stock == 0 ?
                                    ' <span class="badge badge-sm bg-primary"><i class="fas fa-wrench mr-1 text-white"></i></span>' : '') + '</a>';
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

                        var price = 0;
                        var __price = priceGroups.filter(function(value) {

                            return value.price_group_id == price_group_id && value.product_id == variant.product.id && value.variant_id == variant.id;
                        });

                        if (__price.length != 0) {

                            price = __price[0].price ? __price[0].price : variant.variant_price;
                        } else {

                            price = variant.variant_price;
                        }

                        var discount_amount = 0;
                        if (discount.discount_type == 1) {

                            discount_amount = discount.discount_amount
                        } else {

                            discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                        }

                        var name = variant.product.name + ' - ' + variant.variant_name + ' (' + variant.variant_code + ')';

                        $('#search_product').val(name);
                        $('#e_item_name').val(name);
                        $('#e_product_id').val(variant.product.id);
                        $('#e_variant_id').val(variant.id);
                        $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_price_exc_tax').val(parseFloat(price).toFixed(2));
                        $('#e_discount').val(parseFloat(discount.discount_amount).toFixed(2));
                        $('#e_discount_type').val(discount.discount_type);
                        $('#e_discount_amount').val(parseFloat(discount_amount).toFixed(2));
                        $('#e_tax_ac_id').val(variant.product.tax_ac_id != null ? variant.product.tax_ac_id : '');
                        $('#e_tax_type').val(variant.product.tax_type);
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

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/general_default.png') }}" : "{{ file_link('productThumbnail') }}" + product.thumbnail_photo;

                                var updateProductCost = product.update_product_cost != 0 && product.update_product_cost != null ? product.update_product_cost : product.product_cost_with_tax;

                                var updateVariantCost = product.update_variant_cost != 0 && product.update_variant_cost != null ? product.update_variant_cost : product.variant_cost_with_tax;

                                var __updateProductCost = product.is_variant == 1 ? updateVariantCost : updateProductCost;

                                var brand = product.brand_name != null ? ' | ' + product.brand_name : '';

                                if (product.is_variant == 1) {

                                    li += '<li>';
                                    li += '<a class="select_product" onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-v_name="' + product.variant_name + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + ' - ' + product.variant_name + ' (' + product.variant_code + ')' + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-p_code="' + product.variant_code + '" data-p_price_exc_tax="' + product.variant_price + '" data-p_cost_inc_tax="' + __updateProductCost + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + ' (Price: ' + product.variant_price + ' | ' + product.variant_code + brand + ')' + (product.is_manage_stock == 0 ?
                                        ' <span class="badge badge-sm bg-primary"><i class="fas fa-wrench mr-1 text-white"></i></span>' : '') + '</a>';
                                    li += '</li>';
                                } else {

                                    li += '<li>';
                                    li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-product_type="single" data-p_id="' + product.id + '" data-v_id="" data-is_manage_stock="' + product.is_manage_stock + '" data-p_name="' + product.name + ' (' + product.product_code + ')' + '" data-v_name="" data-p_code="' + product.product_code + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-p_price_exc_tax="' + product.product_price + '" data-p_cost_inc_tax="' + __updateProductCost + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' (Price: ' + product.product_price + ' | ' + product.product_code + brand + ')' + (product.is_manage_stock == 0 ? ' <span class="badge badge-sm bg-primary"><i class="fas fa-wrench mr-1 text-white"></i></span>' : '') + '</a>';
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

                    toastr.error("{{ __('Net Connection Error. Please check the connetion.') }}");
                    return;
                }
            }
        });
    }

    // Automatic remove searching product is found signal
    setInterval(function() {

        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function() {

        $('#search_product').removeClass('is-valid');
    }, 1000);

    function selectProduct(e) {

        var price_group_id = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';

        $('.select_area').hide();
        $('#search_product').val('');

        var product_id = e.getAttribute('data-p_id');
        var variant_id = e.getAttribute('data-v_id') ? e.getAttribute('data-v_id') : 'noid';
        var is_manage_stock = e.getAttribute('data-is_manage_stock');
        var product_name = e.getAttribute('data-p_name');
        var variant_name = e.getAttribute('data-v_name');
        var product_code = e.getAttribute('data-p_code');
        var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');
        var product_price_exc_tax = e.getAttribute('data-p_price_exc_tax');
        var p_tax_ac_id = e.getAttribute('data-p_tax_ac_id') != null ? e.getAttribute('data-p_tax_ac_id') : '';
        var p_tax_type = e.getAttribute('data-tax_type');
        $('#search_product').val('');

        var url = "{{ route('general.product.search.check.product.discount', ['productId' => ':product_id', 'priceGroupId' => ':price_group_id']) }}"
        var route = url.replace(':product_id', product_id);
        route = route.replace(':price_group_id', price_group_id);

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(data) {

                if ($.isEmptyObject(data.errorMsg)) {

                    var price = 0;
                    var __price = priceGroups.filter(function(value) {

                        if (variant_id != 'noid') {

                            return value.price_group_id == price_group_id && value.product_id == product_id && value.variant_id == variant_id;
                        } else {

                            return value.price_group_id == price_group_id && value.product_id == product_id;
                        }
                    });

                    if (__price.length != 0) {

                        price = __price[0].price && parseFloat(__price[0].price) > 0 ? parseFloat(__price[0].price) : product_price_exc_tax;
                    } else {

                        price = product_price_exc_tax;
                    }

                    var discount = data.discount;

                    var discount_amount = 0;
                    if (discount.discount_type == 1) {

                        discount_amount = discount.discount_amount
                    } else {

                        discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                    }

                    // var name = product_name.length > 35 ? product_name.substring(0, 35) + '...' : product_name;
                    var name = product_name;

                    $('#search_product').val(name);
                    $('#e_item_name').val(name);
                    $('#e_product_id').val(product_id);
                    $('#e_variant_id').val(variant_id);
                    $('#e_is_manage_stock').val(is_manage_stock);
                    $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                    $('#e_price_exc_tax').val(parseFloat(price).toFixed(2));
                    $('#e_discount').val(parseFloat(discount.discount_amount).toFixed(2));
                    $('#e_discount_type').val(discount.discount_type);
                    $('#e_discount_amount').val(parseFloat(discount_amount).toFixed(2));
                    $('#e_tax_ac_id').val(p_tax_ac_id);
                    $('#e_tax_type').val(p_tax_type);
                    $('#e_unit_cost_inc_tax').val(parseFloat(product_cost_inc_tax).toFixed(2));

                    $('#e_unit_id').empty();
                    $('#e_unit_id').append(
                        '<option value="' + data.unit.id +
                        '" data-is_base_unit="1" data-unit_name="' + data.unit.name +
                        '" data-base_unit_multiplier="1">' + data.unit.name + '</option>'
                    );

                    itemUnitsArray[product_id] = [{
                        'unit_id': data.unit.id,
                        'unit_name': data.unit.name,
                        'unit_code_name': data.unit.code_name,
                        'base_unit_multiplier': 1,
                        'multiplier_details': '',
                        'is_base_unit': 1,
                    }];

                    $('#add_item').html('Add');

                    calculateEditOrAddAmount();
                } else {

                    toastr.error(data.errorMsg);
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
        var e_is_manage_stock = $('#e_is_manage_stock').val();
        var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
        var e_unit_id = $('#e_unit_id').val();
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_price_exc_tax = $('#e_price_exc_tax').val() ? $('#e_price_exc_tax').val() : 0;
        var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;
        var e_discount_type = $('#e_discount_type').val();
        var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
        var e_tax_ac_id = $('#e_tax_ac_id').val();
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
        var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
        var e_tax_type = $('#e_tax_type').val();
        var e_price_inc_tax = $('#e_price_inc_tax').val() ? $('#e_price_inc_tax').val() : 0;
        var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;

        if (e_quantity == '') {

            toastr.error("{{ __('Quantity field must not be empty.') }}");
            return;
        }

        if (e_product_id == '') {

            toastr.error("{{ __('Please select an item.') }}");
            return;
        }

        var uniqueIdForPreventDuplicateEntry = e_product_id + e_variant_id;
        var uniqueIdValue = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).val();

        if (uniqueIdValue == undefined) {

            var tr = '';
            tr += '<tr id="select_item">';
            tr += '<td class="text-start">';
            tr += '<span class="product_name">' + e_item_name + (e_is_manage_stock == 0 ? ' <span class="badge badge-sm bg-primary"><i class="fas fa-wrench mr-1 text-white"></i></span>' : '') + '</span>';
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
            tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + e_unit_cost_inc_tax + '">';
            tr += '<input type="hidden" name="job_card_product_ids[]">';
            tr += '<input type="hidden" class="unique_id" id="' + e_product_id + e_variant_id + '" value="' + e_product_id + e_variant_id + '">';
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
            tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="' + parseFloat(e_price_exc_tax).toFixed(2) + '">';
            tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="' + parseFloat(e_price_inc_tax).toFixed(2) + '">';
            tr += '<span id="span_unit_price_inc_tax" class="fw-bold">' + parseFloat(e_price_inc_tax).toFixed(2) + '</span>';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
            tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '" tabindex="-1">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
            tr += '</td>';
            tr += '</tr>';

            $('#jobcard_product_list').append(tr);
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
            tr.find('#unit_discount').val(parseFloat(e_discount).toFixed(2));
            tr.find('#unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
            tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
            tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
            tr.find('#span_quantity').html(parseFloat(e_quantity).toFixed(2));
            tr.find('#span_unit').html(e_unit_name);
            tr.find('#unit_id').val(e_unit_id);
            tr.find('#unit_price_exc_tax').val(parseFloat(e_price_exc_tax).toFixed(2));
            tr.find('#unit_price_inc_tax').val(parseFloat(e_price_inc_tax).toFixed(2));
            tr.find('#span_unit_price_inc_tax').html(parseFloat(e_price_inc_tax).toFixed(2));
            tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
            tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
            tr.find('.unique_id').val(e_product_id + e_variant_id);
            tr.find('.unique_id').attr('id', e_product_id + e_variant_id);

            clearEditItemFileds();
            calculateTotalAmount();
        }

        $('#add_item').html('Add');
    });

    $(document).on('click', '#select_item', function(e) {

        var tr = $(this);
        var unique_id = tr.find('#unique_id').val();
        var item_name = tr.find('#item_name').val();
        var product_id = tr.find('#product_id').val();
        var variant_id = tr.find('#variant_id').val();
        var tax_ac_id = tr.find('#tax_ac_id').val();
        var tax_type = tr.find('#tax_type').val();
        var unit_tax_amount = tr.find('#unit_tax_amount').val();
        var unit_discount_type = tr.find('#unit_discount_type').val();
        var unit_discount = tr.find('#unit_discount').val();
        var unit_discount_amount = tr.find('#unit_discount_amount').val();
        var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
        var quantity = tr.find('#quantity').val();
        var unit_id = tr.find('#unit_id').val();
        var unit_price_exc_tax = tr.find('#unit_price_exc_tax').val();
        var unit_price_inc_tax = tr.find('#unit_price_inc_tax').val();
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
        $('#e_item_name').val(item_name);
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id);
        $('#e_unit_id').val(unit_id);
        $('#e_quantity').val(parseFloat(quantity).toFixed(2)).focus().select();
        $('#e_price_exc_tax').val(parseFloat(unit_price_exc_tax).toFixed(2));
        $('#e_discount').val(parseFloat(unit_discount).toFixed(2));
        $('#e_discount_type').val(unit_discount_type);
        $('#e_discount_amount').val(parseFloat(unit_discount_amount).toFixed(2));
        $('#e_tax_ac_id').val(tax_ac_id);
        $('#e_tax_amount').val(parseFloat(unit_tax_amount).toFixed(2));
        $('#e_tax_type').val(tax_type);
        $('#e_price_inc_tax').val(parseFloat(unit_price_inc_tax).toFixed(2));
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));

        $('#add_item').html("{{ __('Update') }}");
    });

    // $('body').keyup(function(e) {

    //     if (e.keyCode == 13 || e.keyCode == 9) {

    //         if ($(".selectProduct").attr('href') == undefined) {

    //             return;
    //         }

    //         $(".selectProduct").click();

    //         $('#list').empty();
    //         keyName = e.keyCode;
    //     }
    // });


    $('body').keyup(function(e) {

        // if (e.keyCode == 13 || e.keyCode == 9) {

        //     if ($(".selectProduct").attr('href') == undefined) {

        //         return;
        //     }

        //     $(".selectProduct").click();

        //     $('#list').empty();
        //     keyName = e.keyCode;
        // }

        if (e.keyCode == 13) {

            $('.' + selectObjClassName).click();
            $('.quotation_search_result').hide();
            $('.select_area').hide();
            $('#list').empty();
            $('#quotation_list').empty();
        }
    });


    $(document).on('click', function(e) {

        if ($(e.target).closest(".select_area").length === 0) {

            $('.select_area').hide();
            $('#list').empty();
        }
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

            $('#e_price_exc_tax').focus().select();
        }

        calculateEditOrAddAmount();
    });

    $('#e_price_exc_tax').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '') {

                $('#e_discount').focus().select();
            }
        }
    });

    $('#e_discount').on('input keypress', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '' && $(this).val() > 0) {

                $('#e_discount_type').focus();
            } else {

                $('#e_tax_ac_id').focus();
            }
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
        var val = $(this).val();

        if (e.which == 0) {

            if (val) {

                $('#e_tax_type').focus();
            } else {

                $('#add_item').focus();
            }
        }
    });

    $('#e_tax_type').on('change keypress click', function(e) {

        calculateEditOrAddAmount();

        if (e.which == 0) {

            $('#add_item').focus();
        }
    });

    function calculateEditOrAddAmount() {

        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0.00;
        var e_tax_type = $('#e_tax_type').val();
        var e_discount_type = $('#e_discount_type').val();
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_price_exc_tax = $('#e_price_exc_tax').val() ? $('#e_price_exc_tax').val() : 0;
        var e_unit_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;

        var discount_amount = 0;
        if (e_discount_type == 1) {

            discount_amount = e_unit_discount;
        } else {

            discount_amount = (parseFloat(e_price_exc_tax) / 100) * parseFloat(e_unit_discount);
        }

        var unitPriceWithDiscount = parseFloat(e_price_exc_tax) - parseFloat(discount_amount);
        var taxAmount = parseFloat(unitPriceWithDiscount) / 100 * parseFloat(e_tax_percent);
        var unitPriceIncTax = parseFloat(unitPriceWithDiscount) + parseFloat(taxAmount);

        if (e_tax_type == 2) {

            var inclusiveTax = 100 + parseFloat(e_tax_percent);
            var calcTax = parseFloat(unitPriceWithDiscount) / parseFloat(inclusiveTax) * 100;
            taxAmount = parseFloat(unitPriceWithDiscount) - parseFloat(calcTax);
            unitPriceIncTax = parseFloat(unitPriceWithDiscount) + parseFloat(taxAmount);
        }

        $('#e_tax_amount').val(parseFloat(taxAmount).toFixed(2));
        $('#e_discount_amount').val(parseFloat(parseFloat(discount_amount)).toFixed(2));
        $('#e_price_inc_tax').val(parseFloat(parseFloat(unitPriceIncTax)).toFixed(2));

        var subtotal = parseFloat(unitPriceIncTax) * parseFloat(e_quantity);
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
    }

    // Calculate total amount functionalitie
    function calculateTotalAmount() {

        var quantities = document.querySelectorAll('#quantity');
        var subtotals = document.querySelectorAll('#subtotal');
        var unitTaxAmounts = document.querySelectorAll('#unit_tax_amount');
        // Update Total Item

        var total_item = 0;
        quantities.forEach(function(qty) {

            total_item += 1;
        });

        $('#total_item').val(parseFloat(total_item));

        // Update Net total Amount
        var totalCost = 0;
        var productTotalTaxAmount = 0;
        var i = 0;
        subtotals.forEach(function(subtotal) {

            totalCost += parseFloat(subtotal.value);
            i++;
        });

        $('#total_cost').val(parseFloat(totalCost).toFixed(2));

        var totalQty = 0;
        quantities.forEach(function(qty) {

            totalQty += parseFloat(qty.value);
        });

        $('#total_qty').val(parseFloat(totalQty).toFixed(2));
    }

    function clearEditItemFileds() {

        $('#search_product').val('').focus();
        $('#e_unique_id').val('');
        $('#e_item_name').val('');
        $('#e_product_id').val('');
        $('#e_variant_id').val('');
        $('#e_base_unit_name').val('');
        $('#e_quantity').val(parseFloat(0).toFixed(2));
        $('#e_price_exc_tax').val(parseFloat(0).toFixed(2));
        $('#e_discount_type').val(1);
        $('#e_discount').val(parseFloat(0).toFixed(2));
        $('#e_discount_amount').val(parseFloat(0).toFixed(2));
        $('#e_tax_ac_id').val('');
        $('#e_tax_amount').val(parseFloat(0).toFixed(2));
        $('#e_tax_type').val(1);
        $('#e_price_inc_tax').val(parseFloat(0).toFixed(2));
        $('#e_subtotal').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_inc_tax').val(0);
        $('#e_is_show_discription').val('');
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

    setTimeout(function() {

        $('#customer_account_id').focus().select();
    }, 1000);
</script>

<script>
    @if (auth()->user()->can('product_brand_add'))
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

                        toastr.error("{{ __('Net Connection Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });
    @endif

    @if (auth()->user()->can('devices_create'))
        $(document).on('click', '#addDevice', function(e) {
            e.preventDefault();

            var url = "{{ route('services.settings.devices.create') }}";

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#deviceAddOrEditModal').html(data);
                    $('#deviceAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#device_name').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }

                    toastr.error(err.responseJSON.message);
                }
            });
        });
    @endif

    @if (auth()->user()->can('device_models_create'))
        $(document).on('click', '#addDeviceModel', function(e) {
            e.preventDefault();

            var url = "{{ route('services.settings.device.models.create') }}";

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#deviceModelAddOrEditModal').html(data);
                    $('#deviceModelAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#device_model_name').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }

                    toastr.error(err.responseJSON.message);
                }
            });
        });
    @endif

    @if (auth()->user()->can('status_create'))
        $(document).on('click', '#addStatus', function(e) {
            e.preventDefault();

            var url = "{{ route('services.settings.status.create') }}";

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#statusAddOrEditModal').html(data);
                    $('#statusAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#status_name').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }

                    toastr.error(err.responseJSON.message);
                }
            });
        });
    @endif
</script>

<script>
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

    //Edit Job Card request by ajax
    $('#edit_job_card_form').on('submit', function(e) {
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

                    toastr.error(data.errorMsg);
                    return;
                }

                toastr.success(data);
                window.location = "{{ url()->previous() }}";
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
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

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var status = $('#status').val();
        var nextId = $(this).data('next');

        if (e.which == 13) {

            $('#' + nextId).focus().select();
        }
    });

    function getJobCardId() {

        $.ajax({
            url: "{{ route('services.job.cards.no') }}",
            async: true,
            type: 'get',
            success: function(data) {

                $('#job_card_no').val(data);
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
</script>

<script>
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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('delivery_date'),
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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('due_date'),
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

@if (auth()->user()->can('customer_add'))
    <script>
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

                        toastr.error("{{ __('Net Connection Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });
    </script>
@endif

@if (auth()->user()->can('product_add'))
    <script>
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

                        toastr.error("{{ __('Net Connection Error.') }}");
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
                    toastr.success("{{ __('Product is added successfully.') }}");

                    var name = data.name + ' (' + data.product_code + ')';

                    $('#search_product').val(name);
                    $('#e_item_name').val(name);
                    $('#e_product_id').val(data.id);
                    $('#e_variant_id').val('noid');
                    $('#e_is_manage_stock').val(data.is_manage_stock);
                    $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                    $('#e_price_exc_tax').val(parseFloat(data.product_price).toFixed(2));
                    $('#e_discount').val(0);
                    $('#e_discount_type').val(1);
                    $('#e_discount_amount').val(parseFloat(0).toFixed(2));
                    $('#e_tax_ac_id').val(data.tax_ac_id != null ? data.tax_ac_id : '');
                    $('#e_tax_type').val(data.tax_type);
                    $('#e_unit_cost_inc_tax').val(data.product_cost_with_tax);

                    $('#e_unit_id').empty();
                    $('#e_unit_id').append('<option value="' + data.unit.id +
                        '" data-is_base_unit="1" data-unit_name="' + data.unit.name +
                        '" data-base_unit_multiplier="1">' + data.unit.name + '</option>');

                    itemUnitsArray[data.id] = [{
                        'unit_id': data.unit.id,
                        'unit_name': data.unit.name,
                        'unit_code_name': data.unit.code_name,
                        'base_unit_multiplier': 1,
                        'multiplier_details': '',
                        'is_base_unit': 1,
                    }];

                    $('#add_item').html('Add');
                    calculateEditOrAddAmount();

                    $('#addQuickProductModal').empty();
                    $('#addQuickProductModal').modal('hide');
                },
                error: function(err) {

                    isQuickProductAjaxIn = true;
                    isAllowQuickProductSubmit = true;
                    $('.quick_product_loading_btn').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error.') }}");
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
    </script>
@endif
<script src="{{ asset('assets/plugins/custom/select_li/selectli.custom.js') }}"></script>
