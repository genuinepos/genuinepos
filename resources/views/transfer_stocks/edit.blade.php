@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        .input-group-text-sale {font-size: 7px !important;}
        .select_area { position: relative; background: #ffffff; box-sizing: border-box; position: absolute; width: 100%; z-index: 9999999; padding: 0; left: 0%; display: none; border: 1px solid #706a6d; margin-top: 1px; border-radius: 0px; }

        .select_area ul { list-style: none; margin-bottom: 0; padding: 0px 2px; }

        .select_area ul li a { color: #000000; text-decoration: none; font-size: 11px; padding: 2px 2px; display: block; border: 1px solid lightgray; margin: 2px 0px; }

        .select_area ul li a:hover { background-color: #999396; color: #fff; }
        .selectProduct { background-color: #746e70 !important; color: #fff !important; }
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        label.col-2,label.col-3,label.col-4,label.col-5,label.col-6 { text-align: right; padding-right: 10px;}
        .checkbox_input_wrap {text-align: right;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
{{-- @section('title', 'Add Transfer Stock (Shop/Business To Shop/Business)') --}}
@section('title', 'Edit Transfer Stock')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-exchange-alt"></span>
                    {{-- <h6>{{ __("Add Tranfer Stock (Shop/Business To Shop/Business)") }}</h6> --}}
                    <h6>{{ __("Edit Tranfer Stock") }}</h6>
                </div>

                <div class="col-6">
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Bank") }}</a>
                </div>
            </div>
        </div>
        <div class="p-1">
            <form id="edit_transfer_branch_to_branch_form" action="{{ route('transfer.stocks.update', $transferStock->id) }}" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __("Sender Shop/Business") }}</b></label>
                                        <div class="col-7">
                                            <input type="hidden" name="branch_id" id="branch_id" value="{{ auth()->user()->branch_id }}">
                                            @php
                                                $branchName = '';
                                                if ($transferStock?->senderBranch) {

                                                    if ($transferStock?->senderBranch?->parent_branch_id) {

                                                        $branchName = $transferStock?->senderBranch?->parentBranch?->name.'('.$transferStock?->senderBranch?->area_name.')';
                                                    }else {

                                                        $branchName = $transferStock?->senderBranch?->name.'('.$transferStock?->senderBranch?->area_name.')';
                                                    }
                                                }else{

                                                    $branchName = $generalSettings['business__shop_name'];
                                                }
                                            @endphp

                                            <input readonly type="text" class="form-control fw-bold" value="{{ $branchName }}">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-5"><b>{{ __("Send At") }}</b></label>
                                        <div class="col-7">
                                            <select name="sender_warehouse_id" class="form-control" id="sender_warehouse_id" data-next="receiver_branch_id" autofocus>
                                                <option value="">{{ __("Select Warehouse") }}</option>
                                                @foreach ($warehouses as $w)
                                                    <option {{ $w->id == $transferStock->sender_warehouse_id ? 'SELECTED' : '' }} value="{{ $w->id }}">{{ $w->warehouse_name.'/'.$w->warehouse_code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                @if ($transferStock->total_received_qty == 0)

                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <label class="col-5"><b>{{ __("Receiver Shop/Business") }}</b> <span class="text-danger">*</span></label>
                                            <div class="col-7">
                                                <select name="receiver_branch_id" class="form-control" id="receiver_branch_id" data-next="receiver_warehouse_id" autofocus>
                                                    <option value="" class="fw-bold">{{ __("Select Receiver Shop/Business") }}</option>
                                                    <option {{ $transferStock->receiver_branch_id == null ? 'SELECTED' : '' }} value="NULL">{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})</option>
                                                    @foreach ($branches as $branch)
                                                        <option {{ $branch->id == $transferStock->receiver_branch_id ? 'SELECTED' : '' }} value="{{ $branch->id }}">
                                                            @php
                                                                $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                $areaName = $branch->area_name ? '('.$branch->area_name.')' : '';
                                                                $branchCode = '-' . $branch->branch_code;
                                                            @endphp
                                                            {{ $branchName.$areaName.$branchCode }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_receiver_warehouse_id"></span>
                                            </div>
                                        </div>

                                        <div class="input-group mt-1">
                                            <label class="col-5"><b>{{ __("Receive At") }}</b></label>
                                            <div class="col-7">
                                                <select name="receiver_warehouse_id" class="form-control" id="receiver_warehouse_id" data-next="date" autofocus>
                                                    <option value="">{{ __("Select Warehouse") }}</option>
                                                    @foreach ($selectedBranchWarehouses as $warehouse)
                                                        <option {{ $warehouse->id == $transferStock->receiver_warehouse_id ? 'SELECTED' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name.'/'.$warehouse->warehouse_code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $receiverBranch = '';
                                        if ($transferStock?->receiverBranch) {

                                            if ($transferStock?->receiverBranch?->parent_branch_id) {

                                                $receiverBranch = $transferStock?->receiverBranch?->parentBranch?->name.'('.$transferStock?->receiverBranch?->area_name.')';
                                            }else {

                                                $receiverBranch = $transferStock?->receiverBranch?->name.'('.$transferStock?->receiverBranch?->area_name.')';
                                            }
                                        }else{

                                            $receiverBranch = $generalSettings['business__shop_name'];
                                        }
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <label class="col-5"><b>{{ __("Receiver Shop/Business") }}</b></label>
                                            <div class="col-7">
                                                <input readonly type="text" class="form-control fw-bold" value="{{ $receiverBranch }}" autocomplete="off">
                                                <input type="hidden" name="receiver_branch_id" class="form-control fw-bold" value="{{ $transferStock->receiver_branch_id ? $transferStock->receiver_branch_id : 'NULL' }}">
                                            </div>
                                        </div>

                                        @if ($transferStock->receiver_warehouse_id)
                                            <div class="input-group mt-1">
                                                <label class="col-5"><b>{{ __("Receive At") }}</b></label>
                                                <div class="col-7">
                                                    <input readonly type="text" class="form-control fw-bold" value="{{ $transferStock?->receiverWarehouse?->warehouse_name.'-('.$transferStock?->receiverWarehouse->warehouse_code.')' }}" autocomplete="off">
                                                    <input type="hidden" name="receiver_warehouse_id" class="form-control fw-bold" value="{{ $transferStock->receiver_warehouse_id }}">
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                @endif

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label  class="col-5"><b>{{ __("Transfer Date") }}</b></label>
                                        <div class="col-7">
                                            <input required type="text" name="date" class="form-control" id="date" value="{{ date($generalSettings['business__date_format'], strtotime($transferStock->date)) }}" data-next="search_product" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="card mb-1 p-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row g-xxl-4 align-items-end">
                                    <div class="col-xl-6">
                                        <div class="searching_area" style="position: relative;">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-barcode text-dark input_f"></i>
                                                    </span>
                                                </div>

                                                <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="{{ __("Search Product By Name/Code") }}" autocomplete="off">
                                            </div>

                                            <div class="select_area">
                                                <ul id="list" class="variant_list_area"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-xxl-4 align-items-end">
                                    <div class="hidden_fields">
                                        <input type="hidden" id="e_unique_id">
                                        <input type="hidden" id="e_item_name">
                                        <input type="hidden" id="e_product_id">
                                        <input type="hidden" id="e_variant_id">
                                        <input type="hidden" id="e_current_qty">
                                    </div>

                                    <div class="col-xl-2 col-md-6">
                                        <label class="fw-bold">{{ __("Send Quantity") }}</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control w-60 fw-bold" id="e_quantity" placeholder="{{ __("Send Quantity") }}" value="0.00">
                                            <select id="e_unit_id" class="form-control w-40">
                                                <option value="">{{ __("Unit") }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-6">
                                        <label class="fw-bold">{{ __("Unit Cost(Inc. Tax)") }}</label>
                                        <input type="number" step="any" class="form-control fw-bold" id="e_unit_cost_inc_tax" placeholder="{{ __("Unit Cost(Inc. Tax)") }}" value="0.00">
                                    </div>

                                    <div class="col-xl-2 col-md-6">
                                        <label class="fw-bold">{{ __("Subtotal") }}</label>
                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                    </div>

                                    <div class="col-xl-1 col-md-6">
                                        <div class="btn-box-2">
                                            <a href="#" class="btn btn-sm btn-success" id="add_item">{{ __("Add") }}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <div class="sale-item-sec">
                                            <div class="sale-item-inner">
                                                <div class="table-responsive">
                                                    <table class="display data__table table sale-product-table">
                                                        <thead class="staky">
                                                            <tr>
                                                                <th class="text-start">{{ __("Product") }}</th>
                                                                <th class="text-start">{{ __("Send Qty") }}</th>
                                                                <th class="text-start">{{ __("Unit") }}</th>
                                                                <th class="text-start">{{ __("Unit Cost Inc. Tax") }}</th>
                                                                <th class="text-start">{{ __("Subtotal") }}</th>
                                                                <th><i class="fas fa-trash-alt text-danger"></i></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="transfer_product_list">
                                                            @php
                                                                $itemUnitsArray = [];
                                                            @endphp
                                                            @foreach ($transferStock->transferStockProducts as $transferStockProduct)
                                                                @php
                                                                    if (isset($transferStockProduct->product_id)) {

                                                                        $itemUnitsArray[$transferStockProduct->product_id][] = [
                                                                            'unit_id' => $transferStockProduct->product->unit->id,
                                                                            'unit_name' => $transferStockProduct->product->unit->name,
                                                                            'unit_code_name' => $transferStockProduct->product->unit->code_name,
                                                                            'base_unit_multiplier' => 1,
                                                                            'multiplier_details' => '',
                                                                            'is_base_unit' => 1,
                                                                        ];
                                                                    }

                                                                    $variantName = $transferStockProduct?->variant ? ' - ' . $transferStockProduct?->variant?->variant_name : '';
                                                                    $variantId = $transferStockProduct->variant_id ? $transferStockProduct->variant_id : 'noid';
                                                                @endphp

                                                                <tr id="select_item">
                                                                    <td class="text-start">
                                                                        <span class="product_name">{{ $transferStockProduct->product->name . $variantName }}</span>
                                                                        <input type="hidden" id="item_name" value="{{ $transferStockProduct->product->name . $variantName }}">
                                                                        <input type="hidden" name="product_ids[]" id="product_id" value="{{ $transferStockProduct->product_id }}">
                                                                        <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
                                                                        <input type="hidden" name="transfer_stock_product_ids[]" value="{{ $transferStockProduct->id }}">
                                                                        <input type="hidden" class="unique_id" id="{{ $transferStockProduct->product_id . $variantId }}" value="{{ $transferStockProduct->product_id . $variantId }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_quantity" class="fw-bold">{{ $transferStockProduct->send_qty }}</span>
                                                                        <input type="hidden" name="quantities[]" id="quantity" value="{{ $transferStockProduct->send_qty }}">
                                                                        <input type="hidden" id="current_qty" value="{{ $transferStockProduct->send_qty }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_unit" class="fw-bold">{{ $transferStockProduct?->unit?->name }}</span>
                                                                        <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $transferStockProduct->unit_id }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_unit_cost_inc_tax" class="fw-bold">{{ $transferStockProduct->unit_cost_inc_tax }}</span>
                                                                        <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $transferStockProduct->unit_cost_inc_tax }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_subtotal" class="fw-bold">{{ $transferStockProduct->subtotal }}</span>
                                                                        <input type="hidden" name="subtotals[]" id="subtotal" value="{{ $transferStockProduct->subtotal }}" tabindex="-1">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="form_element rounded my-1">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class=" col-4"><b>{{ __("Total Item & Qty") }}</b></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <input readonly type="number" step="any" name="total_item" class="form-control fw-bold" id="total_item" value="{{ $transferStock->total_item }}" tabindex="-1">
                                                <input readonly type="number" step="any" name="total_qty"
                                                class="form-control fw-bold" id="total_qty" value="{{ $transferStock->total_qty }}" tabindex="-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class=" col-4"><b>{{ __("Total Stock Value") }} :</b> </label>
                                        <div class="col-8">
                                            <input readonly type="number" step="any" name="total_stock_value" class="form-control fw-bold" id="total_stock_value" value="{{ $transferStock->total_stock_value }}" tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label  class="col-2"><b>{{ __("Note") }}</b></label>
                                        <div class="col-10">
                                            <input name="transfer_note" type="text" class="form-control" id="transfer_note" data-next="save_changes" value="{{ $transferStock->transfer_note }}" placeholder="{{ __("Transfer Note") }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="submit_button_area">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>{{ __("Loading") }}...</span> </button>
                                <button type="button" id="save_changes" class="btn btn-success submit_button">{{ __("Save Changes") }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var itemUnitsArray = @json($itemUnitsArray);
        // Calculate total amount functionalitie
        function calculateTotalAmount(){
            var quantities = document.querySelectorAll('#quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            // Update Total Item
            var total_item = 0;
            var total_qty = 0;
            quantities.forEach(function(qty){

                total_item += 1;
                total_qty += parseFloat(qty.value ? qty.value : 0);
            });

            $('#total_qty').val(parseFloat(total_qty).toFixed(2));
            $('#total_item').val(parseFloat(total_item));

            // Update Net total Amount
            var totalStockValue = 0;
            subtotals.forEach(function(subtotal){
                totalStockValue += parseFloat(subtotal.value);
            });

            $('#total_stock_value').val(parseFloat(totalStockValue).toFixed(2));
        }

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

            $('#search_product').focus();

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

                                var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' : product.name;

                                $('#search_product').val(name);
                                $('#e_item_name').val(name);
                                $('#e_product_id').val(product.id);
                                $('#e_variant_id').val('noid');
                                $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                                $('#e_unit_cost_inc_tax').val(product.product_cost_with_tax);

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

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    li += '<li>';
                                    li += '<a onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-v_name="' + variant.variant_name + '" data-p_code="' + variant.variant_code + '" data-p_cost_exc_tax="' + variant.variant_cost + '" data-p_cost_inc_tax="' + variant.variant_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + variant.variant_name + '</a>';
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

                            var name = variant_product.product.name.length > 35 ? variant_product.product.name.substring(0, 35) + '...' : variant_product.product.name;

                            $('#search_product').val(name + ' - ' + variant_product.variant_name);
                            $('#e_item_name').val(name + ' - ' + variant_product.variant_name);
                            $('#e_product_id').val(variant_product.product.id);
                            $('#e_variant_id').val(variant_product.id);
                            $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_unit_cost_inc_tax').val(variant_product.variant_cost_with_tax);

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

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    if (product.is_variant == 1) {

                                        li += '<li>';
                                        li += '<a onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-v_name="' + product.variant_name + '" data-p_code="' + product.variant_code + '" data-p_cost_inc_tax="' + product.variant_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                        li += '</li>';

                                    } else {

                                        li += '<li>';
                                        li += '<a onclick="selectProduct(this); return false;" data-product_type="single" data-p_id="' + product.id + '" data-v_id="" data-v_name="" data-is_manage_stock="' + product.is_manage_stock + '" data-p_name="' + product.name + '" data-p_code="' + product.product_code + '" data-p_cost_inc_tax="' + product.product_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
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
                    $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                    $('#e_unit_cost_inc_tax').val(parseFloat(product_cost_inc_tax).toFixed(2));

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
            var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
            var e_current_qty = $('#e_current_qty').val() ? $('#e_current_qty').val() : 0;
            var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
            var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;

            var senderWarehouseId = $('#sender_warehouse_id').val();

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

                var url = "{{ route('general.product.search.variant.product.stock', ['productId' => ':e_product_id', 'variantId' => ':e_variant_id', 'warehouseId' => ':warehouseId']) }}";
                route = url.replace(':e_product_id', e_product_id);
                route = route.replace(':e_variant_id', e_variant_id);
                route = route.replace(':warehouseId', senderWarehouseId);
            } else {

                var url = "{{ route('general.product.search.single.product.stock', ['productId' => ':e_product_id', 'warehouseId' => ':warehouseId']) }}";
                route = url.replace(':e_product_id', e_product_id);
                route = route.replace(':warehouseId', senderWarehouseId);
            }

            $.ajax({
                url: route,
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    if ($.isEmptyObject(data.errorMsg)) {

                        var stock =  parseFloat(data.stock) + parseFloat(e_current_qty);
                        if (parseFloat(e_quantity) > parseFloat(stock)) {

                            toastr.error("{{ __('Send quantity is exceed the current stock.') }}");
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
                            tr += '<input type="hidden" name="transfer_stock_product_ids[]">';
                            tr += '<input type="hidden" class="unique_id" id="' + e_product_id + e_variant_id + '" value="' + e_product_id + e_variant_id + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<span id="span_quantity" class="fw-bold">' + parseFloat(e_quantity).toFixed(2) + '</span>';
                            tr += '<input type="hidden" id="e_quantity" value="0">';
                            tr += '<input type="hidden" name="quantities[]" id="quantity" value="' + parseFloat(e_quantity).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text text-start">';
                            tr += '<span id="span_unit" class="fw-bold">' + e_unit_name + '</span>';
                            tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<span id="span_unit_cost_inc_tax" class="fw-bold">' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '</span>';
                            tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text text-start">';
                            tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
                            tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '" tabindex="-1">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';

                            $('#transfer_product_list').append(tr);
                            clearEditItemFileds();
                            calculateTotalAmount();
                        } else {

                            var tr = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).closest('tr');

                            tr.find('#item_name').val(e_item_name);
                            tr.find('#product_id').val(e_product_id);
                            tr.find('#variant_id').val(e_variant_id);
                            tr.find('#span_quantity').html(parseFloat(e_quantity).toFixed(2));
                            tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
                            tr.find('#span_unit').html(e_unit_name);
                            tr.find('#unit_id').val(e_unit_id);
                            tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                            tr.find('#span_unit_cost_inc_tax').html(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                            tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
                            tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
                            tr.find('.unique_id').val(e_product_id + e_variant_id);
                            tr.find('.unique_id').attr('id', e_product_id + e_variant_id);

                            clearEditItemFileds();
                            calculateTotalAmount();
                        }

                        $('#add_item').html('Add');
                    } else {

                        toastr.error(data.errorMsg);
                    }
                }
            });
        });

        $(document).on('click', '#select_item', function(e) {

            var tr = $(this);
            var unique_id = tr.find('.unique_id').val();
            var item_name = tr.find('#item_name').val();
            var product_id = tr.find('#product_id').val();
            var unit_id = tr.find('#unit_id').val();
            var variant_id = tr.find('#variant_id').val();
            var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
            var quantity = tr.find('#quantity').val();
            var current_qty = tr.find('#current_qty').val();
            var subtotal = tr.find('#subtotal').val();

            $('#e_unit_id').empty();

            itemUnitsArray[product_id].forEach(function(unit) {

                $('#e_unit_id').append('<option ' + (unit_id == unit.unit_id ? 'selected' : '') +
                    ' value="' + unit.unit_id + '" data-is_base_unit="' + unit.is_base_unit +
                    '" data-unit_name="' + unit.unit_name + '" data-base_unit_multiplier="' + unit
                    .base_unit_multiplier + '">' + unit.unit_name + unit.multiplier_details + '</option>');
            });

            $('#search_product').val(item_name);
            $('#e_item_name').val(item_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val(variant_id);
            $('#e_quantity').val(parseFloat(quantity).toFixed(2)).focus().select();
            $('#e_current_qty').val(parseFloat(current_qty).toFixed(2));
            $('#e_unique_id').val(unique_id);
            $('#e_unit_cost_inc_tax').val(unit_cost_inc_tax);
            $('#e_subtotal').val(subtotal);
            $('#add_item').html('Edit');
        });

        function clearEditItemFileds() {

            $('#e_unique_id').val('');
            $('#search_product').val('').focus();
            $('#e_item_name').val('');
            $('#e_product_id').val('');
            $('#e_variant_id').val('');
            $('#e_quantity').val(0.00);
            $('#e_current_qty').val(0.00);
            $('#e_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_subtotal').val(parseFloat(0).toFixed(2));
        }

        $(document).on('change', '#sender_warehouse_id',function(e){

            $('#transfer_product_list').empty();
            calculateTotalAmount();
        });

        $(document).on('click', '#remove_product_btn',function(e){

            e.preventDefault();

            $(this).closest('tr').remove();

            calculateTotalAmount();

            setTimeout(function () {

                clearEditItemFileds();
            }, 5);
        });

        function calculateEditOrAddAmount() {

            var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
            var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;

            var subtotal = parseFloat(e_unit_cost_inc_tax) * parseFloat(e_quantity);
            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        }

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

                $('#add_item').focus();
            }
        });

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.submit_button').prop('type', 'button');
        });

        var isAllowSubmit = true;
        $(document).on('click', '.submit_button', function() {

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            }
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#save_changes').click();
                return false;
            } else if (e.which == 27) {

                $('.select_area').hide();
                $('#list').empty();

                return false;
            }
        }

        //Add purchase request by ajax
        $('#edit_transfer_branch_to_branch_form').on('submit', function(e){
            e.preventDefault();

            var totalItem = $('#total_item').val();

            if (parseFloat(totalItem) == 0) {

                toastr.error("{{ __('Transfer product table is empty.') }}");
                return;
            }

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){

                    $('.loading_button').hide();
                    if(!$.isEmptyObject(data.errorMsg)){

                        toastr.error(data.errorMsg,'ERROR');
                        return;
                    }

                    toastr.success(data);
                    window.location = "{{ url()->previous() }}";
                }, error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
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
        });

        $(document).on('change keypress click', 'select', function(e) {

            var totalReceivedQty = "{{ $transferStock->total_received_qty }}";
            var nextId = $(this).data('next');

            if (e.which == 0) {

                if ($(this).attr('id') == 'sender_warehouse_id' && parseFloat(totalReceivedQty) > 0) {

                    $('#date').focus().select();
                    return;
                }

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                $('#' + nextId).focus().select();
            }
        });

        setInterval(function(){

            $('#search_product').removeClass('is-invalid');
        }, 500);

        setInterval(function(){

            $('#search_product').removeClass('is-valid');
        }, 1000);

        $('body').keyup(function(e){

            if (e.keyCode == 13 || e.keyCode == 9){

                $(".selectProduct").click();
                $('#list').empty();
                keyName = e.keyCode;
            }
        });

        $(document).on('mouseenter', '#list>li>a',function () {
            $('#list>li>a').removeClass('selectProduct');
            $(this).addClass('selectProduct');
        });

        $(document).on('click', '#receiver_branch_id', function(e) {
            e.preventDefault();

            var branchId = $(this).val();

            // if (branchId == '') {
            //     return;
            // }

            var route = '';
            var url = "{{ route('warehouses.by.branch', ':branchId') }}";
            route = url.replace(':branchId', branchId);

            $.ajax({
                url: route,
                type: 'get',
                success: function(warehouses) {

                    $('#receiver_warehouse_id').empty();
                    $('#receiver_warehouse_id').append('<option value="">Select Warehouse</option>');

                    $.each(warehouses, function(key, warehouse) {

                        $('#receiver_warehouse_id').append('<option value="' + warehouse.id + '">' + warehouse.warehouse_name + '/' + warehouse.warehouse_code + '</option>');
                    });
                }, error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        var dateFormat = "{{ $generalSettings['business__date_format'] }}";
        var _expectedDateFormat = '' ;
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
@endpush
