@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 94%;z-index: 9999999;padding: 0;left: 3%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
    </style>
    <link rel="stylesheet" href="{{ asset('public') }}/backend/asset/css/bootstrap-datepicker.min.css">
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="edit_purchase_form" action="{{ route('purchases.product.update', $purchase->id) }}" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>Edit Purchased Product</h5>
                                        </div>

                                        <div class="col-6">
                                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Supplier :</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" id="supplier_name" class="form-control" value="{{ $purchase->s_name }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            @if ($purchase->warehouse_id)
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Warehouse :</b><span
                                                        class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{ $purchase->w_name.'/'.$purchase->w_code }}">
                                                        <span class="error error_warehouse_id"></span>
                                                    </div>
                                                </div>
                                            @else 
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><span
                                                        class="text-danger">*</span> <b>B.Location :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Invoice ID :</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control" value="{{ $purchase->invoice_id }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Date :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control datepicker changeable" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>Product</th>
                                                                    <th>Quantity</th>
                                                                    <th>Unit Cost(Before Discount)</th>
                                                                    <th>Discount</th>
                                                                    <th>Unit Cost(Before Tax)</th>
                                                                    <th>SubTotal (Before Tax)</th>
                                                                    <th>Unit Tax</th>
                                                                    <th>Net Unit Cost</th>
                                                                    <th>Line Total</th>
                                                                    @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                                        <th>Profit Margin(%)</th>
                                                                        <th>Selling Price</th>
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="text-start">
                                                                    <td>
                                                                        <span title="{{$purchaseProduct->product->name}}" class="product_name">{{ Str::limit($purchaseProduct->product->name, 20) }}</span> 
                                                                        <span class="product_variant">{{ $purchaseProduct->variant ? $purchaseProduct->variant->variant_name : '' }}</span>  
                                                                        <input value="{{ $purchaseProduct->product->id }}" type="hidden" class="productId-{{ $purchaseProduct->product->id }}" id="product_id" name="product_id">
                                                                        <input value="{{ $purchaseProduct->variant ? $purchaseProduct->variant->id : 'noid' }}" type="hidden" id="variant_id" name="variant_id">
                                                                    </td>
                                                        
                                                                    <td>
                                                                        <input value="{{ $purchaseProduct->quantity }}" required name="quantity" type="number" step="any" class="form-control" id="quantity">
                                                                        <input value="{{ $purchaseProduct->unit }}" readonly  type="text" step="any" class="form-control mt-1">
                                                                    </td>
                                                        
                                                                    <td>
                                                                        <input value="{{ $purchaseProduct->unit_cost }}" required name="unit_cost" type="text" class="form-control" id="unit_cost">
                                                                        @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                                                            <input name="lot_number" placeholder="Lot No" type="text" class="form-control mt-1" id="lot_number" value="{{ $purchaseProduct->lot_no }}">
                                                                        @endif
                                                                    </td>
                                                        
                                                                    <td>
                                                                        <input value="{{ $purchaseProduct->unit_discount }}" required name="unit_discount" type="text" class="form-control" id="unit_discount">
                                                                    </td>
                                                    
                                                                    <td>
                                                                        <input readonly value="{{ $purchaseProduct->unit_cost_with_discount }}" name="unit_cost_with_discount" type="text" class="form-control" id="unit_cost_with_discount">
                                                                    </td>
                                                        
                                                                    <td>
                                                                        <input readonly value="{{ $purchaseProduct->subtotal }}" required name="subtotal" type="text" class="form-control" id="subtotal">
                                                                    </td>
                                                        
                                                                    <td>
                                                                        <input readonly type="text" name="tax_percent" id="tax_percent" class="form-control" value="{{ $purchaseProduct->unit_tax_percent }}">
                                                                        <input type="hidden" value="{{ $purchaseProduct->unit_tax }}" name="unit_tax"   id="unit_tax">
                                                                    </td>
                                                        
                                                                    @php
                                                                        $unit_cost_inc_tax = ($purchaseProduct->unit_cost / 100 * $purchaseProduct->unit_tax_percent) + $purchaseProduct->unit_cost;
                                                                    @endphp
                                                                    
                                                                    <td>
                                                                        <input type="hidden" value="{{ bcadd($unit_cost_inc_tax, 0, 2) }}" name="unit_costs_inc_tax" id="unit_cost_inc_tax">
                                                                        <input readonly value="{{ $purchaseProduct->net_unit_cost }}" name="net_unit_cost" type="text" class="form-control" id="net_unit_cost">
                                                                    </td>
                                                        
                                                                    <td>
                                                                        <input readonly value="{{ $purchaseProduct->line_total }}" type="text" name="linetotal" id="line_total" class="form-control">
                                                                    </td>
                                                        
                                                                    @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                                        <td>
                                                                            <input value="{{ $purchaseProduct->profit_margin }}" type="text" name="profit" class="form-control" id="profit">
                                                                        </td>
                                                                    
                                                                        <td>
                                                                            <input value="{{ $purchaseProduct->selling_price }}" type="text" name="selling_price" class="form-control" id="selling_price">
                                                                        </td>
                                                                    @endif
                                                                </tr>
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
                    </div>
                </section>

                <div class="submit_button_area pt-1">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button class="btn btn-sm btn-primary submit_button float-end">Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Quantity increase or dicrease and clculate row amount
        $(document).on('input', '#quantity', function(){
            var qty = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            //Update subtotal 
            var unitCostWithDiscount = tr.find('#unit_cost_with_discount').val();
            var calcSubtotal = parseFloat(unitCostWithDiscount) * parseFloat(qty);
            var subtotal = tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            
            //Update line total
            var netUnitCost = tr.find('#net_unit_cost').val();
            var calcLineTotal = parseFloat(netUnitCost) * parseFloat(qty);
            var lineTotal = tr.find('#line_total').val(parseFloat(calcLineTotal).toFixed(2));
            // console.log(tr);
            calculateTotalAmount();
        });

        // Change tax percent and clculate row amount
        $(document).on('input', '#unit_cost', function(){
            var unitCost = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            
            // update unit cost with discount
            var discount = tr.find('#unit_discount').val();
            var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(discount);
            var unitCostWithDiscount = tr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

            // update subtotal
            var quantity = tr.find('#quantity').val();
            var calcSubtotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(quantity);
            tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));

            // Update net unit cost
            var tax_percent = tr.find('#tax_percent').val();
            //Calc Unit tax
            var calcTaxAmount = parseFloat(calcUnitCostWithDiscount) / 100 * parseFloat(tax_percent);
            tr.find('#unit_tax').val(parseFloat(calcTaxAmount).toFixed(2));
            var calcNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(calcTaxAmount);
            tr.find('#net_unit_cost').val(parseFloat(calcNetUnitCost).toFixed(2));

            // Calc unit inc 
            var unitCostIncTax = parseFloat(unitCost) + parseFloat(calcTaxAmount);
            tr.find('#unit_cost_inc_tax').val(parseFloat(unitCostIncTax).toFixed(2));
            // Update line total
            var calcLineTotal = parseFloat(calcNetUnitCost) * parseFloat(quantity);
            var lineTotal = tr.find('#line_total').val(parseFloat(calcLineTotal).toFixed(2));

            // Update selling price
            var profit = tr.find('#profit').val();
            if (profit > 0) {
                var calcProfit = parseFloat(calcUnitCostWithDiscount) / 100 * parseFloat(profit) + parseFloat(calcUnitCostWithDiscount);
                var sellingPrice = tr.find('#selling_price').val(parseFloat(calcProfit).toFixed(2));
            }
        });

        // Input discount and clculate row amount
        $(document).on('input', '#unit_discount', function(){
            var unit_discount = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            //Update unit cost with discount 
            var unitCost = tr.find('#unit_cost').val();
            var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(unit_discount);
            var unitCostWithDiscount = tr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

            // Update sub-total
            var quantity = tr.find('#quantity').val();
            var calcSubtotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(quantity);
            var subtotal = tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));

            // Update net unit cost
            var tax_percent = tr.find('#tax_percent').val();
            // Calc unit tax
            var calcTaxAmount = parseFloat(calcUnitCostWithDiscount) / 100 * parseFloat(tax_percent);
            tr.find('#unit_tax').val(parseFloat(calcTaxAmount).toFixed(2));
            var calsNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(calcTaxAmount);
            tr.find('#net_unit_cost').val(parseFloat(calsNetUnitCost).toFixed(2));

            // Update line total
            var calcLineTotal = parseFloat(calsNetUnitCost) * parseFloat(quantity);
            var lineTotal = tr.find('#line_total').val(parseFloat(calcLineTotal).toFixed(2));

            // Update profit 
            var profitMargin = tr.find('#profit').val();
            if (profitMargin > 0) {
                var calcProfit = parseFloat(calcUnitCostWithDiscount) / 100 * parseFloat(profitMargin) + parseFloat(calcUnitCostWithDiscount);
                var sellingPrice = tr.find('#selling_price').val(parseFloat(calcProfit).toFixed(2));
            }
        });

        $(document).on('blur', '#unit_discount', function(){
            if ($(this).val() == '') {
                $(this).val(parseFloat(0).toFixed(2));
            }
        });

        $(document).on('blur', '#profit', function(){
            if ($(this).val() == '') {
                $(this).val(parseFloat(0).toFixed(2));
            }
        });

        // Input profit margin and clculate row amount
        $(document).on('input', '#profit', function() {
            var profit = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            
            if (profit > 0) {
                var unit_cost_with_discount = tr.find('#unit_cost_with_discount').val();
                var calcProfit = parseFloat(unit_cost_with_discount)  / 100 * parseFloat(profit) + parseFloat(unit_cost_with_discount);
                var sellingPrice = tr.find('#selling_price').val(parseFloat(calcProfit).toFixed(2));
            }
        });

        // Input profit margin and clculate row amount
        $(document).on('input', '#selling_price', function(){
            var price = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            
            // Update selling price
            var unit_cost_with_discount = tr.find('#unit_cost_with_discount').val();
            var profitAmount = parseFloat(price) - parseFloat(unit_cost_with_discount);
            var calcProfit = parseFloat(profitAmount) / parseFloat(unit_cost_with_discount) * 100;
            var sellingPrice = tr.find('#profit').val(parseFloat(calcProfit).toFixed(2));
        });

        //Edit purchase request by ajax
        $('#edit_purchase_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var inputs = $('.add_input');
                inputs.removeClass('is-invalid');
                $('.error').html('');  
                var countErrorField = 0;  
            $.each(inputs, function(key, val){
                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){
                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.','Some thing want wrong.'); 
                return;
            }

            $.ajax({
                url:url,
                type:'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    if(!$.isEmptyObject(data.errorMsg)){
                        toastr.error(data.errorMsg,'ERROR'); 
                        $('.loading_button').hide();
                    }else{
                        $('.loading_button').hide();
                        toastr.success(data); 
                        //window.location = "{{route('purchases.index_v2')}}";
                    }
                }
            });
        });

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'dd');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'mm');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'yyyy');
        $('#datepicker').datepicker({format: _expectedDateFormat});
    </script>
@endpush