<style>
    .modal-table td {
        font-size: 11px;
        padding: 2px;
    }

    .modal-table input {
        height: 22px;
    }

    .modal-body {
        padding: 0.5rem;
    }

    .product_stock_table_area table thead th {
        background: white !important;
    }

    .product_stock_table_area table tbody td {
        background: white !important;
    }
</style>

<div class="modal-dialog col-80-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Product') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_quick_product_form" action="{{ route('quick.product.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_condition" value="New">
                <input type="hidden" name="is_variant" value="0">
                <input type="hidden" name="type" value="1">
                <input type="hidden" name="weight" value="">
                <input type="hidden" id="quick_product_serial" value="{{ $lastProductSerialCode }}">
                <input type="hidden" id="quick_product_code_prefix" value="{{ $generalSettings['product__product_code_prefix'] }}">
                <div class="row">
                    <div class="col-md-9" style="border-right:1px solid #000;">
                        <div class="row">
                            <div class="col-md-4">
                                <label><b>{{ __('Product Name') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="name" class="form-control" id="quick_product_name" data-next="quick_product_code" placeholder="{{ __('Product Name') }}">
                                <span class="error error_quick_product_name"></span>
                            </div>

                            <div class="col-md-4">
                                <label>
                                    <b>
                                        {{ __('Product Code') }}
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Also known as SKU. Product code(SKU) must be unique. If you leave this field empty, it will be generated automatically.') }}" class="fas fa-info-circle tp"></i>
                                    </b>
                                </label>

                                <input type="text" name="code" class="form-control" autocomplete="off" id="quick_product_code" data-next="quick_product_barcode_type" placeholder="{{ __('Product Code') }}">
                                <input type="hidden" name="auto_generated_code" id="quick_product_auto_generated_code">
                                <span class="error error_quick_product_code"></span>
                            </div>

                            <div class="col-md-4">
                                <label><b>{{ __('Barcode Type') }}</b></label>
                                <select class="form-control" name="barcode_type" id="quick_product_barcode_type" data-next="quick_unit_id">
                                    <option value="CODE128">{{ __('Code 128 (C128)') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label><b>{{ __('Unit') }}</b> <span class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <select required class="form-control select2" name="unit_id" id="quick_unit_id" data-next="quick_product_category_id">
                                        <option value="">{{ __('Select Unit') }}</option>
                                        @php
                                            $defaultUnit = $generalSettings['product__default_unit_id'];
                                        @endphp
                                        @foreach ($units as $unit)
                                            <option {{ $defaultUnit == $unit->id ? 'SELECTED' : '' }} value="{{ $unit->id }}">{{ $unit->name . ' (' . $unit->code_name . ')' }}</option>
                                        @endforeach
                                    </select>

                                    <div class="input-group-prepend">
                                        <span class="input-group-text {{ !auth()->user()->can('product_unit_add') ? 'disabled_element' : '' }} add_button" onclick="{{ auth()->user()->can('product_unit_add') ? 'addUnit();' : '' }} return false;"><i class="fas fa-plus-square input_i"></i></span>
                                    </div>
                                </div>
                                <span class="error error_unit_id"></span>
                            </div>

                            @if ($generalSettings['product__is_enable_categories'] == '1')
                                <div class="col-md-4">
                                    <label><b>{{ __('Category') }}</b> </label>
                                    <div class="input-group flex-nowrap">
                                        <select class="form-control select2 flex-nowrap" onchange="getSubCategories(this); return false;" name="category_id" id="quick_product_category_id" data-next="quick_product_sub_category_id">
                                            <option value="">{{ __('Select Category') }}</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text {{ !auth()->user()->can('product_category_add') ? 'disabled_element' : '' }} add_button" onclick="{{ auth()->user()->can('product_brand_add') ? 'addCategory();' : '' }} return false;"><i class="fas fa-plus-square input_i"></i></span>
                                        </div>
                                    </div>
                                    <span class="error error_category_id"></span>
                                </div>

                                <div class="col-md-4">
                                    <label><b>{{ __('Subcategory') }}</b></label>
                                    <select class="form-control select2" name="sub_category_id" id="quick_product_sub_category_id" data-next="quick_product_brand_id">
                                        <option value="">{{ __('Select Category First') }}</option>
                                    </select>
                                </div>
                            @endif
                        </div>

                        <div class="row mt-1">
                            @if ($generalSettings['product__is_enable_brands'] == '1')
                                <div class="col-md-4">
                                    <label><b>{{ __('Brand.') }}</b></label>
                                    <div class="input-group flex-nowrap">
                                        <select class="form-control select2" name="brand_id" id="quick_product_brand_id" data-next="quick_product_warranty_id">
                                            <option value="">{{ __('Select Brand') }}</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>

                                        <div class="input-group-prepend">
                                            <span class="input-group-text add_button {{ !auth()->user()->can('product_brand_add') ? 'disabled_element' : '' }}" onclick="{{ auth()->user()->can('product_brand_add') ? 'addBrand();' : '' }} return false;"><i class="fas fa-plus-square input_i"></i></span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($generalSettings['product__is_enable_warranty'] == '1')
                                <div class="col-md-4">
                                    <label><b>{{ __('Warranty') }}</b></label>
                                    <div class="input-group flex-nowrap">
                                        <select class="form-control select2" name="warranty_id" id="quick_product_warranty_id" data-next="quick_alert_quantity">
                                            <option value="">{{ __('Select Warranty') }}</option>
                                            @foreach ($warranties as $warranty)
                                                <option value="{{ $warranty->id }}">{{ $warranty->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text {{ !auth()->user()->can('product_warranty_add') ? 'disabled_element' : '' }} add_button" onclick="{{ auth()->user()->can('product_warranty_add') ? 'addWarranty();' : '' }} return false;"><i class="fas fa-plus-square input_i"></i><span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-4">
                                <label><b>{{ __('Alert Quantity') }}</b></label>
                                <input type="number" step="any" name="alert_quantity" class="form-control" id="quick_alert_quantity" value="0" data-next="quick_product_access_branch_id" autocomplete="off">
                            </div>
                        </div>

                        <div class="row mt-1">
                            @if (auth()->user()->can('has_access_to_all_area') == 1)
                                <div class="col-md-4">
                                    <label><b>{{ __('Access Shop') }}</b> </label>
                                    <input type="hidden" name="access_branch_count" value="access_branch_count">
                                    <select class="form-control select2" name="access_branch_ids[]" id="quick_product_access_branch_id" multiple>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error error_quick_product_access_branch_ids"></span>
                                </div>
                            @endif

                            <div class="col-md-4">
                                <label><b>{{ __('Stock Type') }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('menu.stock_type_msg')" class="fas fa-info-circle tp"></i></b> </label>
                                <select class="form-control" name="is_manage_stock" id="quick_product_is_manage_stock" data-next="quick_product_is_show_in_ecom">
                                    <option value="1">{{ __('Manageable Stock') }}</option>
                                    <option value="0">{{ __('Service/Digital Product') }}</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><b>{{ __('Displayed In E-com') }}</b></label>
                                <select name="is_show_in_ecom" class="form-control" id="quick_product_is_show_in_ecom" data-next="quick_product_is_show_emi_on_pos">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label><b>{{ __('Enable IMEI/SL No') }}</b></label>
                                <select name="is_show_emi_on_pos" class="form-control" id="quick_product_is_show_emi_on_pos" data-next="quick_product_is_for_sale">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><b>{{ __('Is For Sale') }}</b></label>
                                <select name="is_for_sale" class="form-control" id="quick_product_is_for_sale" data-next="quick_has_batch_no_expire_date">
                                    <option value="1">{{ __('Yes') }}</option>
                                    <option value="0">{{ __('No') }}</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><b>{{ __('BatchNo/Expire Date') }}</b></label>
                                <select name="has_batch_no_expire_date" class="form-control" id="quick_has_batch_no_expire_date" data-next="quick_product_cost">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label><b>{{ __('Unit Cost(Exc. Tax)') }}</b></label>
                                <input type="number" step="any" name="product_cost" class="form-control fw-bold" id="quick_product_cost" placeholder="0.00" data-next="{{$generalSettings['product__is_enable_price_tax'] == '1' ? 'quick_product_tax_ac_id' : 'quick_product_profit' }}" autocomplete="off">
                            </div>
                        </div>

                        @if ($generalSettings['product__is_enable_price_tax'] == '1')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label><b> {{ __('Vat/Tax') }}</b></label>
                                        <div class="input-group">
                                            <select class="form-control" name="tax_ac_id" id="quick_product_tax_ac_id" data-next="quick_product_tax_type">
                                                <option data-tax_percent="0" value="">{{ __('NoVat/Tax') }}</option>
                                                @foreach ($taxAccounts as $tax)
                                                    <option data-tax_percent="{{ $tax->tax_percent }}" value="{{ $tax->id }}">{{ $tax->name }}</option>
                                                @endforeach
                                            </select>

                                            <select name="tax_type" class="form-control" id="quick_product_tax_type" data-next="quick_product_profit">
                                                <option value="1">{{ __('Exclusive') }}</option>
                                                <option value="2">{{ __('Inclusive') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label><b>{{ __('Unit Cost(Inc. Tax)') }}</b></label>
                                <input readonly type="number" step="any" name="product_cost_with_tax" class="form-control fw-bold" id="quick_product_cost_with_tax" placeholder="0.00" autocomplete="off">
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label><b>{{ __('Profit Margin(%)') }}</b></label>
                                <input type="number" step="any" name="profit" class="form-control fw-bold" id="quick_product_profit" value="{{ $generalSettings['business_or_shop__default_profit'] > 0 ? $generalSettings['business_or_shop__default_profit'] : 0 }}" data-next="quick_product_price" placeholder="0.00" autocomplete="off">
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label><b>{{ __('Unit Price(Exc. Tax)') }}</b></label>
                                <input type="number" step="any" name="product_price" class="form-control fw-bold" id="quick_product_price" data-next="quick_product_quantity" placeholder="0.00" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <p class="m-0" style="font-size: 11px!important;"><span class="fw-bold">{{ __('Opening Stock') }}</span>
                    <div class="product_stock_table_area">
                        <div class="table-responsive">
                            <table id="" class="display table modal-table table-sm">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-white">{{ __('Stock Location') }}</th>
                                        <th class="text-white">{{ __('Quantity') }}</th>
                                        <th class="text-white">{{ __('Unit Cost Inc. Tax') }}</th>
                                        <th class="text-white">{{ __('Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            @if (auth()->user()->branch_id)
                                                @if (auth()->user()?->branch?->parentBranch)
                                                    {{ auth()->user()?->branch?->parentBranch->name . '(' . auth()->user()?->branch?->area_name . ')-' . auth()->user()?->branch?->branch_code }}
                                                @else
                                                    {{ auth()->user()?->branch?->name . '(' . auth()->user()?->branch?->area_name . ')-' . auth()->user()?->branch?->branch_code }}
                                                @endif
                                            @else
                                                {{ $generalSettings['business_or_shop__business_name'] . '(' . __('Business') . ')' }}
                                            @endif
                                            <input type="hidden" name="branch_ids[]" value="{{ auth()->user()->branch_id }}">
                                            <input type="hidden" name="warehouse_ids[]">
                                        </td>

                                        <td>
                                            <input type="number" step="any" name="quantities[]" onkeypress="enterPressQuickProductQuantity(event);" class="form-control fw-bold" id="quick_product_quantity" value="0.00" autocomplete="off">
                                        </td>

                                        <td>
                                            <input type="number" step="any" name="unit_costs_inc_tax[]" onkeypress="enterPressQuickProductCostIncTax(event);" class="form-control fw-bold" id="quick_product_unit_cost_inc_tax" autocomplete="off">
                                        </td>

                                        <td>
                                            <input readonly type="number" step="any" name="subtotals[]" class="form-control fw-bold" id="quick_product_subtotal" value="0.00">
                                        </td>
                                    </tr>

                                    @if (count($warehouses) > 0)
                                        @foreach ($warehouses as $warehouse)
                                            <tr>
                                                <td>
                                                    {{ $warehouse->warehouse_name . ' - ' . $warehouse->warehouse_code }}
                                                    <input type="hidden" name="branch_ids[]" value="{{ auth()->user()->branch_id }}">
                                                    <input type="hidden" name="warehouse_ids[]" value="{{ $warehouse->id }}">
                                                </td>

                                                <td>
                                                    <input type="number" step="any" name="quantities[]" onkeypress="enterPressQuickProductQuantity(event);" class="form-control fw-bold" id="quick_product_quantity" value="0.00" autocomplete="off">
                                                </td>

                                                <td>
                                                    <input type="number" step="any" name="unit_costs_inc_tax[]" onkeypress="enterPressQuickProductCostIncTax(event);" class="form-control fw-bold" id="quick_product_unit_cost_inc_tax" autocomplete="off">
                                                </td>

                                                <td>
                                                    <input readonly type="number" step="any" name="subtotals[]" class="form-control fw-bold" id="quick_product_subtotal" value="0.00">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button quick_product_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="quick_product_save_btn" class="btn btn-sm btn-success quick_product_submit_button">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.select2').select2();

    function costCalculate() {

        var tax_percent = $('#quick_product_tax_ac_id').find('option:selected').data('tax_percent') ? $('#quick_product_tax_ac_id').find('option:selected').data('tax_percent') : 0;
        var product_cost = $('#quick_product_cost').val() ? $('#quick_product_cost').val() : 0;
        var tax_type = $('#quick_product_tax_type').val() ? $('#quick_product_tax_type').val() : 1;
        var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent);

        if (tax_type == 2) {

            var __tax_percent = 100 + parseFloat(tax_percent);
            var calc_tax = parseFloat(product_cost) / parseFloat(__tax_percent) * 100;
            calc_product_cost_tax = parseFloat(product_cost) - parseFloat(calc_tax);
        }

        var product_cost_with_tax = parseFloat(product_cost) + calc_product_cost_tax;
        $('#quick_product_cost_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
        var profit = $('#quick_product_profit').val() ? $('#quick_product_profit').val() : 0;

        if (parseFloat(profit) > 0) {

            var calculate_profit = parseFloat(product_cost) / 100 * parseFloat(profit);
            var product_price = parseFloat(product_cost) + parseFloat(calculate_profit);
            $('#quick_product_price').val(parseFloat(product_price).toFixed(2));
        }
    }

    function calculateAllOpeningBalanceRow() {

        var quickProductCostIncTax = $('#quick_product_cost_with_tax').val() ? $('#quick_product_cost_with_tax').val() : 0;
        var quantities = document.querySelectorAll('#quick_product_quantity');
        var unitCostsIncIax = document.querySelectorAll('#quick_product_unit_cost_inc_tax');
        var subtotals = document.querySelectorAll('#quick_product_subtotal');

        var i = 0;
        unitCostsIncIax.forEach(function(unitCostIncIax) {

            unitCostIncIax.value = parseFloat(quickProductCostIncTax).toFixed(2);
            var __unitCostIncIax = unitCostIncIax.value;
            var quantity = (quantities[i].value ? quantities[i].value : 0);
            var subtotal = parseFloat(__unitCostIncIax) * parseFloat(quantity);
            subtotals[i].value = parseFloat(subtotal).toFixed(2);
            i++;
        });
    }

    function calculateOpeningBalanceRow(tr) {

        var quantity = tr.find('#quick_product_quantity').val() ? tr.find('#quick_product_quantity').val() : 0;
        var productUnitCostIncTax = tr.find('#quick_product_unit_cost_inc_tax').val() ? tr.find('#quick_product_unit_cost_inc_tax').val() : 0;
        var subtotal = parseFloat(quantity) * parseFloat(productUnitCostIncTax);

        tr.find('#quick_product_subtotal').val(parseFloat(subtotal).toFixed(2));
    }

    $(document).on('input', '#quick_product_cost', function() {

        costCalculate();
        calculateAllOpeningBalanceRow();
    });

    $(document).on('input', '#quick_product_quantity', function() {

        var val = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        calculateOpeningBalanceRow(tr);

        if (parseFloat(val) > 0) {
            tr.find('#quick_product_unit_cost_inc_tax').prop('required', true);
        } else {
            tr.find('#quick_product_unit_cost_inc_tax').prop('required', false);
        }
    });

    $(document).on('input', '#quick_product_unit_cost_inc_tax', function() {

        var tr = $(this).closest('tr');
        calculateOpeningBalanceRow(tr);
    });

    $(document).on('input', '#quick_product_price', function() {

        var selling_price = $(this).val() ? $(this).val() : 0;
        var product_cost = $('#quick_product_cost').val() ? $('#quick_product_cost').val() : 0;
        var profitAmount = parseFloat(selling_price) - parseFloat(product_cost);
        var __cost = parseFloat(product_cost) > 0 ? parseFloat(product_cost) : parseFloat(profitAmount);
        var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __calcProfit = calcProfit ? calcProfit : 0;
        $('#quick_product_profit').val(parseFloat(__calcProfit).toFixed(2));
    });

    $(document).on('change', '#quick_product_tax_ac_id', function() {

        costCalculate();
        calculateAllOpeningBalanceRow();
    });

    $(document).on('change', '#quick_product_tax_type', function() {

        costCalculate();
        calculateAllOpeningBalanceRow();
    });

    $(document).on('input', '#quick_product_profit', function() {

        costCalculate();
        calculateAllOpeningBalanceRow();
    });

    function generateProductCode() {

        var product_serial = $('#quick_product_serial').val();
        var code_prefix = $('#quick_product_code_prefix').val();
        var productCode = code_prefix + product_serial;
        $('#quick_product_auto_generated_code').val(productCode);
    }
    generateProductCode();

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    function enterPressQuickProductQuantity(event) {

        var tr = $(event.target).closest('tr');

        var keyPressed = event.key || event.keyCode || event.which;

        if (keyPressed == 'Enter') {

            tr.find('#quick_product_unit_cost_inc_tax').focus().select();
        }
    };

    function enterPressQuickProductCostIncTax(event) {

        var tr = $(event.target).closest('tr');
        var next = tr.next();

        var keyPressed = event.key || event.keyCode || event.which;

        if (keyPressed == 'Enter') {

            if (next.length == 1) {

                next.find('#quick_product_quantity').focus().select();
            } else {

                $('#quick_product_save_btn').focus();
            }
        }
    };

    function getSubCategories(e) {

        var categoryId = $(e).val();

        var url = "{{ route('subcategories.by.category.id', ':category_id') }}";
        var route = url.replace(':category_id', categoryId);

        $.ajax({
            url: route,
            type: 'get',
            success: function(subCategories) {

                $('#quick_product_sub_category_id').empty();
                $('#quick_product_sub_category_id').append('<option value="">' + "{{ __('Select Subcategory') }}" + '</option>');

                $.each(subCategories, function(key, val) {

                    $('#quick_product_sub_category_id').append('<option value="' + val.id + '">' + val.name + '</option>');
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
    };
</script>

<script>
    function addUnit() {
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
    };

    function addCategory() {

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
    };

    function addBrand() {

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
    };

    function addWarranty() {

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
    };
</script>
